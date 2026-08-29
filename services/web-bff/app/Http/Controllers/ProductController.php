<?php

namespace App\Http\Controllers;

use App\Services\CatalogClient;
use App\Services\OrdersClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function showProduct($productid, CatalogClient $catalog, OrdersClient $orders)
    {
        $result = $catalog->product($productid);

        if ($result['status'] !== 200) {
            abort(404);
        }

        $product = $result['body'];
        $product->reviewProducts = collect($product->reviewProducts ?? []);

        $related = collect($catalog->products(['category_id' => $product->category_id])['body']->data ?? [])
            ->reject(fn ($p) => $p->id === $product->id)
            ->take(4);

        $canReview = false;
        if (Session::has('jwt')) {
            $reviewed = $product->reviewProducts->contains(fn ($r) => $r->user_id === Session::get('user')->id);
            $canReview = ! $reviewed;
        }

        return view('showProduct', [
            'product' => $product,
            'relatedProducts' => $related,
            'canReview' => $canReview,
        ]);
    }

    public function AddProduct(CatalogClient $catalog)
    {
        $allcategories = collect($catalog->categories()['body']);

        return view('addproduct', ['allcategories' => $allcategories]);
    }

    public function EditProducts($productid, CatalogClient $catalog)
    {
        $result = $catalog->product($productid);

        if ($result['status'] !== 200) {
            abort(403, "Can't find this product");
        }

        $allcategories = collect($catalog->categories()['body']);

        return view('editproduct', ['allcategories' => $allcategories, 'product' => $result['body']]);
    }

    public function RemoveProducts($productid, CatalogClient $catalog)
    {
        $catalog->deleteProduct($productid);

        return back()->with('success', 'Product deleted successfully!');
    }

    public function storeproduct(Request $request, CatalogClient $catalog)
    {
        $request->validate([
            'name' => ['required'],
            'price' => 'required|integer',
            'quantity' => 'required|integer',
            'description' => 'required',
            'category_id' => 'required',
            'photo' => $request->id ? 'nullable|image|max:2048' : 'required|image|max:2048',
        ]);

        $data = $request->only('name', 'price', 'quantity', 'description', 'category_id');
        $files = ['photo' => $request->file('photo'), 'photos' => $request->file('photos.0')];

        if ($request->id) {
            $catalog->updateProduct($request->id, $data, array_filter($files));

            return redirect('/ProductsTable')->with('success', 'Product updated successfully!');
        }

        $catalog->createProduct($data, array_filter($files));

        return redirect('/ProductsTable')->with('success', 'Product added successfully!');
    }

    public function ProductsTable(CatalogClient $catalog)
    {
        $products = collect($catalog->products(['per_page' => 100])['body']->data ?? []);

        return view('ProductsTable', ['products' => $products]);
    }

    public function AddProductImages($productid, CatalogClient $catalog)
    {
        $result = $catalog->product($productid);
        $product = $result['body'];

        return view('AddProductImage', ['product' => $product, 'productImages' => collect($product->product_photos ?? [])]);
    }

    public function removeproductphoto($productid)
    {
        // Photo deletion is a catalog-service admin concern; not yet exposed as
        // a dedicated endpoint there — tracked as a follow-up (see plan.md).
        return back()->with('error', 'Not implemented yet.');
    }

    public function storeProductImage(Request $request, CatalogClient $catalog)
    {
        $request->validate(['product_id' => 'required', 'photos' => 'required']);

        $catalog->updateProduct($request->product_id, [], ['photos' => $request->file('photos.0')]);

        return back()->with('success', 'Product images uploaded successfully!');
    }
}
