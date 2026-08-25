<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Debt;
use Illuminate\Http\Request;

class HarianController extends Controller
{
    // Rekap transaksi harian — route: admin.harian
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal', date('Y-m-d'));

        $transactions = Transaction::whereDate('created_at', $tanggal)
            ->where('metode_bayar', '!=', 'hutang')
            ->with('product')
            ->latest()
            ->get();

        $all_products = Product::all();

        $total_cash = Transaction::whereDate('created_at', $tanggal)
            ->where('metode_bayar', 'cash')
            ->sum('total_harga');

        $total_qris = Transaction::whereDate('created_at', $tanggal)
            ->where('metode_bayar', 'qris')
            ->sum('total_harga');

        $total_pendapatan_harian = $total_cash + $total_qris;

        $total_hutang_hari_ini = Debt::whereDate('created_at', $tanggal)
            ->sum('nominal');

        return view('admin.harian', compact(
            'transactions',
            'tanggal',
            'total_cash',
            'total_qris',
            'total_pendapatan_harian',
            'total_hutang_hari_ini',
            'all_products'
        ));
    }

    // Hapus transaksi & kembalikan stok — route: admin.delete-transaksi
    public function destroy($id)
    {
        $transaksi = Transaction::findOrFail($id);

        $product = Product::find($transaksi->product_id);
        if ($product) {
            $product->increment('stok_sekarang', $transaksi->jumlah);
        }

        $transaksi->delete();

        return back()->with('success', 'Transaksi berhasil dihapus dan stok dikembalikan!');
    }
}