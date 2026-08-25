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
        $records      = FinanceRecord::latest()->get();
        $total_debit  = $records->sum('debit');
        $total_kredit = $records->sum('kredit');
        $saldo_akhir  = $total_debit - $total_kredit;

        return view('admin.pendapatan', compact(
            'records',
            'saldo_akhir',
            'total_debit',
            'total_kredit'
        ));
    }

    // Simpan catatan kas manual — route: admin.kas-store
    public function store(Request $request)
    {
        $request->validate([
            'tanggal'         => 'required|date',
            'jenis_transaksi' => 'required|string',
        ]);

        $debit  = str_replace('.', '', $request->debit  ?? 0);
        $kredit = str_replace('.', '', $request->kredit ?? 0);

        FinanceRecord::create([
            'tanggal'         => $request->tanggal,
            'jenis_transaksi' => $request->jenis_transaksi,
            'debit'           => $debit,
            'kredit'          => $kredit,
        ]);

        return back()->with('success', 'Catatan kas manual berhasil disimpan!');
    }

    // Perbarui catatan kas — route: admin.kas-update
    public function update(Request $request, $id)
    {
        $record = FinanceRecord::findOrFail($id);

        $record->update([
            'tanggal'         => $request->tanggal,
            'jenis_transaksi' => $request->jenis_transaksi,
            'debit'           => $request->debit  ?? 0,
            'kredit'          => $request->kredit ?? 0,
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