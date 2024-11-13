<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PicklistController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPriceController;
use App\Http\Controllers\ProductStockController;
use App\Http\Controllers\RecapController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\ReturnDetailController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SummaryTransactionsController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionReturnController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
 
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/account', [AuthController::class, 'account']);
    Route::middleware('checkRole:admin')->group(function() {
        Route::get('/dashboard/counter-data', [DashboardController::class, 'counterData']);
        Route::apiResource('/product-categories', ProductCategoryController::class);
        Route::apiResource('/users', UserController::class);
        Route::apiResource('/customers', CustomerController::class);
        Route::apiResource('/products', ProductController::class);
        Route::apiResource('/product-prices', ProductPriceController::class);
        Route::apiResource('/transactions', TransactionController::class);
        Route::get('/transaction-returns/{transaction_id}', [TransactionReturnController::class,'index']);
        Route::post('/transaction-returns/{transaction_id}', [TransactionReturnController::class,'store']);
        // Route::apiResource('/returns', ReturnController::class);
        // Route::apiResource('/return-details', ReturnDetailController::class);
        Route::apiResource('/reports', ReportController::class);
        Route::apiResource('/product-stocks', ProductStockController::class);
        Route::apiResource('/stock-movements', StockMovementController::class);
        Route::apiResource('/summary-transaction', SummaryTransactionsController::class);
        Route::post('/close-summary', [SummaryTransactionsController::class, 'close']);
        Route::get('/recaps', [RecapController::class, 'getTransactionSummaryCart']);
        Route::get('/popular', [RecapController::class, 'getPopularCategoryCart']);
        Route::post('/transaction/new-payment', [TransactionController::class, 'createPayment']);
        
        Route::prefix('/options')->group(function () {
            Route::get('/category', [PicklistController::class, 'category']);
        });
    });
    Route::middleware('checkRole:cashier,admin')->group(function() {
        Route::get('/dashboard/counter-data', [DashboardController::class, 'counterData']);
        Route::apiResource('/customers', CustomerController::class);
        Route::apiResource('/products', ProductController::class);
        Route::apiResource('/transactions', TransactionController::class);
        Route::apiResource('/returns', ReturnController::class);
        Route::apiResource('/return-details', ReturnDetailController::class);
        Route::apiResource('/reports', ReportController::class);
        
        Route::get('/check-open', [SummaryTransactionsController::class, 'check_open']);
        
        Route::get('/transactions/{id}/print-struck', [TransactionController::class, 'print_struck']);

        Route::prefix('/options')->group(function () {
            Route::get('/category', [PicklistController::class, 'category']);
        });
    });
// general route
});

