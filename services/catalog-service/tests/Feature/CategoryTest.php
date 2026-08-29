<?php

namespace Tests\Feature;

use App\Models\Categories;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\Concerns\ActsWithJwt;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use ActsWithJwt, RefreshDatabase;

    public function test_categories_are_publicly_listable(): void
    {
        Categories::create(['name' => 'Gaming Mice', 'description' => 'D', 'imagepath' => 'x']);

        $this->getJson('/api/categories')->assertOk()->assertJsonCount(1);
    }

    public function test_show_returns_a_category_with_its_products(): void
    {
        $category = Categories::create(['name' => 'Gaming Mice', 'description' => 'D', 'imagepath' => 'x']);

        $this->getJson("/api/categories/{$category->id}")
            ->assertOk()
            ->assertJsonStructure(['id', 'name', 'products']);
    }

    public function test_guest_cannot_create_a_category(): void
    {
        $this->postJson('/api/categories', ['name' => 'X'])->assertStatus(401);
    }

    public function test_non_admin_cannot_create_a_category(): void
    {
        $this->withHeaders($this->jwtHeaders())
            ->postJson('/api/categories', ['name' => 'X'])
            ->assertStatus(403);
    }

    public function test_admin_can_create_a_category(): void
    {
        $response = $this->withHeaders($this->adminHeaders())->post('/api/categories', [
            'name' => 'Keyboards',
            'description' => 'Mechanical keyboards',
            'photo' => UploadedFile::fake()->image('cat.jpg'),
        ]);

        $response->assertCreated()->assertJsonPath('name', 'Keyboards');
        $this->assertDatabaseHas('categories', ['name' => 'Keyboards']);
    }

    public function test_creating_a_category_requires_name_description_and_photo(): void
    {
        $this->withHeaders($this->adminHeaders())
            ->postJson('/api/categories', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'description', 'photo']);
    }

    public function test_admin_can_update_a_category_without_changing_the_photo(): void
    {
        $category = Categories::create(['name' => 'Old', 'description' => 'D', 'imagepath' => 'uploads/old.png']);

        $this->withHeaders($this->adminHeaders())->putJson("/api/categories/{$category->id}", [
            'name' => 'New Name',
            'description' => 'D',
        ])->assertOk()->assertJsonPath('name', 'New Name');

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'imagepath' => 'uploads/old.png']);
    }

    public function test_admin_can_delete_a_category(): void
    {
        $category = Categories::create(['name' => 'Old', 'description' => 'D', 'imagepath' => 'x']);

        $this->withHeaders($this->adminHeaders())
            ->deleteJson("/api/categories/{$category->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
