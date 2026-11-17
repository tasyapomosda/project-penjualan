<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Pendapatan - Admin Showcase</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        html { scrollbar-gutter: stable; }
        body { font-family: 'Quicksand', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #f1f5f9; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden text-slate-900 font-medium">
 
    @include('admin.partials.sidebar')
 
    <main id="mainContent" class="flex-1 ml-72 flex flex-col p-3.5 sm:p-6 md:p-10 min-w-0 overflow-hidden">
        
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 mb-3 sm:mb-4 flex-shrink-0">
            <div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-800 leading-tight tracking-tight">Rekap Pendapatan 💰</h2>
                <p class="text-[10px] sm:text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Pencatatan arus kas masuk (debit), keluar (kredit) & saldo</p>
            </div>
            <button onclick="openModal()" class="bg-[#6366f1] hover:bg-indigo-700 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl shadow-md shadow-indigo-100 flex items-center justify-center gap-2 transition-all active:scale-95 text-xs sm:text-sm font-bold w-full sm:w-auto">
                <i data-lucide="plus-circle" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                INPUT KAS BARU
            </button>
        </header>
 
        {{-- Summary Cards (Minimalis & Ringkas) --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 sm:gap-4 mb-3 sm:mb-4 flex-shrink-0">
            <div class="bg-white px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3 group transition-all hover:border-emerald-200">
                <div class="bg-emerald-50 text-emerald-600 p-2 sm:p-2.5 rounded-lg sm:rounded-xl shrink-0"><i data-lucide="trending-up" class="w-4 h-4 sm:w-5 sm:h-5"></i></div>
                <div class="min-w-0 flex-1">
                    <p class="text-slate-400 text-[9px] sm:text-[10px] font-bold uppercase tracking-wider mb-0.5">Total Debit (+)</p>
                    <h3 class="text-xs sm:text-sm md:text-base font-extrabold text-emerald-600 truncate tabular-nums">Rp{{ number_format($totalDebit ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="bg-white px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3 group transition-all hover:border-rose-200">
                <div class="bg-rose-50 text-rose-600 p-2 sm:p-2.5 rounded-lg sm:rounded-xl shrink-0"><i data-lucide="trending-down" class="w-4 h-4 sm:w-5 sm:h-5"></i></div>
                <div class="min-w-0 flex-1">
                    <p class="text-slate-400 text-[9px] sm:text-[10px] font-bold uppercase tracking-wider mb-0.5">Total Kredit (-)</p>
                    <h3 class="text-xs sm:text-sm md:text-base font-extrabold text-rose-600 truncate tabular-nums">Rp{{ number_format($totalKredit ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="bg-indigo-600 text-white px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl shadow-md shadow-indigo-100 flex items-center gap-3 group transition-all">
                <div class="bg-white/20 text-white p-2 sm:p-2.5 rounded-lg sm:rounded-xl shrink-0"><i data-lucide="wallet" class="w-4 h-4 sm:w-5 sm:h-5"></i></div>
                <div class="min-w-0 flex-1">
                    <p class="text-indigo-100 text-[9px] sm:text-[10px] font-bold uppercase tracking-wider mb-0.5">Saldo Bersih</p>
                    <h3 class="text-xs sm:text-sm md:text-base font-extrabold text-white truncate tabular-nums">Rp{{ number_format($saldoBersih ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
 
        {{-- Tabel Riwayat Rekap Pendapatan --}}
        <div class="bg-white rounded-2xl sm:rounded-[2.5rem] border border-slate-100 shadow-sm flex-1 flex flex-col overflow-hidden min-h-[300px]">
            <div class="px-4 py-3 sm:px-6 sm:py-3.5 border-b border-slate-100 flex justify-between items-center bg-slate-50/40">
                <div class="flex items-center gap-2.5">
                    <div class="w-2 h-2 bg-indigo-500 rounded-full shadow-[0_0_8px_rgba(99,102,241,0.6)]"></div>
                    <h4 class="font-bold text-slate-700 text-xs sm:text-sm tracking-tight uppercase">Riwayat Mutasi & Rekap Kas</h4>
                </div>
                <span class="text-[10px] sm:text-xs font-bold text-slate-400">Total: {{ $kasManual->total() }} Catatan</span>
            </div>
 
            <div class="overflow-y-auto overflow-x-auto flex-1 custom-scrollbar px-2 sm:px-4">
                <table class="w-full text-left border-collapse min-w-[780px] sm:min-w-[920px]">
                    <thead class="sticky top-0 bg-white z-10 shadow-[0_1px_0_0_rgba(0,0,0,0.02)]">
                        <tr class="text-slate-800 font-bold text-[11px] sm:text-[12px] uppercase tracking-wider border-b border-slate-100">
                            <th class="px-3 sm:px-5 py-3 sm:py-4 w-40 sm:w-48">Hari / Tanggal</th>
                            <th class="px-3 sm:px-5 py-3 sm:py-4">Keterangan</th>
                            <th class="px-3 sm:px-5 py-3 sm:py-4 text-right w-36 sm:w-44 text-emerald-600">Debit (+)</th>
                            <th class="px-3 sm:px-5 py-3 sm:py-4 text-right w-36 sm:w-44 text-rose-600">Kredit (-)</th>
                            <th class="px-3 sm:px-5 py-3 sm:py-4 text-right w-36 sm:w-44 text-indigo-600">Saldo</th>
                            <th class="px-3 sm:px-5 py-3 sm:py-4 text-center w-20 sm:w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-600 divide-y divide-slate-50 text-xs sm:text-sm">
                        @forelse($kasManual as $k)
                        <tr class="hover:bg-slate-50/60 transition-all">
                            <td class="px-3 sm:px-5 py-3 sm:py-3.5 font-bold text-slate-500 tabular-nums text-xs">
                                {{ \Illuminate\Support\Carbon::parse($k->tanggal)->translatedFormat('l, d M Y') }}
                            </td>
                            <td class="px-3 sm:px-5 py-3 sm:py-3.5">
                                <p class="font-bold text-slate-700 text-xs sm:text-sm">{{ $k->keterangan }}</p>
                            </td>
                            {{-- Kolom Debit --}}
                            <td class="px-3 sm:px-5 py-3 sm:py-3.5 text-right tabular-nums">
                                @if($k->tipe === 'debit')
                                    <span class="font-extrabold text-xs sm:text-sm text-emerald-600">
                                        +Rp{{ number_format($k->nominal, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-slate-300 font-semibold text-xs">-</span>
                                @endif
                            </td>
                            {{-- Kolom Kredit --}}
                            <td class="px-3 sm:px-5 py-3 sm:py-3.5 text-right tabular-nums">
                                @if($k->tipe === 'kredit')
                                    <span class="font-extrabold text-xs sm:text-sm text-rose-500">
                                        -Rp{{ number_format($k->nominal, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-slate-300 font-semibold text-xs">-</span>
                                @endif
                            </td>
                            {{-- Kolom Saldo --}}
                            <td class="px-3 sm:px-5 py-3 sm:py-3.5 text-right tabular-nums">
                                <span class="font-extrabold text-xs sm:text-sm {{ ($k->saldo_berjalan ?? 0) >= 0 ? 'text-slate-800' : 'text-rose-600' }}">
                                    Rp{{ number_format($k->saldo_berjalan ?? 0, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-3 sm:px-5 py-3 sm:py-3.5 text-center">
                                <div class="flex items-center justify-center gap-1.5 sm:gap-2">
                                    <button type="button"
                                            class="btn-edit-kas text-slate-400 hover:text-indigo-600 transition-colors p-1"
                                            data-id="{{ $k->id }}"
                                            data-keterangan="{{ $k->keterangan }}"
                                            data-tipe="{{ $k->tipe }}"
                                            data-nominal="{{ $k->nominal }}"
                                            data-tanggal="{{ \Illuminate\Support\Carbon::parse($k->tanggal)->format('Y-m-d') }}">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </button>
                                    <form action="{{ route('admin.kas-delete', $k->id) }}" method="POST" onsubmit="return confirm('Hapus catatan ini?')">
                                        @csrf @method('DELETE')
                                        <button class="text-slate-400 hover:text-rose-500 transition-colors p-1">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-20 text-center">
                                <div class="flex flex-col items-center opacity-30">
                                    <i data-lucide="database" class="w-12 h-12 mb-3 text-slate-400"></i>
                                    <p class="italic text-xs font-bold uppercase tracking-widest text-slate-500">Belum ada catatan kas.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer Navigasi / Paginasi (10-15 baris per halaman) --}}
            @if($kasManual->hasPages() || $kasManual->total() > 0)
            <div class="px-4 py-2.5 sm:px-6 sm:py-3 border-t border-slate-100 bg-white flex flex-col sm:flex-row items-center justify-between gap-2.5 flex-shrink-0">
                <p class="text-[11px] sm:text-xs text-slate-400 font-medium text-center sm:text-left">
                    Menampilkan <span class="font-bold text-slate-700">{{ $kasManual->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-700">{{ $kasManual->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-700">{{ $kasManual->total() }}</span> data
                </p>

                @if($kasManual->hasPages())
                <div class="flex items-center gap-1.5">
                    {{-- Tombol Sebelumnya --}}
                    @if($kasManual->onFirstPage())
                        <span class="px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-xl border border-slate-200 text-slate-300 text-xs font-bold cursor-not-allowed flex items-center gap-1">
                            <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>
                            <span class="hidden sm:inline">Sebelumnya</span>
                        </span>
                    @else
                        <a href="{{ $kasManual->previousPageUrl() }}" class="px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 text-slate-600 text-xs font-bold transition-all flex items-center gap-1 active:scale-95">
                            <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>
                            <span class="hidden sm:inline">Sebelumnya</span>
                        </a>
                    @endif

                    {{-- Indikator Halaman Aktif --}}
                    <span class="px-3 py-1 sm:py-1.5 rounded-xl bg-indigo-50 text-indigo-600 font-extrabold text-xs border border-indigo-100">
                        {{ $kasManual->currentPage() }} / {{ $kasManual->lastPage() }}
                    </span>

                    {{-- Tombol Selanjutnya --}}
                    @if($kasManual->hasMorePages())
                        <a href="{{ $kasManual->nextPageUrl() }}" class="px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 text-slate-600 text-xs font-bold transition-all flex items-center gap-1 active:scale-95">
                            <span class="hidden sm:inline">Selanjutnya</span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                        </a>
                    @else
                        <span class="px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-xl border border-slate-200 text-slate-300 text-xs font-bold cursor-not-allowed flex items-center gap-1">
                            <span class="hidden sm:inline">Selanjutnya</span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                        </span>
                    @endif
                </div>
                @endif
            </div>
            @endif
        </div>
    </main>
 
    {{-- Modal Input Kas --}}
    <div id="modalKas" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
            
            <div class="bg-white rounded-3xl sm:rounded-[2.5rem] shadow-2xl w-full max-w-md p-6 sm:p-10 z-10 relative">
                <h3 class="text-xl sm:text-2xl font-extrabold text-slate-800 mb-1">Input Kas Baru 🖋️</h3>
                <p class="text-[10px] sm:text-xs text-slate-400 font-bold uppercase tracking-widest mb-6 sm:mb-8">Catat pemasukan atau pengeluaran manual</p>
 
                <form action="{{ route('admin.kas-store') }}" method="POST" class="space-y-4 sm:space-y-6">
                    @csrf

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Tanggal Transaksi</label>
                        <input type="date" name="tanggal" value="{{ now()->format('Y-m-d') }}" required
                               class="w-full px-4 sm:px-5 py-3 sm:py-4 rounded-xl sm:rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-50 outline-none transition-all text-xs sm:text-sm font-bold">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Keterangan</label>
                        <input type="text" name="keterangan" placeholder="Contoh: Beli Token Listrik" required 
                               class="w-full px-4 sm:px-5 py-3 sm:py-4 rounded-xl sm:rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-50 outline-none transition-all text-xs sm:text-sm font-bold">
                    </div>
 
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Tipe Transaksi</label>
                        <select name="tipe" required class="w-full px-4 sm:px-5 py-3 sm:py-4 rounded-xl sm:rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-300 outline-none transition-all text-xs sm:text-sm font-bold appearance-none">
                            <option value="debit">Pemasukan (Debit +)</option>
                            <option value="kredit">Pengeluaran (Kredit -)</option>
                        </select>
                    </div>
 
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Nominal (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-4 sm:left-5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs sm:text-sm">Rp</span>
                            <input type="text" id="nominalDisplay"
                                   placeholder="0"
                                   inputmode="numeric"
                                   class="w-full pl-10 sm:pl-12 pr-4 sm:pr-5 py-3 sm:py-4 rounded-xl sm:rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-50 outline-none transition-all text-xs sm:text-sm font-bold"
                                   oninput="formatNominal(this, 'nominalValue')">
                            <input type="hidden" name="nominal" id="nominalValue">
                        </div>
                    </div>
 
                    <div class="flex gap-2.5 sm:gap-3 pt-3 sm:pt-4">
                        <button type="button" onclick="closeModal()" class="flex-1 px-4 sm:px-6 py-3 sm:py-4 rounded-xl sm:rounded-2xl text-slate-400 font-bold text-xs sm:text-sm hover:bg-slate-50 transition-all uppercase tracking-widest">BATAL</button>
                        <button type="submit" class="flex-[2] bg-indigo-600 text-white px-4 sm:px-6 py-3 sm:py-4 rounded-xl sm:rounded-2xl font-bold text-xs sm:text-sm shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95 uppercase tracking-widest">SIMPAN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit Kas --}}
    <div id="modalEditKas" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeEditModal()"></div>

            <div class="bg-white rounded-3xl sm:rounded-[2.5rem] shadow-2xl w-full max-w-md p-6 sm:p-10 z-10 relative">
                <h3 class="text-xl sm:text-2xl font-extrabold text-slate-800 mb-1">Edit Catatan Kas ✏️</h3>
                <p class="text-[10px] sm:text-xs text-slate-400 font-bold uppercase tracking-widest mb-6 sm:mb-8">Perbarui data pemasukan/pengeluaran</p>

                <form id="formEditKas" action="" method="POST" class="space-y-4 sm:space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Tanggal Transaksi</label>
                        <input type="date" name="tanggal" id="editTanggal" required
                               class="w-full px-4 sm:px-5 py-3 sm:py-4 rounded-xl sm:rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-50 outline-none transition-all text-xs sm:text-sm font-bold">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Keterangan</label>
                        <input type="text" name="keterangan" id="editKeterangan" required
                               class="w-full px-4 sm:px-5 py-3 sm:py-4 rounded-xl sm:rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-50 outline-none transition-all text-xs sm:text-sm font-bold">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Tipe Transaksi</label>
                        <select name="tipe" id="editTipe" required class="w-full px-4 sm:px-5 py-3 sm:py-4 rounded-xl sm:rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-300 outline-none transition-all text-xs sm:text-sm font-bold appearance-none">
                            <option value="debit">Pemasukan (Debit +)</option>
                            <option value="kredit">Pengeluaran (Kredit -)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Nominal (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-4 sm:left-5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs sm:text-sm">Rp</span>
                            <input type="text" id="editNominalDisplay"
                                   placeholder="0"
                                   inputmode="numeric"
                                   class="w-full pl-10 sm:pl-12 pr-4 sm:pr-5 py-3 sm:py-4 rounded-xl sm:rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-50 outline-none transition-all text-xs sm:text-sm font-bold"
                                   oninput="formatNominal(this, 'editNominalValue')">
                            <input type="hidden" name="nominal" id="editNominalValue">
                        </div>
                    </div>

                    <div class="flex gap-2.5 sm:gap-3 pt-3 sm:pt-4">
                        <button type="button" onclick="closeEditModal()" class="flex-1 px-4 sm:px-6 py-3 sm:py-4 rounded-xl sm:rounded-2xl text-slate-400 font-bold text-xs sm:text-sm hover:bg-slate-50 transition-all uppercase tracking-widest">BATAL</button>
                        <button type="submit" class="flex-[2] bg-indigo-600 text-white px-4 sm:px-6 py-3 sm:py-4 rounded-xl sm:rounded-2xl font-bold text-xs sm:text-sm shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95 uppercase tracking-widest">SIMPAN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
 
    <script>
        lucide.createIcons();
 
        function openModal() {
            document.getElementById('modalKas').classList.remove('hidden');
        }
        function closeModal() {
            document.getElementById('modalKas').classList.add('hidden');
            document.getElementById('nominalDisplay').value = '';
            document.getElementById('nominalValue').value = '';
        }

        const kasUpdateBaseUrl = "{{ url('/admin/kas') }}";

        function openEditModal(id, keterangan, tipe, nominal, tanggal) {
            document.getElementById('formEditKas').action = kasUpdateBaseUrl + '/' + id;
            document.getElementById('editKeterangan').value = keterangan;
            document.getElementById('editTipe').value = tipe;
            document.getElementById('editTanggal').value = tanggal;

            document.getElementById('editNominalValue').value = nominal;
            document.getElementById('editNominalDisplay').value = parseInt(nominal, 10).toLocaleString('id-ID');

            document.getElementById('modalEditKas').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('modalEditKas').classList.add('hidden');
        }

        document.querySelectorAll('.btn-edit-kas').forEach(function (btn) {
            btn.addEventListener('click', function () {
                openEditModal(
                    this.dataset.id,
                    this.dataset.keterangan,
                    this.dataset.tipe,
                    this.dataset.nominal,
                    this.dataset.tanggal
                );
            });
        });
 
        function formatNominal(input, targetHiddenId) {
            let raw = input.value.replace(/\D/g, '');
            document.getElementById(targetHiddenId).value = raw;
            if (raw === '') {
                input.value = '';
            } else {
                input.value = parseInt(raw, 10).toLocaleString('id-ID');
            }
        }
    </script>
</body>
</html>