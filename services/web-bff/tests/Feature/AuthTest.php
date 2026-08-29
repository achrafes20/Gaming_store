<?php

namespace Tests\Feature;

use App\Services\OrdersClient;
use App\Services\UsersClient;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class AuthTest extends TestCase
{
    private function usersClientReturning(int $status, array $body): void
    {
        $this->mock(UsersClient::class, function ($mock) use ($status, $body) {
            $mock->shouldReceive('login')->andReturn(['status' => $status, 'body' => (object) $body]);
            $mock->shouldReceive('register')->andReturn(['status' => $status, 'body' => (object) $body]);
            $mock->shouldReceive('favorites')->andReturn(['status' => 200, 'body' => []]);
        });
    }

    public function test_successful_login_stores_the_jwt_and_user_in_session(): void
    {
        $this->usersClientReturning(200, ['token' => 'a.jwt.token', 'user' => (object) ['id' => 1, 'name' => 'Alice', 'role' => 'client']]);

        $response = $this->post('/login', ['email' => 'alice@example.com', 'password' => 'password123']);

        $response->assertRedirect('/');
        $this->assertEquals('a.jwt.token', Session::get('jwt'));
        $this->assertEquals('Alice', Session::get('user')->name);
    }

    public function test_failed_login_shows_an_error_and_does_not_start_a_session(): void
    {
        $this->usersClientReturning(422, ['message' => 'Invalid credentials.']);

        $response = $this->post('/login', ['email' => 'alice@example.com', 'password' => 'wrong']);

        $response->assertSessionHasErrors('email');
        $this->assertNull(Session::get('jwt'));
    }

    public function test_successful_registration_logs_the_user_in(): void
    {
        $this->usersClientReturning(201, ['token' => 'a.jwt.token', 'user' => (object) ['id' => 1, 'name' => 'Alice', 'role' => 'client']]);

        $response = $this->post('/register', [
            'name' => 'Alice', 'email' => 'alice@example.com',
            'password' => 'password123', 'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertEquals('a.jwt.token', Session::get('jwt'));
    }

    public function test_logout_clears_the_session(): void
    {
        Session::put('jwt', 'x');
        Session::put('user', (object) ['id' => 1]);

        $this->post('/logout')->assertRedirect('/');

        $this->assertNull(Session::get('jwt'));
        $this->assertNull(Session::get('user'));
    }

    public function test_guest_is_redirected_to_login_from_a_protected_page(): void
    {
        $this->get('/cart')->assertRedirect('/login');
    }

    public function test_authenticated_session_can_reach_a_protected_page(): void
    {
        Session::put('jwt', 'a.jwt.token');
        Session::put('user', (object) ['id' => 1, 'name' => 'Alice', 'role' => 'client']);

        $this->mock(OrdersClient::class, function ($mock) {
            $mock->shouldReceive('cart')->andReturn(['status' => 200, 'body' => []]);
        });

        $this->get('/cart')->assertOk();
    }
}
