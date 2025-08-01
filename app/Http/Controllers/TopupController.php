<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TopupController extends Controller
{
    public function index()
    {
        return view('topup.index');
    }

public function show($slug)
{
    $games = [
        'mobile-legends' => ['nama' => 'Mobile Legends', 'type' => '2id', 'id' => 'ML'],
        'pubg-mobile' => ['nama' => 'PUBG Mobile', 'type' => '1id', 'id' => 'PUBGM'],
        'free-fire' => ['nama' => 'Free Fire', 'type' => '1id', 'id' => 'FREEFIRE'],
        'genshin-impact' => ['nama' => 'Genshin Impact', 'type' => '1id', 'id' => 'GENSHIN'],
        'delta-force-garena' => ['nama' => 'Delta Force Garena', 'type' => '1id', 'id' => 'DFG'],
        'delta-force-steam' => ['nama' => 'Delta Force Steam', 'type' => '1id', 'id' => 'DFS'],
        'magic-chess-go-go' => ['nama' => 'Magic Chess GO.GO', 'type' => '2id', 'id' => 'MAGICCHESS'],
        'free-fire-max' => ['nama' => 'Free Fire Max', 'type' => '1id', 'id' => 'FFMAX'],
        'honor-of-king' => ['nama' => 'Honor OF King', 'type' => '1id', 'id' => 'HOK'],
    ];

    if (!array_key_exists($slug, $games)) {
        abort(404);
    }

    $game = $games[$slug];

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://ceklaporan.com/android/harga?id=' . $game['id'] . '&kode=M10263&opr=SEMUA',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    $list_game = json_decode($response, true);

    // ✅ Taruh pengecekan di sini
    if (!$list_game || !isset($list_game['hrg'])) {
        return abort(500, 'Gagal mengambil data dari server.');
    }

    return view('topup.form', [
        'slug' => $slug,
        'namaGame' => $game['nama'],
        'type' => $game['type'],
        'list' => $list_game['hrg'],
    ]);
}





public function store(Request $request)
{
    $request->validate([
        'user_id'  => 'required',
        'nominal'  => 'required',
        'harga'    => 'required|numeric',
        'whatsapp' => 'required',
    ]);

    $server_id = $request->input('server_id'); // hanya ada jika 2id
    $game = $request->input('game');
    $user_id = $request->input('user_id');
    $nominal = $request->input('nominal');
    $harga = $request->input('harga');
    $whatsapp = $request->input('whatsapp');

    // Simpan transaksi ke DB atau kirim ke API
    // Contoh simpan sementara (tanpa DB)
    Log::info("Topup: $game | $user_id | $server_id | $nominal | $harga | $whatsapp");

    return back()->with('success', 'Top-up berhasil diproses!');
}



    


}
