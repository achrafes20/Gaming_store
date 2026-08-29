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
            $table->unsignedBigInteger('user_id'); // users-service, pas de FK locale
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');

            // Jamais de CVV stocké, jamais le numéro complet (voir SECURITY.md)
            $table->string('card_number', 4);
            $table->string('expiry_date', 5);
            $table->string('card_name');

            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
