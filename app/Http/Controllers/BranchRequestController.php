<?php

namespace App\Http\Controllers;

use App\Models\BranchRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BranchRequestController extends Controller
{
    /**
     * List branch requests based on user role.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = BranchRequest::with(['branch', 'branchManager', 'items.product']);

        // Check the role of the authenticated user to scope the requests
        if ($user->role === 'branchManager') {
            $query->where('branch_id', $user->branch_id);
        }
        // If 'warehouse' or 'admin', they see all (we skip the where clause).

        // Paginate for easier frontend handling
        $branchRequests = $query->latest()->paginate(15);

        return view('branch-requests.index', compact('branchRequests'));
    }

    /**
     * Show create form with available products.
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('branch-requests.create', compact('products'));
    }

    /**
     * Store a new branch request.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        // Security / Authorization check
        if ($user->role !== 'branchManager' || !$user->branch_id) {
            abort(403, 'Unauthorized.');
        }

        // Validate payload structure
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            // DB Transaction: either all saves succeed, or none do (rollback)
            $branchRequest = DB::transaction(function () use ($validated, $user) {

                $requestRecord = BranchRequest::create([
                    'branch_id' => $user->branch_id,
                    'branchManager_id' => $user->id,
                    'status' => 'pending',
                ]);

                foreach ($validated['items'] as $itemData) {
                    $product = Product::findOrFail($itemData['product_id']);

                    // Rule check: does the requested qty exceed what we currently have?
                    if ($itemData['quantity'] > $product->quantity_in_warehouse) {
                        throw ValidationException::withMessages([
                            'items' => "Requested quantity for product {$product->name} (SKU: {$product->sku}) exceeds available stock ({$product->quantity_in_warehouse}).",
                        ]);
                    }

                    $requestRecord->items()->create([
                        'product_id' => $itemData['product_id'],
                        'quantity' => $itemData['quantity'],
                    ]);
                }

                return $requestRecord;
            });

            return redirect()->route('branch-requests.index')
                ->with('success', 'Stock request created successfully.');

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create request: ' . $e->getMessage());
        }
    }

    /**
     * Dispatch logistics (Warehouse Worker approves and locks in the transaction).
     */
    public function dispatch(Request $request, BranchRequest $branchRequest)
    {
        $user = $request->user();

        // Authorization check
        if ($user->role !== 'warehouse' && $user->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // State machine constraint check
        if ($branchRequest->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be dispatched.');
        }

        try {
            DB::transaction(function () use ($branchRequest, $user) {

                // Get product IDs involved to fetch and lock them
                $productIds = $branchRequest->items->pluck('product_id');

                // PERFORMANCE AND INTEGRITY:
                // We use lockForUpdate() to place a pessimistic row-level lock on these products.
                // This prevents two warehouse workers from dispatching requests at the exact same
                // millisecond and bypassing the inventory check.
                $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

                foreach ($branchRequest->items as $item) {
                    $product = $products->get($item->product_id);

                    // Defensive programming: The stock might have been depleted *between* the
                    // time the manager submitted the request and the worker dispatches it!
                    if (!$product || $item->quantity > $product->quantity_in_warehouse) {
                        throw new \Exception("Insufficient stock on shelves for product ID {$item->product_id} to fulfill request {$branchRequest->id}. Please reject or amend.");
                    }

                    // Decrement absolute stock
                    $product->quantity_in_warehouse -= $item->quantity;
                    $product->save();
                }

                $branchRequest->update([
                    'status' => 'dispatched',
                    'dispatched_by' => $user->id,
                    'dispatched_at' => now(),
                ]);
            });

            return back()->with('success', 'Stock request dispatched and warehouse inventory updated!');

        } catch (\Exception $e) {
            return back()->with('error', 'Dispatch failed: ' . $e->getMessage());
        }
    }
}
