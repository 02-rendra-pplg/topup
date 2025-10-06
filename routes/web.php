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

    Route::get('/status/{trx_id}', [OrderController::class, 'status'])->name('orders.status');

    Route::get('/{trx_id}', [OrderController::class, 'show'])->name('orders.show');
});

// Route::get('/check-nickname', [OrderController::class, 'checkNickname'])->name('orders.checkNickname');

Route::post('/topup/check-nickname', [TopupController::class, 'checkNickname'])->name('topup.checkNickname');



Route::get('/qris/{order_id}', [PembayaranController::class, 'qris'])->name('pembayaran.qris');

Route::get('/login-admin', [AdminController::class, 'logintampil'])->name('admin.login');
Route::post('/login-admin', [AdminController::class, 'login'])->name('admin.login.post');


Route::middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');


    Route::resource('games', GameController::class);
    Route::resource('pembayaran', PembayaranController::class);
    Route::resource('flashsale', FlashSaleController::class);
    Route::resource('banner', BannerController::class);


    Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders.index');
    Route::post('/admin/orders/{trx_id}/verify', [AdminController::class, 'verifyOrder'])->name('admin.orders.verify');

    Route::get('/logout-admin', [AdminController::class, 'logout'])->name('admin.logout');
});
