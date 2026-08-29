<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    protected $table = 'orderdetails';

    protected $fillable = ['product_id', 'price', 'quantity', 'order_id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
