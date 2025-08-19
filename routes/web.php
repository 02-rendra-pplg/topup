<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TopupController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\FlashSaleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PembayaranController;

// Halaman utama
Route::get('/', function () {
    return view('pages.home');
});

// Topup (list & detail produk topup)
Route::get('/topup', [TopupController::class, 'index'])->name('topup.index');
Route::get('/topup/{slug}', [TopupController::class, 'show'])->name('topup.show');
Route::post('/topup/store', [TopupController::class, 'store'])->name('topup.store');

// Order (buat order baru dan lihat detail order + QRIS)
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

// Halaman pembayaran QRIS (optional, misal untuk callback atau tampilan khusus)
Route::get('/qris/{order_id}', [PembayaranController::class, 'qris'])->name('pembayaran.qris');

// Admin login dan area
Route::get('/login-admin', [AdminController::class, 'logintampil'])->name('admin.login');
Route::post('/login-admin', [AdminController::class, 'login'])->name('admin.login.post');

Route::middleware('auth:admin')->group(function() {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('games', GameController::class);
    Route::resource('pembayaran', PembayaranController::class);
    Route::resource('flashsale', FlashSaleController::class);
    Route::resource('banner', BannerController::class);
    Route::post('/logout-admin', [AdminController::class, 'logout'])->name('admin.logout');
});

