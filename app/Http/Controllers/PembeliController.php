<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Debt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembeliController extends Controller
{
    // Halaman katalog pembeli (depan / self-service)
    public function index(Request $request)
    {
        $products = Product::where('stok_sekarang', '>', 0)->get();

        // Info akun yang sedang login (null kalau pengunjung biasa)
        $user = $request->user();
        $akun = [
            'login' => (bool) $user,
            'admin' => $user ? $user->isAdmin() : false,
            'staf'  => $user ? ! $user->isAdmin() : false,
            'nama'  => $user ? $user->name : null,
        ];

        // Daftar staf hanya dikirim ke admin (untuk dropdown "atas nama staf")
        $stafList = ($user && $user->isAdmin())
            ? User::where('role', 'staf')->orderBy('name')->get(['id', 'name'])
            : collect();

        return view('katalog.katalog', compact('products', 'akun', 'stafList'));
    }

    // Proses pembelian dari halaman pembeli
    public function store(Request $request)
    {
        $user = $request->user();

        // Kasbon ('hutang') HANYA diterima dari user yang sudah login.
        // Pengunjung biasa yang mengirim 'hutang' (misalnya lewat DevTools)
        // akan ditolak validasi dengan status 422.
        $metodeBoleh = $user ? 'cash,qris,hutang' : 'cash,qris';

        $request->validate([
            'nama_pembeli'       => 'nullable|string|max:100',
            'metode_bayar'       => 'required|string|in:' . $metodeBoleh,
            'staf_id'            => 'nullable|integer',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.jumlah'     => 'required|integer|min:1',
        ]);

        // Nama pembeli ditentukan di SERVER, bukan dipercaya dari browser.
        $nama = trim((string) $request->nama_pembeli);

        if ($user && ! $user->isAdmin()) {
            // Staf login: nama selalu nama akunnya sendiri
            $nama = $user->name;
        } elseif ($user && $user->isAdmin() && $request->staf_id) {
            // Admin memilih staf dari dropdown (berlaku untuk semua metode bayar)
            $staf = User::where('id', $request->staf_id)
                        ->where('role', 'staf')
                        ->first();

            if (! $staf) {
                return response()->json([
                    'success' => false,
                    'message' => 'Staf tidak ditemukan.'
                ], 422);
            }

            $nama = $staf->name;
        } elseif ($request->metode_bayar === 'hutang') {
            // Kasbon tanpa staf terpilih — tolak
            return response()->json([
                'success' => false,
                'message' => 'Pilih nama staf untuk kasbon.'
            ], 422);
        }

        if ($nama === '') {
            return response()->json([
                'success' => false,
                'message' => 'Nama pembeli wajib diisi.'
            ], 422);
        }

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
                    'nama_pembeli' => $nama,
                    'jumlah'       => $item['jumlah'],
                    'total_harga'  => $product->harga * $item['jumlah'],
                    'metode_bayar' => $request->metode_bayar,
                ]);

                $product->decrement('stok_sekarang', $item['jumlah']);

                if ($request->metode_bayar === 'hutang') {
                    Debt::create([
                        'nama_pembeli' => $nama,
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