<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DetailTransactionController;
use App\Http\Controllers\GoodReceiptController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionReturnController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;






Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('/category', CategoryController::class)
        ->middleware('role:admin');

    Route::apiResource('/satuan', SatuanController::class)
        ->middleware('role:admin');

    Route::apiResource('/product', ProductController::class)
        ->middleware('role:admin');

    Route::apiResource('/transaction', TransactionController::class)
        ->middleware('role:admin|kasir');

    Route::apiResource('/detail-transaction', DetailTransactionController::class)
        ->middleware('role:admin|kasir');

    Route::get('/stock-transaction', [StockTransactionController::class, 'index'])
        ->middleware('role:admin|admin gudang');

    Route::get('/stock-transaction/{id}', [StockTransactionController::class, 'show'])
        ->middleware('role:admin|admin gudang');

    Route::post('/stock-in', [StockInController::class, 'store'])
        ->middleware('role:admin|admin gudang');
        
    Route::post('/stock-out', [StockOutController::class, 'store'])
        ->middleware('role:admin|admin gudang');

    Route::apiResource('/transaction-return', TransactionReturnController::class)
        ->middleware('role:admin|admin gudang');
    
    Route::apiResource('/supplier', SupplierController::class)
        ->middleware('role:admin|admin gudang');

    Route::apiResource('/purchase-order', PurchaseOrderController::class)
        ->middleware('role:admin|admin gudang');

    Route::apiResource('/goods-receipt', GoodReceiptController::class)
        ->middleware('role:admin|admin gudang');

    Route::get('/stock-opname/report/data', [StockInController::class, 'reportData']);
    Route::get('/stock-opname/report/pdf', [StockInController::class, 'report']);
});

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware('auth:sanctum')->post('/me', [AuthController::class, 'me'])->name('me');
Route::middleware('auth:sanctum')->put('/me', [AuthController::class, 'update'])->name('me.update');
