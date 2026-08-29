<?php

namespace Tests\Feature;

use App\Models\Categories;
use App\Models\Product;
use App\Models\ReviewProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Tests\Concerns\ActsWithJwt;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use ActsWithJwt, RefreshDatabase;

    private function product(): Product
    {
        $category = Categories::create(['name' => 'Mice', 'description' => 'D', 'imagepath' => 'x']);

        return Product::create([
            'name' => 'Razer DeathAdder', 'description' => 'D', 'imagepath' => 'x',
            'quantity' => 10, 'price' => 800, 'category_id' => $category->id,
        ]);
    }

    public function test_reviews_are_publicly_listable(): void
    {
        $product = $this->product();
        ReviewProduct::create([
            'product_id' => $product->id, 'user_id' => 1, 'name' => 'Alice', 'email' => 'a@test.com',
            'rating' => 5, 'comment' => 'Great!',
        ]);

        $this->getJson("/api/products/{$product->id}/reviews")->assertOk()->assertJsonCount(1);
    }

    public function test_guest_cannot_submit_a_review(): void
    {
        $product = $this->product();

        $this->postJson("/api/products/{$product->id}/reviews", ['rating' => 5, 'comment' => 'Nice'])
            ->assertStatus(401);
    }

    public function test_review_is_rejected_if_orders_service_says_not_purchased(): void
    {
        Http::fake(['*/api/internal/has-purchased*' => Http::response(['has_purchased' => false])]);
        $product = $this->product();

        $this->withHeaders($this->jwtHeaders(userId: 42))
            ->postJson("/api/products/{$product->id}/reviews", ['rating' => 5, 'comment' => 'Nice'])
            ->assertStatus(403);

        $this->assertDatabaseCount('review_products', 0);
    }

    public function test_authenticated_purchaser_can_submit_a_review(): void
    {
        Http::fake(['*/api/internal/has-purchased*' => Http::response(['has_purchased' => true])]);
        $product = $this->product();

        $this->withHeaders($this->jwtHeaders(userId: 42, overrides: ['name' => 'Bob', 'email' => 'bob@test.com']))
            ->postJson("/api/products/{$product->id}/reviews", ['rating' => 4, 'comment' => 'Solid mouse'])
            ->assertCreated()
            ->assertJsonPath('rating', 4)
            ->assertJsonPath('name', 'Bob');

        $this->assertDatabaseHas('review_products', ['product_id' => $product->id, 'user_id' => 42, 'rating' => 4]);
    }

    public function test_user_cannot_review_the_same_product_twice(): void
    {
        Http::fake(['*/api/internal/has-purchased*' => Http::response(['has_purchased' => true])]);
        $product = $this->product();
        ReviewProduct::create([
            'product_id' => $product->id, 'user_id' => 42, 'name' => 'Bob', 'email' => 'bob@test.com',
            'rating' => 5, 'comment' => 'First review',
        ]);

        $this->withHeaders($this->jwtHeaders(userId: 42))
            ->postJson("/api/products/{$product->id}/reviews", ['rating' => 3, 'comment' => 'Second try'])
            ->assertStatus(409);

        $this->assertDatabaseCount('review_products', 1);
    }

    public function test_review_is_rejected_if_orders_service_is_unreachable(): void
    {
        Http::fake(['*/api/internal/has-purchased*' => fn () => throw new ConnectionException('down')]);
        $product = $this->product();

        $this->withHeaders($this->jwtHeaders(userId: 42))
            ->postJson("/api/products/{$product->id}/reviews", ['rating' => 5, 'comment' => 'Nice'])
            ->assertStatus(403);
    }

    public function test_rating_must_be_between_1_and_5(): void
    {
        Http::fake(['*/api/internal/has-purchased*' => Http::response(['has_purchased' => true])]);
        $product = $this->product();

        $this->withHeaders($this->jwtHeaders(userId: 42))
            ->postJson("/api/products/{$product->id}/reviews", ['rating' => 6, 'comment' => 'Nice'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('rating');
    }
}
