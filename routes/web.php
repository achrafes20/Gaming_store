<?php

use App\Http\Controllers\FirstController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Categories;
use App\Models\Cart;
use App\Http\Controllers\CouponController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*Route::get('/product/{catid?}', function ($catid=null) {
    if(!$catid){
    $result=Categories::all();//$result=DB::table("products")->get();
    return view('product',["products"=>$result]);
}else{
$result=Product::where ("category_id",$catid)->get();//$result=DB::table("products")->where ("category_id",$catid)->get();
    return view('product',["products"=>$result]);
}});
/*

/*Route::get('/', function () {
    $result=DB::table("categories")->get();
    return view('welcome',["categories"=>$result]);
});*/


/*Route::get('/categories', function () {
    $result=DB::table("categories")->get();
    $result2=DB::table("products")->get();
    return view('categories',["categories"=>$result],["products"=>$result2]);
});
*/

Route::get('/', [FirstController::class, 'MainPage']);
Route::get('/categories', [FirstController::class, 'Categories_page'])->name('cats');//il mettre l url facil a modifier
Route::get('/product/{catid?}', [FirstController::class, 'Product_page'])->name('prods');
Route::post('/storereview', [FirstController::class, 'storereview']);
Route::get('/reviews', [FirstController::class, 'reviews']);

Route::post('/search', function(Request $request){
    $products=Product::where('name','like','%'.$request->searchkey.'%')->get();  //search query idea for insperation
    return view('product',['products' =>$products]);
});






Route::get('/addproduct', [ProductController::class, 'AddProduct']);//middleware oblige qu il doit etre connecté  tu peux faire if (auth()->check())
Route::post('/storeproduct', [ProductController::class, 'storeproduct']);
Route::get('/removeproduct/{productid?}', [ProductController::class, 'RemoveProducts']);
Route::get('/editproduct/{productid?}', [ProductController::class, 'EditProducts']);


Route::get('/addcategory', [CategoryController::class, 'Addcategory']);//middleware oblige qu il doit etre connecté  tu peux faire if (auth()->check())
Route::post('/storecategory', [CategoryController::class, 'storecategory']);
Route::get('/removecategory/{categoryid?}', [CategoryController::class, 'Removecategory']);





Auth::routes(); //pour cacher register car ne sert a rien Auth::routes(['register'=>false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/ProductsTable', [App\Http\Controllers\ProductController::class, 'ProductsTable']);
Route::get('/cart', [App\Http\Controllers\CartController::class, 'cart'])->middleware('customauth');
Route::get('/addproducttocart/{productid}', function($productid){
    $user_id=auth()->user()->id;
    $result=Cart::where('user_id',$user_id)->where("product_id",$productid)->first();//first rendre seulement le premier pas de repetition
    if($result){
        $result->quantity++;
        $result->save();
    }
    else{
    $newCart=new Cart();
    $newCart->product_id=$productid;
    $newCart->user_id=$user_id;
    $newCart->quantity=1;
    $newCart->save();
    }
    return redirect("/cart");
})->middleware('auth');

Route::get('/deletecartitem/{cartid}', function($cartid){
Cart::find($cartid)->delete();
return redirect('/cart');
});

Route::get('/AddProductImages/{productid}', [ProductController::class, 'AddProductImages']);
Route::get('/removeproductphoto/{productid}', [ProductController::class, 'removeproductphoto']);
Route::post('/storeProductImage', [ProductController::class, 'storeProductImage']);

Route::get('/single-product/{productid}', [ProductController::class, 'showProduct']);
Route::get('/Completeorder', [CartController::class, 'Completeorder'])->middleware('auth');
Route::post('/StoreOrder', [CartController::class, 'StoreOrder'])->middleware('auth');
Route::get('/previousorder', [CartController::class, 'previousorder'])->middleware('auth');

Route::post('/lang', function (Request $request) {
   session()->put('locale',$request->locale);
    return redirect()->back();//refresh la page
})->name('changeLanguage');


Route::get('/admin', function(){
    return "admin panel";
})->middleware('checkRole:admin,salesman');

Route::get('/test',[TestController::class,'test']);



Route::post('/coupon/apply', [CouponController::class, 'apply'])->name('coupon.apply');
