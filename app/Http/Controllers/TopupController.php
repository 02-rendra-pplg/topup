<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Banner;
use App\Models\FlashSale;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TopupController extends Controller
{
    public function index()
    {
        $flashSales = FlashSale::where('status', 1)
            ->where('mulai', '<=', now())
            ->where('berakhir', '>=', now())
            ->get();

        $banners = Banner::latest()->get();
        $games   = Game::all();

        return view('pages.home', compact('flashSales', 'banners', 'games'));
    }

    public function show($slug)
    {
        $game = Game::where('slug', $slug)->firstOrFail();

        $flashSales = FlashSale::where('status', 1)
            ->where('mulai', '<=', now())
            ->where('berakhir', '>=', now())
            ->get();

        $banners = Banner::orderByDesc('created_at')->get();

        // ambil list harga
        $parsedUrl = parse_url($game->url_api);
        parse_str($parsedUrl['query'] ?? '', $queryParams);

        if (isset($queryParams['id'])) {
            $queryParams['id'] = urlencode($queryParams['id']);
        }

        $encodedUrl =
            ($parsedUrl['scheme'] ?? 'https') . '://' .
            ($parsedUrl['host'] ?? '') .
            ($parsedUrl['path'] ?? '') . '?' .
            http_build_query($queryParams);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $encodedUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
        ]);

        $response = curl_exec($curl);
        if ($response === false) {
            abort(500, 'Gagal menghubungi server harga: ' . curl_error($curl));
        }
        curl_close($curl);

        $list_game = json_decode($response, true);
        if (!$list_game || !isset($list_game['hrg'])) {
            abort(500, 'Gagal mengambil data harga dari server.');
        }

        $pembayarans = Pembayaran::where('status', 1)->get();

        return view('topup.form', [
            'game'         => $game,
            'slug'         => $slug,
            'namaGame'     => $game->name,
            'gameId'       => $game->id,
            'type'         => $game->tipe,
            'logo'         => $game->logo,
            'list'         => $list_game['hrg'],
            'flashSales'   => $flashSales,
            'banners'      => $banners,
            'diamondImage' => $game->logo_diamond,
            'pembayarans'  => $pembayarans,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'game_id'       => 'required|exists:games,id',
            'user_id'       => 'required|string',
            'nominal'       => 'required|string',
            'harga'         => 'required|numeric',
            'whatsapp'      => 'required|string',
            'server_id'     => 'nullable|string',
            'pembayaran_id' => 'required|exists:pembayarans,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $game = Game::find($request->game_id);

        Log::info("Topup: {$game->name} | {$request->user_id} | {$request->server_id} | {$request->nominal} | {$request->harga} | {$request->whatsapp}");

        return back()->with('success', 'Top-up berhasil diproses!');
    }

public function checkNickname(Request $request)
{
    $request->validate([
        'game_id'   => 'required|exists:games,id',
        'user_id'   => 'required|string',
        'server_id' => 'nullable|string',
    ]);

    $game = Game::findOrFail($request->game_id);

    // 🔗 Panggil API resmi ceklaporan.com
    $url = "https://ceklaporan.com/android/cekidgame?"
         . "id=" . urlencode($game->slug)
         . "&user_id=" . urlencode($request->user_id)
         . "&server_id=" . urlencode($request->server_id ?? '');

    Log::info("Memeriksa nickname dari: {$url}");

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true,
    ]);

    $response = curl_exec($curl);
    Log::info("Response API Nickname:", [$response]);
    $error = curl_error($curl);
    curl_close($curl);

    if ($error) {
        Log::error("CURL Error: " . $error);
        return response()->json([
            'success' => false,
            'message' => 'Tidak dapat menghubungi server.'
        ]);
    }

    // Coba decode JSON
    $data = json_decode($response, true);

    // Jika JSON tidak valid, coba cari manual pola "nickname"
    if (!$data && str_contains($response, 'nickname')) {
        preg_match('/"nickname"\s*:\s*"([^"]+)"/', $response, $match);
        if (isset($match[1])) {
            $data = ['nickname' => $match[1]];
        }
    }

    // ✅ Jika nickname ditemukan
    if (isset($data['nickname']) && $data['nickname'] !== '') {
        return response()->json([
            'success' => true,
            'nickname' => $data['nickname']
        ]);
    }

    // ⚙️ Fallback dummy agar user tahu koneksi berhasil tapi data kosong
    return response()->json([
        'success' => false,
        'message' => 'Nickname tidak ditemukan. Pastikan User ID dan Server ID benar.'
    ]);
}


}
