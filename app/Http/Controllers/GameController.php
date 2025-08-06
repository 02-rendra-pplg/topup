<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
        public function index()
    {
        $games = Game::all();
        return view('admin.games.index', compact('games'));
    }

    public function create()
    {
        return view('admin.games.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'logo' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'tipe' => 'required|integer',
            'url_api' => 'required|url',
            'logo_diamond' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $logoPath = $request->file('logo')->store('logos', 'public');
        $diamondPath = $request->file('logo_diamond')->store('diamond_logos', 'public');

        Game::create([
            'name' => $request->name,
            'logo' => $logoPath,
            'tipe' => $request->tipe,
            'url_api' => $request->url_api,
            'logo_diamond' => $diamondPath
        ]);

        return redirect()->route('games.index')->with('success', 'Game berhasil ditambahkan');
    }

        public function edit(Game $game)
    {
        return view('admin.games.edit', compact('game'));
    }
    
    public function update(Request $request, Game $game)
    {
        $request->validate([
            'name' => 'required',
            'tipe' => 'required|integer',
            'url_api' => 'required|url',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'logo_diamond' => 'nullable|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $data = $request->only('name', 'tipe', 'url_api');

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }
        if ($request->hasFile('logo_diamond')) {
            $data['logo_diamond'] = $request->file('logo_diamond')->store('diamond_logos', 'public');
        }

        $game->update($data);

        return redirect()->route('games.index')->with('success', 'Game berhasil diperbarui');
    }

    public function destroy(Game $game)
    {
        if ($game->logo && Storage::disk('public')->exists($game->logo)) {
            Storage::disk('public')->delete($game->logo);
        }

        if ($game->logo_diamond && Storage::disk('public')->exists($game->logo_diamond)) {
            Storage::disk('public')->delete($game->logo_diamond);
        }

        $game->delete();

        return redirect()->route('games.index')->with('success', 'Game berhasil dihapus');
    }
}
