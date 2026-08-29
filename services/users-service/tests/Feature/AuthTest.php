<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\EventPublisher;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Concerns\ActsWithJwt;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use ActsWithJwt, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mock(EventPublisher::class, fn ($mock) => $mock->shouldReceive('publish')->zeroOrMoreTimes());
    }

    public function test_a_user_can_register(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Alice', 'email' => 'alice@example.com',
            'password' => 'password123', 'password_confirmation' => 'password123',
        ]);

        $response->assertCreated()
            ->assertJsonPath('user.email', 'alice@example.com')
            ->assertJsonPath('user.role', 'client')
            ->assertJsonStructure(['user', 'token']);

        $this->assertDatabaseHas('users', ['email' => 'alice@example.com', 'role' => 'client']);
        $this->assertNotEquals('password123', User::first()->password); // hashed, not stored raw
    }

    public function test_registration_issues_a_verifiable_jwt(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Alice', 'email' => 'alice@example.com',
            'password' => 'password123', 'password_confirmation' => 'password123',
        ]);

        $claims = JWT::decode($response->json('token'), new Key(config('services.jwt_secret'), 'HS256'));

        $this->assertEquals('alice@example.com', $claims->email);
        $this->assertEquals('client', $claims->role);
    }

    public function test_registration_requires_matching_password_confirmation(): void
    {
        $this->postJson('/api/register', [
            'name' => 'Alice', 'email' => 'alice@example.com',
            'password' => 'password123', 'password_confirmation' => 'nope',
        ])->assertStatus(422)->assertJsonValidationErrors('password');
    }

    public function test_email_must_be_unique(): void
    {
        User::create(['name' => 'Existing', 'email' => 'alice@example.com', 'password' => Hash::make('x'), 'role' => 'client']);

        $this->postJson('/api/register', [
            'name' => 'Alice', 'email' => 'alice@example.com',
            'password' => 'password123', 'password_confirmation' => 'password123',
        ])->assertStatus(422)->assertJsonValidationErrors('email');
    }

    public function test_a_user_can_log_in_with_correct_credentials(): void
    {
        User::create(['name' => 'Alice', 'email' => 'alice@example.com', 'password' => Hash::make('password123'), 'role' => 'client']);

        $this->postJson('/api/login', ['email' => 'alice@example.com', 'password' => 'password123'])
            ->assertOk()
            ->assertJsonStructure(['user', 'token']);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::create(['name' => 'Alice', 'email' => 'alice@example.com', 'password' => Hash::make('password123'), 'role' => 'client']);

        $this->postJson('/api/login', ['email' => 'alice@example.com', 'password' => 'wrong'])
            ->assertStatus(422);
    }

    public function test_login_fails_for_unknown_email(): void
    {
        $this->postJson('/api/login', ['email' => 'ghost@example.com', 'password' => 'whatever'])
            ->assertStatus(422);
    }

    public function test_me_endpoint_requires_authentication(): void
    {
        $this->getJson('/api/me')->assertStatus(401);
    }

    public function test_me_endpoint_returns_the_authenticated_user(): void
    {
        $this->withHeaders($this->jwtHeaders(userId: 5, overrides: ['name' => 'Alice', 'email' => 'alice@example.com']))
            ->getJson('/api/me')
            ->assertOk()
            ->assertJsonPath('id', 5)
            ->assertJsonPath('email', 'alice@example.com');
    }

    public function test_expired_token_is_rejected(): void
    {
        $expired = JWT::encode([
            'sub' => 1, 'name' => 'X', 'email' => 'x@x.com', 'role' => 'client',
            'iat' => time() - 7200, 'exp' => time() - 3600,
        ], config('services.jwt_secret'), 'HS256');

        $this->withHeaders(['Authorization' => "Bearer {$expired}"])
            ->getJson('/api/me')
            ->assertStatus(401);
    }
}
