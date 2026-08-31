<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Debt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembeliController extends Controller
{
    // Halaman katalog pembeli (depan / self-service)
    public function index()
    {
        $products = Product::where('stok_sekarang', '>', 0)->get();
        return view('katalog.katalog', compact('products'));
    }

    // Proses pembelian dari halaman pembeli
    public function store(Request $request)
    {
        $request->validate([
            'nama_pembeli'       => 'required|string',
            'metode_bayar'       => 'required|string|in:cash,qris',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.jumlah'     => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stok_sekarang < $item['jumlah']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Stok {$product->name_merk} tidak cukup."
                    ], 422);
                }

                Transaction::create([
                    'product_id'   => $product->id,
                    'nama_pembeli' => $request->nama_pembeli,
                    'jumlah'       => $item['jumlah'],
                    'total_harga'  => $product->harga * $item['jumlah'],
                    'metode_bayar' => $request->metode_bayar,
                ]);

                $product->decrement('stok_sekarang', $item['jumlah']);

                if ($request->metode_bayar === 'hutang') {
                    Debt::create([
                        'nama_pembeli' => $request->nama_pembeli,
                        'product_id'   => $product->id,
                        'barang'       => $product->name_merk,
                        'qty'          => $item['jumlah'],
                        'nominal'      => $product->harga * $item['jumlah'],
                        'is_paid'      => false,
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Transaksi gagal: ' . $e->getMessage()
            ], 500);
        }
    }
}