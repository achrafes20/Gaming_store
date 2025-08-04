<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function orderdetails (){
        return $this->hasMany(orderdetails::class);//qui a foreign key faire ca
    }
}
