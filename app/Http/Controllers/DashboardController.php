<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\BranchRequest;
use App\Models\Branch;
use App\Models\Supplier;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->role ?? 'admin';

        return match ($role) {
            'procurement'    => $this->procurementDashboard(),
            'warehouse'      => $this->warehouseDashboard(),
            'branch_manager' => $this->branchDashboard($user),
            default          => $this->adminDashboard(),
        };
    }

    private function procurementDashboard()
    {
        $pendingPOs        = PurchaseOrder::where('status', 'pending')->with('supplier', 'items')->latest()->get();
        $totalPendingValue = $pendingPOs->sum(fn($po) => $po->items->sum(fn($i) => $i->quantity * $i->unit_price));
        $posByStatus       = PurchaseOrder::select('status', DB::raw('count(*) as count'))->groupBy('status')->pluck('count', 'status');
        $recentPOs         = PurchaseOrder::with('supplier', 'items')->latest()->take(10)->get();

        return view('dashboards.procurement', compact('pendingPOs', 'totalPendingValue', 'posByStatus', 'recentPOs'));
    }

    private function warehouseDashboard()
    {
        $trucksToReceive  = PurchaseOrder::where('status', 'approved')->count();
        $trucksToDispatch = BranchRequest::where('status', 'approved')->count();
        $lowStockProducts = Product::whereColumn('quantity_in_warehouse', '<=', 'reorder_level')->with('supplier')->get();
        $totalProducts    = Product::count();
        $totalStockValue  = Product::sum(DB::raw('quantity_in_warehouse * unit_cost'));
        $recentActivity   = BranchRequest::with(['branch', 'items'])->latest()->take(8)->get();

        return view('dashboards.warehouse', compact(
            'trucksToReceive', 'trucksToDispatch', 'lowStockProducts',
            'totalProducts', 'totalStockValue', 'recentActivity'
        ));
    }

    private function branchDashboard($user)
    {
        $branch           = Branch::find($user->branch_id);
        $myRequests       = BranchRequest::where('branch_id', $user->branch_id)->with('items.product')->latest()->take(10)->get();
        $requestCounts    = BranchRequest::where('branch_id', $user->branch_id)->select('status', DB::raw('count(*) as count'))->groupBy('status')->pluck('count', 'status');
        $lowStockProducts = Product::whereColumn('quantity_in_warehouse', '<=', 'reorder_level')->get();

        return view('dashboards.branch', compact('branch', 'myRequests', 'requestCounts', 'lowStockProducts'));
    }

    private function adminDashboard()
    {
        $totalProducts    = Product::count();
        $totalSuppliers   = Supplier::count();
        $totalBranches    = Branch::count();
        $totalStockValue  = Product::sum(DB::raw('quantity_in_warehouse * unit_cost'));
        $pendingPOs       = PurchaseOrder::where('status', 'pending')->count();
        $pendingRequests  = BranchRequest::where('status', 'pending')->count();
        $lowStockProducts = Product::whereColumn('quantity_in_warehouse', '<=', 'reorder_level')->with('supplier')->get();
        $recentPOs        = PurchaseOrder::with('supplier')->latest()->take(5)->get();
        $recentRequests   = BranchRequest::with('branch')->latest()->take(5)->get();

        return view('dashboards.admin', compact(
            'totalProducts', 'totalSuppliers', 'totalBranches', 'totalStockValue',
            'pendingPOs', 'pendingRequests', 'lowStockProducts', 'recentPOs', 'recentRequests'
        ));
    }
}