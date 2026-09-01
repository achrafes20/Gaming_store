<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Payments;
use Illuminate\Database\Seeder;

/**
 * A handful of demo orders so a client's order history and the admin's
 * "all orders" view both have real-looking data instead of an empty
 * state on first run. `user_id`/`product_id` assume users-service and
 * catalog-service ran their own seeders on fresh databases too (a
 * documented convention for this demo dataset — no FK across services,
 * see docs/architecture.md).
 */
class OrderSeeder extends Seeder
{
    /** [user_id, name, email, region, city, status, [[product_id, price, quantity], ...]]. */
    private const ORDERS = [
        [2, 'Sarah Chen', 'sarah.chen@example.com', 'California', 'San Francisco', 'completed', [[4, 169, 1], [8, 99, 1]]],
        [3, 'Marcus Johnson', 'marcus.j@example.com', 'Texas', 'Austin', 'completed', [[1, 499, 1]]],
        [4, 'Priya Patel', 'priya.p@example.com', 'New York', 'Brooklyn', 'pending', [[15, 1599, 1]]],
        [5, 'Tom Becker', 'tom.becker@example.com', 'Washington', 'Seattle', 'completed', [[6, 89, 2], [22, 329, 1]]],
        [2, 'Sarah Chen', 'sarah.chen@example.com', 'California', 'San Francisco', 'completed', [[11, 149, 1]]],
    ];

    public function run(): void
    {
        foreach (self::ORDERS as [$userId, $name, $email, $region, $city, $status, $items]) {
            $total = collect($items)->sum(fn ($item) => $item[1] * $item[2]);

            $order = Order::create([
                'name' => $name,
                'email' => $email,
                'address' => '123 Main St',
                'region' => $region,
                'city' => $city,
                'phone' => '0600000000',
                'user_id' => $userId,
                'discount' => 0,
                'total' => $total,
                'status' => $status,
            ]);

            foreach ($items as [$productId, $price, $quantity]) {
                $order->orderDetails()->create([
                    'product_id' => $productId,
                    'price' => $price,
                    'quantity' => $quantity,
                ]);
            }

            // Last 4 digits only, no CVV — same rule the real checkout flow follows (SECURITY.md).
            Payments::create([
                'user_id' => $userId,
                'order_id' => $order->id,
                'card_number' => (string) random_int(1000, 9999),
                'expiry_date' => '12/28',
                'card_name' => $name,
                'status' => $status === 'pending' ? 'pending' : 'success',
            ]);
        }
    }
}
