<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'nama'       => 'required|string|max:255',
            'logo'       => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'tipe'       => 'required|string',
            'admin'      => 'required|numeric',
            'tipe_admin' => 'required|in:persen,rupiah',
            'status'     => 'required|boolean',
        ]);

        $logoPath = $request->file('logo')->store('pembayaran', 'public');

        Pembayaran::create([
            'nama'       => $request->nama,
            'logo'       => $logoPath,
            'tipe'       => $request->tipe,
            'admin'      => $request->admin,
            'tipe_admin' => $request->tipe_admin,
            'status'     => $request->status,
        ]);

        return redirect()->route('pembayaran.index')->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        return view('admin.pembayaran.edit', compact('pembayaran'));
    }

    public function update(Request $request, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $request->validate([
            'nama'       => 'required|string|max:255',
            'logo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'tipe'       => 'required|string',
            'admin'      => 'required|numeric',
            'tipe_admin' => 'required|in:persen,rupiah',
            'status'     => 'required|boolean',
        ]);

        $data = [
            'nama'       => $request->nama,
            'tipe'       => $request->tipe,
            'admin'      => $request->admin,
            'tipe_admin' => $request->tipe_admin,
            'status'     => $request->status,
        ];

        if ($request->hasFile('logo')) {
            if ($pembayaran->logo && Storage::disk('public')->exists($pembayaran->logo)) {
                Storage::disk('public')->delete($pembayaran->logo);
            }
            $data['logo'] = $request->file('logo')->store('pembayaran', 'public');
        }

        $pembayaran->update($data);

        return redirect()->route('pembayaran.index')->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        if ($pembayaran->logo && Storage::disk('public')->exists($pembayaran->logo)) {
            Storage::disk('public')->delete($pembayaran->logo);
        }

        $pembayaran->delete();

        return redirect()->route('pembayaran.index')->with('success', 'Metode pembayaran berhasil dihapus.');
    }
}
