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

Route::prefix('v1')->middleware(['throttle:60,1'])->group(function () {
    //
    Route::group(['middleware' => 'api', 'prefix' => 'auth',], function () {
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/me', [AuthController::class, 'me']);
    });
    //
    Route::prefix('payments')->group(function () {
        Route::get('/', [PaymentController::class, 'index']);
        Route::post('/', [PaymentController::class, 'store']);
        Route::get('/{payment}', [PaymentController::class, 'show']);
        Route::patch('/{payment}/reject', [PaymentController::class, 'reject']);
        Route::patch('/{payment}/approve', [PaymentController::class, 'approve']);
        Route::delete('/{payment}', [PaymentController::class, 'destroy']);
    });
    //
    Route::prefix('currencies')->group(function () {
        Route::get('/', [CurrencyController::class, 'index']);
        Route::post('/', [CurrencyController::class, 'store']);
        Route::patch('/{currency}/active', [CurrencyController::class, 'active']);
        Route::patch('/{currency}/deactive', [CurrencyController::class, 'deactive']);
    });
    //
    Route::post('deposit/transfer', [DepositController::class, 'transfer']);
    //
});
