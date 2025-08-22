<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
            'kode_produk'    => 'nullable|string',
        ]);

        $game = Game::findOrFail($request->game_id);

        if ($game->tipe == 2 && empty($request->server_id)) {
            return back()->withErrors(['server_id' => 'Server ID wajib diisi untuk game ini.'])->withInput();
        }

        // ✅ Buat trx_id unik
        $trxId = 'ORD-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(5));

        // Buat order
        $order = Order::create([
            'trx_id'         => $trxId,
            'game_id'        => $request->game_id,
            'game_name'      => $game->name,
            'user_id'        => $request->user_id,
            'server_id'      => $request->server_id,
            'whatsapp'       => $request->whatsapp,
            'nominal'        => $request->nominal,
            'price'          => $request->price,
            'amount'         => $request->amount ?? $request->price,
            'payment_method' => $request->payment_method,
            'status'         => 'pending',
            'qris_payload'   => null,
            'expired_at'     => now()->addMinutes(30),
        ]);

        try {
            // Data yang dikirim ke API QRIS
            $data = [
                'imei'        => $this->encrypt_aes("FFFFFFFFB50A26BBFFFFFFFFF2972AA0"),
                'kode'        => $this->encrypt_aes("J0132"),
                'nohp'        => $this->encrypt_aes("082234075846"),
                'nom'         => $this->encrypt_aes($request->price),
                'tujuan'      => $this->encrypt_aes($request->user_id),
                'kode_produk' => $this->encrypt_aes($request->kode_produk ?? 'ML5'),
            ];

            $response = $this->sendToQrisApi($data);
            $decoded  = json_decode($response, true);

            if ($decoded && isset($decoded['qrCode'])) {
                $qrCode = $decoded['qrCode'];

                // Update order dengan QRIS
                $order->update([
                    'qris_payload'   => $qrCode,
                    'qris_image_url' => $order->qris_image_url ?? '',
                ]);

                return view('topup.qris', [
                    'qrisData' => [
                        'trx_id'  => $order->trx_id,
                        'qris'    => $qrCode,
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
                'trx_id'  => $order->trx_id,
                'qris'    => $order->qris_payload,
                'image'   => $order->qris_image_url ?? '',
                'expired' => $order->expired_at,
                'total'   => $order->amount ?? $order->price,
                'id'      => $order->id,
            ],
            'qrSvg' => $order->qris_payload ? QrCode::size(250)->generate($order->qris_payload) : null,
        ]);
    }

    private function sendToQrisApi(array $data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => 'https://ceklaporan.com/api/payment_qris',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($data),
            CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
        ]);

        $response = curl_exec($curl);
        $err      = curl_error($curl);
        curl_close($curl);

        if ($err) {
            Log::error("QRIS API Error: $err");
            return null;
        }

        return $response;
    }

    private function encrypt_aes($string)
    {
        $method = "aes-128-ecb";
        $key    = date("dmdYmdm"); // contoh: 210820252108
        return openssl_encrypt($string, $method, $key);
    }
}
