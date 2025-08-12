<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->decimal('admin', 8, 2)->change();
            $table->enum('tipe_admin', ['persen', 'rupiah'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->integer('admin')->change();
            $table->boolean('tipe_admin')->change();
        });
    }
};
