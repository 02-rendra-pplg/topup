<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TopupController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\FlashSaleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PembayaranController;



Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::prefix('topup')->group(function () {
    Route::get('/', [TopupController::class, 'index'])->name('topup.index');
    Route::get('/{slug}', [TopupController::class, 'show'])->name('topup.show');
    Route::post('/store', [TopupController::class, 'store'])->name('topup.store');
});

Route::prefix('orders')->group(function () {
    Route::post('/', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/{id}', [OrderController::class, 'show'])->name('orders.show');
});

Route::get('/qris/{order_id}', [PembayaranController::class, 'qris'])->name('pembayaran.qris');

Route::get('/login-admin', [AdminController::class, 'logintampil'])->name('admin.login');
Route::post('/login-admin', [AdminController::class, 'login'])->name('admin.login.post');


Route::middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');


    Route::resource('games', GameController::class);
    Route::resource('pembayaran', PembayaranController::class);
    Route::resource('flashsale', FlashSaleController::class);
    Route::resource('banner', BannerController::class);

    Route::post('/logout-admin', [AdminController::class, 'logout'])->name('admin.logout');
});
