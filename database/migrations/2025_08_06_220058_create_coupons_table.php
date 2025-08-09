<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
    $table->id();
    $table->string('code')->unique();
    $table->decimal('discount', 8, 2); // montant ou pourcentage
    $table->enum('type', ['fixed', 'percent']); // type de remise
    $table->integer('usage_limit')->nullable(); // nb max utilisations
    $table->timestamp('expires_at')->nullable(); // date d’expiration
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
