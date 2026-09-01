<?php

namespace Database\Seeders;

use App\Models\Favorite;
use Illuminate\Database\Seeder;

/**
 * A few favorites per client account, so the /favorites page (and the
 * heart-icon state on the storefront) has something to show out of the
 * box. `product_id` here assumes catalog-service's own seeders ran on a
 * fresh database too (IDs 1-26, see ProductSeeder) — a documented
 * convention for this demo dataset, not a real cross-service dependency
 * (no FK: see docs/architecture.md).
 */
class FavoriteSeeder extends Seeder
{
    /** user_id (2-5, the 4 seeded clients — see UserSeeder) => product_ids. */
    private const FAVORITES = [
        2 => [4, 8, 15],
        3 => [1, 11, 20],
        4 => [6, 13, 22],
        5 => [2, 9, 24],
    ];

    public function run(): void
    {
        foreach (self::FAVORITES as $userId => $productIds) {
            foreach ($productIds as $productId) {
                Favorite::create(['user_id' => $userId, 'product_id' => $productId]);
            }
        }
    }
}
