<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');

            // user_id référence users-service (pas de FK locale : base de données dédiée par service)
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('email');

            $table->tinyInteger('rating')->comment('1 to 5');
            $table->text('comment');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_products');
    }
};
