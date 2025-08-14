<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->string('game_id')->nullable();
        $table->string('game_name')->nullable();
        $table->string('user_id')->nullable();
        $table->string('server_id')->nullable();
        $table->string('whatsapp')->nullable();
        $table->string('nominal')->nullable();
        $table->string('payment_method')->nullable();
        $table->decimal('total', 15, 2)->nullable();
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn([
            'game_id',
            'game_name',
            'user_id',
            'server_id',
            'whatsapp',
            'nominal',
            'payment_method',
            'total',
        ]);
    });
}

};
