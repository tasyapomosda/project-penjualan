<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Debt;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HutangController extends Controller
{
    /**
     * Aturan & pesan validasi dipakai bersama oleh store dan update.
     */
    private function rules(): array
    {
        return [
            'nama_pembeli' => 'required|string|max:255',
            'barang'       => 'required|string|max:255',
            'qty'          => 'required|integer|min:1',
            'nominal'      => 'required|numeric|min:1',
        ];
    }

    private function messages(): array
    {
        return [
            'nama_pembeli.required' => 'Nama pembeli wajib diisi.',
            'barang.required'       => 'Nama barang wajib diisi.',
            'qty.required'          => 'Jumlah barang wajib diisi.',
            'qty.min'               => 'Jumlah barang minimal 1.',
            'nominal.required'      => 'Nominal hutang wajib diisi.',
            'nominal.numeric'       => 'Nominal hutang harus berupa angka.',
            'nominal.min'           => 'Hutang dengan nominal 0 tidak bisa diinput. Nominal minimal 1.',
        ];
    }

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
        $request->validate($this->rules(), $this->messages());

        DB::transaction(function () use ($request) {
            // Simpan sebagai catatan hutang
            Debt::create([
                'nama_pembeli' => $request->nama_pembeli,
                'barang'       => $request->barang,
                'qty'          => $request->qty,
                'nominal'      => $request->nominal,
                'is_paid'      => false,
            ]);

            // Catat juga sebagai transaksi, supaya masuk ke riwayat/dashboard
            Transaction::create([
                'product_id'         => null,
                'nama_pembeli'       => $request->nama_pembeli,
                'nama_produk_manual' => $request->barang,
                'jumlah'             => $request->qty,
                'total_harga'        => $request->nominal,
                'metode_bayar'       => 'hutang',
            ]);
        });

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

        if ($debt->is_paid) {
            return back()->with('error', 'Hutang ini sudah lunas sebelumnya.');
        }

        DB::transaction(function () use ($request, $debt) {
            // Catat sebagai transaksi pelunasan
            Transaction::create([
                'product_id'         => $debt->product_id,
                'nama_pembeli'       => $debt->nama_pembeli,
                'nama_produk_manual' => $debt->barang . ' (Pelunasan Hutang)',
                'jumlah'             => $debt->qty,
                'total_harga'        => $debt->nominal,
                'metode_bayar'       => $request->metode_bayar,
            ]);

            $debt->update(['is_paid' => true]);
        });

        return back()->with('success', 'Hutang ' . $debt->nama_pembeli . ' berhasil dilunasi!');
    }

    // Perbarui data hutang — route: admin.hutang-update
    public function update(Request $request, $id)
    {
        $request->validate($this->rules(), $this->messages());

        $debt = Debt::findOrFail($id);
        $debt->update($request->only(['nama_pembeli', 'barang', 'qty', 'nominal']));

        return back()->with('success', 'Data hutang berhasil diperbarui!');
    }

    // Hapus catatan hutang — route: admin.hutang-delete
    public function destroy($id)
    {
        Debt::findOrFail($id)->delete();

        return back()->with('success', 'Catatan hutang berhasil dihapus!');
    }
}