<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = auth()->user()->favorites()->with('product')->get();
        return view('favorites', compact('favorites'));
    }

    public function store($productId)
    {
        $product = Product::findOrFail($productId);

        // éviter doublons
        if (! auth()->user()->favorites()->where('product_id', $productId)->exists()) {
            Favorite::create([
                'user_id' => auth()->id(),
                'product_id' => $productId,
            ]);
        }

        return back()->with('success', 'Product added to favorites!');
    }

    public function destroy($productId)
    {
        auth()->user()->favorites()->where('product_id', $productId)->delete();
        return back()->with('success', 'Product removed from favorites.');
    }

    
    public function toggle($productId)
{
    $favorite = auth()->user()->favorites()->where('product_id', $productId)->first();

    if ($favorite) {
        $favorite->delete();
        return back()->with('success', 'Product removed from favorites.');
    } else {
        Favorite::create([
            'user_id' => auth()->id(),
            'product_id' => $productId,
        ]);
        return back()->with('success', 'Product added to favorites!');
    }
}
}
