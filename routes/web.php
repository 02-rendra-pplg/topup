<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TopupController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\FlashSaleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PembayaranController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama
Route::get('/', function () {
    return view('pages.home');
})->name('home');

// =========================
// Topup (list & detail produk topup)
// =========================
Route::prefix('topup')->group(function () {
    Route::get('/', [TopupController::class, 'index'])->name('topup.index');         // list game
    Route::get('/{slug}', [TopupController::class, 'show'])->name('topup.show');     // detail game
    Route::post('/store', [TopupController::class, 'store'])->name('topup.store');   // simpan order topup
});

// =========================
// Orders (buat order baru & detail order)
// =========================
Route::prefix('orders')->group(function () {
    Route::post('/', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/{id}', [OrderController::class, 'show'])->name('orders.show');
});

// =========================
// Pembayaran QRIS
// =========================
Route::get('/qris/{order_id}', [PembayaranController::class, 'qris'])->name('pembayaran.qris');

// =========================
// Admin login & dashboard
// =========================
Route::get('/login-admin', [AdminController::class, 'logintampil'])->name('admin.login');
Route::post('/login-admin', [AdminController::class, 'login'])->name('admin.login.post');

Route::middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Resource untuk manajemen
    Route::resource('games', GameController::class);
    Route::resource('pembayaran', PembayaranController::class);
    Route::resource('flashsale', FlashSaleController::class);
    Route::resource('banner', BannerController::class);

    Route::post('/logout-admin', [AdminController::class, 'logout'])->name('admin.logout');
});
