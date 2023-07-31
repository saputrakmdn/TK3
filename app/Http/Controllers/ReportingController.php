<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReportingController extends Controller
{
    public function index()
    {
        $data = Barang::query()->select('barang.nama_barang', 'barang.harga_beli', 'barang.harga_jual', DB::raw('SUM(orders.quantity) as qty'), DB::raw('SUM(orders.quantity) * barang.harga_jual as total'), DB::raw('(SUM(orders.quantity) * barang.harga_jual) - (SUM(orders.quantity) * barang.harga_beli) as profit'))->leftJoin('orders', function ($query){
            $query->on('barang.id', '=', 'orders.barang_id');
            $query->where('orders.status', '=', 'accept');
        })->join('users', 'orders.user_id', '=', 'users.id')->groupBy('barang.id')->get();

        return Inertia::render('Reporting/Index', [
            'data' => $data
        ]);
    }
}
