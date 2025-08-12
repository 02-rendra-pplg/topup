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
            'tipe' => 'required|in:diskon,bonus',
            'diskon_persen' => 'nullable|numeric|min:0|max:100',
            'bonus_item' => 'nullable|integer|min:0',
            'keterangan_bonus' => 'nullable|string|max:255',
            'mulai' => 'required|date',
            'berakhir' => 'required|date|after:mulai',
            'status' => 'required|boolean',
        ]);

        FlashSale::create($request->all());

        return redirect()->route('flashsale.index')->with('success', 'Flash Sale berhasil ditambahkan');
    }

    public function edit(FlashSale $flashSale)
    {
        return view('admin.flashsale.edit', compact('flashSale'));
    }

    public function update(Request $request, FlashSale $flashSale)
    {
        $request->validate([
            'nama_promo' => 'required|string|max:255',
            'tipe' => 'required|in:diskon,bonus',
            'diskon_persen' => 'nullable|numeric|min:0|max:100',
            'bonus_item' => 'nullable|integer|min:0',
            'keterangan_bonus' => 'nullable|string|max:255',
            'mulai' => 'required|date',
            'berakhir' => 'required|date|after:mulai',
            'status' => 'required|boolean',
        ]);

        $flashSale->update($request->all());

        return redirect()->route('flashsale.index')->with('success', 'Flash Sale berhasil diperbarui');
    }

    public function destroy(FlashSale $flashSale)
    {
        $flashSale->delete();
        return redirect()->route('flashsale.index')->with('success', 'Flash Sale berhasil dihapus');
    }
}
