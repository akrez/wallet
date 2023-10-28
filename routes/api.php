<?php

use App\Http\Controllers\v1\CurrencyController;
use App\Http\Controllers\v1\DepositController;
use App\Http\Controllers\v1\PaymentController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('v1')->group(function () {
    Route::get('payments', [PaymentController::class, 'index']);
    Route::post('payments', [PaymentController::class, 'store']);
    Route::get('payments/{payment}', [PaymentController::class, 'show']);
    Route::patch('payments/{payment}/reject', [PaymentController::class, 'reject']);
    Route::patch('payments/{payment}/approve', [PaymentController::class, 'approve']);
    //
    Route::get('currencies', [CurrencyController::class, 'index']);
    Route::post('currencies', [CurrencyController::class, 'store']);
    Route::patch('currencies/{currency}/active', [CurrencyController::class, 'active']);
    Route::patch('currencies/{currency}/deactive', [CurrencyController::class, 'deactive']);
    //
    Route::post('deposit/transfer', [DepositController::class, 'transfer']);
});
