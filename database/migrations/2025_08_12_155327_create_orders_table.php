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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('game_id')->nullable();
            $table->string('game_name')->nullable();
            $table->string('user_id')->nullable();
            $table->string('server_id')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('nominal')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->decimal('total', 15, 2)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('status')->default('pending');
            $table->string('qris_invoice_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
