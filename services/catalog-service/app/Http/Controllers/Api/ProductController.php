<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('Category', 'ProductPhotos');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->q.'%');
        }

        return $query->paginate(20);
    }

    public function show(Product $product)
    {
        return $product->load('Category', 'ProductPhotos', 'reviewProducts');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required'],
            'price' => 'required|integer',
            'quantity' => 'required|integer',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'photo' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'photos.*' => 'image|mimes:jpeg,jpg,png,gif,webp|max:2048',
        ]);

        $path = (string) $request->photo->move(
            'uploads',
            Str::uuid()->toString().'-'.$request->photo->getClientOriginalName()
        );

        $product = Product::create([...$data, 'imagepath' => $path]);

        $this->storeExtraPhotos($request, $product);

        return response()->json($product->load('ProductPhotos'), 201);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => ['required'],
            'price' => 'required|integer',
            'quantity' => 'required|integer',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'photos.*' => 'image|mimes:jpeg,jpg,png,gif,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['imagepath'] = (string) $request->photo->move(
                'uploads',
                Str::uuid()->toString().'-'.$request->photo->getClientOriginalName()
            );
        }

        $product->update($data);

        $this->storeExtraPhotos($request, $product);

        return $product->load('ProductPhotos');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->noContent();
    }

    /** Decrement stock after a checkout — called internally by orders-service. */
    public function decrementStock(Request $request, Product $product)
    {
        $data = $request->validate(['quantity' => 'required|integer|min:1']);

        if ($product->quantity < $data['quantity']) {
            return response()->json(['message' => 'Insufficient stock.'], 409);
        }

        $product->decrement('quantity', $data['quantity']);

        return $product;
    }

    private function storeExtraPhotos(Request $request, Product $product): void
    {
        if (! $request->hasFile('photos')) {
            return;
        }

        foreach ($request->file('photos') as $file) {
            $path = (string) $file->move(
                'uploads',
                Str::uuid()->toString().'-'.$file->getClientOriginalName()
            );

            ProductPhoto::create([
                'product_id' => $product->id,
                'imagepath' => $path,
            ]);
        }
    }
}
