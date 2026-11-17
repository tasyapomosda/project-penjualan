<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password - Showcase Snack</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Quicksand', sans-serif; }
    </style>
</head>
<body class="bg-[#0f172a] text-slate-200 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        {{-- Bar atas: kembali & keluar --}}
        <div class="flex items-center justify-between mb-5 px-1">
            <a href="{{ url('/') }}"
               class="text-xs font-bold text-slate-400 hover:text-white transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali ke katalog
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs font-bold text-rose-400 hover:text-rose-300 transition-colors">Keluar</button>
            </form>
        </div>

        <div class="bg-[#1e293b] border border-slate-800 rounded-3xl shadow-2xl p-8 sm:p-10">
            <h1 class="text-xl font-black text-white tracking-tight mb-1">Ganti Password</h1>
            <p class="text-slate-500 text-[10px] uppercase tracking-[0.2em] font-bold mb-8">
                Akun: {{ auth()->user()->name }}
            </p>

            @if(session('success'))
                <div class="mb-6 bg-emerald-900/40 border border-emerald-500/30 text-emerald-300 px-4 py-3 rounded-2xl text-xs font-bold">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-900/40 border border-red-500/30 text-red-300 px-4 py-3 rounded-2xl text-xs font-bold">
                    <ul class="list-disc ml-4 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Password lama --}}
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Password Lama</label>
                    <div x-data="{ show: false }" class="relative">
                        <input :type="show ? 'text' : 'password'" name="current_password" required autocomplete="current-password"
                               class="w-full bg-slate-900 border border-slate-700 rounded-2xl pl-5 pr-12 py-3.5 text-sm text-white font-bold focus:outline-none focus:border-purple-500/60 transition-all">
                        <button type="button" @click="show = !show"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white transition-colors"
                                :aria-label="show ? 'Sembunyikan password' : 'Lihat password'">
                            <svg x-show="!show" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Password baru --}}
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Password Baru</label>
                    <div x-data="{ show: false }" class="relative">
                        <input :type="show ? 'text' : 'password'" name="password" required minlength="8" autocomplete="new-password"
                               class="w-full bg-slate-900 border border-slate-700 rounded-2xl pl-5 pr-12 py-3.5 text-sm text-white font-bold focus:outline-none focus:border-purple-500/60 transition-all">
                        <button type="button" @click="show = !show"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white transition-colors"
                                :aria-label="show ? 'Sembunyikan password' : 'Lihat password'">
                            <svg x-show="!show" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                    <p class="text-[10px] text-slate-500 font-semibold mt-2 ml-1">Minimal 8 karakter.</p>
                </div>

                {{-- Konfirmasi password baru --}}
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ml-1">Ulangi Password Baru</label>
                    <div x-data="{ show: false }" class="relative">
                        <input :type="show ? 'text' : 'password'" name="password_confirmation" required minlength="8" autocomplete="new-password"
                               class="w-full bg-slate-900 border border-slate-700 rounded-2xl pl-5 pr-12 py-3.5 text-sm text-white font-bold focus:outline-none focus:border-purple-500/60 transition-all">
                        <button type="button" @click="show = !show"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white transition-colors"
                                :aria-label="show ? 'Sembunyikan password' : 'Lihat password'">
                            <svg x-show="!show" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-gradient-to-r from-purple-600 to-blue-600 py-4 rounded-2xl text-white font-black text-xs uppercase tracking-widest shadow-xl transition-all active:scale-95 mt-2">
                    Simpan Password Baru
                </button>
            </form>
        </div>
    </div>

</body>
</html>