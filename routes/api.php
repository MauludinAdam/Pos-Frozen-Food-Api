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
        ->middlewareFor(['index', 'show'],'role:Super Admin|Kasir')
        ->middlewareFor(['store', 'update', 'destroy'], 'role:Super Admin');

    Route::apiResource('/satuan', SatuanController::class)
        ->middlewareFor(['index', 'show'], 'role:Super Admin|Kasir')
        ->middlewareFor(['store', 'update', 'destroy'],'role:Super Admin');

    Route::get('/product/generate-code-product', [ProductController::class, 'generateProductCode' ]);
    Route::apiResource('/product', ProductController::class)
        ->middlewareFor(['index', 'show'], 'role:Super Admin|Kasir|Admin')
        ->middlewareFor(['store', 'update', 'destroy'], 'role:Super Admin');

    Route::apiResource('/transaction', TransactionController::class)
        ->middleware('role:Super Admin|Kasir');

    Route::apiResource('/detail-transaction', DetailTransactionController::class)
        ->middleware('role:Super Admin|Kasir');

    Route::get('/stock-transaction/generate-number-transaction', [StockTransactionController::class, 'generateTransactionNumber']);
    Route::apiResource('/stock-transaction', StockTransactionController::class)
        ->middleware('role:Super Admin|Admin');

    Route::apiResource('/transaction-return', TransactionReturnController::class)
        ->middleware('role:Super Admin|Admin');
    
    Route::apiResource('/supplier', SupplierController::class)
        ->middleware('role:Super Admin|Admin');

    Route::apiResource('/purchase-order', PurchaseOrderController::class)
        ->middleware('role:Super Admin|Admin');

    Route::apiResource('/goods-receipt', GoodReceiptController::class)
        ->middleware('role:Super Admin|Admin');

    Route::get('/stock-opname/report/data', [StockInController::class, 'reportData']);
    Route::get('/stock-opname/report/pdf', [StockInController::class, 'report']);
});

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware('auth:sanctum')->post('/me', [AuthController::class, 'me'])->name('me');
Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'me'])->name('me');
Route::middleware('auth:sanctum')->put('/me', [AuthController::class, 'update'])->name('me.update');
