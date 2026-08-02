<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Debt;
use App\Models\Product;
use App\Models\KasManual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    // ─── Query Bersama (dipakai index & exportPdf) ────────────────────────────
    private function getData(string $dari, string $sampai): array
    {
        // FIX: label "(Pelunasan Hutang)" ditulis ke kolom `nama_produk_manual`
        // (lihat AdminController::hutangLunas()), bukan ke `nama_pembeli`.
        // Filter lama tidak pernah kena, sehingga transaksi pelunasan ikut
        // terhitung dan menggelembungkan cash/total (double count dengan hutang asal).
        $transaksiHarian = Transaction::whereBetween(DB::raw('DATE(created_at)'), [$dari, $sampai])
            ->where(function ($q) {
                $q->whereNull('nama_produk_manual')
                  ->orWhere('nama_produk_manual', 'not like', '%(Pelunasan Hutang)%');
            })
            ->select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('COUNT(*) as total_transaksi'),
                DB::raw('SUM(jumlah) as total_item'),
                DB::raw("SUM(CASE WHEN metode_bayar = 'cash' THEN total_harga ELSE 0 END) as cash"),
                DB::raw("SUM(CASE WHEN metode_bayar = 'hutang' THEN total_harga ELSE 0 END) as hutang"),
                DB::raw('SUM(total_harga) as total')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tanggal', 'desc')
            ->get();

        $produkTerlaris = Product::select(
                'products.name_merk',
                DB::raw('SUM(transactions.jumlah) as total_terjual'),
                DB::raw('SUM(transactions.total_harga) as total_pendapatan')
            )
            ->join('transactions', 'products.id', '=', 'transactions.product_id')
            ->whereBetween(DB::raw('DATE(transactions.created_at)'), [$dari, $sampai])
            ->where(function ($q) {
                $q->whereNull('transactions.nama_produk_manual')
                  ->orWhere('transactions.nama_produk_manual', 'not like', '%(Pelunasan Hutang)%');
            })
            ->groupBy('products.id', 'products.name_merk')
            ->orderBy('total_terjual', 'desc')
            ->limit(10)
            ->get();

        $rekapHutang = Debt::select(
                'nama_pembeli',
                DB::raw('COUNT(*) as total_hutang'),
                DB::raw('SUM(nominal) as total_nominal'),
                DB::raw('SUM(CASE WHEN is_paid = 0 THEN nominal ELSE 0 END) as belum_lunas'),
                DB::raw('SUM(CASE WHEN is_paid = 1 THEN nominal ELSE 0 END) as sudah_lunas')
            )
            ->groupBy('nama_pembeli')
            ->orderBy('belum_lunas', 'desc')
            ->get();

        $totalCash = Transaction::whereBetween(DB::raw('DATE(created_at)'), [$dari, $sampai])
            ->where('metode_bayar', 'cash')
            ->where(function ($q) {
                $q->whereNull('nama_produk_manual')
                  ->orWhere('nama_produk_manual', 'not like', '%(Pelunasan Hutang)%');
            })
            ->sum('total_harga');

        // FIX: transaksi hutang murni (bukan pelunasan) tidak pernah punya
        // metode_bayar = 'hutang' setelah lunas, tapi filter ini tetap
        // disamakan pola-nya untuk konsistensi & jaga-jaga ke depan.
        $totalHutang = Transaction::whereBetween(DB::raw('DATE(created_at)'), [$dari, $sampai])
            ->where('metode_bayar', 'hutang')
            ->where(function ($q) {
                $q->whereNull('nama_produk_manual')
                  ->orWhere('nama_produk_manual', 'not like', '%(Pelunasan Hutang)%');
            })
            ->sum('total_harga');

        $hutangBelumLunas = Debt::where('is_paid', false)->sum('nominal');
        $pendapatanBersih = $totalCash - $hutangBelumLunas;

        // ─── Rekap Kas Manual (Debit / Kredit / Saldo Bersih) ──────────────────
        // Card terpisah, tidak memengaruhi perhitungan Pendapatan Bersih di atas.
        $totalDebitManual = KasManual::whereBetween(DB::raw('DATE(tanggal)'), [$dari, $sampai])
            ->where('tipe', 'debit')
            ->sum('nominal');

        $totalKreditManual = KasManual::whereBetween(DB::raw('DATE(tanggal)'), [$dari, $sampai])
            ->where('tipe', 'kredit')
            ->sum('nominal');

        $saldoBersihManual = $totalDebitManual - $totalKreditManual;

        return compact(
            'transaksiHarian', 'produkTerlaris', 'rekapHutang',
            'totalCash', 'totalHutang', 'hutangBelumLunas', 'pendapatanBersih',
            'totalDebitManual', 'totalKreditManual', 'saldoBersihManual'
        );
    }

    // ─── Halaman Laporan ──────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $dari   = $request->input('dari',   date('Y-m-01'));
        $sampai = $request->input('sampai', date('Y-m-d'));

        $data = $this->getData($dari, $sampai);

        return view('admin.laporan', array_merge($data, compact('dari', 'sampai')));
    }

    // ─── Export Excel (placeholder) ───────────────────────────────────────────
    public function exportExcel(Request $request)
    {
        return back()->with('info', 'Fitur export Excel segera hadir.');
    }

    // ─── Export PDF ───────────────────────────────────────────────────────────
    public function exportPdf(Request $request)
    {
        $dari   = $request->input('dari',   date('Y-m-01'));
        $sampai = $request->input('sampai', date('Y-m-d'));

        $data = $this->getData($dari, $sampai);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'admin.laporan-pdf',
            array_merge($data, compact('dari', 'sampai'))
        )->setPaper('a4', 'portrait');

        return $pdf->download("laporan-{$dari}-sd-{$sampai}.pdf");
    }
}