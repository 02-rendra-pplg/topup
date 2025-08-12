<?php

namespace App\Http\Controllers;

use App\Models\FlashSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TopupController extends Controller
{
    public function index()
    {
           $flashSales = FlashSale::where('status', 1)
            ->where('mulai', '<=', now())
            ->where('berakhir', '>=', now())
            ->get();

        return view('topup.index', compact('flashSales'));
    }

public function show($slug)
{
    $games = [
        'mobile-legends' => ['nama' => 'Mobile Legends', 'type' => '2id', 'id' => 'MOBILELEGEND'],
        'pubg-mobile' => ['nama' => 'PUBG Mobile', 'type' => '1id', 'id' => 'PUBGM'],
        'free-fire' => ['nama' => 'Free Fire', 'type' => '1id', 'id' => 'DIAMOND%20FREEFIRE'],
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

if ($response === false) {
    dd([
        'curl_error' => curl_error($curl),
        'curl_errno' => curl_errno($curl),
        'info' => curl_getinfo($curl)
    ]);
}

curl_close($curl);


    // dd([
    //     'raw_response' => $response,
    //     'decoded' => json_decode($response, true)
    // ]);

    $list_game = json_decode($response, true);

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

    $server_id = $request->input('server_id');
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
public function beli(){
    $method = "aes-128-ecb";
    $key = date("dmdYmdm");
    $imei = $this->encrypt_aes("FFFFFFFFB50A26BBFFFFFFFFF2972AA0",$method, $key);


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://ceklaporan.com/android/qrisbayarinject
',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'imei=$imei&kode=encrypt_aes(kode)&nohp=encrypt_aes(nohp)&nom=encrypt_aes(nom)&tujuan=encrypt_aes(tujuan)&kode_produk=encrypt_aes(kode_produk)',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/x-www-form-urlencoded'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;


}
function encrypt_aes($string, $method, $key){
        return openssl_encrypt($string, $method, $key);
}






}
