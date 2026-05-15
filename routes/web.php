<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BranchRequestController;
use App\Http\Controllers\InboundLogistics\PurchaseOrderController;

// Redirect the base URL directly to the dashboard (Better UX for ERPs)
Route::get('/', function () {
    return redirect('/dashboard');
});

// ==========================================
// ALL LOGGED-IN USERS (Common Area)
// ==========================================
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Custom Dashboard Controller (Restored!)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==========================================
    // MODULE 1: ADMIN ZONE (You)
    // ==========================================
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('branches', BranchController::class); // Restored!
    });

    // ==========================================
    // MODULE 2: PROCUREMENT ZONE
    // ==========================================
    Route::middleware(['role:admin,procurement'])->group(function () {
        Route::resource('products', ProductController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('purchase-orders', PurchaseOrderController::class)->except(['show']);
        Route::patch('purchase-orders/{purchase_order}/approve', [PurchaseOrderController::class, 'approve'])->name('purchase-orders.approve');
    });

    // ==========================================
    // MODULE 3: WAREHOUSE ZONE
    // ==========================================
    Route::middleware(['role:admin,warehouse'])->group(function () {
        Route::get('purchase-orders/pending-receipts', [PurchaseOrderController::class, 'pendingReceipts'])->name('purchase-orders.pending-receipts');
        Route::patch('purchase-orders/{purchase_order}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');
        
        // Warehouse dispatching items to branches
        Route::patch('branch-requests/{branch_request}/dispatch', [BranchRequestController::class, 'dispatch'])->name('branch-requests.dispatch');
    });

    // ==========================================
    // MODULE 4: BRANCH MANAGER ZONE
    // ==========================================
    Route::middleware(['role:admin,branchManager'])->group(function () {
        Route::get('branch-requests/create', [BranchRequestController::class, 'create'])->name('branch-requests.create');
        Route::post('branch-requests', [BranchRequestController::class, 'store'])->name('branch-requests.store');
    });

    // Shared Zone: Both Warehouse & Branch Managers need to view the list of requests
    Route::middleware(['role:admin,warehouse,branchManager'])->group(function () {
        Route::get('branch-requests', [BranchRequestController::class, 'index'])->name('branch-requests.index');
    });

    // ==========================================
    // MODULE 5: AUDITOR / REPORTS ZONE
    // ==========================================
    Route::middleware(['role:admin,auditor,procurement,warehouse,branchManager'])->group(function () {
        Route::get('reports/valuation', [ReportController::class, 'valuation'])->name('reports.valuation');
        Route::get('reports/consumption', [ReportController::class, 'consumption'])->name('reports.consumption');
        Route::get('reports/supplier-expenditure', [ReportController::class, 'supplierExpenditure'])->name('reports.supplier-expenditure');
    });

});

require __DIR__.'/auth.php';