<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** A product category — owned by catalog-service, referenced by product_id elsewhere. */
class Categories extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'imagepath'];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
