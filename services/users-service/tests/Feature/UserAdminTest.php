<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Concerns\ActsWithJwt;
use Tests\TestCase;

class UserAdminTest extends TestCase
{
    use ActsWithJwt, RefreshDatabase;

    private function client(): User
    {
        return User::create(['name' => 'Bob', 'email' => 'bob@example.com', 'password' => Hash::make('x'), 'role' => 'client']);
    }

    public function test_guest_cannot_list_users(): void
    {
        $this->getJson('/api/users')->assertStatus(401);
    }

    public function test_non_admin_cannot_list_users(): void
    {
        $this->withHeaders($this->jwtHeaders())->getJson('/api/users')->assertStatus(403);
    }

    public function test_admin_can_list_users(): void
    {
        $this->client();

        $this->withHeaders($this->adminHeaders())
            ->getJson('/api/users')
            ->assertOk()
            ->assertJsonCount(1);
    }

    public function test_admin_can_promote_a_user(): void
    {
        $user = $this->client();

        $this->withHeaders($this->adminHeaders())
            ->postJson("/api/users/{$user->id}/promote")
            ->assertOk()
            ->assertJsonPath('role', 'admin');

        $this->assertDatabaseHas('users', ['id' => $user->id, 'role' => 'admin']);
    }

    public function test_admin_can_demote_a_user(): void
    {
        $user = $this->client();
        $user->update(['role' => 'admin']);

        $this->withHeaders($this->adminHeaders())
            ->postJson("/api/users/{$user->id}/demote")
            ->assertOk()
            ->assertJsonPath('role', 'client');
    }

    public function test_non_admin_cannot_promote_users(): void
    {
        $user = $this->client();

        $this->withHeaders($this->jwtHeaders())
            ->postJson("/api/users/{$user->id}/promote")
            ->assertStatus(403);
    }
}
