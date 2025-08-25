<?php

use App\Http\Controllers\FirstController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\EmailsController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Auth;

Route::get('/', [FirstController::class, 'MainPage']);
Route::get('/categories', [FirstController::class, 'Categories_page'])->name('cats');
Route::get('/product/{catid?}', [FirstController::class, 'Product_page'])->name('prods');
Route::post('/storereview', [FirstController::class, 'storereview']);
Route::get('/reviews', [FirstController::class, 'reviews']);
Route::post('/search', [FirstController::class, 'search']);
Route::get('/addproduct', [ProductController::class, 'AddProduct'])->middleware('customauth2');
Route::post('/storeproduct', [ProductController::class, 'storeproduct'])->middleware('customauth2');
Route::post('/removeproduct/{productid?}', [ProductController::class, 'RemoveProducts'])->middleware('customauth2');
Route::get('/editproduct/{productid?}', [ProductController::class, 'EditProducts'])->middleware('customauth2');
Route::get('/addcategory', [CategoryController::class, 'Addcategory'])->middleware('customauth2');
Route::post('/storecategory', [CategoryController::class, 'storecategory'])->middleware('customauth2');
Route::post('/removecategory/{categoryid?}', [CategoryController::class, 'Removecategory'])->middleware('customauth2');
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/ProductsTable', [App\Http\Controllers\ProductController::class, 'ProductsTable'])->middleware('customauth2');
Route::get('/cart', [App\Http\Controllers\CartController::class, 'cart'])->middleware('customauth');
Route::get('/addproducttocart/{productid}',[App\Http\Controllers\CartController::class, 'addproducttocart'] )->middleware('auth');
Route::get('/deletecartitem/{cartid}',[App\Http\Controllers\CartController::class, 'deletecartitem']);
Route::get('/AddProductImages/{productid}', [ProductController::class, 'AddProductImages'])->middleware('customauth2');
Route::post('/removeproductphoto/{productid}', [ProductController::class, 'removeproductphoto'])->middleware('customauth2');
Route::post('/storeProductImage', [ProductController::class, 'storeProductImage'])->middleware('customauth2');
Route::get('/single-product/{productid}', [ProductController::class, 'showProduct']);
Route::get('/Completeorder', [CartController::class, 'Completeorder'])->middleware('auth');
Route::post('/StoreOrder', [CartController::class, 'StoreOrder'])->middleware('auth');
Route::get('/previousorder', [CartController::class, 'previousorder'])->middleware('auth');
Route::get('/categoryadmin', [CategoryController::class, 'categoryadmin']);
Route::post('/coupon/apply', [CouponController::class, 'apply'])->name('coupon.apply');
Route::post('/coupon/remove', [CouponController::class, 'remove'])->name('coupon.remove');
Route::get('/addcoupon', [CouponController::class, 'addcoupon'])->middleware('customauth2');
Route::post('/storecoupon', [CouponController::class, 'storecoupon'])->middleware('customauth2');
Route::get('/coupons', [CouponController::class, 'coupons']);
Route::post('/RemoveCoupon/{couponid?}', [CouponController::class, 'RemoveCoupon'])->middleware('customauth2');
Route::get('/users', [UsersController::class, 'users']);
Route::get('/Users_admin/{userid?}', [UsersController::class, 'users_admin'])->middleware('customauth2');
Route::get('/Users_client/{userid?}', [UsersController::class, 'users_client'])->middleware('customauth2');
Route::get('/cart_increment/{cartid}', [CartController::class, 'cart_increment']);
Route::get('/cart_decrement/{cartid}', [CartController::class, 'cart_decrement']);
Route::get('/orders', [FirstController::class, 'orders']);
Route::post('/sub', [FirstController::class, 'sub']);
Auth::routes(['verify' => true]);
Route::post('/products/{product}/review', [ReviewController::class, 'store'])->name('review_products.store');
Route::middleware('auth')->group(function () {
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{productId}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{productId}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::post('/favorites/toggle/{productId}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

});

