<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Hutang - Admin Showcase</title>
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

        {{-- Header --}}
        <header class="flex justify-between items-end mb-8 flex-shrink-0">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800 leading-tight tracking-tight">Catatan Hutang 📋</h2>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-1">Kelola tagihan anggota koperasi</p>
            </div>
            <button onclick="openModal('modalTambah')"
                    class="bg-rose-500 hover:bg-rose-600 text-white px-6 py-3.5 rounded-2xl shadow-lg shadow-rose-100 flex items-center gap-2 transition-all active:scale-95 text-sm font-bold">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                INPUT BARU
            </button>
        </header>

        {{-- Flash Message --}}
        @if(session('success'))
        <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl text-sm font-bold flex-shrink-0">
            <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
            {{ session('success') }}
        </div>
        @endif

        {{-- Summary Bar --}}
        <div class="grid grid-cols-2 gap-6 mb-8 flex-shrink-0">
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5 group transition-all hover:border-rose-200">
                <div class="bg-rose-50 text-rose-500 p-4 rounded-2xl transition-transform group-hover:scale-110">
                    <i data-lucide="alert-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Total Hutang Aktif</p>
                    <h3 class="text-xl font-extrabold text-rose-600">Rp{{ number_format($totalHutangSemua, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5 group transition-all hover:border-indigo-200">
                <div class="bg-indigo-50 text-indigo-500 p-4 rounded-2xl transition-transform group-hover:scale-110">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Anggota Berutang</p>
                    <h3 class="text-xl font-extrabold text-slate-700">{{ $groupedDebts->count() }} Orang</h3>
                </div>
            </div>
        </div>

        {{-- Daftar Hutang Per Anggota --}}
        <div class="flex-1 overflow-y-auto custom-scrollbar space-y-5 pr-1 pb-4">
            @forelse($groupedDebts as $nama => $items)
            @php
                $uid        = 'anggota-' . $loop->index;
                $totalOrang = $items->sum('nominal');
                $initial    = strtoupper(substr($nama, 0, 1));
                $colorSets  = [
                    ['bg-indigo-100 text-indigo-600',  'border-indigo-100'],
                    ['bg-rose-100 text-rose-600',      'border-rose-100'],
                    ['bg-emerald-100 text-emerald-600','border-emerald-100'],
                    ['bg-amber-100 text-amber-600',    'border-amber-100'],
                    ['bg-violet-100 text-violet-600',  'border-violet-100'],
                ];
                $cs = $colorSets[$loop->index % count($colorSets)];
            @endphp

            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">

                {{-- Header Anggota --}}
                <button onclick="toggleSection('{{ $uid }}')"
                        class="w-full px-8 py-5 flex justify-between items-center hover:bg-slate-50/60 transition-all text-left group">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 {{ $cs[0] }} rounded-2xl flex items-center justify-center font-extrabold text-xl transition-transform group-hover:scale-105">
                            {{ $initial }}
                        </div>
                        <div>
                            <h4 class="font-extrabold text-base text-slate-800">{{ $nama }}</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                                {{ $items->count() }} item · klik untuk lihat detail
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-8">
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Total Tagihan</p>
                            <p class="font-extrabold text-xl text-rose-500">Rp{{ number_format($totalOrang, 0, ',', '.') }}</p>
                        </div>
                        <div id="icon-{{ $uid }}" class="text-slate-300 transition-transform duration-300">
                            <i data-lucide="chevron-down" class="w-5 h-5"></i>
                        </div>
                    </div>
                </button>

                {{-- Detail Tabel --}}
                <div id="{{ $uid }}" class="hidden border-t border-slate-50">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/50">
                                <tr class="text-slate-300 font-bold text-[9px] uppercase tracking-[0.2em]">
                                    <th class="px-8 py-4 w-36">Tanggal</th>
                                    <th class="px-4 py-4">Produk</th>
                                    <th class="px-4 py-4 text-center w-16">Qty</th>
                                    <th class="px-4 py-4 text-right w-32">Harga/pcs</th>
                                    <th class="px-4 py-4 text-right w-32">Total</th>
                                    <th class="px-4 py-4 text-center w-44">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 text-sm">
                                @foreach($items as $item)
                                <tr class="hover:bg-slate-50/40 transition-all">
                                    <td class="px-8 py-4 text-slate-400 font-bold text-xs tabular-nums whitespace-nowrap">
                                        {{ $item->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-4 font-bold text-slate-700">
                                        {{ $item->barang }}
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="bg-slate-100 text-slate-500 font-extrabold text-xs px-2.5 py-1 rounded-xl">
                                            {{ $item->qty }}x
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-right text-slate-500 font-bold tabular-nums text-xs">
                                        Rp{{ $item->qty > 0 ? number_format($item->nominal / $item->qty, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-right font-extrabold text-rose-500 tabular-nums">
                                        Rp{{ number_format($item->nominal, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-center gap-2">

                                            {{-- ✅ FIX: nominal dikirim sebagai integer murni (tanpa format titik/koma)
                                                 agar tidak bentrok dengan pemisah parameter JS --}}
                                            <button
                                                onclick="openLunasModal(
                                                    {{ $item->id }},
                                                    '{{ addslashes($item->nama_pembeli) }}',
                                                    '{{ addslashes($item->barang) }}',
                                                    {{ (int) $item->nominal }}
                                                )"
                                                class="flex items-center gap-1 bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white px-3 py-1.5 rounded-xl font-bold text-[10px] uppercase tracking-wide transition-all shadow-sm whitespace-nowrap">
                                                <i data-lucide="check" class="w-3 h-3"></i> Lunas
                                            </button>

                                            {{-- Tombol Edit --}}
                                            <button
                                                data-id="{{ $item->id }}"
                                                data-nama="{{ $item->nama_pembeli }}"
                                                data-barang="{{ $item->barang }}"
                                                data-qty="{{ $item->qty }}"
                                                data-nominal="{{ $item->nominal }}"
                                                onclick="openEditModal(this)"
                                                class="text-slate-300 hover:text-indigo-500 transition-colors p-1.5 rounded-xl hover:bg-indigo-50"
                                                title="Edit">
                                                <i data-lucide="pencil" class="w-4 h-4"></i>
                                            </button>

                                            {{-- Tombol Hapus --}}
                                            <form action="{{ route('admin.hutang-delete', $item->id) }}" method="POST"
                                                  onsubmit="return confirm('Hapus data hutang ini secara permanen?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                        class="text-slate-300 hover:text-rose-500 transition-colors p-1.5 rounded-xl hover:bg-rose-50"
                                                        title="Hapus">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="border-t-2 border-slate-100 bg-slate-50/60">
                                    <td colspan="4" class="px-8 py-4 text-right text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">
                                        Total Hutang {{ $nama }}
                                    </td>
                                    <td class="px-4 py-4 text-right font-extrabold text-rose-600 text-lg tabular-nums">
                                        Rp{{ number_format($totalOrang, 0, ',', '.') }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            @empty
            <div class="py-32 text-center bg-white rounded-[3rem] border border-dashed border-slate-100">
                <div class="flex flex-col items-center opacity-20">
                    <i data-lucide="check-circle" class="w-14 h-14 mb-3"></i>
                    <p class="italic text-xs font-bold uppercase tracking-widest">Semua hutang sudah lunas!</p>
                </div>
            </div>
            @endforelse
        </div>
    </main>

    {{-- ===== Modal PILIHAN METODE PELUNASAN ===== --}}
    <div id="modalLunas" class="fixed inset-0 z-[70] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeModal('modalLunas')"></div>
            <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-sm p-8 z-10 relative">

                <div class="w-14 h-14 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-5">
                    <i data-lucide="check-circle" class="w-8 h-8"></i>
                </div>

                <h3 class="text-lg font-extrabold text-slate-800 text-center mb-1">Tandai Lunas</h3>
                <p class="text-xs text-slate-400 font-bold text-center mb-1" id="lunasNama">—</p>
                <p class="text-xs text-slate-500 font-semibold text-center mb-6" id="lunasDetail">—</p>

                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-3 text-center">Pilih Metode Pembayaran</p>

                <form id="formLunas" action="" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="metode_bayar" id="lunasMethods" value="cash">

                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <button type="button" onclick="pilihMetode('cash')"
                                id="btnCash"
                                class="metode-btn flex flex-col items-center gap-2 py-4 rounded-2xl border-2 border-emerald-400 bg-emerald-50 text-emerald-600 transition-all font-bold text-sm">
                            <span class="text-2xl">💵</span>
                            <span>Cash</span>
                        </button>
                        <button type="button" onclick="pilihMetode('qris')"
                                id="btnQris"
                                class="metode-btn flex flex-col items-center gap-2 py-4 rounded-2xl border-2 border-slate-200 bg-slate-50 text-slate-400 transition-all font-bold text-sm">
                            <span class="text-2xl">📱</span>
                            <span>QRIS</span>
                        </button>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="closeModal('modalLunas')"
                                class="flex-1 px-4 py-3.5 rounded-2xl text-slate-400 font-bold text-sm hover:bg-slate-50 transition-all">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-[2] bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-3.5 rounded-2xl font-bold text-sm shadow-lg shadow-emerald-100 transition-all active:scale-95">
                            ✅ Konfirmasi Lunas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== Modal TAMBAH HUTANG ===== --}}
    <div id="modalTambah" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeModal('modalTambah')"></div>
            <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md p-10 z-10 relative">
                <h3 class="text-xl font-extrabold text-slate-800 mb-1">Input Hutang Baru 🖋️</h3>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-8">Catat tagihan anggota koperasi</p>
                <form action="{{ route('admin.hutang-store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Nama Peminjam</label>
                        <input type="text" name="nama_pembeli" placeholder="Contoh: Mba Ria" required
                               class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-rose-300 focus:ring-4 focus:ring-rose-50 outline-none transition-all text-sm font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Produk / Barang</label>
                        <input type="text" name="barang" placeholder="Contoh: Yakult" required
                               class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-rose-300 focus:ring-4 focus:ring-rose-50 outline-none transition-all text-sm font-bold">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Qty</label>
                            <input type="number" name="qty" value="1" min="1" required
                                   class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-rose-300 focus:ring-4 focus:ring-rose-50 outline-none transition-all text-sm font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Total (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">Rp</span>
                                <input type="text" id="tambahNominalDisplay" placeholder="0" inputmode="numeric"
                                       class="w-full pl-10 pr-4 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-rose-300 focus:ring-4 focus:ring-rose-50 outline-none transition-all text-sm font-bold"
                                       oninput="formatNominal(this, 'tambahNominalValue')">
                                <input type="hidden" name="nominal" id="tambahNominalValue">
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeModal('modalTambah')"
                                class="flex-1 px-6 py-4 rounded-2xl text-slate-400 font-bold text-sm hover:bg-slate-50 transition-all">BATAL</button>
                        <button type="submit"
                                class="flex-[2] bg-rose-500 text-white px-6 py-4 rounded-2xl font-bold text-sm shadow-lg shadow-rose-100 hover:bg-rose-600 transition-all active:scale-95">SIMPAN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== Modal EDIT HUTANG ===== --}}
    <div id="modalEdit" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeModal('modalEdit')"></div>
            <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md p-10 z-10 relative">
                <h3 class="text-xl font-extrabold text-slate-800 mb-1">Edit Hutang ✏️</h3>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-8">Ubah data catatan hutang</p>
                <form id="formEdit" action="" method="POST" class="space-y-5">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Nama Peminjam</label>
                        <input type="text" name="nama_pembeli" id="editNama" required
                               class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-50 outline-none transition-all text-sm font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Produk / Barang</label>
                        <input type="text" name="barang" id="editBarang" required
                               class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-50 outline-none transition-all text-sm font-bold">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Qty</label>
                            <input type="number" name="qty" id="editQty" min="1" required
                                   class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-50 outline-none transition-all text-sm font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Total (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">Rp</span>
                                <input type="text" id="editNominalDisplay" placeholder="0" inputmode="numeric"
                                       class="w-full pl-10 pr-4 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-50 outline-none transition-all text-sm font-bold"
                                       oninput="formatNominal(this, 'editNominalValue')">
                                <input type="hidden" name="nominal" id="editNominalValue">
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeModal('modalEdit')"
                                class="flex-1 px-6 py-4 rounded-2xl text-slate-400 font-bold text-sm hover:bg-slate-50 transition-all">BATAL</button>
                        <button type="submit"
                                class="flex-[2] bg-indigo-600 text-white px-6 py-4 rounded-2xl font-bold text-sm shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95">SIMPAN PERUBAHAN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        // ── Modal helpers ──────────────────────────────────────────
        function openModal(id)  { document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

        function toggleSection(uid) {
            const el   = document.getElementById(uid);
            const icon = document.getElementById('icon-' + uid);
            el.classList.toggle('hidden');
            icon.style.transform = el.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        }

        // ── Format nominal rupiah ──────────────────────────────────
        function formatNominal(input, hiddenId) {
            const raw = input.value.replace(/\D/g, '');
            document.getElementById(hiddenId).value = raw;
            input.value = raw === '' ? '' : parseInt(raw, 10).toLocaleString('id-ID');
        }

        // ── Modal Lunas ───────────────────────────────────────────
        function openLunasModal(id, nama, barang, nominal) {
            document.getElementById('lunasNama').textContent   = nama;
            // ✅ FIX: nominal sudah integer dari PHP, format di sini pakai toLocaleString
            document.getElementById('lunasDetail').textContent = barang + ' · Rp' + nominal.toLocaleString('id-ID');

            document.getElementById('formLunas').action = '/admin/hutang/' + id + '/lunas';

            pilihMetode('cash');
            openModal('modalLunas');
        }

        function pilihMetode(metode) {
            document.getElementById('lunasMethods').value = metode;

            const btnCash = document.getElementById('btnCash');
            const btnQris = document.getElementById('btnQris');

            if (metode === 'cash') {
                btnCash.className = 'metode-btn flex flex-col items-center gap-2 py-4 rounded-2xl border-2 border-emerald-400 bg-emerald-50 text-emerald-600 transition-all font-bold text-sm';
                btnQris.className = 'metode-btn flex flex-col items-center gap-2 py-4 rounded-2xl border-2 border-slate-200 bg-slate-50 text-slate-400 transition-all font-bold text-sm';
            } else {
                btnQris.className = 'metode-btn flex flex-col items-center gap-2 py-4 rounded-2xl border-2 border-violet-400 bg-violet-50 text-violet-600 transition-all font-bold text-sm';
                btnCash.className = 'metode-btn flex flex-col items-center gap-2 py-4 rounded-2xl border-2 border-slate-200 bg-slate-50 text-slate-400 transition-all font-bold text-sm';
            }
        }

        // ── Modal Edit ────────────────────────────────────────────
        function openEditModal(btn) {
            const id      = btn.dataset.id;
            const nama    = btn.dataset.nama;
            const barang  = btn.dataset.barang;
            const qty     = btn.dataset.qty;
            const nominal = btn.dataset.nominal;

            document.getElementById('formEdit').action           = `/admin/hutang/${id}`;
            document.getElementById('editNama').value            = nama;
            document.getElementById('editBarang').value          = barang;
            document.getElementById('editQty').value             = qty;
            document.getElementById('editNominalDisplay').value  = parseInt(nominal, 10).toLocaleString('id-ID');
            document.getElementById('editNominalValue').value    = nominal;

            openModal('modalEdit');
        }
    </script>
</body>
</html>

