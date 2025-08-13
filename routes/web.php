<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TopupController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\FlashSaleController;
use App\Http\Controllers\PembayaranController;

// Halaman utama
Route::get('/', function () {
    return view('pages.home');
});

// Topup
Route::get('/topup', [TopupController::class, 'index'])->name('topup.index');
Route::get('/topup/{slug}', [TopupController::class, 'show'])->name('topup.show');
Route::post('/topup', [TopupController::class, 'store'])->name('topup.store');

// QRIS Payment
Route::get('/qris/{order_id}', [PembayaranController::class, 'qris'])->name('pembayaran.qris');
// atau kalau QRIS-nya cuma halaman statis:
// Route::view('/qris', 'pembayaran.qris')->name('pembayaran.qris');

// Admin login
Route::get('/login-admin', [AdminController::class, 'logintampil'])->name('admin.login');
Route::post('/login-admin', [AdminController::class, 'login'])->name('admin.login.post');

// Admin area
Route::middleware('auth:admin')->group(function() {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('games', GameController::class);
    Route::resource('pembayaran', PembayaranController::class);
    Route::resource('flashsale', FlashSaleController::class);
    Route::resource('banner', BannerController::class);
    Route::post('/logout-admin', [AdminController::class, 'logout'])->name('admin.logout');
});
