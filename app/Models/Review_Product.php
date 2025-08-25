<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review_Product extends Model
{
    use HasFactory;

    protected $table = 'review_products'; // si ton nom de table est bien review_products

    protected $fillable = [
        'product_id',
        'user_id',
        'name',
        'email',
        'rating',
        'comment',
    ];

    // Relation avec Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relation avec User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
