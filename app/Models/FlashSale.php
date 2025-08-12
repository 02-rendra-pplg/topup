<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_promo',
        'gambar',
        'tipe',
        'diskon_persen',
        'bonus_item',
        'keterangan_bonus',
        'mulai',
        'berakhir',
        'status',
    ];

    public function isActive()
    {
        $now = now();
        return $this->status && $now->between($this->mulai, $this->berakhir);
    }
}
