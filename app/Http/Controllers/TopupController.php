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
            'game'       => $game,
            'slug'       => $slug,
            'namaGame'   => $game->name,
            'gameId'     => $game->id,
            'type'       => $game->tipe,
            'logo'       => $game->logo,
            'list'       => $list_game['hrg'],
            'flashSales' => $flashSales,
            'banners'    => $banners,
            'diamondImage' => $game->logo_diamond,
            'pembayarans' => $pembayarans,
            // 'publisher' => $game->publisher,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'game_id'   => 'required|exists:games,id',
            'user_id'   => 'required|string',
            'nominal'   => 'required|string',
            'harga'     => 'required|numeric',
            'whatsapp'  => 'required|string',
            'server_id' => 'nullable|string',
            'pembayaran_id' => 'required|exists:pembayarans,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $server_id = $request->input('server_id');
        $game_id   = $request->input('game_id');
        $user_id   = $request->input('user_id');
        $nominal   = $request->input('nominal');
        $harga     = $request->input('harga');
        $whatsapp  = $request->input('whatsapp');

        $game = Game::find($game_id);

        Log::info("Topup: {$game->name} | $user_id | $server_id | $nominal | $harga | $whatsapp");

        return back()->with('success', 'Top-up berhasil diproses!');
    }

    public function beli()
    {
        $method = "aes-128-ecb";
        $key    = date("dmdYmdm");

        $imei = $this->encrypt_aes("FFFFFFFFB50A26BBFFFFFFFFF2972AA0", $method, $key);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://ceklaporan.com/android/qrisbayarinject',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => 'imei=' . $imei . '&kode=encrypt_aes(kode)&nohp=encrypt_aes(nohp)&nom=encrypt_aes(nom)&tujuan=encrypt_aes(tujuan)&kode_produk=encrypt_aes(kode_produk)',
            CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
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
