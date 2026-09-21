<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\PurchaseOrderController;
use App\Http\Controllers\Api\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/inventory', [InventoryController::class, 'index']);

    Route::get('/inventory/low-stock', [InventoryController::class, 'lowStock']);

    Route::post(
    '/purchase-orders',
    [PurchaseOrderController::class, 'store']
);

Route::patch(
    '/purchase-orders/{purchaseOrder}/status',
    [PurchaseOrderController::class, 'updateStatus']
);

Route::get(
    '/reports/supplier-spend',
    [ReportController::class, 'supplierSpend']
);
});