<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Game;

return new class extends Migration
{
    public function up(): void
    {
        // Pastikan semua slug terisi dan unik sebelum membuat unique index
        Game::chunk(100, function ($games) {
            foreach ($games as $game) {
                if (empty($game->slug)) {
                    $slug = Str::slug($game->name);

                    // Cek jika slug sudah ada, tambahkan angka unik
                    $originalSlug = $slug;
                    $counter = 1;
                    while (Game::where('slug', $slug)->where('id', '!=', $game->id)->exists()) {
                        $slug = $originalSlug . '-' . $counter++;
                    }

                    $game->slug = $slug;
                    $game->save();
                }
            }
        });

        // Tambahkan unique index
        Schema::table('games', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropUnique(['slug']);
        });
    }
};
