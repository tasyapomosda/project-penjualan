<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Barang - Admin Showcase</title>
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
                <h2 class="text-2xl font-extrabold text-slate-800 leading-tight tracking-tight">Manajemen Stok 📦</h2>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-1">Update & Pantau ketersediaan jajan</p>
            </div>
            <button onclick="openModal()" class="bg-[#6366f1] hover:bg-indigo-700 text-white px-6 py-3.5 rounded-2xl shadow-lg shadow-indigo-100 flex items-center gap-2 transition-all active:scale-95 text-sm font-bold">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                TAMBAH PRODUK
            </button>
        </header>

        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm flex-1 flex flex-col overflow-hidden">
            <div class="px-10 py-5 border-b border-slate-50 flex justify-between items-center bg-slate-50/10">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 bg-amber-400 rounded-full shadow-[0_0_8px_rgba(251,191,36,0.6)]"></div>
                    <h4 class="font-bold text-slate-700 text-sm tracking-tight italic uppercase">Daftar Inventaris Jajan</h4>
                </div>
            </div>

            <div class="overflow-y-auto flex-1 custom-scrollbar px-4">
                <table class="w-full text-left border-collapse table-fixed">
                    <thead class="sticky top-0 bg-white z-10 shadow-[0_1px_0_0_rgba(0,0,0,0.02)]">
                        <tr class="text-slate-300 font-bold text-[9px] uppercase tracking-[0.2em]">
                            <th class="px-6 py-5 w-16 text-center">No</th>
                            <th class="px-6 py-5 w-64">Nama Produk</th>
                            <th class="px-6 py-5 text-right w-40">Harga Satuan</th>
                            <th class="px-6 py-5 text-center w-24">Stok</th>
                            <th class="px-6 py-5 text-center w-32">Status</th>
                            <th class="px-6 py-5 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-600 divide-y divide-slate-50 text-xs">
                        @forelse($products as $index => $p)
                        <tr class="hover:bg-slate-50/50 transition-all">
                            <td class="px-6 py-4 text-center font-bold text-slate-400 tabular-nums">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 truncate">
                                <span class="font-bold text-slate-700 text-sm block truncate">{{ $p->name_merk }}</span>
                                <span class="text-[9px] text-slate-300 font-bold uppercase tracking-wider">ID: {{ $p->id }}</span>
                            </td>
                            <td class="px-6 py-4 font-extrabold text-slate-800 text-right tabular-nums">
                                Rp{{ number_format($p->harga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-lg font-bold">
                                    {{ $p->stok_sekarang }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($p->stok_sekarang <= 0)
                                    <span class="bg-rose-50 text-rose-500 px-3 py-1 rounded-full font-black text-[8px] uppercase">Habis</span>
                                @elseif($p->stok_sekarang <= 5)
                                    <span class="bg-amber-50 text-amber-500 px-3 py-1 rounded-full font-black text-[8px] uppercase">Menipis</span>
                                @else
                                    <span class="bg-emerald-50 text-emerald-500 px-3 py-1 rounded-full font-black text-[8px] uppercase">Tersedia</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button"
                                            class="btn-edit-produk text-slate-300 hover:text-indigo-500 transition-colors"
                                            data-id="{{ $p->id }}"
                                            data-nama="{{ $p->name_merk }}"
                                            data-harga="{{ $p->harga }}"
                                            data-stok="{{ $p->stok_sekarang }}">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </button>
                                    <form action="{{ route('admin.stok-delete', $p->id) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-300 hover:text-rose-500 transition-colors">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-24 text-center">
                                <div class="flex flex-col items-center opacity-20">
                                    <i data-lucide="package-search" class="w-12 h-12 mb-3"></i>
                                    <p class="italic text-xs font-bold uppercase tracking-widest text-slate-400">Belum ada jajan terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="modalTambah" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
            
            <div class="relative bg-white rounded-[3rem] shadow-2xl w-full max-w-md p-10 z-10 transition-all text-left border border-white">
                <h3 class="text-2xl font-extrabold text-slate-800 mb-1">Tambah Jajan Baru 🥨</h3>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-8 italic">Lengkapi data stok barang koperasi</p>

                <form action="{{ route('admin.stok-store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-3 ml-1">Nama / Merk Produk</label>
                        <input type="text" name="name_merk" placeholder="Contoh: Keripik Singkong" required 
                               class="w-full px-5 py-4 rounded-[1.5rem] bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-300 outline-none transition-all text-sm font-bold">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-3 ml-1">Harga Jual (Rp)</label>
                            <input type="text" name="harga" id="inputHarga" placeholder="0" required 
                                   onkeyup="formatRupiah(this)"
                                   class="w-full px-5 py-4 rounded-[1.5rem] bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-300 outline-none transition-all text-sm font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-3 ml-1">Stok Awal</label>
                            <input type="number" name="stok_awal" placeholder="0" required 
                                   class="w-full px-5 py-4 rounded-[1.5rem] bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-300 outline-none transition-all text-sm font-bold">
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeModal()" class="flex-1 px-6 py-4 rounded-2xl text-slate-400 font-bold text-sm hover:bg-slate-50 transition-all uppercase tracking-widest">BATAL</button>
                        <button type="submit" class="flex-[2] bg-indigo-600 text-white px-6 py-4 rounded-[1.5rem] font-bold text-sm shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95 uppercase tracking-widest">SIMPAN PRODUK</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modalEdit" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeEditModal()"></div>

            <div class="relative bg-white rounded-[3rem] shadow-2xl w-full max-w-md p-10 z-10 transition-all text-left border border-white">
                <h3 class="text-2xl font-extrabold text-slate-800 mb-1">Edit Jajan ✏️</h3>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-8 italic">Perbarui data stok barang</p>

                <form id="formEdit" action="" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-3 ml-1">Nama / Merk Produk</label>
                        <input type="text" name="name_merk" id="editNamaMerk" required
                               class="w-full px-5 py-4 rounded-[1.5rem] bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-300 outline-none transition-all text-sm font-bold">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-3 ml-1">Harga Jual (Rp)</label>
                            <input type="text" name="harga" id="editHarga" required
                                   onkeyup="formatRupiah(this)"
                                   class="w-full px-5 py-4 rounded-[1.5rem] bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-300 outline-none transition-all text-sm font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-3 ml-1">Stok Sekarang</label>
                            <input type="number" name="stok_sekarang" id="editStok" required
                                   class="w-full px-5 py-4 rounded-[1.5rem] bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-300 outline-none transition-all text-sm font-bold">
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeEditModal()" class="flex-1 px-6 py-4 rounded-2xl text-slate-400 font-bold text-sm hover:bg-slate-50 transition-all uppercase tracking-widest">BATAL</button>
                        <button type="submit" class="flex-[2] bg-indigo-600 text-white px-6 py-4 rounded-[1.5rem] font-bold text-sm shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95 uppercase tracking-widest">SIMPAN PERUBAHAN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function openModal() {
            document.getElementById('modalTambah').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('modalTambah').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // URL dasar untuk update produk, contoh: /admin/stok/123
        const stokUpdateBaseUrl = "{{ url('/admin/stok') }}";

        function openEditModal(id, namaMerk, harga, stok) {
            document.getElementById('formEdit').action = stokUpdateBaseUrl + '/' + id;
            document.getElementById('editNamaMerk').value = namaMerk;
            document.getElementById('editHarga').value = new Intl.NumberFormat('id-ID').format(harga);
            document.getElementById('editStok').value = stok;

            document.getElementById('modalEdit').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeEditModal() {
            document.getElementById('modalEdit').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Pasang event listener ke semua tombol edit (aman untuk nama produk apapun,
        // termasuk yang mengandung tanda kutip satu/dua)
        document.querySelectorAll('.btn-edit-produk').forEach(function (btn) {
            btn.addEventListener('click', function () {
                openEditModal(
                    this.dataset.id,
                    this.dataset.nama,
                    this.dataset.harga,
                    this.dataset.stok
                );
            });
        });

        // Fungsi Format Rupiah Real-time
        function formatRupiah(input) {
            let value = input.value.replace(/[^0-9]/g, "");
            if (value) {
                input.value = new Intl.NumberFormat('id-ID').format(value);
            } else {
                input.value = "";
            }
        }
    </script>
</body>
</html> 