<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Payments;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Truncates first so this is safe to re-run — see catalog-service's
     * DatabaseSeeder for the same reasoning. Replaces the default
     * scaffold's single factory-generated `App\Models\User` row (that
     * model is dead code here — see docs/architecture.md — this service
     * doesn't own users) with real domain data.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Payments::truncate();
        OrderDetails::truncate();
        Order::truncate();
        DB::table('coupon_user')->truncate();
        Coupon::truncate();
        Schema::enableForeignKeyConstraints();

        $this->call([
            CouponSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
