<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

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

    public function orderList()
    {
        $data = Order::query()->select('barang.id as id', 'barang.nama_barang as nama_barang', 'users.name as pembeli', 'orders.quantity as qty', 'barang.harga_jual as harga', DB::raw('(orders.quantity * barang.harga_jual) as total'), 'orders.status as status')
            ->join('barang', 'orders.barang_id', '=', 'barang.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->get();
//        dd($data);
        return Inertia::render('Order/List', [
            'orders' => $data
        ]);
    }
}
