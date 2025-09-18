<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // cek dulu, supaya tidak error saat migrate ulang
            if (!Schema::hasColumn('orders', 'verification_code')) {
                $table->string('verification_code')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'verification_code')) {
                $table->dropColumn('verification_code');
            }
        });
    }
};
