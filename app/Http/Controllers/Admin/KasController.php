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
        $kasManual   = FinanceRecord::latest('tanggal')->get();
        $totalDebit  = FinanceRecord::where('tipe', 'debit')->sum('nominal');
        $totalKredit = FinanceRecord::where('tipe', 'kredit')->sum('nominal');
        $saldoBersih = $totalDebit - $totalKredit;

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