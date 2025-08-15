<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Banner;
use App\Models\FlashSale;
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
        $games = Game::all();

        return view('topup.index', compact('flashSales', 'banners', 'games'));
    }

    public function show($slug)
    {
        $game = Game::where('slug', $slug)->firstOrFail();

        $flashSales = FlashSale::where('status', 1)
            ->where('mulai', '<=', now())
            ->where('berakhir', '>=', now())
            ->get();

        $banners = Banner::orderByDesc('created_at')->get();

        // Pastikan parameter id di-encode agar spasi menjadi %20
        $parsedUrl = parse_url($game->url_api);
        parse_str($parsedUrl['query'] ?? '', $queryParams);

        if (isset($queryParams['id'])) {
            $queryParams['id'] = urlencode($queryParams['id']);
        }

        $encodedUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] .
            ($parsedUrl['path'] ?? '') . '?' . http_build_query($queryParams);

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

        return view('topup.form', [
            'slug'       => $slug,
            'namaGame'   => $game->name,
            'type'       => $game->tipe,
            'list'       => $list_game['hrg'],
            'flashSales' => $flashSales,
            'banners'    => $banners,
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

        $server_id = $request->input('server_id');
        $game = $request->input('game');
        $user_id = $request->input('user_id');
        $nominal = $request->input('nominal');
        $harga = $request->input('harga');
        $whatsapp = $request->input('whatsapp');

        Log::info("Topup: $game | $user_id | $server_id | $nominal | $harga | $whatsapp");

        return back()->with('success', 'Top-up berhasil diproses!');
    }

    public function beli()
    {
        $method = "aes-128-ecb";
        $key = date("dmdYmdm");

        $imei = $this->encrypt_aes("FFFFFFFFB50A26BBFFFFFFFFF2972AA0", $method, $key);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://ceklaporan.com/android/qrisbayarinject',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'imei=' . $imei . '&kode=encrypt_aes(kode)&nohp=encrypt_aes(nohp)&nom=encrypt_aes(nom)&tujuan=encrypt_aes(tujuan)&kode_produk=encrypt_aes(kode_produk)',
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    private function encrypt_aes($string, $method, $key)
    {
        return openssl_encrypt($string, $method, $key);
    }
}