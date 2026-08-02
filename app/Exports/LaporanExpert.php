<?php

namespace App\Exports;

use App\Models\Transaction;
use App\Models\Debt;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;

// ─── Sheet 1: Transaksi Harian ───────────────────────────────────────────────
class TransaksiSheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $dari, $sampai;
    public function __construct($dari, $sampai) {
        $this->dari = $dari; $this->sampai = $sampai;
    }
    public function title(): string { return 'Transaksi Harian'; }
    public function headings(): array {
        return ['Tanggal', 'Total Transaksi', 'Total Item', 'Cash (Rp)', 'Hutang (Rp)', 'Total (Rp)'];
    }
    public function collection() {
        return Transaction::selectRaw("
            DATE(created_at) as tanggal,
            COUNT(*) as total_transaksi,
            SUM(jumlah) as total_item,
            SUM(CASE WHEN metode_bayar = 'cash' THEN total_harga ELSE 0 END) as cash,
            SUM(CASE WHEN metode_bayar = 'hutang' THEN total_harga ELSE 0 END) as hutang,
            SUM(total_harga) as total
        ")
        ->whereBetween(DB::raw('DATE(created_at)'), [$this->dari, $this->sampai])
        ->groupBy('tanggal')
        ->orderBy('tanggal', 'desc')
        ->get();
    }
    public function styles(Worksheet $sheet) {
        return [1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '7C3AED']], 'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true]]];
    }
}

// ─── Sheet 2: Produk Terlaris ────────────────────────────────────────────────
class ProdukSheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $dari, $sampai;
    public function __construct($dari, $sampai) {
        $this->dari = $dari; $this->sampai = $sampai;
    }
    public function title(): string { return 'Produk Terlaris'; }
    public function headings(): array {
        return ['Produk', 'Total Terjual (pcs)', 'Total Pendapatan (Rp)'];
    }
    public function collection() {
        return Transaction::selectRaw("p.name_merk, SUM(t.jumlah) as total_terjual, SUM(t.total_harga) as total_pendapatan")
            ->from('transactions as t')
            ->join('products as p', 't.product_id', '=', 'p.id')
            ->whereBetween(DB::raw('DATE(t.created_at)'), [$this->dari, $this->sampai])
            ->groupBy('p.name_merk')
            ->orderBy('total_terjual', 'desc')
            ->get();
    }
    public function styles(Worksheet $sheet) {
        return [1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '7C3AED']]]];
    }
}

// ─── Sheet 3: Hutang ─────────────────────────────────────────────────────────
class HutangSheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    public function title(): string { return 'Rekap Hutang'; }
    public function headings(): array {
        return ['Nama', 'Total Hutang', 'Total Nominal (Rp)', 'Belum Lunas (Rp)', 'Sudah Lunas (Rp)'];
    }
    public function collection() {
        return Debt::selectRaw("
            nama_pembeli,
            COUNT(*) as total_hutang,
            SUM(nominal) as total_nominal,
            SUM(CASE WHEN is_paid = 0 THEN nominal ELSE 0 END) as belum_lunas,
            SUM(CASE WHEN is_paid = 1 THEN nominal ELSE 0 END) as sudah_lunas
        ")->groupBy('nama_pembeli')->orderBy('total_nominal', 'desc')->get();
    }
    public function styles(Worksheet $sheet) {
        return [1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '7C3AED']]]];
    }
}

// ─── Sheet 4: Pendapatan Bersih ───────────────────────────────────────────────
class PendapatanSheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $dari, $sampai;
    public function __construct($dari, $sampai) {
        $this->dari = $dari; $this->sampai = $sampai;
    }
    public function title(): string { return 'Pendapatan Bersih'; }
    public function headings(): array {
        return ['Keterangan', 'Nominal (Rp)'];
    }
    public function collection() {
        $totalCash   = Transaction::whereBetween(DB::raw('DATE(created_at)'), [$this->dari, $this->sampai])
                        ->where('metode_bayar', 'cash')->sum('total_harga');
        $totalHutang = Transaction::whereBetween(DB::raw('DATE(created_at)'), [$this->dari, $this->sampai])
                        ->where('metode_bayar', 'hutang')->sum('total_harga');
        $hutangBelumLunas = Debt::where('is_paid', 0)->sum('nominal');
        $pendapatanBersih = $totalCash - $hutangBelumLunas;

        return collect([
            ['Total Cash Masuk',      $totalCash],
            ['Total Hutang Tercatat', $totalHutang],
            ['Hutang Belum Lunas',    $hutangBelumLunas],
            ['Pendapatan Bersih',     $pendapatanBersih],
        ]);
    }
    public function styles(Worksheet $sheet) {
        return [1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '7C3AED']]]];
    }
}

// ─── Main Export: Gabungkan semua sheet ──────────────────────────────────────
class LaporanExport implements WithMultipleSheets
{
    protected $dari, $sampai;
    public function __construct($dari, $sampai) {
        $this->dari = $dari; $this->sampai = $sampai;
    }
    public function sheets(): array {
        return [
            new TransaksiSheet($this->dari, $this->sampai),
            new ProdukSheet($this->dari, $this->sampai),
            new HutangSheet(),
            new PendapatanSheet($this->dari, $this->sampai),
        ];
    }
}