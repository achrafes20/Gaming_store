<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ReviewProduct;
use Illuminate\Database\Seeder;

/**
 * A handful of product reviews so the storefront shows real social proof
 * instead of an empty "no reviews yet" state on every product page.
 * `user_id` here doesn't correspond to a real users-service account (no FK
 * across services — see docs/architecture.md) — fine for display purposes,
 * since only the denormalized name/email/rating/comment ever render.
 */
class ReviewProductSeeder extends Seeder
{
    private const REVIEWS = [
        ['Sarah Chen', 'sarah.chen@example.com', 5, 'Exactly what I needed for ranked matches — noticeably snappier than my old gear.'],
        ['Marcus Johnson', 'marcus.j@example.com', 4, 'Great value for the price. Build quality feels premium.'],
        ['Priya Patel', 'priya.p@example.com', 5, 'Setup was painless and it looks fantastic on my desk.'],
        ['Tom Becker', 'tom.becker@example.com', 4, 'Does exactly what it says on the box. Would buy again.'],
        ['Aisha Rahman', 'aisha.r@example.com', 5, "Shipped fast and it's exceeded my expectations so far."],
    ];

    public function run(): void
    {
        $products = Product::inRandomOrder()->limit(12)->get();

        foreach ($products as $index => $product) {
            $review = self::REVIEWS[$index % count(self::REVIEWS)];

            ReviewProduct::create([
                'product_id' => $product->id,
                'user_id' => $index + 1,
                'name' => $review[0],
                'email' => $review[1],
                'rating' => $review[2],
                'comment' => $review[3],
            ]);
        }
    }
}
