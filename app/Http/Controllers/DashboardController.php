<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\BranchRequest;
use App\Models\Branch;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * MAIN DASHBOARD ROUTER
     */
    public function index()
    {
        $user = Auth::user();

        // Safety fallback
        if (!$user) {
            abort(403, 'Unauthorized access.');
        }

        $role = $user->role ?? 'admin';

        return match ($role) {

            'procurement' =>
                $this->procurementDashboard(),

            'warehouse' =>
                $this->warehouseDashboard(),

            'branchManager' =>
                $this->branchDashboard($user),

            default =>
                $this->adminDashboard(),
        };
    }

    /**
     * PROCUREMENT DASHBOARD
     */
    private function procurementDashboard()
    {
        $pendingPOs = PurchaseOrder::where(
                'status',
                'pending'
            )
            ->with([
                'supplier',
                'items'
            ])
            ->latest()
            ->get();

        $totalPendingValue = $pendingPOs->sum(
            fn($po) =>
                $po->items->sum(
                    fn($item) =>
                        $item->quantity * $item->unit_price
                )
        );

        $posByStatus = PurchaseOrder::select(
                'status',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('status')
            ->pluck('count', 'status');

        $recentPOs = PurchaseOrder::with([
                'supplier',
                'items'
            ])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboards.procurement', [

            'pendingPOs'        => $pendingPOs,
            'totalPendingValue' => $totalPendingValue,
            'posByStatus'       => $posByStatus,
            'recentPOs'         => $recentPOs,

            // GLOBAL WIDGET
            'lowStockProducts'  => $this->lowStockProducts(),
        ]);
    }

    /**
     * WAREHOUSE DASHBOARD
     */
    private function warehouseDashboard()
    {
        $trucksToReceive = PurchaseOrder::where(
            'status',
            'approved'
        )->count();

        $trucksToDispatch = BranchRequest::where(
            'status',
            'approved'
        )->count();

        $totalProducts = Product::count();

        $totalStockValue = Product::sum(
            DB::raw('quantity_in_warehouse * unit_cost')
        );

        $recentActivity = BranchRequest::with([
                'branch',
                'items.product'
            ])
            ->latest()
            ->take(8)
            ->get();

        return view('dashboards.warehouse', [

            'trucksToReceive'  => $trucksToReceive,
            'trucksToDispatch' => $trucksToDispatch,

            'totalProducts'    => $totalProducts,
            'totalStockValue'  => $totalStockValue,

            'recentActivity'   => $recentActivity,

            // GLOBAL WIDGET
            'lowStockProducts' => $this->lowStockProducts(),
        ]);
    }

    /**
     * BRANCH MANAGER DASHBOARD
     */
    private function branchDashboard(User $user)
    {
        $branch = Branch::find($user->branch_id);

        $myRequests = BranchRequest::where(
                'branch_id',
                $user->branch_id
            )
            ->with([
                'items.product'
            ])
            ->latest()
            ->take(10)
            ->get();

        $requestCounts = BranchRequest::where(
                'branch_id',
                $user->branch_id
            )
            ->select(
                'status',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('dashboards.branch', [

            'branch'           => $branch,

            'myRequests'       => $myRequests,

            'requestCounts'    => $requestCounts,

            // GLOBAL WIDGET
            'lowStockProducts' => $this->lowStockProducts(),
        ]);
    }

    /**
     * ADMIN DASHBOARD
     */
    private function adminDashboard()
    {
        $totalProducts = Product::count();

        $totalSuppliers = Supplier::count();

        $totalBranches = Branch::count();

        $totalStockValue = Product::sum(
            DB::raw('quantity_in_warehouse * unit_cost')
        );

        $pendingPOs = PurchaseOrder::where(
            'status',
            'pending'
        )->count();

        $pendingRequests = BranchRequest::where(
            'status',
            'pending'
        )->count();

        $recentPOs = PurchaseOrder::with([
                'supplier'
            ])
            ->latest()
            ->take(5)
            ->get();

        $recentRequests = BranchRequest::with([
                'branch'
            ])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboards.admin', [

            'totalProducts'    => $totalProducts,
            'totalSuppliers'   => $totalSuppliers,
            'totalBranches'    => $totalBranches,

            'totalStockValue'  => $totalStockValue,

            'pendingPOs'       => $pendingPOs,
            'pendingRequests'  => $pendingRequests,

            'recentPOs'        => $recentPOs,
            'recentRequests'   => $recentRequests,

            // GLOBAL WIDGET
            'lowStockProducts' => $this->lowStockProducts(),
        ]);
    }

    /**
     * GLOBAL LOW STOCK WIDGET
     */
    private function lowStockProducts()
    {
        return Product::whereColumn(
                'quantity_in_warehouse',
                '<=',
                'reorder_level'
            )
            ->with('supplier')
            ->orderBy('quantity_in_warehouse')
            ->get();
    }
}