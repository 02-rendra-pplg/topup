<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class OrderController extends Controller
{
    private $gameNames = [
        'MOBILELEGEND'       => 'Mobile Legends',
        'PUBGM'              => 'PUBG Mobile',
        'DIAMOND%20FREEFIRE' => 'Free Fire',
        'GENSHIN'            => 'Genshin Impact',
        'DFG'                => 'Delta Force Garena',
        'DFS'                => 'Delta Force Steam',
        'MAGICCHESS'         => 'Magic Chess GO.GO',
        'FFMAX'              => 'Free Fire Max',
        'HOK'                => 'Honor of King',
    ];

    public function store(Request $request)
    {
        // Validasi request
        $request->validate([
            'game_id'        => 'required|string|in:' . implode(',', array_keys($this->gameNames)),
            'user_id'        => 'required|string',
            'price'          => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'server_id'      => 'nullable|string',
            'whatsapp'       => 'nullable|string',
            'nominal'        => 'nullable|string',
        ]);

        // Ambil nama game
        $gameName = $this->gameNames[$request->game_id] ?? 'Unknown Game';

        // Simpan order di DB
        $order = Order::create([
            'game_id'        => $request->game_id,
            'game_name'      => $gameName,
            'user_id'        => $request->user_id,
            'server_id'      => $request->server_id,
            'whatsapp'       => $request->whatsapp,
            'nominal'        => $request->nominal,
            'price'          => $request->price,
            'amount'         => $request->price,
            'payment_method' => $request->payment_method,
            'status'         => 'pending',
            'qris_payload'   => null,
            'qris_image_url' => null,
            'expired_at'     => now()->addMinutes(15),
        ]);

        try {
            // Data ke API QRIS (AES Encrypted)
            // $data = [
            //     'order_id'    => $this->encrypt_aes($order->id),
            //     'amount'      => $this->encrypt_aes($order->amount),
            //     'expired'     => $this->encrypt_aes($order->expired_at->timestamp),
            //     'description' => $this->encrypt_aes("Topup {$order->game_name}"),
            // ];
            $data = [
                'imei'        => $this->encrypt_aes("FFFFFFFFB50A26BBFFFFFFFFF2972AA0"),
                'kode'        => $this->encrypt_aes("J0132"),
                'nohp'        => $this->encrypt_aes("082234075846"),
                'nom'         => $this->encrypt_aes($request->nominal),
                'tujuan'      => $this->encrypt_aes($request->tujuan),
                'kode_produk' => $this->encrypt_aes($request->kode_produk),
            ];

            // Kirim ke API QRIS
            $response = $this->sendToQrisApi($data);

            
            // Logging response dari API
            // Log::info('Response dari API QRIS', $response ?? []);

            $decoded  = json_decode($response, true);

            if ( isset($decoded['qrCode'])) {
                $qrCode  = $decoded['qrCode'] ?? $decoded['qrCode'] ?? null;
                $image   = $decoded['qr_image_url'] ?? null;

                $order->update([
                    'qris_payload'   => $qrCode,
                    'qris_image_url' => $image,
                ]);

                return view('topup.qris', [
                    'qrisData' => [
                        'qris'    => $qrCode,
                        'image'   => $image,
                        'expired' => $order->expired_at,
                        'total'   => $order->price,
                        'id'      => $order->id,
                    ],
                    'qrSvg' => QrCode::size(250)->generate($qrCode),
                ]);
            }

            return back()->withErrors(['payment' => 'Gagal membuat QRIS, silakan coba lagi.']);

        } catch (\Exception $e) {
            Log::error('QRIS Error: ' . $e->getMessage());
            return back()->withErrors(['payment' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    private function sendToQrisApi(array $data)
    {
        $apiUrl = env('QRIS_API_URL'); // Atur di .env

        // $response = Http::asForm()
        //     ->timeout(30)
        //     ->post($apiUrl, $data);

        // if (!$response->successful()) {
        //     throw new \Exception("Gagal menghubungi server QRIS (HTTP {$response->status()})");
        // }

        // return $response->body();
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    // private function encrypt_aes($plaintext)
    // {
    //     $key = date("dmdYmdm");
    //     $iv  = env('QRIS_AES_IV');

    //     $encrypted = openssl_encrypt($plaintext, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
    //     return base64_encode($encrypted);
    // }
    function encrypt_aes($string){
        $method = "aes-128-ecb";
        $key = date("dmdYmdm");

        return openssl_encrypt($string, $method, $key);
    }


    public function createQris(Request $request)
{
    $method = "aes-128-ecb";
    $key = date("dmdYmdm");

    // Function helper untuk enkripsi
    $encrypt = function ($string) use ($method, $key) {
        return openssl_encrypt($string, $method, $key);
    };

    // Ambil data dari form
    $imei         = "FFFFFFFFB50A26BBFFFFFFFFF2972AA0"; // bisa juga dari config
    $kode         = "J0132"; // kode merchant / unik
    $nohp         = $request->whatsapp; // dari input user
    $nom          = $request->price; // harga
    $tujuan       = $request->user_id; // contoh, bisa pakai server_id juga
    $kode_produk  = $request->kode_produk; // dari produk yang diklik

    // Data body POST
    $postData = [
        'imei'        => $encrypt($imei),
        'kode'        => $encrypt($kode),
        'nohp'        => $encrypt($nohp),
        'nom'         => $encrypt($nom),
        'tujuan'      => $encrypt($tujuan),
        'kode_produk' => $encrypt($kode_produk),
    ];

    // Kirim request ke API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://ceklaporan.com/android/qrisbayarinject");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        return back()->with('error', "Gagal membuat QRIS: $err");
    }

    // Decode response
    $result = json_decode($response, true);
    if (!$result || empty($result['qris_url'])) {
        return back()->with('error', 'Gagal membuat QRIS, silakan coba lagi.');
    }

    // Simpan ke database
    Order::create([
        'game_id'        => $request->game_id,
        'game_name'      => $request->game_name,
        'user_id'        => $request->user_id,
        'server_id'      => $request->server_id,
        'whatsapp'       => $nohp,
        'nominal'        => $nom,
        'price'          => $nom,
        'amount'         => $nom,
        'payment_method' => 'qris',
        'status'         => 'pending',
        'qris_payload'   => $result['qris_url'], // atau sesuai respon
        'expired_at'     => now()->addMinutes(15),
    ]);

    return redirect()->route('qris.show', ['url' => $result['qris_url']]);
}

}
