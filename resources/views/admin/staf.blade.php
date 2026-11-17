<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Staf - Admin Showcase</title>
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

    <main id="mainContent" class="flex-1 ml-72 flex flex-col p-4 sm:p-6 md:p-10 min-w-0 overflow-hidden">

        {{-- Header --}}
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-8 flex-shrink-0">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800 leading-tight tracking-tight">Kelola Staf 👥</h2>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-1">Akun staf yang boleh memakai kasbon</p>
            </div>
            <button onclick="openModal('modalTambah')"
                    class="bg-[#6366f1] hover:bg-indigo-700 text-white px-6 py-3.5 rounded-2xl shadow-lg shadow-indigo-100 flex items-center gap-2 transition-all active:scale-95 text-sm font-bold">
                <i data-lucide="user-plus" class="w-5 h-5"></i>
                TAMBAH STAF
            </button>
        </header>

        {{-- Flash sukses --}}
        @if(session('success'))
        <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl text-sm font-bold flex-shrink-0">
            <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
            {{ session('success') }}
        </div>
        @endif

        {{-- Pesan error (validasi / hapus ditolak) --}}
        @if($errors->any())
        <div class="mb-6 bg-rose-50 border border-rose-100 text-rose-700 px-6 py-4 rounded-2xl text-sm font-bold flex-shrink-0">
            <div class="flex items-center gap-3 mb-1">
                <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0"></i>
                Ada yang perlu diperbaiki:
            </div>
            <ul class="list-disc ml-12 font-semibold">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Tabel staf --}}
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm flex-1 flex flex-col overflow-hidden">
            <div class="px-10 py-5 border-b border-slate-50 flex justify-between items-center bg-slate-50/10">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 bg-indigo-400 rounded-full shadow-[0_0_8px_rgba(99,102,241,0.6)]"></div>
                    <h4 class="font-bold text-slate-700 text-sm tracking-tight italic uppercase">Daftar Staf ({{ $stafList->count() }})</h4>
                </div>
            </div>

            <div class="overflow-y-auto overflow-x-auto flex-1 custom-scrollbar px-4">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead class="sticky top-0 bg-white z-10 shadow-[0_1px_0_0_rgba(0,0,0,0.02)]">
                        <tr class="text-slate-300 font-bold text-[9px] uppercase tracking-[0.2em]">
                            <th class="px-6 py-5">Nama</th>
                            <th class="px-6 py-5">Email Login</th>
                            <th class="px-6 py-5 text-right">Hutang Aktif</th>
                            <th class="px-6 py-5 text-center w-56">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-600 divide-y divide-slate-50">
                        @forelse($stafList as $s)
                        @php $hutang = (float) ($hutangAktif[$s->name] ?? 0); @endphp
                        <tr class="hover:bg-slate-50/50 transition-all">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center font-extrabold">
                                        {{ strtoupper(substr($s->name, 0, 1)) }}
                                    </div>
                                    <span class="font-bold text-slate-700 text-sm">{{ $s->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-sm text-slate-500 font-semibold">{{ $s->email }}</td>
                            <td class="px-6 py-5 text-right tabular-nums">
                                @if($hutang > 0)
                                    <span class="font-extrabold text-rose-500 text-sm">Rp{{ number_format($hutang, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-xs font-bold text-emerald-500 uppercase tracking-wide">Tidak ada</span>
                                @endif
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button"
                                            data-id="{{ $s->id }}"
                                            data-nama="{{ $s->name }}"
                                            onclick="openResetModal(this)"
                                            class="flex items-center gap-1 bg-indigo-50 text-indigo-600 hover:bg-indigo-500 hover:text-white px-3 py-1.5 rounded-xl font-bold text-[10px] uppercase tracking-wide transition-all whitespace-nowrap">
                                        <i data-lucide="key-round" class="w-3 h-3"></i> Reset Password
                                    </button>
                                    <form action="{{ route('admin.staf-delete', $s->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus akun staf {{ addslashes($s->name) }}?')">
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
                        @empty
                        <tr>
                            <td colspan="4" class="py-24 text-center">
                                <div class="flex flex-col items-center opacity-20">
                                    <i data-lucide="users" class="w-12 h-12 mb-3"></i>
                                    <p class="italic text-xs font-bold uppercase tracking-widest">Belum ada akun staf.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    {{-- ===== Modal TAMBAH STAF ===== --}}
    <div id="modalTambah" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeModal('modalTambah')"></div>
            <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md p-10 z-10 relative">
                <h3 class="text-xl font-extrabold text-slate-800 mb-1">Tambah Staf 🖋️</h3>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-8">Buat akun login untuk staf</p>
                <form action="{{ route('admin.staf-store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Nama Staf</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Bu Nurul" required
                               class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-50 outline-none transition-all text-sm font-bold">
                        <p class="text-[10px] text-slate-400 font-semibold mt-2 ml-1">Nama ini muncul di Rekap Hutang, jadi harus unik.</p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Email (untuk login)</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required
                               class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-50 outline-none transition-all text-sm font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Password Awal</label>
                        <input type="text" name="password" placeholder="Minimal 8 karakter" required minlength="8" autocomplete="off"
                               class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-50 outline-none transition-all text-sm font-bold">
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeModal('modalTambah')"
                                class="flex-1 px-6 py-4 rounded-2xl text-slate-400 font-bold text-sm hover:bg-slate-50 transition-all">BATAL</button>
                        <button type="submit"
                                class="flex-[2] bg-indigo-600 text-white px-6 py-4 rounded-2xl font-bold text-sm shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95">SIMPAN AKUN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== Modal RESET PASSWORD ===== --}}
    <div id="modalReset" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeModal('modalReset')"></div>
            <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-sm p-10 z-10 relative">
                <h3 class="text-xl font-extrabold text-slate-800 mb-1">Reset Password 🔑</h3>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-1">Untuk akun</p>
                <p class="text-sm font-extrabold text-indigo-600 mb-8" id="resetNama">—</p>
                <form id="formReset" action="" method="POST" class="space-y-5">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Password Baru</label>
                        <input type="text" name="password" placeholder="Minimal 8 karakter" required minlength="8" autocomplete="off"
                               class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-50 outline-none transition-all text-sm font-bold">
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeModal('modalReset')"
                                class="flex-1 px-6 py-4 rounded-2xl text-slate-400 font-bold text-sm hover:bg-slate-50 transition-all">BATAL</button>
                        <button type="submit"
                                class="flex-[2] bg-indigo-600 text-white px-6 py-4 rounded-2xl font-bold text-sm shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95">SIMPAN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function openModal(id)  { document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

        function openResetModal(btn) {
            document.getElementById('resetNama').textContent = btn.dataset.nama;
            document.getElementById('formReset').action = "{{ url('/admin/staf') }}/" + btn.dataset.id + "/password";
            openModal('modalReset');
        }

        // Kalau penyimpanan staf baru gagal validasi, buka lagi form-nya
        @if($errors->any() && old('name') !== null)
            openModal('modalTambah');
        @endif
    </script>
</body>
</html>