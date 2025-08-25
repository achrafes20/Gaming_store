<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon_User extends Model
{
    protected $table = 'coupon_user'; // 👈 corrige ici
    public $timestamps = false;
}
