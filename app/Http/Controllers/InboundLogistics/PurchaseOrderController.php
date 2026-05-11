<?php

namespace App\Http\Controllers\InboundLogistics;

use App\Mail\InboundLogistics\PurchaseOrderApproved;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PurchaseOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $purchaseOrders = PurchaseOrder::with(['supplier', 'procurementManager', 'items.product'])
            ->orderByDesc('created_at')
            ->get();

        return view('inbound_logistics.purchase_orders.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('inbound_logistics.purchase_orders.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $items = collect($request->input('items'))->map(function ($item) {
            $product = Product::findOrFail($item['product_id']);
            $quantity = (int) $item['quantity'];
            $unitCost = $product->unit_cost;

            return [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'subtotal' => $quantity * $unitCost,
            ];
        });

        $totalAmount = $items->sum('subtotal');

        DB::transaction(function () use ($request, $items, $totalAmount) {
            $purchaseOrder = PurchaseOrder::create([
                'supplier_id' => $request->input('supplier_id'),
                'procurement_manager_id' => auth()->id(),
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            foreach ($items as $item) {
                $purchaseOrder->items()->create($item);
            }
        });

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase order created successfully.');
    }

    public function approve(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'pending') {
            abort(403, 'Only pending purchase orders may be approved.');
        }

        $purchaseOrder->update(['status' => 'approved']);

        Mail::to($purchaseOrder->supplier->contact_email)
            ->send(new PurchaseOrderApproved($purchaseOrder));

        return back()->with('success', 'Purchase order approved and supplier notified.');
    }

    public function pendingReceipts()
    {
        $purchaseOrders = PurchaseOrder::with(['supplier', 'procurementManager', 'items.product'])
            ->where('status', 'approved')
            ->orderBy('created_at')
            ->get();

        return view('inbound_logistics.purchase_orders.pending_receipts', compact('purchaseOrders'));
    }

    public function receive(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'approved') {
            abort(403, 'Only approved purchase orders can be received.');
        }

        DB::transaction(function () use ($purchaseOrder) {
            foreach ($purchaseOrder->items as $item) {
                $item->product->increment('quantity_in_warehouse', $item->quantity);
            }

            $purchaseOrder->update([
                'status' => 'received',
                'received_by' => auth()->id(),
                'received_at' => now(),
            ]);
        });

        return back()->with('success', 'Purchase order received and warehouse stock updated.');
    }
}
