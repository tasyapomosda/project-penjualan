<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - Showcase Snack</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' };
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Quicksand', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    </style>
    <script>
        // Terapkan tema sebelum body dirender, biar tidak "flash" warna salah
        if (localStorage.getItem('theme') === 'dark' ||
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-slate-50 dark:bg-[#0f172a] text-slate-900 dark:text-slate-200 min-h-screen flex overflow-hidden h-screen font-medium">

    {{-- ─── SIDEBAR — spacing disamakan persis dengan sidebar.blade (p-8, mb-12, gap-4, space-y-2) ── --}}
    <aside class="w-72 bg-white dark:bg-[#1e293b] border-r border-slate-100 dark:border-slate-800 flex flex-col p-8 z-40 h-screen fixed left-0 top-0 transition-colors">

        {{-- Logo --}}
        <div class="flex items-center gap-3 mb-12">
            <div class="bg-[#6366f1] dark:bg-purple-600 p-2 rounded-xl shadow-lg shadow-indigo-100 dark:shadow-purple-900/50">
                <i data-lucide="cookie" class="text-white w-6 h-6"></i>
            </div>
            <div>
                <span class="text-xl font-extrabold text-slate-800 dark:text-white tracking-tight leading-none block">Showcase</span>
                <p class="text-slate-400 dark:text-slate-500 text-[10px] uppercase tracking-widest font-bold">Snack Admin</p>
            </div>
        </div>

        {{-- Nav links --}}
        <nav class="flex-1 space-y-2 overflow-y-auto custom-scrollbar">
            <p class="text-slate-400 dark:text-slate-500 text-[10px] uppercase tracking-widest font-bold px-3 mb-2">Menu</p>

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-4 px-4 py-4 rounded-2xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50/50 dark:hover:text-white dark:hover:bg-slate-800 transition-all font-semibold">
                <i data-lucide="layout-grid" class="w-5 h-5"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.harian') }}"
               class="flex items-center gap-4 px-4 py-4 rounded-2xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50/50 dark:hover:text-white dark:hover:bg-slate-800 transition-all font-semibold">
                <i data-lucide="calendar" class="w-5 h-5"></i>
                <span>Transaksi Harian</span>
            </a>
            <a href="{{ route('admin.stok') }}"
               class="flex items-center gap-4 px-4 py-4 rounded-2xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50/50 dark:hover:text-white dark:hover:bg-slate-800 transition-all font-semibold">
                <i data-lucide="package" class="w-5 h-5"></i>
                <span>Stok Barang</span>
            </a>
            <a href="{{ route('admin.pendapatan') }}"
               class="flex items-center gap-4 px-4 py-4 rounded-2xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50/50 dark:hover:text-white dark:hover:bg-slate-800 transition-all font-semibold">
                <i data-lucide="banknote" class="w-5 h-5"></i>
                <span>Rekap Pendapatan</span>
            </a>
            <a href="{{ route('admin.hutang') }}"
               class="flex items-center gap-4 px-4 py-4 rounded-2xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50/50 dark:hover:text-white dark:hover:bg-slate-800 transition-all font-semibold">
                <i data-lucide="book-open" class="w-5 h-5"></i>
                <span>Rekap Hutang</span>
            </a>

            {{-- Active: Laporan --}}
            <a href="{{ route('admin.laporan') }}"
               class="flex items-center gap-4 px-4 py-4 rounded-2xl bg-indigo-50 dark:bg-purple-600/20 text-indigo-600 dark:text-purple-400 font-bold shadow-sm dark:shadow-none border border-transparent dark:border-purple-500/30">
                <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
                <span>Laporan Penjualan</span>
            </a>
        </nav>

        {{-- Logout — nempel mt-auto, tanpa border pemisah, sama seperti sidebar.blade --}}
        <form action="{{ route('logout') }}" method="POST" class="mt-auto">
            @csrf
            <button type="submit"
                class="flex items-center gap-4 px-4 py-4 w-full rounded-2xl text-rose-500 dark:text-red-400 hover:bg-rose-50 dark:hover:bg-red-500/10 transition-all font-bold">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                <span>Keluar</span>
            </button>
        </form>
    </aside>

    {{-- ─── MAIN CONTENT ─── --}}
    <main class="ml-72 flex-1 p-8 overflow-y-auto custom-scrollbar">

        {{-- ── Header: Judul kiri, Toggle Tema + Filter Periode + Export PDF kanan ── --}}
        <div class="flex items-start justify-between gap-4 mb-6 flex-wrap">
            <div>
                <h2 class="text-2xl font-black text-slate-800 dark:text-white">Laporan Penjualan 📊</h2>
                <p class="text-slate-400 dark:text-slate-500 text-xs mt-1">
                    Periode: <span class="text-indigo-600 dark:text-purple-400 font-bold">{{ $dari }}</span>
                    s/d <span class="text-indigo-600 dark:text-purple-400 font-bold">{{ $sampai }}</span>
                </p>
            </div>

            <div class="flex items-end gap-3 flex-wrap">

                {{-- Toggle Tema Gelap/Terang --}}
                <button type="button" onclick="toggleTheme()"
                    class="flex items-center justify-center w-[38px] h-[38px] rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-500 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all"
                    title="Ganti tema">
                    <i data-lucide="sun" class="w-4 h-4 hidden dark:inline"></i>
                    <i data-lucide="moon" class="w-4 h-4 inline dark:hidden"></i>
                </button>

                <form method="GET" action="{{ route('admin.laporan') }}" class="flex items-end gap-2">
                    <div>
                        <label class="text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase block mb-1">Dari</label>
                        <input type="date" name="dari" value="{{ $dari }}"
                            class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-700 dark:text-white focus:outline-none focus:border-indigo-400 dark:focus:border-purple-500">
                    </div>
                    <div>
                        <label class="text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase block mb-1">Sampai</label>
                        <input type="date" name="sampai" value="{{ $sampai }}"
                            class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-700 dark:text-white focus:outline-none focus:border-indigo-400 dark:focus:border-purple-500">
                    </div>
                    <button type="submit"
                        class="bg-indigo-600 dark:bg-purple-600 hover:bg-indigo-700 dark:hover:bg-purple-700 text-white px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-widest transition-all h-[38px]">
                        Terapkan
                    </button>
                </form>

                <a href="{{ route('admin.laporan.pdf', ['dari' => $dari, 'sampai' => $sampai]) }}"
                   class="flex items-center gap-2 bg-rose-50 dark:bg-red-600/20 hover:bg-rose-100 dark:hover:bg-red-600/40 border border-rose-200 dark:border-red-500/30 text-rose-500 dark:text-red-400 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-widest transition-all h-[38px]">
                    <i data-lucide="file-down" class="w-4 h-4"></i>
                    Export PDF
                </a>
            </div>
        </div>

        {{-- ── Stat Cards ── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-[#1e293b] rounded-2xl p-4 border border-slate-100 dark:border-slate-800 shadow-sm dark:shadow-none">
                <p class="text-slate-400 dark:text-slate-500 text-[10px] uppercase tracking-widest font-bold mb-2">Total Cash</p>
                <p class="font-black text-xl text-emerald-600 dark:text-emerald-400">Rp {{ number_format($totalCash,0,',','.') }}</p>
            </div>
            <div class="bg-white dark:bg-[#1e293b] rounded-2xl p-4 border border-slate-100 dark:border-slate-800 shadow-sm dark:shadow-none">
                <p class="text-slate-400 dark:text-slate-500 text-[10px] uppercase tracking-widest font-bold mb-2">Total Hutang</p>
                <p class="font-black text-xl text-blue-600 dark:text-blue-400">Rp {{ number_format($totalHutang,0,',','.') }}</p>
            </div>
            <div class="bg-white dark:bg-[#1e293b] rounded-2xl p-4 border border-slate-100 dark:border-slate-800 shadow-sm dark:shadow-none">
                <p class="text-slate-400 dark:text-slate-500 text-[10px] uppercase tracking-widest font-bold mb-2">Belum Lunas</p>
                <p class="font-black text-xl text-red-500 dark:text-red-400">Rp {{ number_format($hutangBelumLunas,0,',','.') }}</p>
            </div>
            <div class="bg-white dark:bg-[#1e293b] rounded-2xl p-4 border border-slate-100 dark:border-slate-800 shadow-sm dark:shadow-none">
                <p class="text-slate-400 dark:text-slate-500 text-[10px] uppercase tracking-widest font-bold mb-2">Pendapatan Bersih</p>
                <p class="font-black text-xl {{ $pendapatanBersih >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500 dark:text-red-400' }}">
                    Rp {{ number_format($pendapatanBersih,0,',','.') }}
                </p>
            </div>
        </div>

        {{-- ── Transaksi Harian ── --}}
        <div class="bg-white dark:bg-[#1e293b] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm dark:shadow-none mb-6 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-3">
                <span class="bg-indigo-50 dark:bg-purple-600/20 text-indigo-600 dark:text-purple-400 p-2 rounded-xl text-lg">📋</span>
                <div>
                    <h3 class="text-slate-800 dark:text-white font-bold text-sm">Transaksi Harian</h3>
                    <p class="text-slate-400 dark:text-slate-500 text-[10px]">Per tanggal dalam periode</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-900/50">
                            <th class="text-left px-5 py-3 text-slate-400 text-[10px] uppercase tracking-widest font-bold">Tanggal</th>
                            <th class="text-center px-5 py-3 text-slate-400 text-[10px] uppercase tracking-widest font-bold">Transaksi</th>
                            <th class="text-center px-5 py-3 text-slate-400 text-[10px] uppercase tracking-widest font-bold">Item</th>
                            <th class="text-right px-5 py-3 text-slate-400 text-[10px] uppercase tracking-widest font-bold">Cash (Rp)</th>
                            <th class="text-right px-5 py-3 text-slate-400 text-[10px] uppercase tracking-widest font-bold">Hutang (Rp)</th>
                            <th class="text-right px-5 py-3 text-slate-400 text-[10px] uppercase tracking-widest font-bold">Total (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($transaksiHarian as $t)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-5 py-3 text-slate-800 dark:text-white font-semibold">{{ $t->tanggal }}</td>
                            <td class="px-5 py-3 text-center text-slate-500 dark:text-slate-300">{{ $t->total_transaksi }}</td>
                            <td class="px-5 py-3 text-center text-slate-500 dark:text-slate-300">{{ $t->total_item }}</td>
                            <td class="px-5 py-3 text-right text-emerald-600 dark:text-emerald-400 font-semibold">{{ number_format($t->cash,0,',','.') }}</td>
                            <td class="px-5 py-3 text-right font-semibold {{ $t->hutang > 0 ? 'text-red-500 dark:text-red-400' : 'text-slate-300 dark:text-slate-600' }}">
                                {{ number_format($t->hutang,0,',','.') }}
                            </td>
                            <td class="px-5 py-3 text-right text-slate-800 dark:text-white font-bold">{{ number_format($t->total,0,',','.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-300 dark:text-slate-600 italic">Tidak ada data pada periode ini</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($transaksiHarian->count() > 0)
                    <tfoot class="border-t border-slate-200 dark:border-slate-700">
                        <tr class="bg-slate-50 dark:bg-slate-900/50">
                            <td class="px-5 py-3 text-slate-800 dark:text-white font-black text-xs uppercase" colspan="3">Total</td>
                            <td class="px-5 py-3 text-right text-emerald-600 dark:text-emerald-400 font-black">{{ number_format($transaksiHarian->sum('cash'),0,',','.') }}</td>
                            <td class="px-5 py-3 text-right text-red-500 dark:text-red-400 font-black">{{ number_format($transaksiHarian->sum('hutang'),0,',','.') }}</td>
                            <td class="px-5 py-3 text-right text-slate-800 dark:text-white font-black">{{ number_format($transaksiHarian->sum('total'),0,',','.') }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>

        {{-- ── Grid: Produk Terlaris + Rekap Hutang ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            {{-- Produk Terlaris --}}
            <div class="bg-white dark:bg-[#1e293b] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm dark:shadow-none overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-3">
                    <span class="bg-amber-50 dark:bg-amber-500/20 text-amber-500 dark:text-amber-400 p-2 rounded-xl text-lg">🏆</span>
                    <div>
                        <h3 class="text-slate-800 dark:text-white font-bold text-sm">Produk Terlaris</h3>
                        <p class="text-slate-400 dark:text-slate-500 text-[10px]">Top 10 berdasarkan qty</p>
                    </div>
                </div>
                <div class="p-4 space-y-3">
                    @php $maxQty = $produkTerlaris->max('total_terjual') ?: 1; @endphp
                    @forelse($produkTerlaris as $i => $p)
                    <div class="flex items-center gap-3">
                        <span class="text-slate-300 dark:text-slate-600 text-xs font-bold w-5 text-center">{{ $i+1 }}</span>
                        <div class="flex-1">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-slate-800 dark:text-white text-xs font-semibold">{{ ucwords(strtolower($p->name_merk)) }}</span>
                                <span class="text-slate-400 text-xs">{{ $p->total_terjual }} pcs</span>
                            </div>
                            <div class="bg-slate-100 dark:bg-slate-800 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full"
                                     style="width:{{ ($p->total_terjual / $maxQty) * 100 }}%; background:linear-gradient(to right,#6366f1,#2563eb)">
                                </div>
                            </div>
                        </div>
                        <span class="text-emerald-600 dark:text-emerald-400 text-xs font-bold w-24 text-right">
                            Rp {{ number_format($p->total_pendapatan,0,',','.') }}
                        </span>
                    </div>
                    @empty
                    <p class="text-center text-slate-300 dark:text-slate-600 italic text-sm py-4">Tidak ada data</p>
                    @endforelse
                </div>
            </div>

            {{-- Rekap Hutang --}}
            <div class="bg-white dark:bg-[#1e293b] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm dark:shadow-none overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-3">
                    <span class="bg-red-50 dark:bg-red-600/20 text-red-500 dark:text-red-400 p-2 rounded-xl text-lg">📝</span>
                    <div>
                        <h3 class="text-slate-800 dark:text-white font-bold text-sm">Rekap Hutang</h3>
                        <p class="text-slate-400 dark:text-slate-500 text-[10px]">Status per anggota</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/50">
                                <th class="text-left px-4 py-3 text-slate-400 text-[10px] uppercase font-bold">Nama</th>
                                <th class="text-right px-4 py-3 text-slate-400 text-[10px] uppercase font-bold">Belum Lunas</th>
                                <th class="text-right px-4 py-3 text-slate-400 text-[10px] uppercase font-bold">Lunas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($rekapHutang as $h)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-4 py-3 text-slate-800 dark:text-white font-semibold text-xs">{{ $h->nama_pembeli }}</td>
                                <td class="px-4 py-3 text-right text-xs font-bold {{ $h->belum_lunas > 0 ? 'text-red-500 dark:text-red-400' : 'text-slate-300 dark:text-slate-600' }}">
                                    Rp {{ number_format($h->belum_lunas,0,',','.') }}
                                </td>
                                <td class="px-4 py-3 text-right text-xs {{ $h->sudah_lunas > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-300 dark:text-slate-600' }}">
                                    Rp {{ number_format($h->sudah_lunas,0,',','.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-slate-300 dark:text-slate-600 italic">Tidak ada data hutang</td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($rekapHutang->count() > 0)
                        <tfoot class="border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                            <tr>
                                <td class="px-4 py-3 text-slate-800 dark:text-white font-black text-xs uppercase">Total</td>
                                <td class="px-4 py-3 text-right text-red-500 dark:text-red-400 font-black text-xs">Rp {{ number_format($rekapHutang->sum('belum_lunas'),0,',','.') }}</td>
                                <td class="px-4 py-3 text-right text-emerald-600 dark:text-emerald-400 font-black text-xs">Rp {{ number_format($rekapHutang->sum('sudah_lunas'),0,',','.') }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        {{-- ── Pendapatan Bersih ── --}}
        <div class="bg-white dark:bg-[#1e293b] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm dark:shadow-none overflow-hidden mb-6">
            <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-3">
                <span class="bg-emerald-50 dark:bg-emerald-600/20 text-emerald-600 dark:text-emerald-400 p-2 rounded-xl text-lg">💰</span>
                <div>
                    <h3 class="text-slate-800 dark:text-white font-bold text-sm">Pendapatan Bersih</h3>
                    <p class="text-slate-400 dark:text-slate-500 text-[10px]">Kalkulasi akhir periode</p>
                </div>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 divide-x divide-slate-100 dark:divide-slate-800">
                <div class="p-5">
                    <p class="text-slate-400 dark:text-slate-500 text-[10px] uppercase tracking-widest font-bold mb-1">Total Cash Masuk</p>
                    <p class="font-black text-xl text-emerald-600 dark:text-emerald-400 mb-1">Rp {{ number_format($totalCash,0,',','.') }}</p>
                    <p class="text-slate-400 dark:text-slate-600 text-[10px] italic">Pembayaran tunai diterima</p>
                </div>
                <div class="p-5">
                    <p class="text-slate-400 dark:text-slate-500 text-[10px] uppercase tracking-widest font-bold mb-1">Total Hutang</p>
                    <p class="font-black text-xl text-blue-600 dark:text-blue-400 mb-1">Rp {{ number_format($totalHutang,0,',','.') }}</p>
                    <p class="text-slate-400 dark:text-slate-600 text-[10px] italic">Kasbon seluruh periode</p>
                </div>
                <div class="p-5">
                    <p class="text-slate-400 dark:text-slate-500 text-[10px] uppercase tracking-widest font-bold mb-1">Hutang Belum Lunas</p>
                    <p class="font-black text-xl text-red-500 dark:text-red-400 mb-1">Rp {{ number_format($hutangBelumLunas,0,',','.') }}</p>
                    <p class="text-slate-400 dark:text-slate-600 text-[10px] italic">Masih perlu ditagih</p>
                </div>
                <div class="p-5">
                    <p class="text-slate-400 dark:text-slate-500 text-[10px] uppercase tracking-widest font-bold mb-1">Pendapatan Bersih</p>
                    <p class="font-black text-xl mb-1 {{ $pendapatanBersih >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500 dark:text-red-400' }}">
                        Rp {{ number_format($pendapatanBersih,0,',','.') }}
                    </p>
                    <p class="text-slate-400 dark:text-slate-600 text-[10px] italic">Cash - hutang belum lunas</p>
                </div>
            </div>
        </div>

        {{-- ── Rekap Pendapatan Manual (Buku Kas Manual) ── --}}
        <div class="bg-white dark:bg-[#1e293b] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm dark:shadow-none overflow-hidden mb-6">
            <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-3">
                <div>
                    <h3 class="text-slate-800 dark:text-white font-bold text-sm">Rekap Pendapatan Manual</h3>
                    <p class="text-slate-400 dark:text-slate-500 text-[10px]">Dari Buku Kas Manual, tidak termasuk dalam Pendapatan Bersih di atas</p>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 divide-y lg:divide-y-0 lg:divide-x divide-slate-100 dark:divide-slate-800">
                <div class="p-5">
                    <p class="text-slate-400 dark:text-slate-500 text-[10px] uppercase tracking-widest font-bold mb-1">Total Debit</p>
                    <p class="font-black text-xl text-emerald-600 dark:text-emerald-400 mb-1">Rp {{ number_format($totalDebitManual,0,',','.') }}</p>
                    <p class="text-slate-400 dark:text-slate-600 text-[10px] italic">Pemasukan kas manual</p>
                </div>
                <div class="p-5">
                    <p class="text-slate-400 dark:text-slate-500 text-[10px] uppercase tracking-widest font-bold mb-1">Total Kredit</p>
                    <p class="font-black text-xl text-red-500 dark:text-red-400 mb-1">Rp {{ number_format($totalKreditManual,0,',','.') }}</p>
                    <p class="text-slate-400 dark:text-slate-600 text-[10px] italic">Pengeluaran kas manual</p>
                </div>
                <div class="p-5">
                    <p class="text-slate-400 dark:text-slate-500 text-[10px] uppercase tracking-widest font-bold mb-1">Saldo Bersih</p>
                    <p class="font-black text-xl mb-1 {{ $saldoBersihManual >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500 dark:text-red-400' }}">
                        Rp {{ number_format($saldoBersihManual,0,',','.') }}
                    </p>
                    <p class="text-slate-400 dark:text-slate-600 text-[10px] italic">Debit - kredit kas manual</p>
                </div>
            </div>
        </div>

    </main>

    <script>
        lucide.createIcons();

        function toggleTheme() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        }
    </script>
</body>
</html>