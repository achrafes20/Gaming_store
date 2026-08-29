<?php

namespace Tests\Feature;

use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\ActsWithJwt;
use Tests\TestCase;

class ContactReviewTest extends TestCase
{
    use ActsWithJwt, RefreshDatabase;

    public function test_contact_reviews_are_publicly_listable(): void
    {
        Review::create(['name' => 'A', 'phone' => '1', 'email' => 'a@t.com', 'subject' => 'S', 'message' => 'M']);

        $this->getJson('/api/reviews')->assertOk()->assertJsonCount(1);
    }

    public function test_anyone_can_submit_a_contact_review(): void
    {
        $this->postJson('/api/reviews', [
            'name' => 'Alice', 'phone' => '0600000000', 'email' => 'alice@example.com',
            'subject' => 'Great store', 'message' => 'Loved the service!',
        ])->assertCreated();

        $this->assertDatabaseHas('reviews', ['email' => 'alice@example.com', 'subject' => 'Great store']);
    }

    public function test_contact_review_requires_all_fields(): void
    {
        $this->postJson('/api/reviews', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'phone', 'email', 'subject', 'message']);
    }

    public function test_guest_cannot_delete_a_contact_review(): void
    {
        $review = Review::create(['name' => 'A', 'phone' => '1', 'email' => 'a@t.com', 'subject' => 'S', 'message' => 'M']);

        $this->deleteJson("/api/reviews/{$review->id}")->assertStatus(401);
    }

    public function test_non_admin_cannot_delete_a_contact_review(): void
    {
        $review = Review::create(['name' => 'A', 'phone' => '1', 'email' => 'a@t.com', 'subject' => 'S', 'message' => 'M']);

        $this->withHeaders($this->jwtHeaders())
            ->deleteJson("/api/reviews/{$review->id}")
            ->assertStatus(403);
    }

    public function test_admin_can_delete_a_contact_review(): void
    {
        $review = Review::create(['name' => 'A', 'phone' => '1', 'email' => 'a@t.com', 'subject' => 'S', 'message' => 'M']);

        $this->withHeaders($this->adminHeaders())
            ->deleteJson("/api/reviews/{$review->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }
}
