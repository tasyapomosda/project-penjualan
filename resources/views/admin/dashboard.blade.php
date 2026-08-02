<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Showcase</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        html { scrollbar-gutter: stable; }
        body { font-family: 'Quicksand', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #f1f5f9; border-radius: 10px; }
        .glass-clock { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(8px); border: 1px solid #f1f5f9; }
    </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden text-slate-900 font-medium">

    @include('admin.partials.sidebar')

    <main class="flex-1 ml-72 flex flex-col p-6 md:p-10 min-w-0 overflow-hidden">
        
        <header class="flex justify-between items-center mb-10 flex-shrink-0">
            <div class="glass-clock px-8 py-4 rounded-[2.5rem] shadow-sm flex items-center gap-5 border border-white transition-all hover:shadow-md">
                <div class="bg-purple-100 text-purple-600 p-2.5 rounded-xl">
                    <i data-lucide="sparkles" class="w-5 h-5"></i>
                </div>
                <div class="flex items-center gap-4">
                    <h3 class="text-base font-bold text-slate-700 tracking-tight" id="txtDayDate">Memuat...</h3>
                    <span class="text-slate-200 font-light text-xl">|</span>
                    <p class="text-lg font-extrabold text-purple-600 tabular-nums tracking-wide" id="txtTime">
                        --:--:-- WIB
                    </p>
                </div>
            </div>

            <div class="text-right">
                <h2 class="text-3xl font-extrabold text-slate-800 leading-tight tracking-tighter">Ringkasan Utama 📊</h2>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-[0.3em] italic">Cooperative Management</p>
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5 group hover:border-amber-200 transition-all">
                <div class="bg-amber-50 text-amber-500 p-4 rounded-2xl group-hover:scale-110 transition-transform">
                    <i data-lucide="star" class="w-6 h-6"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Terlaris</p>
                    <h3 class="text-base font-extrabold text-slate-800 truncate">{{ $topProduct->product->name_merk ?? 'Belum ada' }}</h3>
                </div>
            </div>
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5 group hover:border-blue-200 transition-all">
                <div class="bg-blue-50 text-blue-500 p-4 rounded-2xl group-hover:scale-110 transition-transform">
                    <i data-lucide="smile" class="w-6 h-6"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Top Buyer</p>
                    <h3 class="text-base font-extrabold text-slate-800 truncate">{{ $topBuyer->nama_pembeli ?? 'Belum ada' }}</h3>
                </div>
            </div>
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5 group hover:border-emerald-200 transition-all">
                <div class="bg-emerald-50 text-emerald-500 p-4 rounded-2xl group-hover:scale-110 transition-transform">
                    <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Item Terjual</p>
                    <h3 class="text-base font-extrabold text-slate-800">{{ $total_jajan }} <span class="text-xs text-slate-400">Pcs</span></h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm flex-1 flex flex-col overflow-hidden">
            <div class="px-10 py-5 border-b border-slate-50 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse shadow-[0_0_8px_rgba(52,211,153,0.6)]"></div>
                    <h4 class="font-bold text-slate-700 text-sm tracking-tight italic uppercase">Aktivitas Terakhir</h4>
                </div>
                <a href="{{ route('admin.harian') }}" class="bg-purple-50 text-purple-600 px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-purple-600 hover:text-white transition-all">Semua →</a>
            </div>

            <div class="overflow-y-auto flex-1 custom-scrollbar px-4">
                <table class="w-full text-left border-collapse table-fixed">
                    <thead class="sticky top-0 bg-white z-10 shadow-[0_1px_0_0_rgba(0,0,0,0.02)]">
                        <tr class="text-slate-300 font-bold text-[9px] uppercase tracking-[0.2em]">
                            {{-- FIX: kolom waktu diperlebar untuk menampung tanggal + jam --}}
                            <th class="px-6 py-5 w-36 text-center">Waktu</th>
                            <th class="px-6 py-5">Pembeli</th>
                            <th class="px-6 py-5">Produk</th>
                            <th class="px-6 py-5 text-center w-20">Qty</th>
                            <th class="px-6 py-5 text-right w-32">Total</th>
                            <th class="px-10 py-5 text-center w-32">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-600 divide-y divide-slate-50">
                        @forelse($transactions as $t)
                        <tr class="hover:bg-slate-50/50 transition-all">

                            {{-- FIX: tampilkan tanggal singkat + jam --}}
                            <td class="px-6 py-4 text-center">
                                <p class="font-bold text-slate-500 text-[10px] tabular-nums">
                                    {{ $t->created_at->translatedFormat('d M') }}
                                </p>
                                <p class="font-bold text-slate-400 text-[10px] tabular-nums">
                                    {{ $t->created_at->format('H:i') }}
                                </p>
                            </td>

                            <td class="px-6 py-4 font-bold text-slate-700 text-sm truncate">{{ $t->nama_pembeli }}</td>

                            {{-- FIX: tambah badge QRIS & Kasbon di sebelah nama produk --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="font-semibold text-slate-400 italic text-xs truncate">
                                        {{ $t->product->name_merk ?? $t->nama_produk_manual ?? '-' }}
                                    </span>
                                    @if($t->metode_bayar === 'qris')
                                        <span class="bg-violet-50 text-violet-500 border border-violet-100 px-1.5 py-0.5 rounded-md text-[8px] font-black uppercase tracking-widest flex-shrink-0">
                                            QRIS
                                        </span>
                                    @elseif($t->metode_bayar === 'hutang')
                                        <span class="bg-rose-50 text-rose-400 border border-rose-100 px-1.5 py-0.5 rounded-md text-[8px] font-black uppercase tracking-widest flex-shrink-0">
                                            Kasbon
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-lg font-bold text-[11px]">{{ $t->jumlah }}</span>
                            </td>
                            <td class="px-6 py-4 font-extrabold text-slate-800 text-right text-sm tabular-nums">
                                Rp{{ number_format($t->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="px-10 py-4 text-center">
                                @if($t->metode_bayar === 'hutang')
                                    <span class="bg-red-50 text-red-500 px-3 py-1 rounded-full font-black text-[8px] uppercase tracking-tighter">HUTANG</span>
                                @elseif($t->metode_bayar === 'qris')
                                    <span class="bg-violet-50 text-violet-500 px-3 py-1 rounded-full font-black text-[8px] uppercase tracking-tighter">QRIS</span>
                                @else
                                    <span class="bg-emerald-50 text-emerald-500 px-3 py-1 rounded-full font-black text-[8px] uppercase tracking-tighter">LUNAS</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-24 text-center">
                                <div class="flex flex-col items-center opacity-20">
                                    <i data-lucide="inbox" class="w-12 h-12 mb-3"></i>
                                    <p class="italic text-xs font-bold uppercase tracking-widest">Belum ada jajan yang laku.</p>
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
        function updateClock() {
            const now = new Date();
            const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
            const dayDate = now.toLocaleDateString('id-ID', options);
            const time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }) + ' WIB';
            document.getElementById('txtDayDate').innerText = dayDate;
            document.getElementById('txtTime').innerText = time;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>