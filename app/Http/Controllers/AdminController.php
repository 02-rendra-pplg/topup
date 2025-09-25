<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Game;
use App\Models\Pembayaran;
use App\Models\Banner;
use App\Models\FlashSale;

class AdminController extends Controller
{
    public function logintampil()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Email atau pasword salah']);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        $games_count      = Game::count();
        $payments_count   = Pembayaran::count();
        $banners_count    = Banner::count();
        $flashsales_count = FlashSale::count();

        $chart_labels = ['Games', 'Metode Pembayaran', 'Banner', 'Flash Sale'];
        $chart_data   = [$games_count, $payments_count, $banners_count, $flashsales_count];

        return view('admin.dashboard', compact(
            'games_count',
            'payments_count',
            'banners_count',
            'flashsales_count',
            'chart_labels',
            'chart_data'
        ));
    }

    public function orders()
    {
        $orders = Order::latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function verifyOrder($trx_id)
    {
        $order = Order::where('trx_id', $trx_id)->firstOrFail();
        $order->status = 'success';
        $order->save();

        return redirect()->back()->with('success', 'Pesanan berhasil diverifikasi!');
    }
}
