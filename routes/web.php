<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.submit');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get(
        '/purchase-orders/create',
        [PurchaseOrderController::class, 'create']
    )->name('purchase-orders.create');

    Route::post(
        '/purchase-orders',
        [PurchaseOrderController::class, 'store']
    )->name('purchase-orders.store');

    Route::get(
        '/purchase-orders/{purchaseOrder}',
        [PurchaseOrderController::class, 'show']
    )->name('purchase-orders.show');

    Route::patch(
        '/purchase-orders/{purchaseOrder}/status',
        [PurchaseOrderController::class, 'updateStatus']
    )->name('purchase-orders.update-status');

    Route::get('/inventory', [InventoryController::class, 'index'])
    ->name('inventory.index');
});

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});