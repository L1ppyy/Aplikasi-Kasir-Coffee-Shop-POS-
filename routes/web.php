<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, CashierController};
use App\Http\Controllers\Admin\{
    DashboardController, ProductController, CategoryController,
    UserController, TransactionController, ReportController,
    SettingController, ExpenseController
};

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Cashier
Route::middleware(['auth'])->prefix('kasir')->name('cashier.')->group(function () {
    Route::get('/', [CashierController::class, 'index'])->name('index');
    Route::get('/produk', [CashierController::class, 'getProducts'])->name('products');
    Route::post('/checkout', [CashierController::class, 'checkout'])->name('checkout');
    Route::get('/riwayat', [CashierController::class, 'history'])->name('history');
});

// Admin
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', ProductController::class)->except('show');
    Route::post('products/{product}/stock', [ProductController::class, 'adjustStock'])->name('products.stock');

    Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit']);
    Route::resource('users', UserController::class)->except(['show', 'create', 'edit']);

    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::patch('transactions/{transaction}/status', [TransactionController::class, 'updateStatus'])->name('transactions.status');

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

    Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::delete('expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('reports/export/excel',[ReportController::class, 'exportExcel'])->name('reports.export.excel');
});
