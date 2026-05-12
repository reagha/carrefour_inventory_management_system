<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
        // Route::resource('suppliers', SupplierController::class);
        // Route::resource('purchase-orders', PurchaseOrderController::class);
    });

    Route::middleware(['role:admin,warehouse'])->group(function () {
        // Route::resource('products', ProductController::class);
    });

    Route::middleware(['role:admin,branch_manager'])->group(function () {
        // Route::resource('branch-requests', BranchRequestController::class);
    });

    Route::middleware(['role:admin,auditor,procurement,warehouse,branch_manager'])->group(function () {
        Route::get('reports/valuation',            [ReportController::class, 'valuation'])->name('reports.valuation');
        Route::get('reports/consumption',          [ReportController::class, 'consumption'])->name('reports.consumption');
        Route::get('reports/supplier-expenditure', [ReportController::class, 'supplierExpenditure'])->name('reports.supplier-expenditure');
    });

});

require __DIR__.'/auth.php';