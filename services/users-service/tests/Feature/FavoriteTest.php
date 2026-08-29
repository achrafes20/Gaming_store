<?php

namespace Tests\Feature;

use App\Models\Favorite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Concerns\ActsWithJwt;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use ActsWithJwt, RefreshDatabase;

    private function user(int $id): User
    {
        return User::create([
            'id' => $id, 'name' => "User {$id}", 'email' => "user{$id}@example.com",
            'password' => Hash::make('x'), 'role' => 'client',
        ]);
    }

    public function test_guest_cannot_view_favorites(): void
    {
        $this->getJson('/api/favorites')->assertStatus(401);
    }

    public function test_favorites_are_scoped_to_the_authenticated_user(): void
    {
        Favorite::create(['user_id' => $this->user(1)->id, 'product_id' => 10]);
        Favorite::create(['user_id' => $this->user(2)->id, 'product_id' => 20]);

        $this->withHeaders($this->jwtHeaders(userId: 1))
            ->getJson('/api/favorites')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.product_id', 10);
    }

    public function test_toggling_an_unfavorited_product_adds_it(): void
    {
        $this->user(1);

        $this->withHeaders($this->jwtHeaders(userId: 1))
            ->postJson('/api/favorites/42/toggle')
            ->assertOk()
            ->assertJsonPath('favorited', true);

        $this->assertDatabaseHas('favorites', ['user_id' => 1, 'product_id' => 42]);
    }

    public function test_toggling_an_already_favorited_product_removes_it(): void
    {
        Favorite::create(['user_id' => $this->user(1)->id, 'product_id' => 42]);

        $this->withHeaders($this->jwtHeaders(userId: 1))
            ->postJson('/api/favorites/42/toggle')
            ->assertOk()
            ->assertJsonPath('favorited', false);

        $this->assertDatabaseMissing('favorites', ['user_id' => 1, 'product_id' => 42]);
    }
}
