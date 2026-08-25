<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Debt;
use App\Models\Transaction;
use Illuminate\Http\Request;

class HutangController extends Controller
{
    // Halaman rekap hutang — route: admin.hutang
    public function index()
    {
        $groupedDebts     = Debt::where('is_paid', false)->get()->groupBy('nama_pembeli');
        $totalHutangSemua = Debt::where('is_paid', false)->sum('nominal');

        return view('admin.hutang', compact('groupedDebts', 'totalHutangSemua'));
    }

    // Tambah hutang manual — route: admin.hutang-store
    public function store(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:255',
            'barang'       => 'required|string|max:255',
            'qty'          => 'required|integer|min:1',
            'nominal'      => 'required|numeric|min:0',
        ]);

        Debt::create([
            'nama_pembeli' => $request->nama_pembeli,
            'barang'       => $request->barang,
            'qty'          => $request->qty,
            'nominal'      => $request->nominal,
            'is_paid'      => false,
        ]);

        return back()->with('success', 'Data hutang berhasil ditambah!');
    }

    // Tandai hutang lunas — route: admin.hutang-lunas
    // ⚠️ Route ini harus didaftarkan SEBELUM route /{id} di web.php
    public function lunas(Request $request, $id)
    {
        $request->validate([
            'metode_bayar' => 'required|string|in:cash,qris',
        ]);

        $debt = Debt::findOrFail($id);

        // Catat sebagai transaksi pelunasan
        Transaction::create([
            'product_id'          => $debt->product_id,
            'nama_pembeli'        => $debt->nama_pembeli . ' (Pelunasan Hutang)',
            'nama_produk_manual'  => $debt->barang . ' (Pelunasan Hutang)',
            'jumlah'              => $debt->qty,
            'total_harga'         => $debt->nominal,
            'metode_bayar'        => $request->metode_bayar,
        ]);

        $debt->update(['is_paid' => true]);

        return back()->with('success', 'Hutang ' . $debt->nama_pembeli . ' berhasil dilunasi!');
    }

    // Perbarui data hutang — route: admin.hutang-update
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:255',
            'barang'       => 'required|string|max:255',
            'qty'          => 'required|integer|min:1',
            'nominal'      => 'required|numeric|min:0',
        ]);

        $debt = Debt::findOrFail($id);
        $debt->update([
            'nama_pembeli' => $request->nama_pembeli,
            'barang'       => $request->barang,
            'qty'          => $request->qty,
            'nominal'      => $request->nominal,
        ]);

        return back()->with('success', 'Data hutang berhasil diperbarui!');
    }

    // Hapus catatan hutang — route: admin.hutang-delete
    public function destroy($id)
    {
        Debt::findOrFail($id)->delete();
        return back()->with('success', 'Catatan hutang berhasil dihapus!');
    }
}