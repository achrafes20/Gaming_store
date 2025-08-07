<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductPhot;
use App\Models\ProductPhoto;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Milon\Barcode\DNS1D;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProductController extends Controller
{
    public function AddProduct()
    {
        //$user=Auth::user()->email;
        //dd($user);
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


            $qrCode = QrCode::size(200)->generate('https://google.com');
            $d = new DNS1D();
            $barcode = $d->getBarcodeHTML('002010698648', 'C39');



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
            return redirect('/product');
        } else {
            abort(403, "please enter product id in the route");
        }
    }




    public function storeproduct(Request $request)
    {
        $request->validate([
            'name' => ['required',  'max:100'], //unique:products dans le meme nom dans la DB
            'price' => 'required|integer',
            'quantity' => 'required|integer',
            'description' => 'required',
            'photo' => 'image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ]);
        //Modification

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
            return redirect('/product');

            //Ajouter un nouveau produit
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
            return redirect('/');
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
            'photo' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);


        $photo = new ProductPhoto();
        $photo->product_id = $request->product_id;

        $path = '';
        if ($request->has('photo')) {
            $path = $request->photo->move(
                'uploads',
                Str::uuid()->toString() . '-' .  $request->photo->getClientOriginalName()
            );
            $photo->imagepath = $path;
        }
        $photo->save();
        return redirect('/ProductsTable');
    }

    public function showProduct($productid)
    {
        $product = Product::with('Category', 'ProductPhotos')->find($productid); //tu utilise with quand tu modifie models
        $relatedProducts = Product::where('category_id', $product->category_id)->where('id', '!=', $productid)
            ->inRandomOrder()
            ->limit(4)
            ->get();
        return view('showProduct', ['product' => $product, 'relatedProducts' => $relatedProducts]);
    }
}
