<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Relier le paiement à un utilisateur et à une commande
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('order_id');

            // Infos carte (⚠️ à chiffrer ou stocker de façon sécurisée en prod)
            $table->string('card_number', 20);
            $table->string('expiry_date', 5); // MM/YY
            $table->string('cvv', 4);
            $table->string('card_name');

            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->timestamps();

            // Clés étrangères
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
