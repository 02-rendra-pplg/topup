<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TopupController;

Route::get('/', function () {
    return view('pages.home');
});

Route::get('/topup', [TopupController::class, 'index'])->name('topup.index');
Route::get('/topup/{slug}', [TopupController::class, 'show'])->name('topup.show');
Route::post('/topup', [TopupController::class, 'store'])->name('topup.store');

Route::get('/login-admin', [AdminController::class, 'logintampil'])->name('admin.login');
Route::post('/login-admin', [AdminController::class, 'login'])->name('admin.login.post');

Route::middleware('auth:admin')->group(function() {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('/games', GameController::class);
    Route::post('/logout-admin', [AdminController::class, 'logout'])->name('admin.logout');
});

