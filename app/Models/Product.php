<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
   /* public function Carts (){
        return $this->hasMany(Cart::class);//qui a foreign key faire ca
    }
    si tu veux le contraire pour cart*/


    public function Category(){
        return $this->belongsTo(Categories::class,'category_id');//qui a foreign key faire ca product qui va appeler categories
    }
    public function ProductPhotos(){
        return $this->hasMany(ProductPhoto::class);//le product qui va appeler les autres photos
    }
}


