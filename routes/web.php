<?php

use App\Http\Controllers\InboundLogistics\PurchaseOrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BranchRequestController;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('branches', \App\Http\Controllers\BranchController::class);
    });

    Route::middleware(['role:admin,procurement'])->group(function () {
        Route::resource('products', ProductController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('purchase-orders', PurchaseOrderController::class)->except(['show']);
        Route::patch('purchase-orders/{purchase_order}/approve', [PurchaseOrderController::class, 'approve'])->name('purchase-orders.approve');
    });

    Route::middleware(['role:admin,warehouse'])->group(function () {
        Route::get('purchase-orders/pending-receipts', [PurchaseOrderController::class, 'pendingReceipts'])->name('purchase-orders.pending-receipts');
        Route::patch('purchase-orders/{purchase_order}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');
        Route::patch('branch-requests/{branch_request}/dispatch', [BranchRequestController::class, 'dispatch'])->name('branch-requests.dispatch');
    });

    Route::middleware(['role:admin,branch_manager'])->group(function () {
        Route::get('branch-requests', [BranchRequestController::class, 'index'])->name('branch-requests.index');
        Route::get('branch-requests/create', fn () => view('branch-requests.create'))->name('branch-requests.create');
        Route::post('branch-requests', [BranchRequestController::class, 'store'])->name('branch-requests.store');
    });

    Route::middleware(['role:admin,auditor,procurement,warehouse,branch_manager'])->group(function () {
        Route::get('reports/valuation', [ReportController::class, 'valuation'])->name('reports.valuation');
        Route::get('reports/consumption', [ReportController::class, 'consumption'])->name('reports.consumption');
        Route::get('reports/supplier-expenditure', [ReportController::class, 'supplierExpenditure'])->name('reports.supplier-expenditure');
    });
});

require __DIR__.'/auth.php';