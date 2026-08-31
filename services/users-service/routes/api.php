<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SubController;
use App\Http\Controllers\Api\UserAdminController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:auth');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:auth');

Route::post('/sub', [SubController::class, 'store']);
Route::get('/reviews', [ReviewController::class, 'index']);
Route::post('/reviews', [ReviewController::class, 'store']);

Route::middleware('jwt.auth')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites/{productId}/toggle', [FavoriteController::class, 'toggle']);
});

Route::middleware('jwt.auth:admin')->group(function () {
    Route::get('/users', [UserAdminController::class, 'index']);
    Route::post('/users/{user}/promote', [UserAdminController::class, 'promote']);
    Route::post('/users/{user}/demote', [UserAdminController::class, 'demote']);
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);
});
