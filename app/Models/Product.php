<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function Category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
    public function ProductPhotos()
    {
        return $this->hasMany(ProductPhoto::class);
    }
    public function review_products()
{
    return $this->hasMany(Review_Product::class);
}

public function favoritedBy()
{
    return $this->hasMany(Favorite::class);
}
}
