<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopupController;

// Halaman utama
Route::get('/', function () {
    return view('pages.home');
});

// Halaman daftar top-up semua game
Route::get('/topup', [TopupController::class, 'index'])->name('topup.index');

// Halaman form top-up per game (gunakan slug atau nama game)
Route::get('/topup/{slug}', [TopupController::class, 'show'])->name('topup.show');

// Proses form top-up
Route::post('/topup', [TopupController::class, 'store'])->name('topup.store');

// (Opsional) Jika kamu ingin menampilkan detail game dengan slug
// Pastikan ini tidak bentrok dengan route di atas
// Route::get('/topup/{slug}', [TopupController::class, 'show']);

