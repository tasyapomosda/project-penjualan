<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Showcase Snack - Catalog</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Quicksand', sans-serif; }

        /* Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        body.theme-light .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; }

        /* Shake animation */
        @keyframes shake {
            0%,100% { transform: translateX(0); }
            20%      { transform: translateX(-8px); }
            40%      { transform: translateX(8px); }
            60%      { transform: translateX(-5px); }
            80%      { transform: translateX(5px); }
        }
        .shake { animation: shake 0.4s ease; }

        /* Tabs */
        .tab-active   { background: linear-gradient(135deg,#7c3aed,#2563eb); color: white; }
        .tab-inactive { background: rgba(30,41,59,0.8); color: #94a3b8; }
        .tab-inactive:hover { color: #e2e8f0; }

        /* Custom dropdown */
        .custom-dropdown { position: relative; }
        .custom-dropdown-menu {
            position: absolute; bottom: calc(100% + 6px); left: 0; right: 0;
            background: #0f172a; border: 1px solid #334155; border-radius: 14px;
            overflow: hidden; z-index: 999; box-shadow: 0 -8px 24px rgba(0,0,0,0.5);
        }
        .custom-dropdown-option {
            display: flex; align-items: center; gap: 10px;
            padding: 11px 16px; font-size: 12px; font-weight: 700;
            color: #e2e8f0; cursor: pointer; transition: background 0.15s;
        }
        .custom-dropdown-option:hover { background: #1e293b; }
        .custom-dropdown-option.selected { background: #1e3a5f; color: #7dd3fc; }

        /* Bottom drawer */
        .drawer-backdrop {
            position: fixed; inset: 0; background: rgba(2,6,23,0.75);
            backdrop-filter: blur(4px); z-index: 80; transition: opacity 0.3s ease;
        }
        .bottom-drawer {
            position: fixed; bottom: 0; left: 0; right: 0;
            background: #1e293b; border-radius: 24px 24px 0 0;
            border-top: 1px solid rgba(148,163,184,0.12); z-index: 90;
            max-height: 88vh; display: flex; flex-direction: column;
            transition: transform 0.35s cubic-bezier(0.32,0.72,0,1);
            box-shadow: 0 -8px 40px rgba(0,0,0,0.5);
        }
        .drawer-handle {
            width: 40px; height: 4px; background: rgba(148,163,184,0.25);
            border-radius: 4px; margin: 12px auto 0; flex-shrink: 0;
        }

        /* Floating cart button */
        .float-cart {
            position: fixed; bottom: 20px; left: 16px; right: 16px; z-index: 70;
            background: linear-gradient(135deg,#7c3aed,#2563eb); border-radius: 20px;
            padding: 14px 18px; display: flex; align-items: center;
            justify-content: space-between; box-shadow: 0 8px 32px rgba(124,77,255,0.45);
            cursor: pointer; transition: transform 0.15s, box-shadow 0.15s;
        }
        .float-cart:active { transform: scale(0.97); }

        /* Theme toggle */
        .theme-toggle-btn {
            display: flex; align-items: center; gap: 6px;
            padding: 6px 12px; border-radius: 999px; font-size: 11px;
            font-weight: 700; cursor: pointer; border: 1.5px solid;
            transition: all 0.2s ease; flex-shrink: 0; user-select: none;
            letter-spacing: 0.04em;
        }
        .toggle-pill {
            width: 32px; height: 18px; border-radius: 9px;
            position: relative; transition: background 0.25s; flex-shrink: 0;
        }
        .toggle-pill::after {
            content: ''; position: absolute; width: 14px; height: 14px;
            border-radius: 50%; background: #fff; top: 2px; left: 2px;
            transition: transform 0.25s; box-shadow: 0 1px 4px rgba(0,0,0,0.3);
        }

        /* ── DARK THEME (default) ── */
        body.theme-dark { background-color: #0f172a; color: #e2e8f0; }
        body.theme-dark .theme-toggle-btn { background: rgba(30,41,59,0.8); border-color: #334155; color: #94a3b8; }
        body.theme-dark .theme-toggle-btn:hover { border-color: #64748b; color: #e2e8f0; }
        body.theme-dark .toggle-pill { background: #334155; }
        body.theme-dark .toggle-pill::after { transform: translateX(0); }

        /* ── LIGHT THEME ── */
        body.theme-light { background-color: #f1f5f9; color: #1e293b; }
        body.theme-light .theme-toggle-btn { background: #fff; border-color: #e2e8f0; color: #64748b; }
        body.theme-light .theme-toggle-btn:hover { border-color: #7c3aed; color: #7c3aed; }
        body.theme-light .toggle-pill { background: #7c3aed; }
        body.theme-light .toggle-pill::after { transform: translateX(14px); }

        body.theme-light main > div:first-child { background-color: #fff !important; border-color: #e2e8f0 !important; }
        body.theme-light h1 { color: #1e293b !important; }
        body.theme-light .bg-slate-800\/50 { background-color: #f8fafc !important; border-color: #e2e8f0 !important; }
        body.theme-light input { color: #1e293b !important; }
        body.theme-light input::placeholder { color: #cbd5e1 !important; }
        body.theme-light .text-slate-500 { color: #94a3b8 !important; }
        body.theme-light .bg-\[\#1e293b\] { background-color: #ffffff !important; border-color: #e2e8f0 !important; }
        body.theme-light .bg-\[\#1e293b\]:hover { border-color: rgba(124,58,237,0.4) !important; }
        body.theme-light .bg-slate-900\/50 { background-color: #f1f5f9 !important; }
        body.theme-light .bg-slate-800\/80 { background-color: rgba(241,245,249,0.9) !important; border-color: #e2e8f0 !important; }
        body.theme-light h3.font-bold.text-white { color: #1e293b !important; }
        body.theme-light .text-purple-400 { color: #7c3aed !important; }
        body.theme-light .bg-slate-900\/80 { background-color: #f8fafc !important; border-color: #e2e8f0 !important; }
        body.theme-light .text-white { color: #1e293b; }
        body.theme-light .tab-inactive { background: #f1f5f9; color: #94a3b8; }
        body.theme-light .tab-inactive:hover { color: #1e293b; }

        /* Sidebar light */
        body.theme-light aside.hidden.md\:flex { background-color: #ffffff !important; border-color: #e2e8f0 !important; }
        body.theme-light aside .bg-purple-600\/20 { background-color: #ede9fe !important; border-color: rgba(124,58,237,0.3) !important; }
        body.theme-light aside h2.text-white { color: #1e293b !important; }
        body.theme-light aside .bg-slate-900\/50 { background-color: #f8fafc !important; border-color: #e2e8f0 !important; }
        body.theme-light aside .text-white { color: #1e293b !important; }
        body.theme-light aside .text-slate-500 { color: #94a3b8 !important; }
        body.theme-light aside .border-slate-800 { border-color: #e2e8f0 !important; }
        body.theme-light aside .border-t { border-color: #e2e8f0 !important; }
        body.theme-light aside h3.text-white { color: #1e293b !important; }
        body.theme-light aside .bg-slate-900 { background-color: #f8fafc !important; border-color: #e2e8f0 !important; color: #1e293b !important; }
        body.theme-light aside .bg-slate-700 { background-color: #f1f5f9 !important; border-color: #e2e8f0 !important; color: #1e293b !important; }
        body.theme-light aside .bg-slate-700:hover { background-color: #e2e8f0 !important; }
        body.theme-light .bg-slate-900\/60 { background-color: #f8fafc !important; border-color: #e2e8f0 !important; }
        body.theme-light .text-slate-400 { color: #94a3b8 !important; }
        body.theme-light .border-slate-700 { border-color: #e2e8f0 !important; }

        /* Dropdown light */
        body.theme-light .custom-dropdown-menu { background: #fff !important; border-color: #e2e8f0 !important; box-shadow: 0 -8px 24px rgba(0,0,0,0.1) !important; }
        body.theme-light .custom-dropdown-option { color: #1e293b !important; }
        body.theme-light .custom-dropdown-option:hover { background: #f1f5f9 !important; }
        body.theme-light .custom-dropdown-option.selected { background: #ede9fe !important; color: #7c3aed !important; }

        /* Bottom drawer light */
        body.theme-light .bottom-drawer { background: #ffffff !important; border-top-color: #e2e8f0 !important; }
        body.theme-light .drawer-handle { background: rgba(148,163,184,0.4) !important; }
        body.theme-light .bottom-drawer .bg-slate-900\/50 { background-color: #f8fafc !important; border-color: #e2e8f0 !important; }
        body.theme-light .bottom-drawer .bg-slate-900 { background-color: #f8fafc !important; border-color: #e2e8f0 !important; }
        body.theme-light .bottom-drawer .border-slate-800 { border-color: #e2e8f0 !important; }
        body.theme-light .bottom-drawer .text-white { color: #1e293b !important; }
        body.theme-light .bottom-drawer .text-slate-500 { color: #94a3b8 !important; }
        body.theme-light .bottom-drawer .bg-slate-700 { background-color: #f1f5f9 !important; border-color: #e2e8f0 !important; color: #1e293b !important; }
        body.theme-light .bottom-drawer .bg-purple-600\/20 { background-color: #ede9fe !important; border-color: rgba(124,58,237,0.3) !important; }
        body.theme-light .bottom-drawer h2.text-white { color: #1e293b !important; }
        body.theme-light .bottom-drawer .tab-inactive { background: #f1f5f9; color: #94a3b8; }
        body.theme-light main .flex-1.overflow-y-auto { background-color: #f1f5f9; }
    </style>
</head>
<body class="bg-[#0f172a] text-slate-200 h-screen overflow-hidden flex flex-col md:flex-row theme-dark"
      x-data="cartSystem()" x-init="init()">

    {{-- ════════════════════════════════════
         MAIN CATALOG
    ════════════════════════════════════ --}}
    <main class="flex-1 flex flex-col h-screen min-h-0">

        {{-- Header --}}
        <div class="flex-shrink-0 bg-[#0f172a] px-6 pt-6 pb-4 border-b border-slate-800/60
                    flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div class="flex items-center gap-3">
                <div>
                    <h1 class="text-2xl font-black text-white tracking-tight">Showcase Snack</h1>
                    <p class="text-slate-500 text-[10px] uppercase tracking-[0.2em] italic">Campus Self-Service System</p>
                </div>
                <button onclick="toggleTheme()" class="theme-toggle-btn" aria-label="Toggle tema">
                    <span class="toggle-pill"></span>
                    <span id="toggleLabel">☀️</span>
                </button>
            </div>

            <div class="flex flex-row gap-2 sm:gap-3 w-full lg:w-auto">
                {{-- Search --}}
                <div class="bg-slate-800/50 px-3 py-2 sm:px-4 sm:py-2.5 rounded-xl sm:rounded-2xl border border-slate-700 shadow-inner flex items-center gap-2 flex-1 min-w-0 sm:w-64 focus-within:border-purple-500/50 transition-all">
                    <i data-lucide="search" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-slate-500 flex-shrink-0"></i>
                    <input type="text" x-model="search" placeholder="Cari jajan..."
                        class="bg-transparent text-xs sm:text-sm text-white focus:outline-none w-full placeholder:text-slate-600 font-medium">
                </div>

                {{-- Nama Pembeli --}}
                <div :class="shakeName ? 'shake border-red-500/70' : 'border-slate-700'"
                     class="bg-slate-800/50 px-3 py-2 sm:px-4 sm:py-2.5 rounded-xl sm:rounded-2xl border shadow-inner flex items-center gap-2 flex-1 min-w-0 sm:w-48 transition-all">
                    <i data-lucide="user" class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0"
                       :class="!namaPembeli ? 'text-red-400/60' : 'text-slate-500'"></i>
                    <input type="text" x-model="namaPembeli" placeholder="Isi nama pembeli"
                        @input="onNamaChange()"
                        class="bg-transparent text-xs sm:text-sm text-white focus:outline-none w-full placeholder:text-slate-600 font-bold"
                        :class="!namaPembeli ? 'placeholder:text-red-400/40' : ''">
                </div>
            </div>
        </div>

        {{-- Katalog Grid --}}
        <div class="flex-1 overflow-y-auto custom-scrollbar px-6 pt-6 pb-28 md:pb-6">
            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @forelse($products as $p)
                @php $namaTampil = ucwords(strtolower($p->name_merk)); @endphp
                <div x-show="!search || '{{ strtolower($p->name_merk) }}'.includes(search.toLowerCase())"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="bg-[#1e293b] rounded-[2rem] p-4 border border-slate-800 hover:border-purple-500/50 transition-all group flex flex-col justify-between
                            {{ $p->stok_sekarang <= 0 ? 'opacity-50' : '' }}">
                    <div>
                        <div class="bg-slate-900/50 rounded-2xl h-28 flex items-center justify-center mb-4 relative overflow-hidden">
                            @if($p->foto)
                                <img src="{{ asset('storage/'.$p->foto) }}" alt="{{ $namaTampil }}"
                                     class="w-full h-full object-cover rounded-2xl group-hover:scale-105 transition-transform">
                            @else
                                <i data-lucide="package" class="text-slate-700 w-8 h-8 group-hover:scale-110 transition-transform"></i>
                            @endif
                            <span class="absolute top-2 right-2 bg-slate-800/80 text-[8px] font-bold px-2 py-1 rounded-full border border-slate-700"
                                  :class="{{ $p->stok_sekarang }} > 5 ? 'text-slate-400' : {{ $p->stok_sekarang }} > 0 ? 'text-yellow-400' : 'text-red-400'">
                                {{ $p->stok_sekarang <= 0 ? 'Habis' : 'Stok: '.$p->stok_sekarang }}
                            </span>
                            @if($p->stok_sekarang > 0 && $p->stok_sekarang <= 5)
                                <span class="absolute top-2 left-2 bg-yellow-400/20 text-yellow-400 text-[8px] font-bold px-2 py-1 rounded-full border border-yellow-400/30">
                                    Hampir Habis
                                </span>
                            @endif
                        </div>
                        <h3 class="font-bold text-white text-sm mb-1 truncate">{{ $namaTampil }}</h3>
                        <p class="text-purple-400 font-bold text-xs mb-4">Rp{{ number_format($p->harga, 0, ',', '.') }}</p>
                    </div>

                    <div class="flex items-center justify-between rounded-xl p-1 border transition-all"
                         :class="count('{{ $p->id }}') > 0
                            ? 'bg-purple-600/20 border-purple-500/40'
                            : 'bg-slate-900/80 border-slate-800'">
                        <button @click.stop="remove('{{ $p->id }}')"
                                class="p-2 hover:text-red-400 transition-colors rounded-lg"
                                :disabled="{{ $p->stok_sekarang }} === 0">
                            <i data-lucide="minus" class="w-4 h-4"></i>
                        </button>
                        <span class="text-sm font-bold min-w-[1.5rem] text-center transition-colors"
                              :class="count('{{ $p->id }}') > 0 ? 'text-purple-300' : 'text-white'"
                              x-text="count('{{ $p->id }}')">0</span>
                        <button @click.stop="add('{{ $p->id }}', {{ Js::from($namaTampil) }}, {{ $p->harga }}, {{ $p->stok_sekarang }})"
                                class="p-2 hover:text-emerald-400 transition-colors rounded-lg"
                                {{ $p->stok_sekarang <= 0 ? 'disabled' : '' }}>
                            <i data-lucide="plus" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-20 text-center opacity-20">
                    <i data-lucide="info" class="w-12 h-12 mx-auto mb-2"></i>
                    <p class="font-bold uppercase tracking-widest text-xs">Jajan tidak tersedia</p>
                </div>
                @endforelse
            </div>
        </div>
    </main>

    {{-- ════════════════════════════════════
         DESKTOP SIDEBAR
         Konten keranjang di-include dari partial
    ════════════════════════════════════ --}}
    <aside class="hidden md:flex w-full md:w-96 bg-[#1e293b] border-l border-slate-800 flex-col shadow-2xl z-50">
        <div class="p-6 pb-0">
            <div class="flex items-center gap-3 mb-5">
                <div class="bg-purple-600/20 p-2 rounded-xl border border-purple-500/30 text-purple-400">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                </div>
                <h2 class="text-lg font-bold text-white">Halaman Transaksi</h2>
            </div>
            @include('catalog.partials.cart-tabs')
        </div>
        @include('catalog.partials.cart-panel', ['context' => 'desktop'])
    </aside>

    {{-- ════════════════════════════════════
         MOBILE — FLOATING CART BUTTON
    ════════════════════════════════════ --}}
    <div class="md:hidden float-cart" @click="drawerOpen = true">
        <div class="flex items-center gap-3">
            <i data-lucide="shopping-cart" class="w-5 h-5 text-white flex-shrink-0"></i>
            <span class="bg-white/20 text-white text-[10px] font-black px-2 py-0.5 rounded-full"
                  x-text="totalItems + ' Item'"></span>
            <span class="text-white font-bold text-sm">Halaman Transaksi</span>
        </div>
        <span class="text-white font-black text-sm" x-text="formatRupiah(totalHarga)"></span>
    </div>

    {{-- ════════════════════════════════════
         MOBILE — BACKDROP
    ════════════════════════════════════ --}}
    <div class="md:hidden drawer-backdrop"
         x-show="drawerOpen" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="drawerOpen = false">
    </div>

    {{-- ════════════════════════════════════
         MOBILE — BOTTOM DRAWER
    ════════════════════════════════════ --}}
    <div class="md:hidden bottom-drawer"
         x-show="drawerOpen" x-cloak
         :style="drawerOpen ? 'transform:translateY(0)' : 'transform:translateY(100%)'">

        <div class="drawer-handle"></div>

        <div class="px-5 pt-3 pb-0 flex-shrink-0">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="bg-purple-600/20 p-2 rounded-xl border border-purple-500/30 text-purple-400">
                        <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-lg font-bold text-white">Halaman Transaksi</h2>
                </div>
                <button @click="drawerOpen = false" class="text-slate-500 hover:text-white transition-colors p-1">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            @include('catalog.partials.cart-tabs')
        </div>

        @include('catalog.partials.cart-panel', ['context' => 'mobile'])
    </div>

    {{-- ════════════════════════════════════
         TOAST NOTIFIKASI
    ════════════════════════════════════ --}}
    <div x-show="toast.show" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[200] px-5 py-3 rounded-2xl shadow-2xl text-sm font-bold flex items-center gap-2 border whitespace-nowrap"
         :class="toast.type === 'success'
            ? 'bg-emerald-900/90 border-emerald-500/40 text-emerald-300'
            : 'bg-red-900/90 border-red-500/40 text-red-300'">
        <span x-text="toast.message"></span>
    </div>

    {{-- ════════════════════════════════════
         MODAL: KONFIRMASI GANTI KERANJANG
    ════════════════════════════════════ --}}
    <div x-show="confirmLoad.show" x-cloak
         class="fixed inset-0 z-[150] flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-[#020617]/80 backdrop-blur-md" @click="confirmLoad.show = false"></div>
        <div x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative bg-[#1e293b] rounded-3xl shadow-2xl w-full max-w-xs p-8 text-center border border-slate-700">
            <div class="w-14 h-14 bg-yellow-400/10 text-yellow-400 rounded-full flex items-center justify-center mx-auto mb-5">
                <i data-lucide="alert-triangle" class="w-7 h-7"></i>
            </div>
            <h3 class="text-base font-black text-white mb-2">Ganti Keranjang?</h3>
            <p class="text-slate-400 text-xs mb-6 leading-relaxed">
                Keranjang aktif kamu saat ini akan digantikan. Lanjutkan?
            </p>
            <div class="flex gap-3">
                <button @click="confirmLoad.show = false"
                        class="flex-1 bg-slate-700 hover:bg-slate-600 text-white py-2.5 rounded-xl text-xs font-bold transition-all">
                    Batal
                </button>
                <button @click="konfirmasiMuat()"
                        class="flex-1 bg-yellow-500 hover:bg-yellow-400 text-slate-900 py-2.5 rounded-xl text-xs font-black transition-all">
                    Ya, Ganti
                </button>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════
         MODAL: SUKSES
    ════════════════════════════════════ --}}
    <div x-show="showModal" x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-[#020617]/90 backdrop-blur-xl" @click="resetPage()"></div>
        <div x-show="showModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative bg-white rounded-[3rem] shadow-2xl w-full max-w-sm p-12 text-center">
            <div class="w-20 h-20 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-8">
                <i data-lucide="check" class="w-12 h-12 stroke-[4]"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-900 mb-2">Berhasil!</h3>
            <p class="text-slate-500 font-medium text-sm leading-relaxed mb-10">
                Terima kasih <span class="font-bold text-indigo-600" x-text="namaPembeli"></span>!
                Transaksi kamu sudah tercatat
            </p>
            <button @click="resetPage()"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-[1.5rem] font-bold text-sm transition-all active:scale-95 shadow-xl uppercase tracking-widest">
                Done!
            </button>
        </div>
    </div>

    <script>
        lucide.createIcons();

        /* ── THEME ── */
        const THEME_KEY = 'snack_theme';
        function applyTheme(theme) {
            document.body.classList.remove('theme-dark','theme-light');
            document.body.classList.add('theme-' + theme);
            const label = document.getElementById('toggleLabel');
            if (label) label.textContent = theme === 'light' ? '🌙' : '☀️';
        }
        function toggleTheme() {
            const next = document.body.classList.contains('theme-dark') ? 'light' : 'dark';
            applyTheme(next);
            try { localStorage.setItem(THEME_KEY, next); } catch(e) {}
        }
        (function() {
            try {
                const saved = localStorage.getItem(THEME_KEY);
                if (saved === 'light') applyTheme('light');
            } catch(e) {}
        })();

        /* ── CART SYSTEM ── */
        function cartSystem() {
            return {
                namaPembeli: '', search: '', metodeBayar: 'cash',
                cart: {}, totalHarga: 0, totalItems: 0,
                loading: false, showModal: false,
                showNameWarning: false, shakeName: false,
                activeTab: 'current', savedCarts: [],
                loadedFromSaved: null, drawerOpen: false,
                toast: { show: false, message: '', type: 'success', _timer: null },
                confirmLoad: { show: false, idx: null },

                init() {
                    try {
                        const stored = localStorage.getItem('snack_saved_carts');
                        if (stored) this.savedCarts = JSON.parse(stored);
                    } catch(e) { this.savedCarts = []; }
                    this.$nextTick(() => lucide.createIcons());
                },

                onNamaChange() {
                    if (this.showNameWarning && this.namaPembeli.trim()) this.showNameWarning = false;
                },

                add(id, name, price, stok) {
                    const key = String(id);
                    if (!this.cart[key]) this.cart[key] = { name, price: Number(price), qty: 0 };
                    if (this.cart[key].qty >= Number(stok)) return;
                    this.cart[key].qty++;
                    this.sync();
                },

                remove(id) {
                    const key = String(id);
                    if (!this.cart[key] || this.cart[key].qty <= 0) return;
                    this.cart[key].qty--;
                    if (this.cart[key].qty <= 0) this.hapusTotal(key);
                    else this.sync();
                },

                hapusTotal(id) {
                    delete this.cart[String(id)];
                    this.sync();
                },

                sync() {
                    this.cart = { ...this.cart };
                    this.calculate();
                },

                calculate() {
                    let total = 0, items = 0;
                    Object.values(this.cart).forEach(i => {
                        if (i?.qty > 0) { total += Number(i.price) * Number(i.qty); items += Number(i.qty); }
                    });
                    this.totalHarga = isNaN(total) ? 0 : total;
                    this.totalItems = isNaN(items) ? 0 : items;
                    this.$nextTick(() => lucide.createIcons());
                },

                count(id) {
                    return this.cart[String(id)]?.qty ?? 0;
                },

                formatRupiah(n) {
                    const val = Number(n);
                    if (isNaN(val)) return 'Rp 0';
                    return new Intl.NumberFormat('id-ID', { style:'currency', currency:'IDR', minimumFractionDigits:0 }).format(val);
                },

                showToast(message, type = 'success', duration = 2500) {
                    if (this.toast._timer) clearTimeout(this.toast._timer);
                    Object.assign(this.toast, { message, type, show: true });
                    this.toast._timer = setTimeout(() => { this.toast.show = false; }, duration);
                },

                _requireNama() {
                    if (this.namaPembeli.trim()) return true;
                    this.showNameWarning = true;
                    this.shakeName = true;
                    setTimeout(() => { this.shakeName = false; this.showNameWarning = false; }, 2500);
                    return false;
                },

                simpanKeranjang() {
                    if (!this._requireNama() || this.totalItems === 0) return;
                    const namaLower = this.namaPembeli.trim().toLowerCase();
                    const idx = this.savedCarts.findIndex(s => s.nama.toLowerCase() === namaLower);
                    const obj = {
                        nama: this.namaPembeli.trim(),
                        cart: JSON.parse(JSON.stringify(this.cart)),
                        total: this.totalHarga,
                        metode: this.metodeBayar,
                        waktu: new Date().toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit' })
                    };
                    if (idx >= 0) { this.savedCarts.splice(idx, 1, obj); this.showToast('Keranjang "' + obj.nama + '" diperbarui ✅'); }
                    else          { this.savedCarts.push(obj);             this.showToast('Keranjang "' + obj.nama + '" disimpan 🔖'); }
                    this._persistSaved();
                    this.cart = {}; this.namaPembeli = ''; this.metodeBayar = 'cash';
                    this.loadedFromSaved = null; this.calculate(); this.activeTab = 'saved';
                    this.$nextTick(() => lucide.createIcons());
                },

                muatKeranjang(idx) {
                    if (this.totalItems > 0) { this.confirmLoad = { show:true, idx }; this.$nextTick(() => lucide.createIcons()); return; }
                    this._doMuat(idx);
                },
                konfirmasiMuat() { this.confirmLoad.show = false; this._doMuat(this.confirmLoad.idx); },
                _doMuat(idx) {
                    const saved = this.savedCarts[idx];
                    if (!saved) return;
                    this.cart = JSON.parse(JSON.stringify(saved.cart));
                    this.namaPembeli = saved.nama; this.metodeBayar = saved.metode;
                    this.loadedFromSaved = idx; this.calculate(); this.activeTab = 'current';
                    this.showToast('Keranjang "' + saved.nama + '" dimuat 🛒');
                    this.$nextTick(() => lucide.createIcons());
                },

                hapusSavedCart(idx) {
                    const nama = this.savedCarts[idx]?.nama || '';
                    this.savedCarts.splice(idx, 1);
                    this._persistSaved();
                    this.showToast('Keranjang "' + nama + '" dihapus 🗑️', 'error');
                    this.$nextTick(() => lucide.createIcons());
                },

                async bayarSaved(idx) {
                    const saved = this.savedCarts[idx]; if (!saved) return;
                    const prev = { cart:this.cart, nama:this.namaPembeli, metode:this.metodeBayar, total:this.totalHarga, items:this.totalItems };
                    this.cart = saved.cart; this.namaPembeli = saved.nama; this.metodeBayar = saved.metode;
                    this.totalHarga = saved.total;
                    this.totalItems = Object.values(saved.cart).reduce((a,i) => a + i.qty, 0);
                    await this.submitForm(true);
                    if (this.showModal) { this.savedCarts.splice(idx,1); this._persistSaved(); }
                    else { Object.assign(this, { cart:prev.cart, namaPembeli:prev.nama, metodeBayar:prev.metode, totalHarga:prev.total, totalItems:prev.items }); }
                },

                _persistSaved() {
                    try { localStorage.setItem('snack_saved_carts', JSON.stringify(this.savedCarts)); } catch(e) {}
                },

                async submitForm(skipValidasi = false) {
                    if (!skipValidasi && (!this._requireNama() || this.totalItems === 0)) return;
                    this.loading = true; this.showNameWarning = false;
                    const payload = {
                        nama_pembeli: this.namaPembeli.trim(),
                        metode_bayar: this.metodeBayar,
                        items: Object.entries(this.cart).filter(([,i]) => i.qty > 0).map(([id,i]) => ({ product_id:id, jumlah:i.qty })),
                        _token: document.querySelector('meta[name="csrf-token"]').content
                    };
                    try {
                        const res = await fetch("{{ route('transaksi.store') }}", {
                            method: 'POST',
                            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':payload._token },
                            body: JSON.stringify(payload)
                        });
                        if (res.ok) {
                            if (this.loadedFromSaved !== null) { this.savedCarts.splice(this.loadedFromSaved,1); this._persistSaved(); this.loadedFromSaved = null; }
                            this.showModal = true; this.drawerOpen = false;
                        } else {
                            const err = await res.json().catch(() => ({}));
                            alert('Transaksi gagal: ' + (err.message || res.statusText));
                        }
                    } catch(e) {
                        console.error(e); alert('Gagal terhubung ke server.');
                    } finally {
                        this.loading = false;
                    }
                },

                resetPage() { window.location.reload(); }
            }
        }
    </script>
</body>
</html>