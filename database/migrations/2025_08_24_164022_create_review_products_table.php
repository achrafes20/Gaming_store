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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // infos utilisateur (peuvent être redondantes avec user mais utiles pour garder une trace)
            $table->string('name');
            $table->string('email');

            // review
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
