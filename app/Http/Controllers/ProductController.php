<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        // Eager load supplier to prevent N+1 performance issues
        $products = Product::with('supplier')
            ->orderBy('name', 'asc')
            ->paginate(15); // Added pagination for better performance as the warehouse grows

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $user = auth()->user();

        if (! $user || ! in_array($user->role, ['admin', 'procurement'])) {
            return view('products.request_assistance');
        }

        $suppliers = Supplier::orderBy('name', 'asc')->get();
        return view('products.create', compact('suppliers'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|string|unique:products,sku|max:50',
            'name' => 'required|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id',
            'unit_cost' => 'required|integer|min:0', // Ugandan Shillings as Integer
            'reorder_level' => 'required|integer|min:0',
            'quantity_in_warehouse' => 'nullable|integer|min:0',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        // Load relationships to show details on a view page
        $product->load(['supplier', 'purchaseOrderItems', 'branchRequestItems']);
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $suppliers = Supplier::orderBy('name', 'asc')->get();
        return view('products.edit', compact('product', 'suppliers'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            // SKU unique check must ignore the current product's ID
            'sku' => ['required', 'string', 'max:50', Rule::unique('products')->ignore($product->id)],
            'name' => 'required|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id',
            'unit_cost' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'quantity_in_warehouse' => 'required|integer|min:0',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // BUSINESS RULE: Prevent deletion if product has history in the supply chain
        // We check relationship existence to maintain data integrity
        $hasPurchaseOrders = $product->purchaseOrderItems()->exists();
        $hasBranchRequests = $product->branchRequestItems()->exists();

        if ($hasPurchaseOrders || $hasBranchRequests) {
            return redirect()->route('products.index')
                ->with('error', 'Integrity Error: Cannot delete product "' . $product->name . '" because it has existing transaction records.');
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}