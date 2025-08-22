<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

   protected $fillable = [
    'trx_id','game_id','game_name','user_id','server_id',
    'whatsapp','nominal','price','amount','payment_method',
    'status','qris_payload','qris_image_url','expired_at'
];


    protected $dates = ['expired_at'];
}
