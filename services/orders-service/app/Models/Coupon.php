<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Coupon extends Model
{
    protected $fillable = ['code', 'discount', 'type', 'usage_limit', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function isValid(): bool
    {
        return (! $this->expires_at || $this->expires_at->isFuture())
            && (is_null($this->usage_limit) || $this->usage_limit > 0);
    }

    public function calculateDiscount(float $total): float
    {
        return $this->type === 'fixed'
            ? min($this->discount, $total)
            : $total * ($this->discount / 100);
    }

    public function usedBy(int $userId): bool
    {
        return DB::table('coupon_user')
            ->where('coupon_id', $this->id)
            ->where('user_id', $userId)
            ->exists();
    }

    public function markUsedBy(int $userId): void
    {
        DB::table('coupon_user')->insert([
            'coupon_id' => $this->id,
            'user_id' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($this->usage_limit) {
            $this->decrement('usage_limit');
        }
    }
}
