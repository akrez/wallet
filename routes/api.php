<?php

use App\Http\Controllers\AuthController;
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

Route::prefix('v1')->middleware('throttle:50,1')->group(function () {
    //
    Route::prefix('auth')->middleware('api')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });
    //
    Route::middleware('auth')->group(function () {
        //
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/profile', [AuthController::class, 'profile']);
        //
        Route::resource('payments', PaymentController::class)->only(['index', 'store', 'show', 'destroy']);
        Route::patch('payments/{payment}/reject', [PaymentController::class, 'reject']);
        Route::patch('payments/{payment}/approve', [PaymentController::class, 'approve']);
        //
        Route::resource('currencies', CurrencyController::class)->only(['index', 'store', 'show']);
        Route::patch('currencies/{currency}/active', [CurrencyController::class, 'active']);
        Route::patch('currencies/{currency}/deactive', [CurrencyController::class, 'deactive']);
        //
        Route::post('deposit/transfer', [DepositController::class, 'transfer']);
    });
});
