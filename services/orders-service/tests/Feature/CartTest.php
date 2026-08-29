<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Services\CatalogClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\ActsWithJwt;
use Tests\TestCase;

class CartTest extends TestCase
{
    use ActsWithJwt, RefreshDatabase;

    private function fakeProduct(int $id = 1, int $quantity = 10, float $price = 800): void
    {
        $this->mock(CatalogClient::class, function ($mock) use ($id, $quantity, $price) {
            $mock->shouldReceive('findProduct')->with($id)
                ->andReturn(['id' => $id, 'name' => 'Razer DeathAdder', 'price' => $price, 'quantity' => $quantity]);
        });
    }

    public function test_guest_cannot_view_the_cart(): void
    {
        $this->getJson('/api/cart')->assertStatus(401);
    }

    public function test_cart_is_empty_by_default(): void
    {
        $this->withHeaders($this->jwtHeaders(userId: 1))
            ->getJson('/api/cart')
            ->assertOk()
            ->assertJsonCount(0);
    }

    public function test_adding_a_product_creates_a_cart_line(): void
    {
        $this->fakeProduct(id: 1, quantity: 10);

        $this->withHeaders($this->jwtHeaders(userId: 1))
            ->postJson('/api/cart', ['product_id' => 1])
            ->assertCreated()
            ->assertJsonPath('quantity', 1);

        $this->assertDatabaseHas('carts', ['user_id' => 1, 'product_id' => 1, 'quantity' => 1]);
    }

    public function test_adding_the_same_product_twice_increments_quantity(): void
    {
        $this->fakeProduct(id: 1, quantity: 10);
        Cart::create(['user_id' => 1, 'product_id' => 1, 'quantity' => 1]);

        $this->withHeaders($this->jwtHeaders(userId: 1))
            ->postJson('/api/cart', ['product_id' => 1])
            ->assertCreated()
            ->assertJsonPath('quantity', 2);
    }

    public function test_cannot_add_out_of_stock_product(): void
    {
        $this->fakeProduct(id: 1, quantity: 0);

        $this->withHeaders($this->jwtHeaders(userId: 1))
            ->postJson('/api/cart', ['product_id' => 1])
            ->assertStatus(422);

        $this->assertDatabaseCount('carts', 0);
    }

    public function test_cannot_exceed_available_stock(): void
    {
        $this->fakeProduct(id: 1, quantity: 2);
        Cart::create(['user_id' => 1, 'product_id' => 1, 'quantity' => 2]);

        $this->withHeaders($this->jwtHeaders(userId: 1))
            ->postJson('/api/cart', ['product_id' => 1])
            ->assertStatus(422);

        $this->assertDatabaseHas('carts', ['product_id' => 1, 'quantity' => 2]);
    }

    public function test_incrementing_respects_stock_ceiling(): void
    {
        $this->fakeProduct(id: 1, quantity: 2);
        $cart = Cart::create(['user_id' => 1, 'product_id' => 1, 'quantity' => 2]);

        $this->withHeaders($this->jwtHeaders(userId: 1))
            ->postJson("/api/cart/{$cart->id}/increment")
            ->assertOk()
            ->assertJsonPath('quantity', 2); // unchanged: already at stock ceiling
    }

    public function test_decrementing_below_one_removes_the_line(): void
    {
        $cart = Cart::create(['user_id' => 1, 'product_id' => 1, 'quantity' => 1]);

        $this->withHeaders($this->jwtHeaders(userId: 1))
            ->postJson("/api/cart/{$cart->id}/decrement")
            ->assertNoContent();

        $this->assertDatabaseMissing('carts', ['id' => $cart->id]);
    }

    public function test_decrementing_above_one_just_decrements(): void
    {
        $cart = Cart::create(['user_id' => 1, 'product_id' => 1, 'quantity' => 3]);

        $this->withHeaders($this->jwtHeaders(userId: 1))
            ->postJson("/api/cart/{$cart->id}/decrement")
            ->assertOk()
            ->assertJsonPath('quantity', 2);
    }

    public function test_a_user_cannot_delete_another_users_cart_line(): void
    {
        $cart = Cart::create(['user_id' => 2, 'product_id' => 1, 'quantity' => 1]);

        $this->withHeaders($this->jwtHeaders(userId: 1))
            ->deleteJson("/api/cart/{$cart->id}")
            ->assertNotFound();

        $this->assertDatabaseHas('carts', ['id' => $cart->id]);
    }

    public function test_a_user_cannot_increment_another_users_cart_line(): void
    {
        $this->fakeProduct(id: 1, quantity: 10);
        $cart = Cart::create(['user_id' => 2, 'product_id' => 1, 'quantity' => 1]);

        $this->withHeaders($this->jwtHeaders(userId: 1))
            ->postJson("/api/cart/{$cart->id}/increment")
            ->assertNotFound();
    }

    public function test_a_user_cannot_decrement_another_users_cart_line(): void
    {
        $cart = Cart::create(['user_id' => 2, 'product_id' => 1, 'quantity' => 1]);

        $this->withHeaders($this->jwtHeaders(userId: 1))
            ->postJson("/api/cart/{$cart->id}/decrement")
            ->assertNotFound();
    }
}
