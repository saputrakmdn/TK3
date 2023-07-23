<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BarangController extends Controller
{
    public function index()
    {
        $data = Barang::query()->get();
        return Inertia::render('Barang/Index', [
            'records' => $data
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'deskripsi' => 'required',
            'jenis_barang'=> 'required',
            'stock_barang' => 'required',
            'harga_beli' => 'required',
            'harga_jual' => 'required',
            'gambar_barang' => 'required'
        ]);
        $file = $request->file('gambar_barang');

        Storage::disk('public')->putFileAs('/', $file, $file->getClientOriginalName());
        $form = $request->all();
        $form['gambar_barang'] = Storage::url($file->getClientOriginalName());

        Barang::create($form);

        return back();
    }

    public function delete(Request $request)
    {
        Barang::query()->where('id', '=', $request->id)->delete();

        return back();
    }

    public function edit(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'deskripsi' => 'required',
            'jenis_barang'=> 'required',
            'stock_barang' => 'required',
            'harga_beli' => 'required',
            'harga_jual' => 'required',
        ]);

        $form = $request->except(['id', 'gambar_barang']);

        if($request->hasFile('gambar_barang')){
            $file = $request->file('gambar_barang');

            Storage::disk('public')->putFileAs('/', $file, $file->getClientOriginalName());
            $form['gambar_barang'] = Storage::url($file->getClientOriginalName());
        }

        Barang::query()->where('id', $request->id)->update($form);

        return back();
    }
}
