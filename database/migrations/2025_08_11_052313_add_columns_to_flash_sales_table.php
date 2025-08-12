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
        Schema::table('flash_sales', function (Blueprint $table) {
            $table->string('nama_promo')->after('id');
            $table->enum('tipe', ['diskon', 'bonus'])->after('nama_promo');
            $table->decimal('diskon_persen', 5, 2)->nullable()->after('tipe');
            $table->integer('bonus_item')->nullable()->after('diskon_persen');
            $table->string('keterangan_bonus')->nullable()->after('bonus_item');
            $table->dateTime('mulai')->after('keterangan_bonus');
            $table->dateTime('berakhir')->after('mulai');
            $table->boolean('status')->default(1)->after('berakhir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flash_sales', function (Blueprint $table) {
            //
        });
    }
};
