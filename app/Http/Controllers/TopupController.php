<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\FlashSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TopupController extends Controller
{
    private $games = [
        'mobile-legends'       => ['nama' => 'Mobile Legends', 'type' => '2id', 'id' => 'MOBILELEGEND'],
        'pubg-mobile'          => ['nama' => 'PUBG Mobile', 'type' => '1id', 'id' => 'PUBGM'],
        'free-fire'            => ['nama' => 'Free Fire', 'type' => '1id', 'id' => 'DIAMOND%20FREEFIRE'],
        'genshin-impact'       => ['nama' => 'Genshin Impact', 'type' => '1id', 'id' => 'GENSHIN'],
        'delta-force-garena'   => ['nama' => 'Delta Force Garena', 'type' => '1id', 'id' => 'DFG'],
        'delta-force-steam'    => ['nama' => 'Delta Force Steam', 'type' => '1id', 'id' => 'DFS'],
        'magic-chess-go-go'    => ['nama' => 'Magic Chess GO.GO', 'type' => '2id', 'id' => 'MAGICCHESS'],
        'free-fire-max'        => ['nama' => 'Free Fire Max', 'type' => '1id', 'id' => 'FFMAX'],
        'honor-of-king'        => ['nama' => 'Honor OF King', 'type' => '1id', 'id' => 'HOK'],
    ];

    public function index()
    {
        $flashSales = FlashSale::where('status', 1)
            ->where('mulai', '<=', now())
            ->where('berakhir', '>=', now())
            ->get();

        $banners = Banner::latest()->get();

        return view('topup.index', compact('flashSales','banners'));
    }

    public function show($slug)
    {
        if (!array_key_exists($slug, $this->games)) {
            abort(404, 'Game tidak ditemukan.');
        }

        $game = $this->games[$slug];

        $flashSales = FlashSale::where('status', 1)
            ->where('mulai', '<=', now())
            ->where('berakhir', '>=', now())
            ->get();

        $banners = Banner::orderByDesc('created_at')->get();

        // Ambil harga list dari API
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://ceklaporan.com/android/harga?id=' . $game['id'] . '&kode=M10263&opr=SEMUA',
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

        return view('topup.form', [
            'slug'       => $slug,
            'namaGame'   => $game['nama'],
            'type'       => $game['type'],
            'gameId'     => $game['id'], // string
            'list'       => $list_game['hrg'],
            'flashSales' => $flashSales,
            'banners'    => $banners,
        ]);
    }

    public function store(Request $request)
    {
        // Ambil semua game_id dari $this->games
        $validGameIds = array_column($this->games, 'id');

        // Validasi string dan in array
        $validator = Validator::make($request->all(), [
            'game_id'  => 'required|string|in:' . implode(',', $validGameIds),
            'user_id'  => 'required|string',
            'nominal'  => 'required|string',
            'harga'    => 'required|numeric',
            'whatsapp' => 'required|string',
            'server_id'=> 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Log::info("Topup Request", $request->only(['game_id', 'user_id', 'server_id', 'nominal', 'harga', 'whatsapp']));

        return back()->with('success', 'Top-up berhasil diproses!');
    }

    // Optional: metode beli / QRIS
    public function beli()
    {
        $method = "aes-128-ecb";
        $key = date("dmdYmdm");

        $imei = $this->encrypt_aes("FFFFFFFFB50A26BBFFFFFFFFF2972AA0", $method, $key);
        $kode_produk = $this->encrypt_aes("MOBILELEGEND", $method, $key);
        $nom = $this->encrypt_aes("10000", $method, $key);
        $tujuan = $this->encrypt_aes("123456789", $method, $key);
        $nohp = $this->encrypt_aes("628123456789", $method, $key);

        $postFields = http_build_query([
            'imei' => $imei,
            'kode' => $kode_produk,
            'nohp' => $nohp,
            'nom'  => $nom,
            'tujuan' => $tujuan,
            'kode_produk' => $kode_produk,
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://ceklaporan.com/android/qrisbayarinject',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            abort(500, 'Gagal menghubungi server pembayaran: ' . curl_error($curl));
        }

        curl_close($curl);

        return response($response);
    }

    private function encrypt_aes($string, $method, $key)
    {
        return openssl_encrypt($string, $method, $key);
    }
}
