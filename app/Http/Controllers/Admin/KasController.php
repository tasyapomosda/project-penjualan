<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceRecord;
use Illuminate\Http\Request;

class KasController extends Controller
{
    // Halaman rekap pendapatan & kas — route: admin.pendapatan
    public function index()
    {
        $totalDebit  = FinanceRecord::where('tipe', 'debit')->sum('nominal');
        $totalKredit = FinanceRecord::where('tipe', 'kredit')->sum('nominal');
        $saldoBersih = $totalDebit - $totalKredit;

        // Hitung akumulasi saldo berjalan (running balance) berdasarkan urutan kronologis
        $allRecords = FinanceRecord::orderBy('tanggal', 'asc')->orderBy('id', 'asc')->get(['id', 'tipe', 'nominal']);
        $saldoMap = [];
        $running = 0;
        foreach ($allRecords as $rec) {
            if ($rec->tipe === 'debit') {
                $running += (float) $rec->nominal;
            } else {
                $running -= (float) $rec->nominal;
            }
            $saldoMap[$rec->id] = $running;
        }

        // Paginasi 15 baris per halaman (terbaru di atas)
        $kasManual = FinanceRecord::orderBy('tanggal', 'desc')->orderBy('id', 'desc')->paginate(15);
        foreach ($kasManual as $item) {
            $item->saldo_berjalan = $saldoMap[$item->id] ?? 0;
        }

        return view('admin.pendapatan', compact(
            'kasManual',
            'totalDebit',
            'totalKredit',
            'saldoBersih'
        ));
    }

    // Simpan catatan kas manual — route: admin.kas-store
    public function store(Request $request)
    {
        $request->validate([
            'tanggal'    => 'required|date',
            'keterangan' => 'required|string',
            'tipe'       => 'required|in:debit,kredit',
            'nominal'    => 'required|numeric',
        ]);

        FinanceRecord::create([
            'tanggal'    => $request->tanggal,
            'keterangan' => $request->keterangan,
            'tipe'       => $request->tipe,
            'nominal'    => $request->nominal,
        ]);

        return back()->with('success', 'Catatan kas manual berhasil disimpan!');
    }

    // Perbarui catatan kas — route: admin.kas-update
    public function update(Request $request, $id)
    {
        $record = FinanceRecord::findOrFail($id);

        $record->update([
            'tanggal'    => $request->tanggal,
            'keterangan' => $request->keterangan,
            'tipe'       => $request->tipe,
            'nominal'    => $request->nominal,
        ]);

        return redirect()->route('admin.pendapatan')
            ->with('success', 'Catatan kas berhasil diperbarui!');
    }

    // Hapus catatan kas — route: admin.kas-delete
    public function destroy($id)
    {
        FinanceRecord::findOrFail($id)->delete();
        return back()->with('success', 'Catatan kas berhasil dihapus!');
    }
}