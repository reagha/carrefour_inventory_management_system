<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ==========================================
// ALL LOGGED-IN USERS (Common Area)
// ==========================================
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Everyone can see the main dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Everyone can edit their own profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // ==========================================
    // MODULE 1: ADMIN ZONE (You)
    // ==========================================
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // ==========================================
    // MODULE 2: PROCUREMENT ZONE (Teammate 2)
    // ==========================================
    // Admins and Procurement Managers can access this zone
    Route::middleware(['role:admin,procurement'])->group(function () {
        
        // Example: Teammate 2 will put their routes here later!
        // Route::resource('suppliers', SupplierController::class);
        // Route::resource('purchase-orders', PurchaseOrderController::class);

    });

    // ==========================================
    // MODULE 3: WAREHOUSE ZONE (Teammate 3)
    // ==========================================
    // Admins and Warehouse Workers can access this zone
    Route::middleware(['role:admin,warehouse'])->group(function () {
        
        // Example: Teammate 3 will put their routes here later!
        // Route::resource('products', ProductController::class);
        // Route::get('warehouse/dispatch', [DispatchController::class, 'index']);

    });

    // ==========================================
    // MODULE 4: BRANCH MANAGER ZONE (Teammate 4)
    // ==========================================
    // Admins and Branch Managers can access this zone
    Route::middleware(['role:admin,branch_manager'])->group(function () {
        
        // Example: Teammate 4 will put their routes here later!
        // Route::resource('branch-requests', BranchRequestController::class);

    });

    // ==========================================
    // MODULE 5: AUDITOR ZONE (Teammate 5)
    // ==========================================
    // Usually Auditors just need Read-Only access to reports
    Route::middleware(['role:admin,auditor'])->group(function () {
        
        // Example: Teammate 5 will put report routes here later!
        // Route::get('reports/financials', [ReportController::class, 'financials']);

    });

});

require __DIR__.'/auth.php';
