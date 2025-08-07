<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $fillable = [
        'nama', 'logo', 'tipe', 'admin', 'tipe_admin', 'status',
    ];
}
