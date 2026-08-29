<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** A customer order — owned by orders-service; product_id in order_details references catalog-service. */
class Order extends Model
{
    protected $fillable = [
        'name', 'email', 'address', 'phone', 'note', 'user_id',
        'region', 'city', 'discount', 'total', 'status',
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function payment()
    {
        return $this->hasOne(Payments::class);
    }
}
