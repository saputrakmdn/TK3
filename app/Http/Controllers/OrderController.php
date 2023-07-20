<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $data = Barang::query()->get();
        return Inertia::render('Order/Index', [
            'products' => $data
        ]);
    }

    public function submit(Request $request)
    {
        $order = new Order();
        $order->quantity = $request->qty;
        $order->barang_id = $request->id;
        $order->user_id = auth()->user()->id;
        $order->status = 'pending';
        $order->save();
        return back();
    }
}
