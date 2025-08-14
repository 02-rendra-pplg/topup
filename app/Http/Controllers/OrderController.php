<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Pastikan game_id integer dan ada di DB
        $request->validate([
            'game_id'        => 'required|integer|exists:games,id',
            'user_id'        => 'required|string',
            'price'          => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'server_id'      => 'nullable|string',
            'whatsapp'       => 'nullable|string',
            'nominal'        => 'nullable|string',
        ]);

        // Ambil nama game
        $game = Game::findOrFail((int) $request->game_id);

        // Simpan order
        $order = Order::create([
            'game_id'        => $game->id,
            'game_name'      => $game->name,
            'user_id'        => $request->user_id,
            'server_id'      => $request->server_id,
            'whatsapp'       => $request->whatsapp,
            'nominal'        => $request->nominal,
            'price'          => $request->price,
            'payment_method' => $request->payment_method,
            'status'         => 'pending',
            'qris_payload'   => null,
            'qris_image_url' => null,
            'expired_at'     => now()->addMinutes(15),
        ]);

        // Panggil API QRIS
        $response = Http::post('https://api-qris-provider.com/create', [
            'order_id' => $order->id,
            'amount'   => $order->price,
            'expired'  => $order->expired_at->timestamp,
        ]);

        if ($response->successful()) {
            $data = $response->json();

            $order->update([
                'qris_payload'   => $data['payload'] ?? null,
                'qris_image_url' => $data['qr_image_url'] ?? null,
            ]);
        } else {
            Log::error('Gagal membuat QRIS', [
                'order_id' => $order->id,
                'response' => $response->body()
            ]);
            return back()->withErrors(['payment' => 'Gagal membuat QRIS, silakan coba lagi.']);
        }

        return redirect()->route('orders.show', $order->id);
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);

        return view('topup.qris', [
            'qrisData' => [
                'qris'    => $order->qris_payload,
                'image'   => $order->qris_image_url,
                'expired' => $order->expired_at,
                'total'   => $order->price,
                'id'      => $order->id,
            ],
            'id' => $order->id
        ]);
    }
}
