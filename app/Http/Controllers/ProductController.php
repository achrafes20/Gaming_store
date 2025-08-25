<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Categories;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductPhot;
use App\Models\ProductPhoto;
use App\Models\Review_Product;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Milon\Barcode\DNS1D;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function AddProduct()
    {

        $allcategories = Categories::all();

        return view('addproduct', ['allcategories' => $allcategories]);
    }

    public function EditProducts($productid = null)
    {
        if ($productid != null) {
            $currentProduct = Product::find($productid);
            if ($currentProduct == null) {
                abort("403", "Can't Find this product");
            }
            $allcategories = Categories::all();


            return view('editproduct', ['allcategories' => $allcategories, 'product' => $currentProduct, 'qrCode' => $qrCode, 'barcode' => $barcode]);
        } else {
            return redirect("/addproduct");
        }
    }



    public function RemoveProducts($productid = null)
    {
        if ($productid != null) {
            $currentProduct = Product::find($productid);
            $currentProduct->delete();
            return redirect()->back();
        } else {
            abort(403, "please enter product id in the route");
        }
    }




    public function storeproduct(Request $request)
    {

        $request->validate([
            'name' => ['required'],
            'price' => 'required|integer',
            'quantity' => 'required|integer',
            'description' => 'required',
            'photo' => 'image|max:2048',
            'photos.*' => 'image|max:2048',
        ]);


        if ($request->id) {
            $currentProduct = Product::find($request->id);
            $currentProduct->name = $request->name;
            $currentProduct->price = $request->price;
            $currentProduct->quantity = $request->quantity;
            $currentProduct->description = $request->description;
            $currentProduct->category_id = $request->category_id;

            if ($request->has('photo')) {
                $path = $request->photo->move(
                    'uploads',
                    Str::uuid()->toString() . '-' .  $request->photo->getClientOriginalName()
                );
                $currentProduct->imagepath = $path;
            }

            $currentProduct->save();


            if ($request->hasfile('photos')) {
                foreach ($request->file('photos') as $file) {
                    $photo = new ProductPhoto();
                    $photo->product_id = $currentProduct->id;

                    $path = $file->move(
                        'uploads',
                        Str::uuid()->toString() . '-' . $file->getClientOriginalName()
                    );

                    $photo->imagepath = $path;
                    $photo->save();
                }
            }

            return redirect()->back();
        } else {
            $newProduct = new Product();
            $newProduct->name = $request->name;
            $newProduct->price = $request->price;
            $newProduct->quantity = $request->quantity;
            $newProduct->description = $request->description;
            $newProduct->category_id = $request->category_id;

            $path = '';
            if ($request->has('photo')) {
                $path = $request->photo->move(
                    'uploads',
                    Str::uuid()->toString() . '-' .  $request->photo->getClientOriginalName()
                );
            }

            $newProduct->imagepath = $path;
            $newProduct->save();


            if ($request->hasfile('photos')) {
                foreach ($request->file('photos') as $file) {
                    $photo = new ProductPhoto();
                    $photo->product_id = $newProduct->id;

                    $path = $file->move(
                        'uploads',
                        Str::uuid()->toString() . '-' . $file->getClientOriginalName()
                    );

                    $photo->imagepath = $path;
                    $photo->save();
                }
            }

            return redirect('/ProductsTable');
        }
    }

    public function ProductsTable()
    {
        $products = Product::all();
        return view('ProductsTable', ['products' => $products]);
    }


    public function AddProductImages($productid)
    {
        $product = Product::find($productid);
        $productImages = ProductPhoto::where('product_id', $productid)->get();
        return view('AddProductImage', ['product' => $product, 'productImages' => $productImages]);
    }

    public function removeproductphoto($imageid = null)
    {
        if ($imageid) {
            $photo = ProductPhoto::find($imageid);
            $photo->delete();
            return redirect('/ProductsTable');
        } else {
            abort(403, "Please enter image id in the route");
        }
    }


    public function storeProductImage(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'photos.*' => 'image|max:2048',
            'photos' => 'required',
        ]);

        if ($request->hasfile('photos')) {
            foreach ($request->file('photos') as $file) {
                $photo = new ProductPhoto();
                $photo->product_id = $request->product_id;

                $path = $file->move(
                    'uploads',
                    Str::uuid()->toString() . '-' . $file->getClientOriginalName()
                );

                $photo->imagepath = $path;
                $photo->save();
            }
        }

        return redirect('/ProductsTable');
    }

    public function showProduct($productid)
{
    $product = Product::with('Category', 'ProductPhotos', 'review_products.user')->findOrFail($productid);

    $relatedProducts = Product::where('category_id', $product->category_id)
        ->where('id', '!=', $productid)
        ->inRandomOrder()
        ->limit(4)
        ->get();

    $cart = Cart::find($productid);

    $user = auth()->user();
    $canReview = false;
    $alreadyReviewed = false;

    if ($user) {
        // Vérifier si l’utilisateur a déjà acheté ce produit
        $hasBought = DB::table('orderdetails')
    ->join('orders', 'orderdetails.order_id', '=', 'orders.id')
    ->where('orderdetails.product_id', $productid)
    ->where('orders.user_id', $user->id)
    ->exists();

        // Vérifier si l’utilisateur a déjà laissé un avis
        $alreadyReviewed = $product->review_products()->where('user_id', $user->id)->exists();

        $canReview = $hasBought && !$alreadyReviewed;
    }

    return view('showProduct', [
        'product' => $product,
        'relatedProducts' => $relatedProducts,
        'cart' => $cart,
        'canReview' => $canReview
    ]);
}



}
