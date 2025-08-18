<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'game_id'        => 'required|integer|exists:games,id',
            'user_id'        => 'required|string',
            'price'          => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'server_id'      => 'nullable|string',
            'whatsapp'       => 'nullable|string',
            'nominal'        => 'nullable|string',
        ]);

        $game = Game::findOrFail($request->game_id);

        if ($game->tipe == 2 && empty($request->server_id)) {
            return back()->withErrors(['server_id' => 'Server ID wajib diisi untuk game ini.'])->withInput();
        }

        $order = Order::create([
            'game_id'        => $game->id,
            'game_name'      => $game->name,
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
            // Data yang harus dikirim ke API QRIS Inject
            $data = [
                'imei'        => $this->encrypt_aes("FFFFFFFFB50A26BBFFFFFFFFF2972AA0"),
                'kode'        => $this->encrypt_aes("J0132"),
                'nohp'        => $this->encrypt_aes("082234075846"),
                'nom'         => $this->encrypt_aes($request->price),
                'tujuan'      => $this->encrypt_aes($request->user_id),
                'kode_produk' => $this->encrypt_aes($request->kode_produk ?? 'ML5'),
            ];
            // return http_build_query($data);
            $response = $this->sendToQrisApi($data);
            // return $response;
            $decoded  = json_decode($response, true);

            if ($decoded && isset($decoded['qrCode'])) {
                $qrCode = $decoded['qrCode'];
                $image  = $decoded['qr_image_url'] ?? null;

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

    public function show($id)
    {
        $order = Order::findOrFail($id);

        return view('topup.qris', [
            'qrisData' => [
                'qris'    => $order->qris_payload,
                'image'   => $order->qris_image_url,
                'expired' => $order->expired_at,
                'total'   => $order->amount,
                'id'      => $order->id,
            ],
        ]);
    }

    private function sendToQrisApi(array $data)
    {
        $apiUrl = "https://ceklaporan.com/android/qrisbayarinject";

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($data),
            CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
        ]);

        $response = curl_exec($ch);
        $err      = curl_error($ch);
        curl_close($ch);

        if ($err) {
            Log::error("QRIS API Error: $err");
            return null;
        }

        return $response;
    }

    private function encrypt_aes($string)
    {
        $method = "aes-128-ecb";
        $key    = date("dmdYmdm"); // contoh: 160820251608
        return openssl_encrypt($string, $method, $key);
    }
}
