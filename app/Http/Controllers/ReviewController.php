<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review_Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class ReviewController extends Controller
{
    public function store(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $user = Auth::user();
        $hasBought = DB::table('orderdetails')
            ->join('orders', 'orderdetails.order_id', '=', 'orders.id')
            ->where('orderdetails.product_id', $productId)
            ->where('orders.user_id', $user->id)
            ->exists();
        $alreadyReviewed = $product->review_products()
            ->where('user_id', $user->id)
            ->exists();

        if (! $hasBought || $alreadyReviewed) {
            return back()->with('error', 'You are not allowed to review this product.');
        }
        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'comment'  => 'required|string',
            'photos.*' => 'image|max:2048'
        ]);
        Review_Product::create([
            'product_id' => $productId,
            'user_id'    => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        return back()->with('success', 'Thank you! Your review has been submitted.');
    }
}
