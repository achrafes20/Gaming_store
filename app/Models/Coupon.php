<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Coupon extends Model
{
    protected $fillable = ['code', 'discount', 'type', 'usage_limit', 'expires_at'];
    protected $casts = [
        'expires_at' => 'datetime',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_user')->withTimestamps();
    }

    public function isValid()
    {
        return (!$this->expires_at || $this->expires_at->isFuture())
            && (is_null($this->usage_limit) || $this->usage_limit > 0);
    }

    public function calculateDiscount($total)
    {
        if ($this->type === 'fixed') {
            return min($this->discount, $total);
        } else {

            return $total * ($this->discount / 100);
        }
    }

    public function apply($total)
    {
        $discount = $this->calculateDiscount($total);
        return max(0, $total - $discount);
    }
}
