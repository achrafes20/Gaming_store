<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Services\CatalogClient;
use App\Services\EventPublisher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\Concerns\ActsWithJwt;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use ActsWithJwt, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Never let a real checkout test try to reach RabbitMQ.
        $this->mock(EventPublisher::class, fn ($mock) => $mock->shouldReceive('publish')->zeroOrMoreTimes());

        // POST /orders is throttled per-IP (SECURITY.md) — the array cache
        // store persists across test methods in the same process, so
        // without this a later test could get 429'd by an earlier one
        // hitting the same limiter key.
        Cache::flush();
    }

    private function fakeCatalog(int $productId = 1, float $price = 800, int $quantity = 10): void
    {
        $this->mock(CatalogClient::class, function ($mock) use ($productId, $price, $quantity) {
            $mock->shouldReceive('findProduct')->with($productId)
                ->andReturn(['id' => $productId, 'name' => 'Razer DeathAdder', 'price' => $price, 'quantity' => $quantity]);
            $mock->shouldReceive('decrementStock')->with($productId, \Mockery::type('int'));
        });
    }

    private function checkoutPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Test User', 'email' => 'test@example.com', 'address' => '1 Test St',
            'region' => 'Casablanca-Settat', 'city' => 'Casablanca', 'phone' => '0600000000',
            'payment_method' => 'cod',
        ], $overrides);
    }

    public function test_guest_cannot_checkout(): void
    {
        $this->postJson('/api/orders', $this->checkoutPayload())->assertStatus(401);
    }

    public function test_checkout_with_an_empty_cart_is_rejected(): void
    {
        $this->withHeaders($this->jwtHeaders(userId: 1))
            ->postJson('/api/orders', $this->checkoutPayload())
            ->assertStatus(422);
    }

    public function test_checkout_creates_an_order_and_clears_the_cart(): void
    {
        $this->fakeCatalog(productId: 1, price: 800, quantity: 10);
        Cart::create(['user_id' => 1, 'product_id' => 1, 'quantity' => 2]);

        $response = $this->withHeaders($this->jwtHeaders(userId: 1))
            ->postJson('/api/orders', $this->checkoutPayload())
            ->assertCreated();

        $response->assertJsonPath('total', 1600)
            ->assertJsonPath('status', 'pending')
            ->assertJsonCount(1, 'order_details');

        $this->assertDatabaseHas('orders', ['user_id' => 1, 'total' => 1600, 'status' => 'pending']);
        $this->assertDatabaseHas('orderdetails', ['product_id' => 1, 'quantity' => 2, 'price' => 800]);
        $this->assertDatabaseCount('carts', 0);
    }

    public function test_card_payment_marks_the_order_paid_and_stores_only_the_last_4_digits(): void
    {
        $this->fakeCatalog();
        Cart::create(['user_id' => 1, 'product_id' => 1, 'quantity' => 1]);

        $this->withHeaders($this->jwtHeaders(userId: 1))->postJson('/api/orders', $this->checkoutPayload([
            'payment_method' => 'card',
            'card_number' => '4111111111111234',
            'expiry_date' => '12/28',
            'cvv' => '123',
            'card_name' => 'Test User',
        ]))->assertCreated()->assertJsonPath('status', 'paid');

        $this->assertDatabaseHas('payments', ['card_number' => '1234', 'status' => 'success']);
        $this->assertDatabaseMissing('payments', ['card_number' => '4111111111111234']);
        // CVV must never be persisted (see docs/architecture — PCI-DSS).
        $this->assertDatabaseMissing('payments', ['cvv' => '123']);
    }

    public function test_card_payment_without_card_details_is_rejected(): void
    {
        $this->fakeCatalog();
        Cart::create(['user_id' => 1, 'product_id' => 1, 'quantity' => 1]);

        $this->withHeaders($this->jwtHeaders(userId: 1))
            ->postJson('/api/orders', $this->checkoutPayload(['payment_method' => 'card']))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['card_number', 'expiry_date', 'cvv', 'card_name']);
    }

    public function test_checkout_rejects_a_line_that_exceeds_current_stock(): void
    {
        $this->mock(CatalogClient::class, function ($mock) {
            $mock->shouldReceive('findProduct')->with(1)
                ->andReturn(['id' => 1, 'name' => 'X', 'price' => 100, 'quantity' => 1]);
        });
        Cart::create(['user_id' => 1, 'product_id' => 1, 'quantity' => 5]);

        $this->withHeaders($this->jwtHeaders(userId: 1))
            ->postJson('/api/orders', $this->checkoutPayload())
            ->assertStatus(409);

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('carts', 1); // nothing committed — cart still intact
    }

    public function test_valid_coupon_discounts_the_order_total(): void
    {
        $this->fakeCatalog(productId: 1, price: 1000, quantity: 10);
        Cart::create(['user_id' => 1, 'product_id' => 1, 'quantity' => 1]);
        Coupon::create(['code' => 'SAVE10', 'discount' => 10, 'type' => 'percent', 'expires_at' => now()->addDay()]);

        $this->withHeaders($this->jwtHeaders(userId: 1))
            ->postJson('/api/orders', $this->checkoutPayload(['coupon_code' => 'SAVE10']))
            ->assertCreated()
            ->assertJsonPath('discount', 100)
            ->assertJsonPath('total', 1000);

        $this->assertDatabaseCount('coupon_user', 1);
    }

    public function test_already_used_coupon_is_silently_ignored_at_checkout(): void
    {
        $this->fakeCatalog(productId: 1, price: 1000, quantity: 10);
        Cart::create(['user_id' => 1, 'product_id' => 1, 'quantity' => 1]);
        $coupon = Coupon::create(['code' => 'SAVE10', 'discount' => 10, 'type' => 'percent', 'expires_at' => now()->addDay()]);
        $coupon->markUsedBy(1);

        $this->withHeaders($this->jwtHeaders(userId: 1))
            ->postJson('/api/orders', $this->checkoutPayload(['coupon_code' => 'SAVE10']))
            ->assertCreated()
            ->assertJsonPath('discount', 0);
    }

    public function test_user_only_sees_their_own_orders(): void
    {
        Order::create(['name' => 'A', 'email' => 'a@t.com', 'address' => 'x', 'region' => 'r', 'city' => 'c', 'phone' => '1', 'user_id' => 1, 'total' => 100]);
        Order::create(['name' => 'B', 'email' => 'b@t.com', 'address' => 'x', 'region' => 'r', 'city' => 'c', 'phone' => '1', 'user_id' => 2, 'total' => 200]);

        $this->withHeaders($this->jwtHeaders(userId: 1))
            ->getJson('/api/orders')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.user_id', 1);
    }

    public function test_admin_all_orders_endpoint_returns_every_order(): void
    {
        Order::create(['name' => 'A', 'email' => 'a@t.com', 'address' => 'x', 'region' => 'r', 'city' => 'c', 'phone' => '1', 'user_id' => 1, 'total' => 100]);
        Order::create(['name' => 'B', 'email' => 'b@t.com', 'address' => 'x', 'region' => 'r', 'city' => 'c', 'phone' => '1', 'user_id' => 2, 'total' => 200]);

        $this->withHeaders($this->adminHeaders())
            ->getJson('/api/admin/orders')
            ->assertOk()
            ->assertJsonCount(2);
    }

    public function test_non_admin_cannot_list_all_orders(): void
    {
        $this->withHeaders($this->jwtHeaders())->getJson('/api/admin/orders')->assertStatus(403);
    }

    public function test_internal_has_purchased_endpoint(): void
    {
        $order = Order::create(['name' => 'A', 'email' => 'a@t.com', 'address' => 'x', 'region' => 'r', 'city' => 'c', 'phone' => '1', 'user_id' => 7, 'total' => 100]);
        $order->orderDetails()->create(['product_id' => 42, 'price' => 100, 'quantity' => 1]);

        $this->withHeaders(['X-Internal-Secret' => config('services.internal_service_secret')])
            ->getJson('/api/internal/has-purchased?user_id=7&product_id=42')
            ->assertOk()
            ->assertJsonPath('has_purchased', true);

        $this->withHeaders(['X-Internal-Secret' => config('services.internal_service_secret')])
            ->getJson('/api/internal/has-purchased?user_id=7&product_id=999')
            ->assertOk()
            ->assertJsonPath('has_purchased', false);
    }

    /** SECURITY.md, OWASP A01 — this endpoint used to have no authentication at all. */
    public function test_internal_has_purchased_endpoint_rejects_requests_without_the_shared_secret(): void
    {
        $this->getJson('/api/internal/has-purchased?user_id=7&product_id=42')->assertStatus(403);

        $this->withHeaders(['X-Internal-Secret' => 'wrong'])
            ->getJson('/api/internal/has-purchased?user_id=7&product_id=42')
            ->assertStatus(403);
    }
}
