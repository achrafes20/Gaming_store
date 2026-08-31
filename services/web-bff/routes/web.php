<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FirstController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FirstController::class, 'MainPage']);
Route::get('/categories', [FirstController::class, 'Categories_page'])->name('cats');
Route::get('/product/{catid?}', [FirstController::class, 'Product_page'])->name('prods');
Route::get('/reviews', [FirstController::class, 'reviews']);
Route::post('/search', [FirstController::class, 'search']);
Route::get('/single-product/{productid}', [ProductController::class, 'showProduct']);
Route::post('/sub', [FirstController::class, 'sub']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:auth');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:auth');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{productId}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{productId}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::post('/favorites/toggle/{productId}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/Completeorder', [CartController::class, 'Completeorder']);
    Route::post('/StoreOrder', [CartController::class, 'StoreOrder'])->middleware('throttle:checkout');
    Route::get('/previousorder', [CartController::class, 'previousorder']);
    Route::post('/addproducttocart/{productid}', [CartController::class, 'addproducttocart']);
    Route::get('/cart', [CartController::class, 'cart']);
    Route::post('/storereview', [FirstController::class, 'storereview']);
    Route::post('/cart_increment/{cartid}', [CartController::class, 'cart_increment']);
    Route::post('/cart_decrement/{cartid}', [CartController::class, 'cart_decrement']);
    Route::post('/deletecartitem/{cartid}', [CartController::class, 'deletecartitem']);
    Route::post('/coupon/apply', [CouponController::class, 'apply'])->name('coupon.apply');
    Route::post('/coupon/remove', [CouponController::class, 'remove'])->name('coupon.remove');
    Route::post('/products/{product}/review', [ReviewController::class, 'store'])->name('review_products.store');
});

Route::middleware('admin')->group(function () {
    Route::get('/addproduct', [ProductController::class, 'AddProduct']);
    Route::post('/storeproduct', [ProductController::class, 'storeproduct']);
    Route::post('/removeproduct/{productid?}', [ProductController::class, 'RemoveProducts']);
    Route::get('/editproduct/{productid?}', [ProductController::class, 'EditProducts']);
    Route::post('/removereview/{reviewid?}', [FirstController::class, 'RemoveReview']);
    Route::get('/addcategory', [CategoryController::class, 'Addcategory']);
    Route::post('/storecategory', [CategoryController::class, 'storecategory']);
    Route::post('/removecategory/{categoryid?}', [CategoryController::class, 'Removecategory']);
    Route::get('/ProductsTable', [ProductController::class, 'ProductsTable']);
    Route::get('/AddProductImages/{productid}', [ProductController::class, 'AddProductImages']);
    Route::post('/removeproductphoto/{productid}', [ProductController::class, 'removeproductphoto']);
    Route::post('/storeProductImage', [ProductController::class, 'storeProductImage']);
    Route::get('/addcoupon', [CouponController::class, 'addcoupon']);
    Route::post('/storecoupon', [CouponController::class, 'storecoupon']);
    Route::post('/RemoveCoupon/{couponid?}', [CouponController::class, 'RemoveCoupon']);
    Route::post('/Users_admin/{userid?}', [UsersController::class, 'users_admin']);
    Route::post('/Users_client/{userid?}', [UsersController::class, 'users_client']);
    Route::get('/orders', [FirstController::class, 'orders']);
    Route::get('/users', [UsersController::class, 'users']);
    Route::get('/categoryadmin', [CategoryController::class, 'categoryadmin']);
    Route::get('/coupons', [CouponController::class, 'coupons']);
});
