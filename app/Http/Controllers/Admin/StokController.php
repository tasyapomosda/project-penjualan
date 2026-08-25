<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class StokController extends Controller
{
    // Halaman daftar stok — route: admin.stok
    public function index()
    {
        $products = Product::latest()->get();
        return view('admin.stok', compact('products'));
    }

    // Tambah produk baru — route: admin.stok-store
    public function store(Request $request)
    {
        $request->validate([
            'name_merk'     => 'required|string|max:255',
            'kategori'      => 'nullable|string|max:100',
            'harga'         => 'required|numeric|min:0',
            'stok_sekarang' => 'required|integer|min:0',
        ]);

        Product::create([
            'name_merk'     => $request->name_merk,
            'kategori'      => $request->kategori,
            'harga'         => $request->harga,
            'stok_awal'     => $request->stok_sekarang,
            'stok_sekarang' => $request->stok_sekarang,
        ]);

        return back()->with('success', 'Produk berhasil ditambah!');
    }

    // Perbarui data produk — route: admin.stok-update
    public function update(Request $request, $id)
    {
        $request->validate([
            'name_merk'     => 'required|string|max:255',
            'kategori'      => 'nullable|string|max:100',
            'harga'         => 'required|numeric|min:0',
            'stok_sekarang' => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($id);
        $product->update([
            'name_merk'     => $request->name_merk,
            'kategori'      => $request->kategori,
            'harga'         => $request->harga,
            'stok_sekarang' => $request->stok_sekarang,
        ]);

        return back()->with('success', 'Data jajan berhasil diperbarui!');
    }

    // Hapus produk — route: admin.stok-delete
    public function destroy($id)
    {
        Product::destroy($id);
        return back()->with('success', 'Produk berhasil dihapus!');
    }
}