<?php

namespace Tests\Feature;

use App\Models\Coupon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\ActsWithJwt;
use Tests\TestCase;

class CouponTest extends TestCase
{
    use ActsWithJwt, RefreshDatabase;

    public function test_preview_computes_percent_discount(): void
    {
        Coupon::create(['code' => 'SAVE10', 'discount' => 10, 'type' => 'percent', 'expires_at' => now()->addDay()]);

        $this->withHeaders($this->jwtHeaders(userId: 1))
            ->postJson('/api/coupons/preview', ['code' => 'SAVE10', 'total' => 1000])
            ->assertOk()
            ->assertJsonPath('discount', 100)
            ->assertJsonPath('new_total', 900);
    }

    public function test_preview_computes_fixed_discount_capped_at_total(): void
    {
        Coupon::create(['code' => 'FLAT50', 'discount' => 50, 'type' => 'fixed', 'expires_at' => now()->addDay()]);

        $this->withHeaders($this->jwtHeaders(userId: 1))
            ->postJson('/api/coupons/preview', ['code' => 'FLAT50', 'total' => 30])
            ->assertOk()
            ->assertJsonPath('discount', 30)
            ->assertJsonPath('new_total', 0);
    }

    public function test_unknown_coupon_code_is_rejected(): void
    {
        $this->withHeaders($this->jwtHeaders(userId: 1))
            ->postJson('/api/coupons/preview', ['code' => 'NOPE', 'total' => 100])
            ->assertStatus(422);
    }

    public function test_expired_coupon_is_rejected(): void
    {
        Coupon::create(['code' => 'OLD', 'discount' => 10, 'type' => 'percent', 'expires_at' => now()->subDay()]);

        $this->withHeaders($this->jwtHeaders(userId: 1))
            ->postJson('/api/coupons/preview', ['code' => 'OLD', 'total' => 100])
            ->assertStatus(422);
    }

    public function test_already_used_coupon_is_rejected_on_preview(): void
    {
        $coupon = Coupon::create(['code' => 'SAVE10', 'discount' => 10, 'type' => 'percent', 'expires_at' => now()->addDay()]);
        $coupon->markUsedBy(1);

        $this->withHeaders($this->jwtHeaders(userId: 1))
            ->postJson('/api/coupons/preview', ['code' => 'SAVE10', 'total' => 100])
            ->assertStatus(422);
    }

    public function test_guest_cannot_manage_coupons(): void
    {
        $this->getJson('/api/coupons')->assertStatus(401);
    }

    public function test_non_admin_cannot_manage_coupons(): void
    {
        $this->withHeaders($this->jwtHeaders())->getJson('/api/coupons')->assertStatus(403);
    }

    public function test_admin_can_list_create_update_and_delete_coupons(): void
    {
        $admin = $this->adminHeaders();

        $created = $this->withHeaders($admin)->postJson('/api/coupons', [
            'code' => 'NEW20', 'discount' => 20, 'type' => 'percent', 'expires_at' => now()->addWeek()->toDateString(),
        ])->assertCreated()->json();

        $this->withHeaders($admin)->getJson('/api/coupons')->assertOk()->assertJsonCount(1);

        $this->withHeaders($admin)->putJson("/api/coupons/{$created['id']}", [
            'code' => 'NEW25', 'discount' => 25, 'type' => 'percent', 'expires_at' => now()->addWeek()->toDateString(),
        ])->assertOk()->assertJsonPath('code', 'NEW25');

        $this->withHeaders($admin)->deleteJson("/api/coupons/{$created['id']}")->assertNoContent();
        $this->assertDatabaseCount('coupons', 0);
    }

    public function test_coupon_code_must_be_unique(): void
    {
        Coupon::create(['code' => 'DUP', 'discount' => 10, 'type' => 'percent', 'expires_at' => now()->addDay()]);

        $this->withHeaders($this->adminHeaders())->postJson('/api/coupons', [
            'code' => 'DUP', 'discount' => 5, 'type' => 'fixed', 'expires_at' => now()->addDay()->toDateString(),
        ])->assertStatus(422)->assertJsonValidationErrors('code');
    }
}
