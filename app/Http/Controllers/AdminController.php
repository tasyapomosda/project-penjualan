<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Debt;
use Illuminate\Support\Facades\DB;
use App\Models\KasManual;

class AdminController extends Controller
{
    // ─── DASHBOARD ───────────────────────────────────────────────────────────
    public function index()
    {
        $total_jajan = Transaction::whereDate('created_at', today())->sum('jumlah');

        $transactions = Transaction::with('product')
                        ->latest()
                        ->take(10)
                        ->get();

        // FIX: variable $topProduct (bukan $terlaris) sesuai dashboard.blade
        $topProduct = Transaction::with('product')
                        ->select('product_id', DB::raw('SUM(jumlah) as total_qty'))
                        ->groupBy('product_id')
                        ->orderByDesc('total_qty')
                        ->first();

        $topBuyer = Transaction::select('nama_pembeli', DB::raw('count(*) as total'))
                        ->groupBy('nama_pembeli')
                        ->orderByDesc('total')
                        ->first();

        return view('admin.dashboard', compact(
            'total_jajan',
            'transactions',
            'topProduct',
            'topBuyer',
        ));
    }

       // ─── HUTANG: Simpan baru ──────────────────────────────────────────────────
    public function hutangStore(Request $request)
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
            'is_paid'      => 0,
        ]);

        return redirect()->route('admin.hutang')->with('success', 'Hutang berhasil dicatat!');
    }

    // ─── HUTANG: Edit / Update ────────────────────────────────────────────────
    public function hutangUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:255',
            'barang'       => 'required|string|max:255',
            'qty'          => 'required|integer|min:1',
            'nominal'      => 'required|numeric|min:0',
        ]);

        Debt::findOrFail($id)->update([
            'nama_pembeli' => $request->nama_pembeli,
            'barang'       => $request->barang,
            'qty'          => $request->qty,
            'nominal'      => $request->nominal,
        ]);

        return redirect()->route('admin.hutang')->with('success', 'Data hutang berhasil diperbarui!');
    }

    // ─── HUTANG: Hapus ────────────────────────────────────────────────────────
    public function hutangDelete($id)
    {
        Debt::findOrFail($id)->delete();
        return redirect()->route('admin.hutang')->with('success', 'Data hutang berhasil dihapus.');
    }

    // ─── HUTANG: Lunas ───────────────────────────────────────────────────────
    // Method baru — menerima metode_bayar (cash / qris) dari modal hutang.blade
    // Setelah tandai lunas, buat record Transaction baru agar muncul di:
    //   - Transaksi Harian   → dengan badge QRIS jika metode = qris
    //   - Aktivitas Terakhir → sama
    public function hutangLunas(Request $request, $id)
    {
        $request->validate([
            'metode_bayar' => 'required|in:cash,qris',
        ]);

        $debt = Debt::findOrFail($id);

        // 1. Tandai hutang sebagai lunas
        $debt->update(['is_paid' => 1]);

        // 2. Buat record transaksi pelunasan agar muncul di riwayat harian
        Transaction::create([
            'nama_pembeli'       => $debt->nama_pembeli,
            'product_id'         => null,
            'jumlah'             => $debt->qty,
            'total_harga'        => $debt->nominal,
            'metode_bayar'       => $request->metode_bayar,
            'nama_produk_manual' => $debt->barang . ' (Pelunasan Hutang)',
        ]);

        $label = $request->metode_bayar === 'qris' ? 'via QRIS' : 'tunai';

        return redirect()->route('admin.hutang')
                        ->with('success', "Hutang {$debt->nama_pembeli} untuk \"{$debt->barang}\" lunas {$label}! ✅");
    }

    // ─── HUTANG: List ─────────────────────────────────────────────────────────
    public function hutang()
    {
        $debts = Debt::where('is_paid', 0)->latest()->get();

        $groupedDebts     = $debts->groupBy('nama_pembeli');
        $totalHutangSemua = $debts->sum('nominal');

        return view('admin.hutang', compact(
            'groupedDebts',
            'totalHutangSemua'
        ));
    }

    // ─── KAS MANUAL ──────────────────────────────────────────────────────────
    public function storeKas(Request $request)
    {
        $request->validate([
        'keterangan' => 'required|string|max:255',
        'tipe'       => 'required|in:debit,kredit',
        'nominal'    => 'required|numeric|min:0',
        'tanggal'    => 'required|date',
        ]);

        KasManual::create([
            'keterangan' => $request->keterangan,
            'tipe'       => $request->tipe,
            'nominal'    => $request->nominal,
            'tanggal'    => $request->tanggal,
        ]);

        return redirect()->route('admin.pendapatan')->with('success', 'Catatan kas berhasil disimpan! 💸');
    }

    public function kasDelete($id)
    {
        KasManual::findOrFail($id)->delete();
        return redirect()->route('admin.pendapatan')->with('success', 'Data berhasil dihapus.');
    }

    public function kasUpdate(Request $request, $id)
    {
        $request->validate([
        'keterangan' => 'required|string|max:255',
        'tipe'       => 'required|in:debit,kredit',
        'nominal'    => 'required|numeric|min:0',
        'tanggal'    => 'required|date',
        ]);

        KasManual::findOrFail($id)->update([
            'keterangan' => $request->keterangan,
            'tipe'       => $request->tipe,
            'nominal'    => $request->nominal,
            'tanggal'    => $request->tanggal,
        ]);

        return redirect()->route('admin.pendapatan')->with('success', 'Catatan kas berhasil diperbarui!');
    }

    // ─── PRODUK: Simpan ──────────────────────────────────────────────────────
    public function storeProduct(Request $request)
    {
        $cleanHarga = (int) str_replace('.', '', $request->harga);

        $request->validate([
            'name_merk' => 'required|string|max:255',
            'stok_awal' => 'required|integer|min:0',
        ]);

        Product::create([
            'name_merk'     => $request->name_merk,
            'kategori'      => $request->kategori ?? 'Umum',
            'harga'         => $cleanHarga,
            'stok_awal'     => $request->stok_awal,
            'stok_sekarang' => $request->stok_awal,
        ]);

        return redirect()->back()->with('success', 'Jajan baru berhasil ditambah! 🍿');
    }

    // ─── TRANSAKSI: API response (dari catalog) ───────────────────────────────
    public function store(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Transaksi Anda Berhasil! 🍿',
        ]);
    }

    // ─── PENDAPATAN: Rekap & Kas Manual ──────────────────────────────────────
    public function pendapatan()
    {
        $kasManual = KasManual::latest()->get();

        $totalDebit  = KasManual::where('tipe', 'debit')->sum('nominal');
        $totalKredit = KasManual::where('tipe', 'kredit')->sum('nominal');
        $saldoBersih = $totalDebit - $totalKredit;

        return view('admin.pendapatan', compact(
            'kasManual',
            'totalDebit',
            'totalKredit',
            'saldoBersih'
        ));
    }

    // ─── PRODUK: Update ──────────────────────────────────────────────────────
    public function updateProduct(Request $request, $id)
    {
        $cleanHarga = (int) str_replace('.', '', $request->harga);

        $request->validate([
            'name_merk'     => 'required|string|max:255',
            'stok_sekarang' => 'required|integer|min:0',
        ]);

        Product::findOrFail($id)->update([
            'name_merk'     => $request->name_merk,
            'kategori'      => $request->kategori ?? 'Umum',
            'harga'         => $cleanHarga,
            'stok_sekarang' => $request->stok_sekarang,
        ]);

        return redirect()->back()->with('success', 'Data produk berhasil diperbarui!');
    }

    // ─── PRODUK: Hapus ───────────────────────────────────────────────────────
    public function deleteProduct($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }
}