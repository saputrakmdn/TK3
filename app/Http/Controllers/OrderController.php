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
        $barang = Barang::query()->where('id', '=', $request->id)->first();
        if($barang->stock_barang < $request->qty)
            return back()->withErrors(['stock' => "Order gagal. Stock tidak mencukupi"]);

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
        $data = Order::query()->select('orders.id as id', 'barang.nama_barang as nama_barang', 'users.name as pembeli', 'orders.quantity as qty', 'barang.harga_jual as harga', DB::raw('(orders.quantity * barang.harga_jual) as total'), 'orders.status as status', 'orders.barang_id as barang_id')
            ->join('barang', 'orders.barang_id', '=', 'barang.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->get();

        return Inertia::render('Order/List', [
            'orders' => $data
        ]);
    }

    public function changeStatus(Request $request)
    {
        //accept order
        $status = $request->status;
        $id = $request->id;


        if($status == 'accept'){
            $barang = Barang::query()->where('id', '=', $request->barang_id)->first();
            if($barang->stock_barang < $request->qty)
                return back()->withErrors(['stock' => "Order tidak dapat diproses. Stock tidak mencukupi"]);

            Barang::query()->where('id', '=', $request->barang_id)->decrement('stock_barang', $request->qty);
        }

        Order::query()->where('id', '=', $id)->update(
            ['status' => $status]
        );

        return back();
    }
}
