<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/getproductsdata', [ApiController::class,'getProductsData']);
Route::post('/xsubmit', [ApiController::class,'postData']);
