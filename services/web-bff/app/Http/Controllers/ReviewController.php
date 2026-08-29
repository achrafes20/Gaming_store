<?php

namespace App\Http\Controllers;

use App\Services\CatalogClient;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, $product, CatalogClient $catalog)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        $result = $catalog->storeReview($product, $request->only('rating', 'comment'));

        if ($result['status'] !== 201) {
            return back()->with('error', $result['body']->message ?? 'You are not allowed to review this product.');
        }

        return back()->with('success', 'Thank you! Your review has been submitted.');
    }
}
