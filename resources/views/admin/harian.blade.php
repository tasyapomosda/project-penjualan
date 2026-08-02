<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - Showcase Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-700 h-screen flex overflow-hidden">

    @include('admin.partials.sidebar')

    <main class="flex-1 ml-72 flex flex-col p-12 overflow-hidden relative">
        
        <div class="flex justify-between items-start mb-10">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                    Riwayat Transaksi 🗓️
                </h1>
                <p class="text-slate-400 font-bold text-xs uppercase tracking-[0.2em] mt-2">Data Jualan Harian</p>
            </div>

            <form action="{{ route('admin.harian') }}" method="GET" class="flex items-center gap-3 bg-white p-2 rounded-2xl border border-slate-100 shadow-sm">
                <i data-lucide="filter" class="w-4 h-4 text-slate-400 ml-2"></i>
                <input type="date" name="tanggal" value="{{ $tanggal }}" 
                    onchange="this.form.submit()"
                    class="bg-transparent border-none text-sm font-bold text-slate-700 focus:ring-0 cursor-pointer uppercase">
            </form>
        </div>

        <div class="grid grid-cols-3 gap-6 mb-10">
            {{-- Cash Masuk --}}
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center gap-6 group hover:shadow-md transition-all">
                <div class="bg-emerald-50 text-emerald-500 p-5 rounded-[1.5rem] group-hover:scale-110 transition-transform">
                    <i data-lucide="wallet" class="w-8 h-8"></i>
                </div>
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Cash Masuk</p>
                    <h3 class="text-2xl font-black text-slate-900">Rp{{ number_format($total_cash, 0, ',', '.') }}</h3>
                </div>
            </div>

            {{-- QRIS Masuk --}}
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center gap-6 group hover:shadow-md transition-all">
                <div class="bg-violet-50 text-violet-500 p-5 rounded-[1.5rem] group-hover:scale-110 transition-transform">
                    <i data-lucide="qr-code" class="w-8 h-8"></i>
                </div>
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">QRIS Masuk</p>
                    <h3 class="text-2xl font-black text-slate-900">Rp{{ number_format($total_qris, 0, ',', '.') }}</h3>
                </div>
            </div>

            {{-- Total Pendapatan --}}
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center gap-6 group hover:shadow-md transition-all text-left">
                <div class="bg-sky-50 text-sky-500 p-5 rounded-[1.5rem] group-hover:scale-110 transition-transform">
                    <i data-lucide="trending-up" class="w-8 h-8"></i>
                </div>
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Total Pendapatan</p>
                    <h3 class="text-2xl font-black text-slate-900">Rp{{ number_format($total_pendapatan_harian, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm flex-1 flex flex-col overflow-hidden">
            <div class="overflow-y-auto flex-1 custom-scrollbar">
                <table class="w-full text-left border-collapse table-fixed">
                    <thead class="sticky top-0 bg-white/90 backdrop-blur-md z-10">
                        <tr class="text-slate-400 font-bold text-xs uppercase tracking-[0.15em] border-b border-slate-50">
                            <th class="px-10 py-7 w-32">Waktu</th>
                            <th class="px-10 py-7">Pembeli</th>
                            <th class="px-10 py-7">Produk</th>
                            <th class="px-10 py-7 text-center w-28">Qty</th>
                            <th class="px-10 py-7 text-right w-48">Total</th>
                            <th class="px-10 py-7 text-center w-36">Status</th>
                            <th class="px-10 py-7 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($transactions as $t)
                        <tr class="hover:bg-slate-50/50 transition-all text-sm group">
                            <td class="px-10 py-6 font-medium text-slate-400 tabular-nums italic">
                                {{ $t->created_at->format('H:i') }}
                            </td>
                            <td class="px-10 py-6">
                                <span class="font-bold text-slate-800 text-base">{{ $t->nama_pembeli }}</span>
                            </td>
                            <td class="px-10 py-6">
                                <div class="flex items-center gap-2 flex-wrap">
                                    {{-- ⚠️ Fallback: cek nama_produk_manual dulu, baru product relation --}}
                                    <span class="font-semibold text-slate-500 italic">
                                        {{ $t->nama_produk_manual ?? $t->product->name_merk ?? 'Jajan Manual' }}
                                    </span>
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
                            <td class="px-10 py-6 text-center">
                                <span class="bg-slate-100 px-3 py-1 rounded-xl font-bold text-slate-600 text-xs">{{ $t->jumlah }}</span>
                            </td>
                            <td class="px-10 py-6 font-black text-slate-900 text-right text-base tabular-nums">
                                Rp{{ number_format($t->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="px-10 py-6 text-center">
                                <span class="bg-emerald-50 text-emerald-500 px-5 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-emerald-100">
                                    BERHASIL
                                </span>
                            </td>
                            <td class="px-10 py-6 text-center">
                                <form action="{{ route('admin.delete-transaksi', $t->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-300 hover:text-rose-500 transition-all transform hover:scale-110">
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
                                    <p class="font-black uppercase tracking-[0.3em] text-sm">Tidak Ada Transaksi Hari Ini</p>
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