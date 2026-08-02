<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\FinanceRecord;
use App\Models\Debt;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    // Halaman Depan Pembeli
    public function index()
    {
        $products = Product::where('stok_sekarang', '>', 0)->get();
        return view('pembeli.index', compact('products'));
    }

    /**
     * PROSES PEMBELIAN OTOMATIS
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pembeli'       => 'required|string',
            'metode_bayar'       => 'required|string',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.jumlah'     => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Cek stok cukup
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

                // Kurangi stok
                $product->decrement('stok_sekarang', $item['jumlah']);

                // Jika hutang/kasbon, catat ke tabel hutang
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

    /**
     * DASHBOARD ADMIN
     */
    public function admin(Request $request)
    {
        // Produk Terlaris
        $topProduct = Transaction::select('product_id', DB::raw('SUM(jumlah) as total_qty'))
            ->groupBy('product_id')->with('product')->orderBy('total_qty', 'desc')->first();

        // Pembeli Tersering
        $topBuyer = Transaction::select('nama_pembeli', DB::raw('COUNT(*) as total_transaksi'))
            ->groupBy('nama_pembeli')->orderBy('total_transaksi', 'desc')->first();

        // Total pendapatan: hanya hitung yang 'cash'
        $total_pendapatan = Transaction::where('metode_bayar', 'cash')->sum('total_harga');

        // Item terjual: kecuali record pelunasan
        $total_jajan = Transaction::where('nama_pembeli', 'not like', '%(Pelunasan Hutang)%')->sum('jumlah');

        $transactions = Transaction::with('product')->latest()->get();

        return view('admin.index', compact('topProduct', 'topBuyer', 'transactions', 'total_pendapatan', 'total_jajan'));
    }

    // ─── CRUD PRODUK ────────────────────────────────────────────────────────

    public function produk()
    {
        $products = Product::latest()->get();
        return view('admin.produk', compact('products'));
    }

    public function storeProduk(Request $request)
    {
        Product::create([
            'name_merk'     => $request->name_merk,
            'kategori'      => $request->kategori,
            'harga'         => $request->harga,
            'stok_awal'     => $request->stok_sekarang,
            'stok_sekarang' => $request->stok_sekarang,
        ]);

        return back()->with('success', 'Produk berhasil ditambah!');
    }

    public function updateProduk(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update([
            'name_merk'    => $request->name_merk,
            'kategori'     => $request->kategori,
            'harga'        => $request->harga,
            'stok_sekarang'=> $request->stok_sekarang,
        ]);
        return back()->with('success', 'Data jajan berhasil diperbarui!');
    }

    public function deleteProduk($id)
    {
        Product::destroy($id);
        return back()->with('success', 'Produk dihapus!');
    }

    // ─── STOK BARANG ────────────────────────────────────────────────────────
    public function stok()
    {
        $products = Product::latest()->get();
        return view('admin.stok', compact('products'));
    }

    // ─── REKAP PENDAPATAN ───────────────────────────────────────────────────

    public function rekapPendapatan()
    {
        $records      = FinanceRecord::latest()->get();
        $total_debit  = $records->sum('debit');
        $total_kredit = $records->sum('kredit');
        $saldo_akhir  = $total_debit - $total_kredit;

        return view('admin.pendapatan', compact('records', 'saldo_akhir', 'total_debit', 'total_kredit'));
    }

    // ─── REKAP HUTANG ───────────────────────────────────────────────────────

    public function rekapHutang()
    {
        $groupedDebts     = Debt::where('is_paid', false)->get()->groupBy('nama_pembeli');
        $totalHutangSemua = Debt::where('is_paid', false)->sum('nominal');

        return view('admin.hutang', compact('groupedDebts', 'totalHutangSemua'));
    }

    // NOTE: method markAsPaid() lama sudah DIHAPUS dari sini.
    // Sebelumnya method ini memanggil $request->metode_bayar padahal
    // parameter $request tidak pernah dideklarasikan (public function markAsPaid($id)),
    // sehingga akan fatal error "Undefined variable $request" kalau ke-trigger.
    // Fungsinya sudah digantikan sepenuhnya oleh AdminController::hutangLunas(),
    // yang benar menerima Request, validasi metode_bayar, dan menulis label
    // "(Pelunasan Hutang)" ke kolom nama_produk_manual. Pastikan routes tidak
    // lagi mengarah ke TransaksiController@markAsPaid.

    // ─── HAPUS TRANSAKSI ────────────────────────────────────────────────────

    public function deleteTransaksi($id)
    {
        $transaksi = Transaction::findOrFail($id);

        // Kembalikan stok
        $product = Product::find($transaksi->product_id);
        if ($product) {
            $product->increment('stok_sekarang', $transaksi->jumlah);
        }

        $transaksi->delete();

        return back()->with('success', 'Transaksi berhasil dihapus dan stok dikembalikan!');
    }

    // ─── KAS MANUAL ─────────────────────────────────────────────────────────

    public function storeFinance(Request $request)
    {
        $request->validate([
            'tanggal'         => 'required|date',
            'jenis_transaksi' => 'required|string',
        ]);

        $debit  = str_replace('.', '', $request->debit ?? 0);
        $kredit = str_replace('.', '', $request->kredit ?? 0);

        FinanceRecord::create([
            'tanggal'         => $request->tanggal,
            'jenis_transaksi' => $request->jenis_transaksi,
            'debit'           => $debit,
            'kredit'          => $kredit,
        ]);

        return back()->with('success', 'Catatan kas manual berhasil disimpan!');
    }

    public function updateFinance(Request $request, $id)
    {
        $record = FinanceRecord::findOrFail($id);

        $data           = $request->all();
        $data['debit']  = $request->debit ?? 0;
        $data['kredit'] = $request->kredit ?? 0;

        $record->update($data);

        return redirect()->route('admin.pendapatan')->with('success', 'Catatan kas berhasil diperbarui!');
    }

    public function deleteFinance($id)
    {
        FinanceRecord::findOrFail($id)->delete();
        return back()->with('success', 'Catatan kas berhasil dihapus!');
    }

    // ─── HUTANG MANUAL (dari form admin) ────────────────────────────────────

    public function updateDebt(Request $request, $id)
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

    // ─── HAPUS HUTANG ────────────────────────────────────────────────────────

    public function deleteDebt($id)
    {
        Debt::findOrFail($id)->delete();

        return back()->with('success', 'Catatan hutang berhasil dihapus!');
    }

    // ─── REKAP HARIAN ───────────────────────────────────────────────────────

   public function rekapHarian(Request $request)
    {
        $tanggal = $request->input('tanggal', date('Y-m-d'));

        $transactions = Transaction::whereDate('created_at', $tanggal)
                        ->where('metode_bayar', '!=', 'hutang')
                        ->with('product')
                        ->latest()
                        ->get();

        $all_products = Product::all();

        // Breakdown uang masuk per metode bayar
        $total_cash = Transaction::whereDate('created_at', $tanggal)
                        ->where('metode_bayar', 'cash')
                        ->sum('total_harga');

        $total_qris = Transaction::whereDate('created_at', $tanggal)
                        ->where('metode_bayar', 'qris')
                        ->sum('total_harga');

        $total_pendapatan_harian = $total_cash + $total_qris;

        // Hutang yang dicatat pada tanggal yang dipilih
        $total_hutang_hari_ini = Debt::whereDate('created_at', $tanggal)->sum('nominal');

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
}