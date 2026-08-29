<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\CouponController;
use Illuminate\Support\Facades\Route;

// Internal, service-to-service only (called by catalog-service) — see docs/architecture.md.
Route::get('/internal/has-purchased', [CheckoutController::class, 'hasPurchased']);

Route::middleware('jwt.auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::post('/cart/{cart}/increment', [CartController::class, 'increment']);
    Route::post('/cart/{cart}/decrement', [CartController::class, 'decrement']);
    Route::delete('/cart/{cart}', [CartController::class, 'destroy']);

    Route::get('/orders', [CheckoutController::class, 'index']);
    Route::post('/orders', [CheckoutController::class, 'store']);

    Route::post('/coupons/preview', [CouponController::class, 'preview']);
});

Route::middleware('jwt.auth:admin')->group(function () {
    Route::get('/coupons', [CouponController::class, 'index']);
    Route::post('/coupons', [CouponController::class, 'store']);
    Route::put('/coupons/{coupon}', [CouponController::class, 'update']);
    Route::delete('/coupons/{coupon}', [CouponController::class, 'destroy']);

    Route::get('/admin/orders', [CheckoutController::class, 'all']);
});
