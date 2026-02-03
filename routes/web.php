<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'track'])->name('track');


// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public tracking route (no auth required)
// Route::get('/track/{invoice}', [TransactionController::class, 'track'])->name('track');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routes accessible by all authenticated users (admin, owner, karyawan)
    Route::middleware(['role:admin,owner,karyawan'])->group(function () {
        
        // Customer Routes
        Route::resource('customers', CustomerController::class);
        Route::get('customers/search', [CustomerController::class, 'search'])->name('customers.search');
        Route::patch('customers/{customer}/toggle-member', [CustomerController::class, 'toggleMember'])->name('customers.toggle-member');
        
        // Package Routes
        Route::resource('packages', PackageController::class);
        Route::patch('packages/{package}/toggle-status', [PackageController::class, 'toggleStatus'])->name('packages.toggle-status');
        Route::get('api/packages/active', [PackageController::class, 'getActive'])->name('api.packages.active');
        
        // Transaction Routes
        Route::resource('transactions', TransactionController::class);
        Route::patch('transactions/{transaction}/status', [TransactionController::class, 'updateStatus'])->name('transactions.update-status');
        Route::patch('transactions/{transaction}/delivery-status', [TransactionController::class, 'updateDeliveryStatus'])->name('transactions.update-delivery-status');
        Route::get('transactions/{transaction}/invoice', [TransactionController::class, 'printInvoice'])->name('transactions.invoice');
        Route::get('api/discount-preview', [TransactionController::class, 'getDiscountPreview'])->name('api.discount-preview');
        
    });
    
    // Admin & Owner Only Routes
    Route::middleware(['role:admin,owner'])->group(function () {
        
        // User Management Routes
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        
        // Discount Management Routes
        Route::resource('discounts', DiscountController::class);
        Route::patch('discounts/{discount}/toggle-status', [DiscountController::class, 'toggleStatus'])->name('discounts.toggle-status');
        
        // Reports Routes
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/transactions', [ReportController::class, 'transactions'])->name('transactions');
            Route::get('/customers', [ReportController::class, 'customers'])->name('customers');
            Route::get('/packages', [ReportController::class, 'packages'])->name('packages');
            Route::get('/export/transactions', [ReportController::class, 'exportTransactions'])->name('export.transactions');
            Route::get('/export/customers', [ReportController::class, 'exportCustomers'])->name('export.customers');
            Route::get('/export/packages', [ReportController::class, 'exportPackages'])->name('export.packages');
        });
        
    });
});
