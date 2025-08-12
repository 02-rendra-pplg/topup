<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrisController extends Controller
{
    public function kirimQris(Request $request)
    {
        // Validasi input
        $request->validate([
            'imei'         => 'required',
            'kode'         => 'required',
            'nohp'         => 'required',
            'nom'          => 'required',
            'tujuan'       => 'required',
            'kode_produk'  => 'required',
        ]);

        try {
            // Enkripsi semua data
            $data = [
                'imei'        => $this->encrypt_aes($request->imei),
                'kode'        => $this->encrypt_aes($request->kode),
                'nohp'        => $this->encrypt_aes($request->nohp),
                'nom'         => $this->encrypt_aes($request->nom),
                'tujuan'      => $this->encrypt_aes($request->tujuan),
                'kode_produk' => $this->encrypt_aes($request->kode_produk),
            ];

            // Logging data yang dikirim (tanpa info sensitif jika mau aman)
            Log::info('Data terkirim ke API QRIS', $data);

            // Kirim ke API eksternal
            $response = $this->sendToQrisApi($data);
            $decoded = json_decode($response, true);

            // Logging response dari API
            Log::info('Response dari API QRIS', $decoded ?? []);

            // Pastikan struktur response sesuai
            if (isset($decoded['qris']) || isset($decoded['qrCode'])) {
                $qrCode  = $decoded['qris'] ?? $decoded['qrCode'] ?? null;
                $expired = $decoded['expired'] ?? $decoded['expire_at'] ?? null;
                $id      = $decoded['id'] ?? null;
                $total   = $decoded['total'] ?? $request->nom;

                // Jika request via AJAX
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'qrCode'  => $qrCode,
                        'expired' => $expired,
                        'id'      => $id,
                        'total'   => $total,
                        'raw'     => $decoded,
                    ]);
                }

                // Fallback ke view
               return view('topup.qris', [
    'qrisData' => $decoded,
    'id'       => $id ?? 'N/A',
    'qrSvg'    => QrCode::size(250)->generate($qrCode), // generate QR langsung
]);
            } else {
                return $this->handleError($request, 'QRIS gagal diproses.');
            }

        } catch (\Exception $e) {
            Log::error('QRIS Error: ' . $e->getMessage());
            return $this->handleError($request, 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function sendToQrisApi(array $data)
    {
        // Versi HTTP Client Laravel
        $response = Http::asForm()
            ->timeout(30)
            ->post(env('QRIS_API_URL'), $data);

        if (!$response->successful()) {
            throw new \Exception("Gagal menghubungi server QRIS (HTTP {$response->status()})");
        }

        return $response->body();
    }

    private function encrypt_aes($plaintext)
    {
        $key = env('QRIS_AES_KEY');
        $iv  = env('QRIS_AES_IV');

        $encrypted = openssl_encrypt($plaintext, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($encrypted);
    }

    private function handleError(Request $request, string $message)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => false, 'message' => $message], 500);
        }
        return redirect()->back()->withErrors([$message]);
    }
}
