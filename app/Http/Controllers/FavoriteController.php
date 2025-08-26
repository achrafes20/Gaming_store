<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Auth::user()->favorites()->with('product')->get();
        return view('favorites', compact('favorites'));
    }
    public function store($productId)
    {
        $product = Product::findOrFail($productId);
        if (! Auth::user()->favorites()->where('product_id', $productId)->exists()) {
            Favorite::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
            ]);
        }
        return back()->with('success', 'Product added to favorites!');
    }

    public function destroy($productId)
    {
        Auth::user()->favorites()->where('product_id', $productId)->delete();
        return back()->with('success', 'Product removed from favorites.');
    }
    public function toggle($productId)
    {
        $favorite = Auth::user()->favorites()->where('product_id', $productId)->first();
        if ($favorite) {
            $favorite->delete();
            return back()->with('success', 'Product removed from favorites.');
        } else {
            Favorite::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
            ]);
            return back()->with('success', 'Product added to favorites!');
        }
    }
}
