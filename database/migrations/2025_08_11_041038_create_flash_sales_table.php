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
        Schema::create('flash_sales', function (Blueprint $table) {
            $table->id();
            $table->string('nama_promo');
            $table->enum('tipe', ['diskon', 'bonus']);
            $table->decimal('diskon_persen', 5, 2)->nullable();
            $table->integer('bonus_item')->nullable();
            $table->string('keterangan_bonus')->nullable();
            $table->dateTime('mulai');
            $table->dateTime('berakhir');
            $table->boolean('status')->default(1); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flash_sales');
    }
};
