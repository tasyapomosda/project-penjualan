<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - Showcase Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        /* Judul halaman memakai font yang sama dengan header halaman admin lain */
        .font-header { font-family: 'Quicksand', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-700 h-screen flex overflow-hidden">

    @include('admin.partials.sidebar')

    @php
        // Penanda pelunasan yang ditulis sistem pada nama pembeli / produk.
        // Hanya dipakai untuk TAMPILAN, data di database tidak diubah.
        $penandaPelunasan = '(Pelunasan Hutang)';
        $bersihkan = fn ($teks) => trim(str_replace($penandaPelunasan, '', (string) $teks));
    @endphp

    <main id="mainContent" class="flex-1 ml-72 flex flex-col p-3.5 sm:p-6 md:p-10 overflow-hidden relative">

        {{-- ===== HEADER ===== --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 mb-4 sm:mb-6">
            <div>
                <h1 class="font-header text-2xl font-extrabold text-slate-800 leading-tight tracking-tight">
                    Riwayat Transaksi
                </h1>
                <p class="font-header text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-1">Data Jualan Harian</p>
            </div>

            {{-- Filter Tanggal --}}
            <form action="{{ route('admin.harian') }}" method="GET"
                  class="flex items-center gap-2 bg-white px-3 py-2 rounded-2xl border border-slate-100 shadow-sm w-full sm:w-auto justify-between sm:justify-start">
                <div class="flex items-center gap-2">
                    <i data-lucide="filter" class="w-4 h-4 text-slate-400 ml-1"></i>
                    <span class="text-xs text-slate-400 font-semibold sm:hidden">Pilih Tanggal:</span>
                </div>
                <input type="date" name="tanggal" value="{{ $tanggal }}"
                    onchange="this.form.submit()"
                    class="bg-transparent border-none text-xs sm:text-sm font-bold text-slate-700 focus:ring-0 cursor-pointer">
            </form>
        </div>

        {{-- ===== SUMMARY CARDS ===== --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-4 sm:mb-6">

            {{-- Cash Masuk --}}
            <div class="bg-white p-3.5 sm:p-4 rounded-2xl sm:rounded-[1.75rem] border border-slate-100 shadow-sm flex items-center gap-3.5 sm:gap-4 group hover:shadow-md transition-all">
                <div class="bg-emerald-50 text-emerald-500 p-2.5 sm:p-3 rounded-xl sm:rounded-[1.1rem] group-hover:scale-110 transition-transform flex-shrink-0">
                    <i data-lucide="wallet" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                </div>
                <div>
                    <p class="text-slate-400 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest mb-0.5 sm:mb-1">Cash Masuk</p>
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900">
                        Rp{{ number_format($total_cash, 0, ',', '.') }}
                    </h3>
                </div>
            </div>

            {{-- QRIS Masuk --}}
            <div class="bg-white p-3.5 sm:p-4 rounded-2xl sm:rounded-[1.75rem] border border-slate-100 shadow-sm flex items-center gap-3.5 sm:gap-4 group hover:shadow-md transition-all">
                <div class="bg-violet-50 text-violet-500 p-2.5 sm:p-3 rounded-xl sm:rounded-[1.1rem] group-hover:scale-110 transition-transform flex-shrink-0">
                    <i data-lucide="qr-code" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                </div>
                <div>
                    <p class="text-slate-400 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest mb-0.5 sm:mb-1">QRIS Masuk</p>
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900">
                        Rp{{ number_format($total_qris, 0, ',', '.') }}
                    </h3>
                </div>
            </div>

            {{-- Total Pendapatan --}}
            <div class="bg-white p-3.5 sm:p-4 rounded-2xl sm:rounded-[1.75rem] border border-slate-100 shadow-sm flex items-center gap-3.5 sm:gap-4 group hover:shadow-md transition-all">
                <div class="bg-sky-50 text-sky-500 p-2.5 sm:p-3 rounded-xl sm:rounded-[1.1rem] group-hover:scale-110 transition-transform flex-shrink-0">
                    <i data-lucide="trending-up" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                </div>
                <div>
                    <p class="text-slate-400 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest mb-0.5 sm:mb-1">Total Pendapatan</p>
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900">
                        Rp{{ number_format($total_pendapatan_harian, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>

        {{-- ===== DAFTAR TRANSAKSI ===== --}}
        <div class="bg-white rounded-2xl sm:rounded-[2.5rem] border border-slate-100 shadow-sm flex-1 flex flex-col overflow-hidden min-h-[300px]">

            {{-- ───────── MOBILE: daftar kartu ───────── --}}
            <div class="sm:hidden overflow-y-auto flex-1 custom-scrollbar divide-y divide-slate-50">
                @forelse($transactions as $t)
                @php
                    $isPelunasan = str_contains((string) $t->nama_pembeli, $penandaPelunasan);
                    $nama        = $bersihkan($t->nama_pembeli);
                    $produk      = $bersihkan($t->nama_produk_manual ?? optional($t->product)->name_merk ?? 'Jajan Manual');
                @endphp
                <div class="px-4 py-3.5 flex items-start gap-3">

                    {{-- Kiri: pembeli, produk, waktu --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="font-bold text-slate-800 text-sm break-words">{{ $nama }}</span>
                            @if($isPelunasan)
                                <span class="bg-amber-50 text-amber-600 border border-amber-100 px-1.5 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-widest whitespace-nowrap">
                                    Pelunasan Hutang
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 font-semibold mt-0.5 break-words">
                            {{ $produk }}
                            <span class="text-slate-400 font-bold">× {{ $t->jumlah }}</span>
                        </p>
                        <div class="flex items-center gap-2 mt-1.5">
                            <span class="text-[10px] text-slate-400 font-bold tabular-nums">{{ $t->created_at->format('H:i') }}</span>
                            @if($t->metode_bayar === 'qris')
                                <span class="bg-violet-50 text-violet-500 border border-violet-100 px-1.5 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-widest">QRIS</span>
                            @endif
                        </div>
                    </div>

                    {{-- Kanan: total, status, hapus --}}
                    <div class="flex flex-col items-end gap-1.5 flex-shrink-0">
                        <span class="font-black text-slate-900 text-sm tabular-nums">
                            Rp{{ number_format($t->total_harga, 0, ',', '.') }}
                        </span>
                        @if($t->metode_bayar === 'hutang')
                            <span class="bg-rose-50 text-rose-500 px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border border-rose-100">Kasbon</span>
                        @else
                            <span class="bg-emerald-50 text-emerald-500 px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border border-emerald-100">Lunas</span>
                        @endif
                        <form action="{{ route('admin.delete-transaksi', $t->id) }}" method="POST"
                              onsubmit="return confirm('Hapus transaksi ini? Stok akan dikembalikan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="p-1.5 text-slate-300 hover:text-rose-500 transition-all active:scale-95"
                                    title="Hapus transaksi">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="py-20 text-center">
                    <div class="flex flex-col items-center opacity-20">
                        <i data-lucide="database" class="w-12 h-12 mb-3"></i>
                        <p class="font-black uppercase tracking-[0.25em] text-xs">Tidak Ada Transaksi</p>
                    </div>
                </div>
                @endforelse
            </div>

            {{-- ───────── DESKTOP / TABLET: tabel ───────── --}}
            <div class="hidden sm:block overflow-y-auto overflow-x-auto flex-1 custom-scrollbar">
                <table class="w-full text-left border-collapse table-fixed min-w-[750px]">
                    <thead class="sticky top-0 bg-white/90 backdrop-blur-md z-10">
                        <tr class="text-slate-800 font-bold text-xs uppercase tracking-[0.15em] border-b border-slate-50">
                            <th class="px-8 py-4 w-32">Waktu</th>
                            <th class="px-8 py-4">Pembeli</th>
                            <th class="px-8 py-4">Produk</th>
                            <th class="px-6 py-4 text-center w-28">Qty</th>
                            <th class="px-8 py-4 text-right w-48">Total</th>
                            <th class="px-6 py-4 text-center w-36">Status</th>
                            <th class="px-6 py-4 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @forelse($transactions as $t)
                        @php
                            $isPelunasan = str_contains((string) $t->nama_pembeli, $penandaPelunasan);
                            $nama        = $bersihkan($t->nama_pembeli);
                            $produk      = $bersihkan($t->nama_produk_manual ?? optional($t->product)->name_merk ?? 'Jajan Manual');
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-all group">

                            {{-- Waktu --}}
                            <td class="px-8 py-4 font-medium text-slate-400 tabular-nums italic text-xs">
                                {{ $t->created_at->format('H:i') }}
                            </td>

                            {{-- Nama Pembeli (+ badge pelunasan) --}}
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-bold text-slate-800 text-base break-words">{{ $nama }}</span>
                                    @if($isPelunasan)
                                        <span class="bg-amber-50 text-amber-600 border border-amber-100 px-2 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-widest whitespace-nowrap">
                                            Pelunasan Hutang
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Produk + Badge Metode --}}
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-semibold text-slate-500 italic text-sm break-words">{{ $produk }}</span>
                                    @if($t->metode_bayar === 'qris')
                                        <span class="bg-violet-50 text-violet-500 border border-violet-100 px-2 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-widest">
                                            QRIS
                                        </span>
                                    @elseif($t->metode_bayar === 'hutang')
                                        <span class="bg-rose-50 text-rose-400 border border-rose-100 px-2 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-widest">
                                            Kasbon
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Qty --}}
                            <td class="px-6 py-4 text-center">
                                <span class="bg-slate-100 px-3 py-1 rounded-xl font-bold text-slate-600 text-xs">
                                    {{ $t->jumlah }}
                                </span>
                            </td>

                            {{-- Total Harga --}}
                            <td class="px-8 py-4 font-black text-slate-900 text-right text-base tabular-nums">
                                Rp{{ number_format($t->total_harga, 0, ',', '.') }}
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4 text-center">
                                @if($t->metode_bayar === 'hutang')
                                    <span class="bg-rose-50 text-rose-500 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-rose-100">
                                        Kasbon
                                    </span>
                                @else
                                    <span class="bg-emerald-50 text-emerald-500 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-emerald-100">
                                        Lunas
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi Hapus --}}
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('admin.delete-transaksi', $t->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus transaksi ini? Stok akan dikembalikan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 text-slate-300 hover:text-rose-500 transition-all transform hover:scale-110 active:scale-95"
                                            title="Hapus transaksi">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-32 text-center">
                                <div class="flex flex-col items-center opacity-20">
                                    <i data-lucide="database" class="w-16 h-16 mb-4"></i>
                                    <p class="font-black uppercase tracking-[0.3em] text-sm">
                                        Tidak Ada Transaksi Hari Ini
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>