<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public function Product (){
        return $this->belongsTo(Product::class,'product_id');//qui a foreign key faire ca
    }
}
//pour l appeler faire item->product->id
