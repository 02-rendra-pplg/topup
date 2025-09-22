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
        // dd('jossjiss');
        $game = Game::findOrFail($request->game_id);

        if ($game->tipe == 2 && empty($request->server_id)) {
            return back()->withErrors(['server_id' => 'Server ID wajib diisi untuk game ini.'])->withInput();
        }

        $trxId = 'ORD-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(5));

        $fee       = 1000;
        $unique    = rand(100, 999);
        $total     = $request->price + $fee + $unique;

        $order = Order::create([
            'trx_id'         => $trxId,
            'game_id'        => $request->game_id,
            'game_name'      => $game->name,
            'user_id'        => $request->user_id,
            'server_id'      => $request->server_id,
            'whatsapp'       => $request->whatsapp,
            'nominal'        => $request->nominal,
            'price'          => $request->price,
            'amount'         => $total,
            'payment_method' => $request->payment_method,
            'status'         => 'pending',
            'qris_payload'   => null,
            'expired_at'     => now()->addHours(15),
            'fee'            => $fee,
            'unique_code'    => $unique,
            'verification_code' => rand(100000, 999999),
        ]);

        try {
            $data = [
                'imei'        => $this->encrypt_aes("FFFFFFFFA8E24478000000005075E30C"),
                'kode'        => $this->encrypt_aes("M10263"),
                'nohp'        => $this->encrypt_aes("082229024046"),
                'nom'         => $this->encrypt_aes($total),
                'tujuan'      => $this->encrypt_aes($request->user_id),
                'kode_produk' => $this->encrypt_aes($request->kode_produk ?? 'ML5'),
            ];

            $response = $this->sendToQrisApi($data);
//             Log::info('Data QRIS dikirim: ' . json_encode($data));
// Log::info('Response QRIS: ' . $response);
            $decoded  = json_decode($response, true);

            if ($decoded && isset($decoded['qrCode'])) {
                $qrCode = $decoded['qrCode'];

                $order->update([
                    'qris_payload'   => $qrCode,
                    'qris_image_url' => $order->qris_image_url ?? '',
                ]);

                return redirect()->route('orders.show', $order->trx_id);
            }

            return back()->withErrors(['payment' => 'Gagal membuat QRIS, silakan coba lagi.']);
        } catch (\Exception $e) {
            Log::error('QRIS Error: ' . $e->getMessage());
            return back()->withErrors(['payment' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function show($trxId)
    {
        $order = Order::where('trx_id', $trxId)->firstOrFail();

        return view('topup.qris', [
            'qrisData'=> [
            'trx_id'   => $order->trx_id,
            'qris'     => $order->qris_payload,
            'expired'  => $order->expired_at,
            'price'    => $order->price,
            'fee'      => $order->fee,
            'unique_code' => $order->unique_code,
            'total'    => $order->amount,
            'game_name'=> $order->game_name,
            'user_id'  => $order->user_id,
            'server_id'=> $order->server_id,
            'status'   => $order->status, // <- penting
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
        $key    = date("dmdYmdm");
        return openssl_encrypt($string, $method, $key);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'trx_id' => 'required|string|exists:orders,trx_id',
            'verification_code' => 'required|string',
        ]);

        $order = Order::where('trx_id', $request->trx_id)->firstOrFail();

        if ($order->verification_code === $request->verification_code) {
            $order->update(['status' => 'paid']);
            return back()->with('success', 'Order berhasil diverifikasi dan sudah dibayar.');
        }

        return back()->withErrors(['verification_code' => 'Kode verifikasi salah.']);
    }

    public function status($trxId)
    {   
        $order = Order::where('trx_id', $trxId)->firstOrFail();

        // tampilkan view form masukkan kode verifikasi
        return view('admin.orders.status', compact('order'));
    }
}
