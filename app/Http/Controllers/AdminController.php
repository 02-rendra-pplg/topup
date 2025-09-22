<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

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
        return view('admin.dashboard');
    }

    public function orders()
    {
        $orders = Order::latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    // ✅ Tambahan: Update order jadi paid
    public function verifyOrder($trx_id)
    {
        // cari order berdasarkan trx_id
        $order = Order::where('trx_id', $trx_id)->firstOrFail();

        // ubah status jadi success (atau paid)
        $order->status = 'success';
        $order->save();

        // kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Pesanan berhasil diverifikasi!');
    }
}
