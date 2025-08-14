<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_name',
        'game_id',
        'user_id',
        'server_id',
        'amount',
        'payment_method',
        'status',
        'qris_invoice_id',
        'qris_payload',
        'expired_at',
        'total',
    ];

    protected $dates = ['expired_at'];
}
