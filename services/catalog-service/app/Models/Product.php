<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'imagepath', 'quantity', 'price', 'category_id',
    ];

    public function Category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function ProductPhotos()
    {
        return $this->hasMany(ProductPhoto::class);
    }

    public function reviewProducts()
    {
        return $this->hasMany(ReviewProduct::class);
    }
}
