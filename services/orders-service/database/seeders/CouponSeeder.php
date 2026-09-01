<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

/** A mix of active and expired coupons — so the admin coupon list, and a real checkout-time coupon demo, both have something real to show. */
class CouponSeeder extends Seeder
{
    public function run(): void
    {
        Coupon::create(['code' => 'WELCOME10', 'discount' => 10, 'type' => 'percent', 'usage_limit' => null, 'expires_at' => now()->addMonths(6)]);
        Coupon::create(['code' => 'SAVE20', 'discount' => 20, 'type' => 'fixed', 'usage_limit' => 50, 'expires_at' => now()->addMonths(3)]);
        Coupon::create(['code' => 'GAMER15', 'discount' => 15, 'type' => 'percent', 'usage_limit' => 100, 'expires_at' => now()->addYear()]);
        Coupon::create(['code' => 'SUMMER2025', 'discount' => 25, 'type' => 'percent', 'usage_limit' => 10, 'expires_at' => now()->subMonth()]); // expired on purpose, demonstrates the expiry check
    }
}
