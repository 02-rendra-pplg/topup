<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

            // Kirim ke API eksternal
            $response = $this->sendToQrisApi($data);
            $decoded = json_decode($response, true);

            if (isset($decoded['qris'])) {
                return view('topup.qris', [
                    'qrisData' => $decoded,
                    'id' => $decoded['id'] ?? 'N/A',
                ]);
            } else {
             return redirect()->back()->withErrors(['QRIS gagal diproses.']);
            }

        } catch (\Exception $e) {
            Log::error('QRIS Error: ' . $e->getMessage());

            return redirect()->back()->withErrors(['Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    private function sendToQrisApi(array $data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://ceklaporan.com/android/qrisbayarinject',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
            ],
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (curl_errno($curl)) {
            throw new \Exception('cURL Error: ' . curl_error($curl));
        }

        curl_close($curl);

        if ($httpCode !== 200) {
            throw new \Exception("Gagal menghubungi server QRIS (HTTP $httpCode)");
        }

        return $response;
    }

    private function encrypt_aes($plaintext)
    {
        $key = '1234567890abcdef'; // Panjang 16 karakter
        $iv  = 'abcdef1234567890'; // Panjang 16 karakter

        $encrypted = openssl_encrypt($plaintext, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);

        return base64_encode($encrypted);
    }
}
