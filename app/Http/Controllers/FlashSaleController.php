<?php

namespace App\Http\Controllers;

use App\Models\FlashSale;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function index()
    {
        $flashSales = FlashSale::all();
        return view('admin.flashsale.index', compact('flashSales'));
    }

    public function create()
    {
        return view('admin.flashsale.create');
    }

  public function store(Request $request)
{
    $request->validate([
        'nama_promo' => 'required|string|max:255',
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'tipe' => 'required|in:diskon,bonus',
        'diskon_persen' => 'nullable|numeric|min:0|max:100',
        'bonus_item' => 'nullable|integer|min:0',
        'keterangan_bonus' => 'nullable|string|max:255',
        'mulai' => 'required|date',
        'berakhir' => 'required|date|after:mulai',
        'status' => 'required|boolean',
    ]);

    $data = $request->except('gambar');

    if ($request->hasFile('gambar')) {
        $path = $request->file('gambar')->store('flashsale', 'public');
        $data['gambar'] = basename($path);
    }

    FlashSale::create($data);

    return redirect()->route('flashsale.index')->with('success', 'Flash Sale berhasil ditambahkan');
}

    public function edit($id)
    {
        $flashSale = FlashSale::findOrFail($id);
        return view('admin.flashsale.edit', compact('flashSale'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_promo' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tipe' => 'required|in:diskon,bonus',
            'diskon_persen' => 'nullable|numeric|min:0|max:100',
            'bonus_item' => 'nullable|integer|min:0',
            'keterangan_bonus' => 'nullable|string|max:255',
            'mulai' => 'required|date',
            'berakhir' => 'required|date|after:mulai',
            'status' => 'required|boolean',
        ]);

        $flashSale = FlashSale::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $filename = time() . '.' . $request->gambar->extension();
            $request->gambar->storeAs('public/flashsale', $filename);
            $data['gambar'] = $filename;
        }

        $flashSale->update($data);

        return redirect()->route('flashsale.index')->with('success', 'Flash Sale berhasil diperbarui');
    }

    public function destroy($id)
{
    $flashSale = FlashSale::findOrFail($id);

    if ($flashSale->gambar && file_exists(public_path('uploads/flashsale/' . $flashSale->gambar))) {
        unlink(public_path('uploads/flashsale/' . $flashSale->gambar));
    }

    $flashSale->delete();

    return redirect()->route('flashsale.index')->with('success', 'Flash Sale berhasil dihapus');
}

}
