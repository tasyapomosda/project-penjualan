<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1e293b; background: #fff; }
        .header { background: #0f172a; color: white; padding: 20px 24px; margin-bottom: 20px; }
        .header h1 { font-size: 20px; font-weight: bold; }
        .header p { font-size: 10px; color: #94a3b8; margin-top: 4px; }
        .section { margin-bottom: 20px; padding: 0 24px; }
        .section-title { font-size: 12px; font-weight: bold; color: #7c3aed; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px; border-bottom: 2px solid #7c3aed; padding-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        th { background: #1e293b; color: white; padding: 6px 10px; text-align: left; font-size: 9px; text-transform: uppercase; }
        td { padding: 6px 10px; border-bottom: 1px solid #e2e8f0; }
        tr:nth-child(even) td { background: #f8fafc; }
        .tfoot td { background: #1e293b; color: white; font-weight: bold; }
        .stat-grid { display: table; width: 100%; margin-bottom: 20px; padding: 0 24px; }
        .stat-box { display: table-cell; width: 25%; padding: 12px 16px; background: #f1f5f9; border-right: 3px solid #7c3aed; margin-right: 8px; }
        .stat-box .label { font-size: 9px; color: #64748b; text-transform: uppercase; font-weight: bold; }
        .stat-box .value { font-size: 16px; font-weight: bold; color: #1e293b; margin-top: 4px; }
        .text-green { color: #059669; }
        .text-red { color: #dc2626; }
        .text-blue { color: #2563eb; }
        .footer { text-align: center; color: #94a3b8; font-size: 9px; padding: 12px; border-top: 1px solid #e2e8f0; margin-top: 20px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Penjualan — Showcase Snack 🍪</h1>
        <p>Periode: {{ $dari }} s/d {{ $sampai }} &nbsp;|&nbsp; Dicetak: {{ now()->format('d M Y H:i') }}</p>
    </div>

    {{-- Stat Cards --}}
    <div class="stat-grid">
        <div class="stat-box">
            <div class="label">Total Cash</div>
            <div class="value text-green">Rp {{ number_format($totalCash,0,',','.') }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Total Hutang</div>
            <div class="value text-blue">Rp {{ number_format($totalHutang,0,',','.') }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Belum Lunas</div>
            <div class="value text-red">Rp {{ number_format($hutangBelumLunas,0,',','.') }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Pendapatan Bersih</div>
            <div class="value {{ $pendapatanBersih >= 0 ? 'text-green' : 'text-red' }}">Rp {{ number_format($pendapatanBersih,0,',','.') }}</div>
        </div>
    </div>

    {{-- Transaksi Harian --}}
    <div class="section">
        <div class="section-title">Transaksi Harian</div>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th><th>Transaksi</th><th>Item</th>
                    <th>Cash (Rp)</th><th>Hutang (Rp)</th><th>Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksiHarian as $t)
                <tr>
                    <td>{{ $t->tanggal }}</td>
                    <td>{{ $t->total_transaksi }}</td>
                    <td>{{ $t->total_item }}</td>
                    <td class="text-green">{{ number_format($t->cash,0,',','.') }}</td>
                    <td class="{{ $t->hutang > 0 ? 'text-red' : '' }}">{{ number_format($t->hutang,0,',','.') }}</td>
                    <td><strong>{{ number_format($t->total,0,',','.') }}</strong></td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="tfoot">
                    <td colspan="3"><strong>TOTAL</strong></td>
                    <td>{{ number_format($transaksiHarian->sum('cash'),0,',','.') }}</td>
                    <td>{{ number_format($transaksiHarian->sum('hutang'),0,',','.') }}</td>
                    <td>{{ number_format($transaksiHarian->sum('total'),0,',','.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Produk Terlaris --}}
    <div class="section">
        <div class="section-title">Produk Terlaris</div>
        <table>
            <thead>
                <tr><th>No</th><th>Produk</th><th>Qty Terjual</th><th>Total Pendapatan (Rp)</th></tr>
            </thead>
            <tbody>
                @foreach($produkTerlaris as $i => $p)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ ucwords(strtolower($p->name_merk)) }}</td>
                    <td>{{ $p->total_terjual }} pcs</td>
                    <td class="text-green">{{ number_format($p->total_pendapatan,0,',','.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Rekap Hutang --}}
    <div class="section">
        <div class="section-title">Rekap Hutang per Anggota</div>
        <table>
            <thead>
                <tr><th>Nama</th><th>Jml Hutang</th><th>Total (Rp)</th><th>Belum Lunas (Rp)</th><th>Sudah Lunas (Rp)</th></tr>
            </thead>
            <tbody>
                @foreach($rekapHutang as $h)
                <tr>
                    <td>{{ $h->nama_pembeli }}</td>
                    <td>{{ $h->total_hutang }}x</td>
                    <td>{{ number_format($h->total_nominal,0,',','.') }}</td>
                    <td class="{{ $h->belum_lunas > 0 ? 'text-red' : '' }}">{{ number_format($h->belum_lunas,0,',','.') }}</td>
                    <td class="{{ $h->sudah_lunas > 0 ? 'text-green' : '' }}">{{ number_format($h->sudah_lunas,0,',','.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="tfoot">
                    <td colspan="3"><strong>TOTAL</strong></td>
                    <td>{{ number_format($rekapHutang->sum('belum_lunas'),0,',','.') }}</td>
                    <td>{{ number_format($rekapHutang->sum('sudah_lunas'),0,',','.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="footer">
        Showcase Snack &copy; {{ now()->year }} &nbsp;|&nbsp; Laporan ini dibuat otomatis oleh sistem
    </div>

</body>
</html>