<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'publisher',
        'slug',
        'logo',
        'tipe',
        'url_api',
        'logo_diamond',
        'logo_weekly',
        'logo_member',
    ];

    protected static function booted()
    {
        static::creating(function ($game) {
            if (empty($game->slug)) {
                $game->slug = Str::slug($game->name);
            }
        });
    }
}
