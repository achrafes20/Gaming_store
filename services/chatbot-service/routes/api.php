<?php

use App\Http\Controllers\Api\ChatController;
use Illuminate\Support\Facades\Route;

Route::middleware(['jwt.auth', 'throttle:chat'])->group(function () {
    Route::post('/chat', [ChatController::class, 'store']);
});
