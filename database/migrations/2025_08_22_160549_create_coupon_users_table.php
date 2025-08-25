<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupon_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'coupon_id']); // 🔴 empêche un user d'utiliser 2 fois le même coupon
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_user');
    }
};
