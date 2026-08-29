<?php

namespace Tests\Feature;

use App\Models\Sub;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubTest extends TestCase
{
    use RefreshDatabase;

    public function test_anyone_can_subscribe_to_the_newsletter(): void
    {
        $this->postJson('/api/sub', ['email' => 'fan@example.com'])
            ->assertCreated();

        $this->assertDatabaseHas('subs', ['email' => 'fan@example.com']);
    }

    public function test_subscribing_twice_with_the_same_email_is_rejected(): void
    {
        Sub::create(['email' => 'fan@example.com']);

        $this->postJson('/api/sub', ['email' => 'fan@example.com'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_subscribing_requires_a_valid_email(): void
    {
        $this->postJson('/api/sub', ['email' => 'not-an-email'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }
}
