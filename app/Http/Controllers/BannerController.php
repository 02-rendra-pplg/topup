<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::all();
        return view('admin.flashsale.banner.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.flashsale.banner.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $path = $request->file('gambar')->store('banners', 'public');

        Banner::create([
            'judul' => $request->judul,
            'gambar' => $path
        ]);

        return redirect()->route('banner.index')->with('success', 'Banner berhasil ditambahkan');
    }

    public function edit(Banner $banner)
    {
        return view('admin.flashsale.banner.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = ['judul' => $request->judul];

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('banners', 'public');
            $data['gambar'] = $path;
        }

        $banner->update($data);

        return redirect()->route('banner.index')->with('success', 'Banner berhasil diupdate');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return redirect()->route('banner.index')->with('success', 'Banner berhasil dihapus');
    }
}
