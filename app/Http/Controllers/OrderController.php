<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'game_id' => 'required',
            'user_id' => 'required',
            'price'   => 'required|numeric',
            'payment_method' => 'required',
        ]);

        $order = Order::create([
            'game_id' => $request->game_id,
            'user_id' => $request->user_id,
            'price'   => $request->price,
            'payment_method' => $request->payment_method,
            'status'  => 'pending',
        ]);

        return redirect()->route('orders.show', $order->id);
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        return view('qris.show', compact('order'));
    }
}
