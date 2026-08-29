<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ReviewProduct;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Product $product)
    {
        return $product->reviewProducts()->latest()->get();
    }

    public function store(Request $request, Product $product)
    {
        $user = $request->attributes->get('auth_user');

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        if (! $this->hasPurchased($user['id'], $product->id)) {
            return response()->json(['message' => 'You must have purchased this product to review it.'], 403);
        }

        if ($product->reviewProducts()->where('user_id', $user['id'])->exists()) {
            return response()->json(['message' => 'You have already reviewed this product.'], 409);
        }

        $review = ReviewProduct::create([
            'product_id' => $product->id,
            'user_id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'rating' => $data['rating'],
            'comment' => $data['comment'],
        ]);

        return response()->json($review, 201);
    }

    /** Asks orders-service (source of truth for orders) whether the user bought this product. */
    private function hasPurchased(int $userId, int $productId): bool
    {
        try {
            $client = new Client(['base_uri' => config('services.orders_service_url'), 'timeout' => 3]);
            $response = $client->get('/api/internal/has-purchased', [
                'query' => ['user_id' => $userId, 'product_id' => $productId],
            ]);

            return json_decode($response->getBody()->getContents(), true)['has_purchased'] ?? false;
        } catch (GuzzleException) {
            // orders-service unreachable: fail closed, review refused rather than trusting blindly.
            return false;
        }
    }
}
