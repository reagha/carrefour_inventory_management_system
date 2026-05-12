<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Branch;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use App\Models\BranchRequest;
use App\Models\BranchRequestItem;
use App\Models\PurchaseOrderItem;

class ReportController extends Controller
{
    /* ── Inventory Valuation ── */
    public function valuation(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        $query = Product::with('supplier');

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $products = $query->orderByDesc(DB::raw('quantity_in_warehouse * unit_cost'))
            ->get()
            ->map(function ($p) {
                $p->total_value = $p->quantity_in_warehouse * $p->unit_cost;
                return $p;
            });

        $grandTotal      = $products->sum('total_value');
        $lowStockCount   = $products->filter(fn($p) => $p->quantity_in_warehouse <= $p->reorder_level)->count();
        $outOfStockCount = $products->filter(fn($p) => $p->quantity_in_warehouse == 0)->count();

        return view('reports.valuation', compact(
            'products', 'grandTotal', 'lowStockCount', 'outOfStockCount', 'startDate', 'endDate'
        ));
    }

    /* ── Branch Consumption ── */
    public function consumption(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        $branchActivity = Branch::withCount([
            'branchRequests as total_requests' => fn($q) => $this->applyRequestDateFilter($q, $startDate, $endDate),
            'branchRequests as approved_requests' => fn($q) => $this->applyRequestDateFilter($q, $startDate, $endDate)->where('status', 'approved'),
            'branchRequests as pending_requests'  => fn($q) => $this->applyRequestDateFilter($q, $startDate, $endDate)->where('status', 'pending'),
        ])->get()->sortByDesc('total_requests');

        $topProducts = BranchRequestItem::with('product')
            ->whereHas('branchRequest', fn($q) => $this->applyRequestDateFilter($q, $startDate, $endDate))
            ->select('product_id', DB::raw('sum(quantity) as total_qty'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        return view('reports.consumption', compact(
            'branchActivity', 'topProducts', 'startDate', 'endDate'
        ));
    }

    /* ── Supplier Expenditure ── */
    public function supplierExpenditure(Request $request)
    {
        $startDate = $request->input('start_date', now()->subMonths(6)->toDateString());
        $endDate   = $request->input('end_date',   now()->toDateString());

        $suppliers = Supplier::with(['purchaseOrders' => function ($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate])->with('items');
        }])->get()->map(function ($supplier) {
            $supplier->total_committed = $supplier->purchaseOrders->sum(function ($po) {
                return $po->items->sum(fn($i) => $i->quantity * $i->unit_price);
            });
            $supplier->total_orders    = $supplier->purchaseOrders->count();
            $supplier->approved_orders = $supplier->purchaseOrders->where('status', 'approved')->count();
            $supplier->pending_orders  = $supplier->purchaseOrders->where('status', 'pending')->count();
            return $supplier;
        })->sortByDesc('total_committed');

        $grandTotal = $suppliers->sum('total_committed');

        // Monthly trend
        $monthlyTrend = PurchaseOrder::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw("strftime('%Y-%m', created_at) as month"),
                DB::raw('count(*) as po_count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('reports.supplier-expenditure', compact(
            'suppliers', 'grandTotal', 'monthlyTrend', 'startDate', 'endDate'
        ));
    }

    private function applyRequestDateFilter($query, $startDate, $endDate)
    {
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query;
    }
}