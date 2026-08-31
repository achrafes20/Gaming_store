<?php

namespace Tests\Feature;

use App\Models\Categories;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\Concerns\ActsWithJwt;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use ActsWithJwt, RefreshDatabase;

    private function category(): Categories
    {
        return Categories::create(['name' => 'Gaming Mice', 'description' => 'Souris', 'imagepath' => 'uploads/cat.png']);
    }

    private function product(array $overrides = []): Product
    {
        return Product::create(array_merge([
            'name' => 'Razer DeathAdder',
            'description' => 'Souris gaming',
            'imagepath' => 'uploads/product.png',
            'quantity' => 10,
            'price' => 800,
            'category_id' => $this->category()->id,
        ], $overrides));
    }

    public function test_products_are_publicly_listable(): void
    {
        $this->product();

        $this->getJson('/api/products')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Razer DeathAdder');
    }

    public function test_products_can_be_filtered_by_category(): void
    {
        $catA = $this->category();
        $catB = Categories::create(['name' => 'Keyboards', 'description' => 'K', 'imagepath' => 'x']);
        $this->product(['name' => 'Mouse A', 'category_id' => $catA->id]);
        $this->product(['name' => 'Keyboard B', 'category_id' => $catB->id]);

        $response = $this->getJson("/api/products?category_id={$catB->id}");

        $response->assertOk()->assertJsonCount(1, 'data')->assertJsonPath('data.0.name', 'Keyboard B');
    }

    public function test_products_can_be_searched_by_name(): void
    {
        $this->product(['name' => 'Razer DeathAdder']);
        $this->product(['name' => 'Logitech G502']);

        $this->getJson('/api/products?q=Razer')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Razer DeathAdder');
    }

    public function test_show_returns_a_single_product_with_relations(): void
    {
        $product = $this->product();

        $this->getJson("/api/products/{$product->id}")
            ->assertOk()
            ->assertJsonPath('id', $product->id)
            ->assertJsonStructure(['id', 'name', 'category', 'product_photos', 'review_products']);
    }

    public function test_show_returns_404_for_unknown_product(): void
    {
        $this->getJson('/api/products/999')->assertNotFound();
    }

    public function test_guest_cannot_create_a_product(): void
    {
        $this->postJson('/api/products', ['name' => 'X'])->assertStatus(401);
    }

    public function test_non_admin_cannot_create_a_product(): void
    {
        $this->withHeaders($this->jwtHeaders())
            ->postJson('/api/products', ['name' => 'X'])
            ->assertStatus(403);
    }

    public function test_admin_can_create_a_product(): void
    {
        $category = $this->category();

        $response = $this->withHeaders($this->adminHeaders())->post('/api/products', [
            'name' => 'New Product',
            'price' => 500,
            'quantity' => 5,
            'description' => 'A product',
            'category_id' => $category->id,
            'photo' => UploadedFile::fake()->image('photo.jpg'),
        ]);

        $response->assertCreated()->assertJsonPath('name', 'New Product');
        $this->assertDatabaseHas('products', ['name' => 'New Product', 'category_id' => $category->id]);
    }

    public function test_creating_a_product_requires_a_valid_category(): void
    {
        $this->withHeaders($this->adminHeaders())->postJson('/api/products', [
            'name' => 'X',
            'price' => 10,
            'quantity' => 1,
            'description' => 'D',
            'category_id' => 999,
        ])->assertStatus(422)->assertJsonValidationErrors('category_id');
    }

    public function test_admin_can_update_a_product(): void
    {
        $product = $this->product();

        $this->withHeaders($this->adminHeaders())->putJson("/api/products/{$product->id}", [
            'name' => 'Updated Name',
            'price' => 900,
            'quantity' => 3,
            'description' => $product->description,
            'category_id' => $product->category_id,
        ])->assertOk()->assertJsonPath('name', 'Updated Name');

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Updated Name', 'quantity' => 3]);
    }

    public function test_admin_can_delete_a_product(): void
    {
        $product = $this->product();

        $this->withHeaders($this->adminHeaders())
            ->deleteJson("/api/products/{$product->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_internal_endpoint_decrements_stock(): void
    {
        $product = $this->product(['quantity' => 10]);

        $this->withHeaders(['X-Internal-Secret' => config('services.internal_service_secret')])
            ->patchJson("/api/internal/products/{$product->id}/decrement-stock", ['quantity' => 3])
            ->assertOk()
            ->assertJsonPath('quantity', 7);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 7]);
    }

    public function test_internal_endpoint_refuses_to_oversell(): void
    {
        $product = $this->product(['quantity' => 2]);

        $this->withHeaders(['X-Internal-Secret' => config('services.internal_service_secret')])
            ->patchJson("/api/internal/products/{$product->id}/decrement-stock", ['quantity' => 5])
            ->assertStatus(409);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 2]);
    }

    /** SECURITY.md, OWASP A01 — this endpoint used to have no authentication at all. */
    public function test_internal_endpoint_rejects_requests_without_the_shared_secret(): void
    {
        $product = $this->product(['quantity' => 10]);

        $this->patchJson("/api/internal/products/{$product->id}/decrement-stock", ['quantity' => 3])
            ->assertStatus(403);

        $this->withHeaders(['X-Internal-Secret' => 'wrong'])
            ->patchJson("/api/internal/products/{$product->id}/decrement-stock", ['quantity' => 3])
            ->assertStatus(403);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 10]);
    }
}
