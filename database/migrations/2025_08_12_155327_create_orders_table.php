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
    $table->string('game_name'); // nama game atau produk
    $table->string('user_id'); // ID pengguna game
    $table->string('server_id')->nullable(); // kalau gamenya punya server ID
    $table->decimal('amount', 10, 2); // jumlah harga
    $table->string('payment_method'); // contoh: 'qris'
    $table->string('status')->default('pending'); // pending, paid, failed
    $table->string('qris_invoice_id')->nullable(); // ID transaksi dari QRIS API
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
