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
 
    <main class="flex-1 ml-72 flex flex-col p-6 md:p-10 min-w-0 overflow-hidden">
        
        <header class="flex justify-between items-end mb-8 flex-shrink-0">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800 leading-tight tracking-tight">Buku Kas (Manual) 💰</h2>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-1">Input saldo masuk/keluar secara manual</p>
            </div>
            <button onclick="openModal()" class="bg-[#6366f1] hover:bg-indigo-700 text-white px-6 py-3.5 rounded-2xl shadow-lg shadow-indigo-100 flex items-center gap-2 transition-all active:scale-95 text-sm font-bold">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                INPUT KAS BARU
            </button>
        </header>
 
        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5 group transition-all hover:border-emerald-200">
                <div class="bg-emerald-50 text-emerald-500 p-4 rounded-2xl transition-transform group-hover:scale-110"><i data-lucide="trending-up" class="w-6 h-6"></i></div>
                <div class="min-w-0">
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Total Debit (+)</p>
                    <h3 class="text-lg font-extrabold text-emerald-600 truncate">Rp{{ number_format($totalDebit ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5 group transition-all hover:border-rose-200">
                <div class="bg-rose-50 text-rose-500 p-4 rounded-2xl transition-transform group-hover:scale-110"><i data-lucide="trending-down" class="w-6 h-6"></i></div>
                <div class="min-w-0">
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Total Kredit (-)</p>
                    <h3 class="text-lg font-extrabold text-rose-600 truncate">Rp{{ number_format($totalKredit ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="bg-indigo-600 p-6 rounded-[2rem] shadow-xl shadow-indigo-100 flex items-center gap-5 group transition-all transform hover:scale-[1.02]">
                <div class="bg-white/20 text-white p-4 rounded-2xl"><i data-lucide="wallet" class="w-6 h-6"></i></div>
                <div class="min-w-0">
                    <p class="text-white/60 text-[10px] font-bold uppercase tracking-widest mb-1">Saldo Bersih</p>
                    <h3 class="text-lg font-extrabold text-white truncate">Rp{{ number_format($saldoBersih ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
 
        {{-- Tabel Riwayat --}}
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm flex-1 flex flex-col overflow-hidden">
            <div class="px-10 py-5 border-b border-slate-50 flex justify-between items-center bg-slate-50/10">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 bg-indigo-400 rounded-full shadow-[0_0_8px_rgba(99,102,241,0.6)]"></div>
                    <h4 class="font-bold text-slate-700 text-sm tracking-tight italic uppercase">Riwayat Kas Manual</h4>
                </div>
            </div>
 
            <div class="overflow-y-auto flex-1 custom-scrollbar px-4">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 bg-white z-10 shadow-[0_1px_0_0_rgba(0,0,0,0.02)]">
                        <tr class="text-slate-300 font-bold text-[9px] uppercase tracking-[0.2em]">
                            <th class="px-6 py-5 w-48">Hari / Tanggal</th>
                            <th class="px-6 py-5">Keterangan</th>
                            <th class="px-6 py-5 text-right">Nominal</th>
                            <th class="px-6 py-5 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-600 divide-y divide-slate-50">
                        @forelse($kasManual as $k)
                        <tr class="hover:bg-slate-50/50 transition-all">
                            <td class="px-6 py-5 font-bold text-slate-400 tabular-nums text-sm">
                                {{ \Illuminate\Support\Carbon::parse($k->tanggal)->translatedFormat('l, d M Y') }}
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-700 text-sm">{{ $k->keterangan }}</p>
                                {{-- Badge tipe --}}
                                @if($k->tipe == 'debit')
                                    <span class="inline-flex items-center gap-1 mt-1 text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg uppercase tracking-wide">
                                        <i data-lucide="arrow-up-right" class="w-3 h-3"></i> Pemasukan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 mt-1 text-[10px] font-bold text-rose-500 bg-rose-50 px-2 py-0.5 rounded-lg uppercase tracking-wide">
                                        <i data-lucide="arrow-down-left" class="w-3 h-3"></i> Pengeluaran
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-right tabular-nums">
                                <span class="font-extrabold text-base {{ $k->tipe == 'debit' ? 'text-emerald-500' : 'text-rose-500' }}">
                                    {{ $k->tipe == 'debit' ? '+' : '-' }}Rp{{ number_format($k->nominal, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button"
                                            class="btn-edit-kas text-slate-300 hover:text-indigo-500 transition-colors"
                                            data-id="{{ $k->id }}"
                                            data-keterangan="{{ $k->keterangan }}"
                                            data-tipe="{{ $k->tipe }}"
                                            data-nominal="{{ $k->nominal }}"
                                            data-tanggal="{{ \Illuminate\Support\Carbon::parse($k->tanggal)->format('Y-m-d') }}">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </button>
                                    <form action="{{ route('admin.kas-delete', $k->id) }}" method="POST" onsubmit="return confirm('Hapus catatan ini?')">
                                        @csrf @method('DELETE')
                                        <button class="text-slate-300 hover:text-rose-500 transition-colors">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-24 text-center">
                                <div class="flex flex-col items-center opacity-20">
                                    <i data-lucide="database" class="w-12 h-12 mb-3"></i>
                                    <p class="italic text-xs font-bold uppercase tracking-widest">Belum ada catatan kas manual.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
 
    {{-- Modal Input Kas --}}
    <div id="modalKas" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
            
            <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md p-10 z-10 relative">
                <h3 class="text-xl font-extrabold text-slate-800 mb-1">Input Kas Baru 🖋️</h3>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-8">Catat pemasukan atau pengeluaran manual</p>
 
                <form action="{{ route('admin.kas-store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Tanggal Transaksi</label>
                        <input type="date" name="tanggal" value="{{ now()->format('Y-m-d') }}" required
                               class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-50 outline-none transition-all text-sm font-bold">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Keterangan</label>
                        <input type="text" name="keterangan" placeholder="Contoh: Beli Token Listrik" required 
                               class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-50 outline-none transition-all text-sm font-bold">
                    </div>
 
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Tipe Transaksi</label>
                        <select name="tipe" required class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-300 outline-none transition-all text-sm font-bold appearance-none">
                            <option value="debit">Pemasukan (Debit +)</option>
                            <option value="kredit">Pengeluaran (Kredit -)</option>
                        </select>
                    </div>
 
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Nominal (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">Rp</span>
                            <input type="text" id="nominalDisplay"
                                   placeholder="0"
                                   inputmode="numeric"
                                   class="w-full pl-12 pr-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-50 outline-none transition-all text-sm font-bold"
                                   oninput="formatNominal(this, 'nominalValue')">
                            <input type="hidden" name="nominal" id="nominalValue">
                        </div>
                    </div>
 
                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeModal()" class="flex-1 px-6 py-4 rounded-2xl text-slate-400 font-bold text-sm hover:bg-slate-50 transition-all">BATAL</button>
                        <button type="submit" class="flex-[2] bg-indigo-600 text-white px-6 py-4 rounded-2xl font-bold text-sm shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95">SIMPAN CATATAN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit Kas --}}
    <div id="modalEditKas" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeEditModal()"></div>

            <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md p-10 z-10 relative">
                <h3 class="text-xl font-extrabold text-slate-800 mb-1">Edit Catatan Kas ✏️</h3>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-8">Perbarui data pemasukan/pengeluaran</p>

                <form id="formEditKas" action="" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Tanggal Transaksi</label>
                        <input type="date" name="tanggal" id="editTanggal" required
                               class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-50 outline-none transition-all text-sm font-bold">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Keterangan</label>
                        <input type="text" name="keterangan" id="editKeterangan" required
                               class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-50 outline-none transition-all text-sm font-bold">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Tipe Transaksi</label>
                        <select name="tipe" id="editTipe" required class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-300 outline-none transition-all text-sm font-bold appearance-none">
                            <option value="debit">Pemasukan (Debit +)</option>
                            <option value="kredit">Pengeluaran (Kredit -)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Nominal (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">Rp</span>
                            <input type="text" id="editNominalDisplay"
                                   placeholder="0"
                                   inputmode="numeric"
                                   class="w-full pl-12 pr-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-50 outline-none transition-all text-sm font-bold"
                                   oninput="formatNominal(this, 'editNominalValue')">
                            <input type="hidden" name="nominal" id="editNominalValue">
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeEditModal()" class="flex-1 px-6 py-4 rounded-2xl text-slate-400 font-bold text-sm hover:bg-slate-50 transition-all">BATAL</button>
                        <button type="submit" class="flex-[2] bg-indigo-600 text-white px-6 py-4 rounded-2xl font-bold text-sm shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95">SIMPAN PERUBAHAN</button>
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