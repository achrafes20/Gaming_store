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

    public function isValid()
    {
        return (!$this->expires_at || $this->expires_at->isFuture())
            && (!$this->usage_limit || $this->usage_limit > 0);
    }

    public function apply($total)
    {
        return $this->type === 'fixed'
            ? max(0, $total - $this->discount)
            : max(0, $total - ($total * $this->discount / 100));
    }
}

