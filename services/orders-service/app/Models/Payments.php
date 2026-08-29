<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    protected $fillable = [
        'user_id', 'order_id', 'card_number', 'expiry_date', 'card_name', 'status',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
