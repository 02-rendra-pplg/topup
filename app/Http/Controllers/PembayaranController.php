<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::all();
        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    public function create()
    {
        return view('admin.pembayaran.create');
    }

   public function store(Request $request)
{
    $request->validate([
        'nama' => 'required',
        'logo' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        'tipe' => 'required|in:QRIS,e-wallet,store,VA',
        'admin' => 'required|numeric|min:0',
        'tipe_admin' => 'required|in:persen,rupiah',
        'status' => 'required|boolean',
    ]);

    $logoPath = $request->file('logo')->store('logos_pembayaran', 'public');

    Pembayaran::create([
        'nama' => $request->nama,
        'logo' => $logoPath,
        'tipe' => $request->tipe,
        'admin' => $request->admin,
        'tipe_admin' => $request->tipe_admin,
        'status' => $request->status,
    ]);

    return redirect()->route('pembayaran.index')->with('success', 'Metode pembayaran berhasil ditambahkan.');
}

    public function edit(Pembayaran $pembayaran)
    {
        return view('admin.pembayaran.edit', compact('pembayaran'));
    }

    public function update(Request $request, Pembayaran $pembayaran)
{
    $request->validate([
        'nama' => 'required',
        'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        'tipe' => 'required|in:QRIS,e-wallet,store,VA',
        'admin' => 'required|numeric|min:0',
        'tipe_admin' => 'required|in:persen,rupiah',
        'status' => 'required|boolean',
    ]);

    $data = $request->only('nama', 'tipe', 'admin', 'tipe_admin', 'status');

    if ($request->hasFile('logo')) {
        $data['logo'] = $request->file('logo')->store('logos_pembayaran', 'public');
    }

    $pembayaran->update($data);

    return redirect()->route('pembayaran.index')->with('success', 'Metode pembayaran diperbarui.');
}


    public function destroy(Pembayaran $pembayaran)
    {
        if ($pembayaran->logo && Storage::disk('public')->exists($pembayaran->logo)) {
            Storage::disk('public')->delete($pembayaran->logo);
        }

        $pembayaran->delete();

        return redirect()->route('pembayaran.index')->with('success', 'Metode pembayaran dihapus.');
    }

    public function qris($orderId)
{
    // Ambil data order dari database
    $order = \App\Models\Order::where('id', $orderId)->firstOrFail();

    // Kirim ke view qris.blade.php
    return view('topup.qris', [
        'qrisData' => [
            'qris' => $order->qris_payload, // payload atau URL QRIS
            'expired' => $order->expired_at,
            'total' => $order->total,
            'id' => $order->id
        ]
    ]);
}

}
