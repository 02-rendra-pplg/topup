<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'qris_payload',
        'expired_at',
        'total',
    ];

    protected $dates = ['expired_at'];
}