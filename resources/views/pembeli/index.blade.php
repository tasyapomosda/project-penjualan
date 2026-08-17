<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Showcase Snack - Catalog</title>
    <script>
        tailwind.config = {
            safelist: [
                'translate-y-full', 'translate-y-0',
                'opacity-0', 'opacity-100',
                'scale-90', 'scale-95', 'scale-100',
            ]
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Quicksand', sans-serif; }

        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }

        /* ── CUSTOM DROPDOWN ── */
        .custom-dropdown { position: relative; }
        .custom-dropdown-menu {
            position: absolute;
            bottom: calc(100% + 6px);
            left: 0;
            right: 0;
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 14px;
            overflow: hidden;
            z-index: 999;
            box-shadow: 0 -8px 24px rgba(0,0,0,0.5);
        }
        .custom-dropdown-option {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 16px;
            font-size: 12px;
            font-weight: 700;
            color: #e2e8f0;
            cursor: pointer;
            transition: background 0.15s;
        }
        .custom-dropdown-option:hover { background: #1e293b; }
        .custom-dropdown-option.selected { background: #1e3a5f; color: #7dd3fc; }

        /* ── SHAKE ANIMATION ── */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%       { transform: translateX(-8px); }
            40%       { transform: translateX(8px); }
            60%       { transform: translateX(-5px); }
            80%       { transform: translateX(5px); }
        }
        .shake { animation: shake 0.4s ease; }

        /* ── TABS ── */
        .tab-active {
            background: linear-gradient(135deg, #7c3aed, #2563eb);
            color: white;
        }
        .tab-inactive {
            background: rgba(30,41,59,0.8);
            color: #94a3b8;
        }
        .tab-inactive:hover { color: #e2e8f0; }

        /* ── BOTTOM DRAWER ── */
        .drawer-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(2,6,23,0.75);
            backdrop-filter: blur(4px);
            z-index: 80;
            transition: opacity 0.3s ease;
        }
        .bottom-drawer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #1e293b;
            border-radius: 24px 24px 0 0;
            border-top: 1px solid rgba(148,163,184,0.12);
            z-index: 90;
            max-height: 88vh;
            display: flex;
            flex-direction: column;
            transition: transform 0.35s cubic-bezier(0.32,0.72,0,1);
            box-shadow: 0 -8px 40px rgba(0,0,0,0.5);
        }
        .drawer-handle {
            width: 40px;
            height: 4px;
            background: rgba(148,163,184,0.25);
            border-radius: 4px;
            margin: 12px auto 0;
            flex-shrink: 0;
        }

        /* ── FLOATING CART BUTTON ── */
        .float-cart {
            position: fixed;
            bottom: 20px;
            left: 16px;
            right: 16px;
            z-index: 70;
            background: linear-gradient(135deg, #7c3aed, #2563eb);
            border-radius: 20px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 8px 32px rgba(124,77,255,0.45);
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .float-cart:active { transform: scale(0.97); }

        /* ══════════════════════════════════════════════
           THEME TOGGLE BUTTON
        ══════════════════════════════════════════════ */
        .theme-toggle-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            border: 1.5px solid;
            transition: all 0.2s ease;
            flex-shrink: 0;
            user-select: none;
            letter-spacing: 0.04em;
        }
        /* toggle track */
        .toggle-pill {
            width: 32px;
            height: 18px;
            border-radius: 9px;
            position: relative;
            transition: background 0.25s;
            flex-shrink: 0;
        }
        .toggle-pill::after {
            content: '';
            position: absolute;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #fff;
            top: 2px;
            left: 2px;
            transition: transform 0.25s;
            box-shadow: 0 1px 4px rgba(0,0,0,0.3);
        }

        /* ══════════════════════════════════════════════
           DARK THEME (default)
        ══════════════════════════════════════════════ */
        body.theme-dark {
            background-color: #0f172a;
            color: #e2e8f0;
        }
        body.theme-dark .theme-toggle-btn {
            background: rgba(30,41,59,0.8);
            border-color: #334155;
            color: #94a3b8;
        }
        body.theme-dark .theme-toggle-btn:hover {
            border-color: #64748b;
            color: #e2e8f0;
        }
        body.theme-dark .toggle-pill { background: #334155; }
        body.theme-dark .toggle-pill::after { transform: translateX(0); }

        /* ══════════════════════════════════════════════
           LIGHT THEME
        ══════════════════════════════════════════════ */
        body.theme-light {
            background-color: #f1f5f9;
            color: #1e293b;
        }

        /* scrollbar light */
        body.theme-light .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; }

        /* toggle button light */
        body.theme-light .theme-toggle-btn {
            background: #fff;
            border-color: #e2e8f0;
            color: #64748b;
        }
        body.theme-light .theme-toggle-btn:hover {
            border-color: #7c3aed;
            color: #7c3aed;
        }
        body.theme-light .toggle-pill { background: #7c3aed; }
        body.theme-light .toggle-pill::after { transform: translateX(14px); }

        /* header */
        body.theme-light main > div:first-child {
            background-color: #fff !important;
            border-color: #e2e8f0 !important;
        }
        body.theme-light h1 { color: #1e293b !important; }
        body.theme-light p.text-slate-500 { color: #94a3b8 !important; }

        /* search & input fields */
        body.theme-light .bg-slate-800\/50 {
            background-color: #f8fafc !important;
            border-color: #e2e8f0 !important;
        }
        body.theme-light input {
            color: #1e293b !important;
        }
        body.theme-light input::placeholder { color: #cbd5e1 !important; }
        body.theme-light .text-slate-500 { color: #94a3b8 !important; }

        /* product cards */
        body.theme-light .bg-\[\#1e293b\] {
            background-color: #ffffff !important;
            border-color: #e2e8f0 !important;
        }
        body.theme-light .bg-\[\#1e293b\]:hover {
            border-color: rgba(124,58,237,0.4) !important;
        }
        body.theme-light .bg-slate-900\/50 {
            background-color: #f1f5f9 !important;
        }
        body.theme-light .text-slate-700 { color: #cbd5e1 !important; }
        body.theme-light .bg-slate-800\/80 {
            background-color: rgba(241,245,249,0.9) !important;
            border-color: #e2e8f0 !important;
        }
        body.theme-light h3.font-bold.text-white { color: #1e293b !important; }
        body.theme-light .text-purple-400 { color: #7c3aed !important; }

        /* qty controls */
        body.theme-light .bg-slate-900\/80 {
            background-color: #f8fafc !important;
            border-color: #e2e8f0 !important;
        }
        body.theme-light .text-white { color: #1e293b; }

        /* sidebar desktop */
        body.theme-light aside.hidden.md\:flex {
            background-color: #ffffff !important;
            border-color: #e2e8f0 !important;
        }
        body.theme-light aside .bg-purple-600\/20 {
            background-color: #ede9fe !important;
            border-color: rgba(124,58,237,0.3) !important;
        }
        body.theme-light aside h2.text-white { color: #1e293b !important; }

        /* tabs */
        body.theme-light .tab-inactive {
            background: #f1f5f9;
            color: #94a3b8;
        }
        body.theme-light .tab-inactive:hover { color: #1e293b; }

        /* cart items */
        body.theme-light aside .bg-slate-900\/50 {
            background-color: #f8fafc !important;
            border-color: #e2e8f0 !important;
        }
        body.theme-light aside .text-white { color: #1e293b !important; }
        body.theme-light aside .text-slate-500 { color: #94a3b8 !important; }
        body.theme-light aside .border-slate-800 { border-color: #e2e8f0 !important; }

        /* total area */
        body.theme-light aside .border-t { border-color: #e2e8f0 !important; }
        body.theme-light aside .text-slate-500.text-\[10px\] { color: #94a3b8 !important; }
        body.theme-light aside h3.text-white { color: #1e293b !important; }
        body.theme-light aside .bg-purple-600\/20.text-purple-400 { color: #7c3aed !important; }

        /* dropdown sidebar */
        body.theme-light aside .bg-slate-900 {
            background-color: #f8fafc !important;
            border-color: #e2e8f0 !important;
            color: #1e293b !important;
        }
        body.theme-light .custom-dropdown-menu {
            background: #fff !important;
            border-color: #e2e8f0 !important;
            box-shadow: 0 -8px 24px rgba(0,0,0,0.1) !important;
        }
        body.theme-light .custom-dropdown-option { color: #1e293b !important; }
        body.theme-light .custom-dropdown-option:hover { background: #f1f5f9 !important; }
        body.theme-light .custom-dropdown-option.selected { background: #ede9fe !important; color: #7c3aed !important; }

        /* simpan button sidebar */
        body.theme-light aside .bg-slate-700 {
            background-color: #f1f5f9 !important;
            border-color: #e2e8f0 !important;
            color: #1e293b !important;
        }
        body.theme-light aside .bg-slate-700:hover {
            background-color: #e2e8f0 !important;
        }

        /* saved carts */
        body.theme-light .bg-slate-900\/60 {
            background-color: #f8fafc !important;
            border-color: #e2e8f0 !important;
        }
        body.theme-light .text-slate-400 { color: #94a3b8 !important; }
        body.theme-light .border-slate-700 { border-color: #e2e8f0 !important; }
        body.theme-light .text-slate-600 { color: #94a3b8 !important; }

        /* bottom drawer light */
        body.theme-light .bottom-drawer {
            background: #ffffff !important;
            border-top-color: #e2e8f0 !important;
        }
        body.theme-light .drawer-handle { background: rgba(148,163,184,0.4) !important; }
        body.theme-light .bottom-drawer .bg-slate-900\/50 {
            background-color: #f8fafc !important;
            border-color: #e2e8f0 !important;
        }
        body.theme-light .bottom-drawer .bg-slate-900 {
            background-color: #f8fafc !important;
            border-color: #e2e8f0 !important;
        }
        body.theme-light .bottom-drawer .border-slate-800 { border-color: #e2e8f0 !important; }
        body.theme-light .bottom-drawer .text-white { color: #1e293b !important; }
        body.theme-light .bottom-drawer .text-slate-500 { color: #94a3b8 !important; }
        body.theme-light .bottom-drawer .bg-slate-700 {
            background-color: #f1f5f9 !important;
            border-color: #e2e8f0 !important;
            color: #1e293b !important;
        }
        body.theme-light .bottom-drawer .bg-purple-600\/20 {
            background-color: #ede9fe !important;
            border-color: rgba(124,58,237,0.3) !important;
        }
        body.theme-light .bottom-drawer h2.text-white { color: #1e293b !important; }
        body.theme-light .bottom-drawer .tab-inactive { background: #f1f5f9; color: #94a3b8; }

        /* main scroll area */
        body.theme-light main .flex-1.overflow-y-auto { background-color: #f1f5f9; }
    </style>
</head>
<body class="bg-[#0f172a] text-slate-200 h-screen overflow-hidden flex flex-col md:flex-row theme-dark"
      x-data="cartSystem()" x-init="init()">

    {{-- ─── MAIN CATALOG ─────────────────────────────────────────────── --}}
    <main class="flex-1 flex flex-col h-screen min-h-0">

        {{-- ── HEADER STICKY ── --}}
        <div class="flex-shrink-0 bg-[#0f172a] px-6 pt-6 pb-4 border-b border-slate-800/60
                    flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div class="flex items-center gap-3">
                <div>
                    <h1 class="text-2xl font-black text-white tracking-tight">Showcase Snack 🍪</h1>
                    <p class="text-slate-500 text-[10px] uppercase tracking-[0.2em] italic">Campus Self-Service System</p>
                </div>
                {{-- ── THEME TOGGLE ── --}}
                <button id="themeToggleBtn"
                        onclick="toggleTheme()"
                        class="theme-toggle-btn"
                        title="Ganti tema terang/gelap"
                        aria-label="Toggle tema">
                    <span class="toggle-pill" id="togglePill"></span>
                    <span id="toggleLabel">☀️ Terang</span>
                </button>
            </div>

            <div class="flex flex-row gap-2 sm:gap-3 w-full lg:w-auto">
                {{-- Search --}}
                <div class="bg-slate-800/50 px-3 py-2 sm:px-4 sm:py-2.5 rounded-xl sm:rounded-2xl border border-slate-700 shadow-inner flex items-center gap-2 sm:gap-3 flex-1 min-w-0 sm:w-64 focus-within:border-purple-500/50 transition-all">
                    <i data-lucide="search" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-slate-500 flex-shrink-0"></i>
                    <input type="text" x-model="search" placeholder="Cari jajan..."
                        class="bg-transparent text-xs sm:text-sm text-white focus:outline-none w-full min-w-0 placeholder:text-slate-600 font-medium">
                </div>

                {{-- Nama Pembeli --}}
                <div :class="shakeName ? 'shake border-red-500/70' : 'border-slate-700'"
                     class="bg-slate-800/50 px-3 py-2 sm:px-4 sm:py-2.5 rounded-xl sm:rounded-2xl border shadow-inner flex items-center gap-2 sm:gap-3 flex-1 min-w-0 sm:w-48 transition-all">
                    <i data-lucide="user" class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0"
                       :class="!namaPembeli ? 'text-red-400/60' : 'text-slate-500'"></i>
                    <input type="text" x-model="namaPembeli" placeholder="Nama kamu..."
                        @input="onNamaChange()"
                        class="bg-transparent text-xs sm:text-sm text-white focus:outline-none w-full min-w-0 placeholder:text-slate-600 font-bold"
                        :class="!namaPembeli ? 'placeholder:text-red-400/40' : ''">
                </div>
            </div>
        </div>

        {{-- ── KATALOG SCROLL AREA ── --}}
        <div class="flex-1 overflow-y-auto custom-scrollbar px-6 pt-6 pb-28 md:pb-6">
            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @forelse($products as $p)
                @php $namaTampil = ucwords(strtolower($p->name_merk)); @endphp
                    <div x-show="!search || '{{ strtolower($p->name_merk) }}'.includes(search.toLowerCase())"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="bg-[#1e293b] rounded-[2rem] p-4 border border-slate-800 hover:border-purple-500/50 transition-all group flex flex-col justify-between">

                        <div>
                            <div class="bg-slate-900/50 rounded-2xl h-28 flex items-center justify-center mb-4 relative overflow-hidden">
                                <i data-lucide="package" class="text-slate-700 w-8 h-8 group-hover:scale-110 transition-transform"></i>
                                <span class="absolute top-2 right-2 bg-slate-800/80 text-[8px] font-bold px-2 py-1 rounded-full border border-slate-700"
                                      :class="{{ $p->stok_sekarang }} > 0 ? 'text-slate-400' : 'text-red-400'">
                                    Stok: {{ $p->stok_sekarang }}
                                </span>
                            </div>
                            <h3 class="font-bold text-white text-sm mb-1 truncate">{{ $namaTampil }}</h3>
                            <p class="text-purple-400 font-bold text-xs mb-4">Rp{{ number_format($p->harga, 0, ',', '.') }}</p>
                        </div>

                        <div class="flex items-center justify-between bg-slate-900/80 rounded-xl p-1 border border-slate-800">
                            <button @click.stop="remove('{{ $p->id }}')"
                                    class="p-2 hover:text-red-400 transition-colors rounded-lg"
                                    :disabled="{{ $p->stok_sekarang }} === 0">
                                <i data-lucide="minus" class="w-4 h-4"></i>
                            </button>
                            <span class="text-sm font-bold text-white min-w-[1.5rem] text-center"
                                  x-text="count('{{ $p->id }}')">0</span>
                            <button @click.stop="add(
                                        '{{ $p->id }}',
                                        {{ Js::from($namaTampil) }},
                                        {{ $p->harga }},
                                        {{ $p->stok_sekarang }}
                                    )"
                                    class="p-2 hover:text-emerald-400 transition-colors rounded-lg">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center opacity-20">
                        <i data-lucide="info" class="w-12 h-12 mx-auto mb-2"></i>
                        <p class="font-bold uppercase tracking-widest text-xs">Ups, Jajanan Kosong</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    {{-- ═══════════════════════════════════════════════════════════════
         DESKTOP SIDEBAR
    ═══════════════════════════════════════════════════════════════ --}}
    <aside class="hidden md:flex w-full md:w-96 bg-[#1e293b] border-l border-slate-800 flex-col shadow-2xl z-50">

        <div class="p-6 pb-0">
            <div class="flex items-center gap-3 mb-5">
                <div class="bg-purple-600/20 p-2 rounded-xl border border-purple-500/30 text-purple-400">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                </div>
                <h2 class="text-lg font-bold text-white">Pesanan Kamu</h2>
            </div>

            <div class="flex gap-2 mb-4">
                <button @click="activeTab = 'current'"
                        :class="activeTab === 'current' ? 'tab-active' : 'tab-inactive'"
                        class="flex-1 py-2 rounded-xl text-xs font-bold transition-all">
                    Keranjang
                    <span x-show="totalItems > 0"
                          class="ml-1 bg-white/20 text-white text-[9px] px-1.5 py-0.5 rounded-full font-black"
                          x-text="totalItems"></span>
                </button>
                <button @click="activeTab = 'saved'"
                        :class="activeTab === 'saved' ? 'tab-active' : 'tab-inactive'"
                        class="flex-1 py-2 rounded-xl text-xs font-bold transition-all">
                    Disimpan
                    <span x-show="savedCarts.length > 0"
                          class="ml-1 bg-emerald-400/30 text-emerald-300 text-[9px] px-1.5 py-0.5 rounded-full font-black"
                          x-text="savedCarts.length"></span>
                </button>
            </div>
        </div>

        {{-- TAB: Keranjang Aktif --}}
        <div x-show="activeTab === 'current'" class="flex flex-col flex-1 overflow-hidden px-6 pb-6">
            <div class="flex-1 overflow-y-auto custom-scrollbar pr-1 mb-4">
                <template x-if="totalItems === 0">
                    <div class="flex flex-col items-center justify-center h-40 opacity-20 italic">
                        <i data-lucide="shopping-basket" class="w-12 h-12 mb-2"></i>
                        <p class="text-xs">Belum ada barang dipilih</p>
                    </div>
                </template>
                <div class="space-y-3">
                    <template x-for="[id, item] in Object.entries(cart)" :key="id">
                        <div x-show="item.qty > 0" class="bg-slate-900/50 rounded-2xl p-4 border border-slate-800">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-sm font-bold text-white tracking-tight" x-text="item.name"></span>
                                <button @click="hapusTotal(id)" class="text-slate-600 hover:text-red-400 transition-all ml-2 flex-shrink-0">
                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                </button>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] text-slate-500 font-bold"
                                      x-text="item.qty + ' x ' + formatRupiah(item.price)"></span>
                                <span class="text-sm font-bold text-purple-400"
                                      x-text="formatRupiah(item.price * item.qty)"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-800 space-y-3">
                <div class="flex justify-between items-end">
                    <div>
                        <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Total Bayar</p>
                        <h3 class="text-2xl font-black text-white" x-text="formatRupiah(totalHarga)">Rp0</h3>
                    </div>
                    <span class="bg-purple-600/20 text-purple-400 text-[10px] font-bold px-3 py-1 rounded-full border border-purple-500/30"
                          x-text="totalItems + ' Items'"></span>
                </div>
                <div class="custom-dropdown" x-data="{ open: false }">
                    <button type="button" @click="open = !open" @click.outside="open = false"
                        class="w-full bg-slate-900 border border-slate-700 rounded-2xl px-4 py-3.5 text-xs text-white font-bold flex items-center justify-between cursor-pointer hover:border-slate-500 transition-all">
                        <span x-text="metodeBayar === 'cash' ? '💵 Cash' : metodeBayar === 'hutang' ? '📝 Kasbon (Hutang)' : '📱 QRIS'"></span>
                        <svg :class="open ? 'rotate-180' : ''" class="transition-transform w-3.5 h-3.5 text-slate-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div x-show="open" x-cloak class="custom-dropdown-menu">
                        <div class="custom-dropdown-option" :class="metodeBayar === 'cash' ? 'selected' : ''"
                             @click="metodeBayar = 'cash'; open = false">💵 Cash</div>
                        <div class="custom-dropdown-option" :class="metodeBayar === 'hutang' ? 'selected' : ''"
                             @click="metodeBayar = 'hutang'; open = false">📝 Kasbon (Hutang)</div>
                        <div class="custom-dropdown-option" :class="metodeBayar === 'qris' ? 'selected' : ''"
                             @click="metodeBayar = 'qris'; open = false">📱 QRIS</div>
                    </div>
                </div>
                <p x-show="showNameWarning" x-cloak
                   class="text-red-400 text-[10px] font-bold text-center tracking-widest uppercase">
                   ⚠️ Isi nama kamu dulu ya!
                </p>
                <button @click="simpanKeranjang()"
                    :disabled="totalItems === 0"
                    class="w-full bg-slate-700 hover:bg-slate-600 border border-slate-600 py-3 rounded-2xl text-white font-bold text-xs uppercase tracking-widest disabled:opacity-30 transition-all flex items-center justify-center gap-2 active:scale-95">
                    <i data-lucide="bookmark" class="w-4 h-4"></i>
                    Simpan Dulu (Belum Bayar)
                </button>
                <button @click="submitForm()"
                    :disabled="totalItems === 0 || loading"
                    class="w-full bg-gradient-to-r from-purple-600 to-blue-600 py-4 rounded-2xl text-white font-black text-xs uppercase tracking-widest shadow-xl disabled:opacity-30 disabled:grayscale transition-all flex items-center justify-center gap-2 active:scale-95">
                    <template x-if="!loading"><span>Selesaikan Pembelian</span></template>
                    <template x-if="loading">
                        <span class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                            </svg>
                            Memproses...
                        </span>
                    </template>
                </button>
            </div>
        </div>

        {{-- TAB: Keranjang Disimpan --}}
        <div x-show="activeTab === 'saved'" class="flex flex-col flex-1 overflow-hidden px-6 pb-6">
            <div class="flex-1 overflow-y-auto custom-scrollbar pr-1">
                <template x-if="savedCarts.length === 0">
                    <div class="flex flex-col items-center justify-center h-40 opacity-20 italic">
                        <i data-lucide="bookmark" class="w-12 h-12 mb-2"></i>
                        <p class="text-xs">Belum ada keranjang disimpan</p>
                    </div>
                </template>
                <div class="space-y-3 mt-1">
                    <template x-for="(saved, idx) in savedCarts" :key="idx">
                        <div class="bg-slate-900/60 rounded-2xl p-4 border border-slate-700 hover:border-purple-500/40 transition-all">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="text-white font-black text-sm" x-text="saved.nama"></p>
                                    <p class="text-slate-500 text-[10px]" x-text="'Disimpan ' + saved.waktu"></p>
                                </div>
                                <button @click="hapusSavedCart(idx)"
                                        class="text-slate-600 hover:text-red-400 transition-colors flex-shrink-0 ml-2">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            </div>
                            <div class="space-y-1 mb-3">
                                <template x-for="[id, item] in Object.entries(saved.cart)" :key="id">
                                    <div x-show="item.qty > 0" class="flex justify-between items-center">
                                        <span class="text-[11px] text-slate-400 truncate max-w-[60%]"
                                              x-text="item.qty + 'x ' + item.name"></span>
                                        <span class="text-[11px] text-purple-400 font-bold"
                                              x-text="formatRupiah(item.price * item.qty)"></span>
                                    </div>
                                </template>
                            </div>
                            <div class="flex justify-between items-center mb-3 pt-2 border-t border-slate-800 gap-2">
                                <div class="custom-dropdown flex-1" x-data="{ open: false }">
                                    <button type="button" @click="open = !open" @click.outside="open = false"
                                        class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2 py-1.5 text-[10px] text-white font-bold flex items-center justify-between cursor-pointer hover:border-slate-500 transition-all">
                                        <span x-text="saved.metode === 'hutang' ? '📝 Kasbon' : saved.metode === 'qris' ? '📱 QRIS' : '💵 Cash'"></span>
                                        <svg :class="open ? 'rotate-180' : ''" class="transition-transform w-3 h-3 text-slate-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                    </button>
                                    <div x-show="open" x-cloak class="custom-dropdown-menu">
                                        <div class="custom-dropdown-option" :class="saved.metode === 'cash' ? 'selected' : ''"
                                            @click="saved.metode = 'cash'; open = false; _persistSaved()">💵 Cash</div>
                                        <div class="custom-dropdown-option" :class="saved.metode === 'hutang' ? 'selected' : ''"
                                            @click="saved.metode = 'hutang'; open = false; _persistSaved()">📝 Kasbon (Hutang)</div>
                                        <div class="custom-dropdown-option" :class="saved.metode === 'qris' ? 'selected' : ''"
                                            @click="saved.metode = 'qris'; open = false; _persistSaved()">📱 QRIS</div>
                                    </div>
                                </div>
                                <span class="text-sm font-black text-white flex-shrink-0"
                                    x-text="formatRupiah(saved.total)"></span>
                            </div>
                            </div>
                            <div class="flex gap-2">
                                <button @click="muatKeranjang(idx)"
                                        class="flex-1 bg-slate-700 hover:bg-slate-600 text-white text-[10px] font-bold py-2 rounded-xl transition-all flex items-center justify-center gap-1">
                                    <i data-lucide="edit-3" class="w-3 h-3"></i> Edit
                                </button>
                                <button @click="bayarSaved(idx)" :disabled="loading"
                                        class="flex-1 bg-gradient-to-r from-purple-600 to-blue-600 text-white text-[10px] font-black py-2 rounded-xl transition-all disabled:opacity-30 flex items-center justify-center gap-1 active:scale-95">
                                    <i data-lucide="check-circle" class="w-3 h-3"></i> Bayar
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </aside>

    {{-- MOBILE — FLOATING CART BUTTON --}}
    <div class="md:hidden float-cart" @click="drawerOpen = true">
        <div class="flex items-center gap-3">
            <i data-lucide="shopping-cart" class="w-5 h-5 text-white flex-shrink-0"></i>
            <span class="bg-white/20 text-white text-[10px] font-black px-2 py-0.5 rounded-full"
                  x-text="totalItems + ' Item'"></span>
            <span class="text-white font-bold text-sm">Pesanan Kamu</span>
        </div>
        <span class="text-white font-black text-sm" x-text="formatRupiah(totalHarga)"></span>
    </div>

    {{-- MOBILE — BOTTOM DRAWER --}}
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

    <div class="md:hidden bottom-drawer"
         x-show="drawerOpen" x-cloak
         :style="drawerOpen ? 'transform: translateY(0)' : 'transform: translateY(100%)'"
         x-transition:enter="transition ease-out duration-350"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-250"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="drawer-handle"></div>

        <div class="px-5 pt-3 pb-0 flex-shrink-0">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="bg-purple-600/20 p-2 rounded-xl border border-purple-500/30 text-purple-400">
                        <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-lg font-bold text-white">Pesanan Kamu</h2>
                </div>
                <button @click="drawerOpen = false"
                        class="text-slate-500 hover:text-white transition-colors p-1">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="flex gap-2 mb-4">
                <button @click="activeTab = 'current'"
                        :class="activeTab === 'current' ? 'tab-active' : 'tab-inactive'"
                        class="flex-1 py-2 rounded-xl text-xs font-bold transition-all">
                    Keranjang
                    <span x-show="totalItems > 0"
                          class="ml-1 bg-white/20 text-white text-[9px] px-1.5 py-0.5 rounded-full font-black"
                          x-text="totalItems"></span>
                </button>
                <button @click="activeTab = 'saved'"
                        :class="activeTab === 'saved' ? 'tab-active' : 'tab-inactive'"
                        class="flex-1 py-2 rounded-xl text-xs font-bold transition-all">
                    Disimpan
                    <span x-show="savedCarts.length > 0"
                          class="ml-1 bg-emerald-400/30 text-emerald-300 text-[9px] px-1.5 py-0.5 rounded-full font-black"
                          x-text="savedCarts.length"></span>
                </button>
            </div>
        </div>

        <div x-show="activeTab === 'current'" class="flex flex-col flex-1 overflow-hidden px-5 pb-5">
            <div class="flex-1 overflow-y-auto custom-scrollbar pr-1 mb-3 min-h-[100px]">
                <template x-if="totalItems === 0">
                    <div class="flex flex-col items-center justify-center h-32 opacity-20 italic">
                        <i data-lucide="shopping-basket" class="w-10 h-10 mb-2"></i>
                        <p class="text-xs">Belum ada barang dipilih</p>
                    </div>
                </template>
                <div class="space-y-3">
                    <template x-for="[id, item] in Object.entries(cart)" :key="id">
                        <div x-show="item.qty > 0" class="bg-slate-900/50 rounded-2xl p-4 border border-slate-800">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-sm font-bold text-white tracking-tight" x-text="item.name"></span>
                                <button @click="hapusTotal(id)" class="text-slate-600 hover:text-red-400 transition-all ml-2 flex-shrink-0">
                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                </button>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] text-slate-500 font-bold"
                                      x-text="item.qty + ' x ' + formatRupiah(item.price)"></span>
                                <span class="text-sm font-bold text-purple-400"
                                      x-text="formatRupiah(item.price * item.qty)"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="pt-2.5 border-t border-slate-800 space-y-2 flex-shrink-0">
                <div class="flex justify-between items-center">
                    <div class="flex items-baseline gap-2">
                        <p class="text-slate-500 text-[9px] font-bold uppercase tracking-widest">Total</p>
                        <h3 class="text-lg font-black text-white" x-text="formatRupiah(totalHarga)">Rp0</h3>
                    </div>
                    <span class="bg-purple-600/20 text-purple-400 text-[9px] font-bold px-2 py-0.5 rounded-full border border-purple-500/30"
                          x-text="totalItems + ' Items'"></span>
                </div>
                <div class="custom-dropdown" x-data="{ open: false }">
                    <button type="button" @click="open = !open" @click.outside="open = false"
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white font-bold flex items-center justify-between cursor-pointer hover:border-slate-500 transition-all">
                        <span x-text="metodeBayar === 'cash' ? '💵 Cash' : metodeBayar === 'hutang' ? '📝 Kasbon (Hutang)' : '📱 QRIS'"></span>
                        <svg :class="open ? 'rotate-180' : ''" class="transition-transform w-3.5 h-3.5 text-slate-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div x-show="open" x-cloak class="custom-dropdown-menu">
                        <div class="custom-dropdown-option" :class="metodeBayar === 'cash' ? 'selected' : ''"
                             @click="metodeBayar = 'cash'; open = false">💵 Cash</div>
                        <div class="custom-dropdown-option" :class="metodeBayar === 'hutang' ? 'selected' : ''"
                             @click="metodeBayar = 'hutang'; open = false">📝 Kasbon (Hutang)</div>
                        <div class="custom-dropdown-option" :class="metodeBayar === 'qris' ? 'selected' : ''"
                             @click="metodeBayar = 'qris'; open = false">📱 QRIS</div>
                    </div>
                </div>
                <p x-show="showNameWarning" x-cloak
                   class="text-red-400 text-[10px] font-bold text-center tracking-widest uppercase">
                   ⚠️ Isi nama kamu dulu ya!
                </p>
                <div class="flex gap-2">
                    <button @click="simpanKeranjang()"
                        :disabled="totalItems === 0"
                        title="Simpan Dulu (Belum Bayar)"
                        class="flex-shrink-0 bg-slate-700 hover:bg-slate-600 border border-slate-600 px-3.5 rounded-xl text-white disabled:opacity-30 transition-all flex items-center justify-center active:scale-95">
                        <i data-lucide="bookmark" class="w-4 h-4"></i>
                    </button>
                    <button @click="submitForm()"
                        :disabled="totalItems === 0 || loading"
                        class="flex-1 bg-gradient-to-r from-purple-600 to-blue-600 py-2.5 rounded-xl text-white font-black text-xs uppercase tracking-wide shadow-xl disabled:opacity-30 disabled:grayscale transition-all flex items-center justify-center gap-2 active:scale-95">
                        <template x-if="!loading"><span>Selesaikan Pembelian</span></template>
                        <template x-if="loading">
                            <span class="flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                                </svg>
                                Memproses...
                            </span>
                        </template>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="activeTab === 'saved'" class="flex flex-col flex-1 overflow-hidden px-5 pb-5">
            <div class="flex-1 overflow-y-auto custom-scrollbar pr-1 max-h-[60vh]">
                <template x-if="savedCarts.length === 0">
                    <div class="flex flex-col items-center justify-center h-32 opacity-20 italic">
                        <i data-lucide="bookmark" class="w-10 h-10 mb-2"></i>
                        <p class="text-xs">Belum ada keranjang disimpan</p>
                    </div>
                </template>
                <div class="space-y-3 mt-1">
                    <template x-for="(saved, idx) in savedCarts" :key="idx">
                        <div class="bg-slate-900/60 rounded-2xl p-4 border border-slate-700 hover:border-purple-500/40 transition-all">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="text-white font-black text-sm" x-text="saved.nama"></p>
                                    <p class="text-slate-500 text-[10px]" x-text="'Disimpan ' + saved.waktu"></p>
                                </div>
                                <button @click="hapusSavedCart(idx)"
                                        class="text-slate-600 hover:text-red-400 transition-colors flex-shrink-0 ml-2">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            </div>
                            <div class="space-y-1 mb-3">
                                <template x-for="[id, item] in Object.entries(saved.cart)" :key="id">
                                    <div x-show="item.qty > 0" class="flex justify-between items-center">
                                        <span class="text-[11px] text-slate-400 truncate max-w-[60%]"
                                              x-text="item.qty + 'x ' + item.name"></span>
                                        <span class="text-[11px] text-purple-400 font-bold"
                                              x-text="formatRupiah(item.price * item.qty)"></span>
                                    </div>
                                </template>
                            </div>
                            <div class="flex justify-between items-center mb-3 pt-2 border-t border-slate-800">
                                <span class="text-[10px] text-slate-500 font-bold uppercase"
                                      x-text="saved.metode === 'hutang' ? '📝 Kasbon' : saved.metode === 'qris' ? '📱 QRIS' : '💵 Cash'"></span>
                                <span class="text-sm font-black text-white"
                                      x-text="formatRupiah(saved.total)"></span>
                            </div>
                            <div class="flex gap-2">
                                <button @click="muatKeranjang(idx)"
                                        class="flex-1 bg-slate-700 hover:bg-slate-600 text-white text-[10px] font-bold py-2 rounded-xl transition-all flex items-center justify-center gap-1">
                                    <i data-lucide="edit-3" class="w-3 h-3"></i> Edit
                                </button>
                                <button @click="bayarSaved(idx)" :disabled="loading"
                                        class="flex-1 bg-gradient-to-r from-purple-600 to-blue-600 text-white text-[10px] font-black py-2 rounded-xl transition-all disabled:opacity-30 flex items-center justify-center gap-1 active:scale-95">
                                    <i data-lucide="check-circle" class="w-3 h-3"></i> Bayar
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── TOAST NOTIFIKASI ──────────────────────────────────────────── --}}
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

    {{-- ─── MODAL KONFIRMASI MUAT KERANJANG ───────────────────────────── --}}
    <div x-show="confirmLoad.show" x-cloak class="fixed inset-0 z-[150] flex items-center justify-center p-4">
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

    {{-- ─── MODAL SUKSES ──────────────────────────────────────────────── --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-[#020617]/90 backdrop-blur-xl" @click="resetPage()"></div>
        <div x-show="showModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative bg-white rounded-[3rem] shadow-2xl w-full max-w-sm p-12 text-center">
            <div class="w-20 h-20 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-8">
                <i data-lucide="check" class="w-12 h-12 stroke-[4]"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-900 mb-2">Berhasil! 🎉</h3>
            <p class="text-slate-500 font-medium text-sm leading-relaxed mb-10">
                Terima kasih <span class="font-bold text-indigo-600" x-text="namaPembeli"></span>!
                Transaksi kamu sudah tercatat. Silakan ambil jajannya ya!
            </p>
            <button @click="resetPage()"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-[1.5rem] font-bold text-sm transition-all active:scale-95 shadow-xl shadow-indigo-100 uppercase tracking-widest">
                Oke, Siap!
            </button>
        </div>
    </div>

    <script>
        lucide.createIcons();

        /* ═══════════════════════════════════════════
           THEME SYSTEM
        ═══════════════════════════════════════════ */
        const THEME_KEY = 'snack_theme';

        function applyTheme(theme) {
            const body = document.body;
            const label = document.getElementById('toggleLabel');
            body.classList.remove('theme-dark', 'theme-light');
            body.classList.add('theme-' + theme);
            if (label) {
                label.textContent = theme === 'light' ? '🌙 Gelap' : '☀️ Terang';
            }
        }

        function toggleTheme() {
            const isDark = document.body.classList.contains('theme-dark');
            const next = isDark ? 'light' : 'dark';
            applyTheme(next);
            try { localStorage.setItem(THEME_KEY, next); } catch(e) {}
        }

        // Load saved theme on page load (before Alpine boots)
        (function() {
            try {
                const saved = localStorage.getItem(THEME_KEY);
                if (saved === 'light') {
                    applyTheme('light');
                }
            } catch(e) {}
        })();

        /* ═══════════════════════════════════════════
           CART SYSTEM (Alpine)
        ═══════════════════════════════════════════ */
        function cartSystem() {
            return {
                namaPembeli: '',
                search: '',
                metodeBayar: 'cash',
                cart: {},
                totalHarga: 0,
                totalItems: 0,
                loading: false,
                showModal: false,
                showNameWarning: false,
                shakeName: false,

                activeTab: 'current',
                savedCarts: [],
                loadedFromSaved: null,
                toast: { show: false, message: '', type: 'success', _timer: null },
                confirmLoad: { show: false, idx: null },

                drawerOpen: false,

                init() {
                    try {
                        const stored = localStorage.getItem('snack_saved_carts');
                        if (stored) this.savedCarts = JSON.parse(stored);
                    } catch(e) { this.savedCarts = []; }
                    this.$nextTick(() => lucide.createIcons());
                },

                onNamaChange() {
                    if (this.showNameWarning && this.namaPembeli.trim()) {
                        this.showNameWarning = false;
                    }
                },

                add(id, name, price, stok) {
                    const cleanId = String(id);
                    if (!this.cart[cleanId]) {
                        this.cart[cleanId] = { name: name, price: Number(price), qty: 0 };
                    }
                    if (this.cart[cleanId].qty >= Number(stok)) return;
                    this.cart[cleanId].qty++;
                    this.sync();
                },

                remove(id) {
                    const cleanId = String(id);
                    if (this.cart[cleanId] && this.cart[cleanId].qty > 0) {
                        this.cart[cleanId].qty--;
                        if (this.cart[cleanId].qty <= 0) {
                            this.hapusTotal(cleanId);
                        } else {
                            this.sync();
                        }
                    }
                },

                hapusTotal(id) {
                    const cleanId = String(id);
                    delete this.cart[cleanId];
                    this.sync();
                },

                sync() {
                    this.cart = Object.assign({}, this.cart);
                    this.calculate();
                },

                calculate() {
                    let total = 0, items = 0;
                    Object.values(this.cart).forEach(item => {
                        if (item && item.qty > 0) {
                            total += Number(item.price) * Number(item.qty);
                            items += Number(item.qty);
                        }
                    });
                    this.totalHarga = isNaN(total) ? 0 : total;
                    this.totalItems = isNaN(items) ? 0 : items;
                    this.$nextTick(() => lucide.createIcons());
                },

                count(id) {
                    const cleanId = String(id);
                    return this.cart[cleanId] ? this.cart[cleanId].qty : 0;
                },

                formatRupiah(number) {
                    const val = Number(number);
                    if (isNaN(val)) return 'Rp 0';
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency', currency: 'IDR', minimumFractionDigits: 0
                    }).format(val);
                },

                showToast(message, type = 'success', duration = 2500) {
                    if (this.toast._timer) clearTimeout(this.toast._timer);
                    this.toast.message = message;
                    this.toast.type = type;
                    this.toast.show = true;
                    this.toast._timer = setTimeout(() => { this.toast.show = false; }, duration);
                },

                simpanKeranjang() {
                    if (!this.namaPembeli.trim()) {
                        this.showNameWarning = true;
                        this.shakeName = true;
                        setTimeout(() => { this.shakeName = false; this.showNameWarning = false; }, 2500);
                        return;
                    }
                    if (this.totalItems === 0) return;

                    const namaLower = this.namaPembeli.trim().toLowerCase();
                    const existingIdx = this.savedCarts.findIndex(
                        s => s.nama.toLowerCase() === namaLower
                    );

                    const savedObj = {
                        nama: this.namaPembeli.trim(),
                        cart: JSON.parse(JSON.stringify(this.cart)),
                        total: this.totalHarga,
                        metode: this.metodeBayar,
                        waktu: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
                    };

                    if (existingIdx >= 0) {
                        this.savedCarts.splice(existingIdx, 1, savedObj);
                        this.showToast('Keranjang "' + savedObj.nama + '" diperbarui ✅');
                    } else {
                        this.savedCarts.push(savedObj);
                        this.showToast('Keranjang "' + savedObj.nama + '" disimpan 🔖');
                    }

                    this._persistSaved();
                    this.cart = {};
                    this.namaPembeli = '';
                    this.metodeBayar = 'cash';
                    this.loadedFromSaved = null;
                    this.calculate();
                    this.activeTab = 'saved';
                    this.$nextTick(() => lucide.createIcons());
                },

                muatKeranjang(idx) {
                    if (this.totalItems > 0) {
                        this.confirmLoad.show = true;
                        this.confirmLoad.idx = idx;
                        this.$nextTick(() => lucide.createIcons());
                        return;
                    }
                    this._doMuat(idx);
                },

                konfirmasiMuat() {
                    this.confirmLoad.show = false;
                    this._doMuat(this.confirmLoad.idx);
                },

                _doMuat(idx) {
                    const saved = this.savedCarts[idx];
                    if (!saved) return;
                    this.cart = JSON.parse(JSON.stringify(saved.cart));
                    this.namaPembeli = saved.nama;
                    this.metodeBayar = saved.metode;
                    this.loaodedFromSaved = idx;
                    this.calculate();
                    this.activeTab = 'current';
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
                    const saved = this.savedCarts[idx];
                    if (!saved) return;

                    const prevCart   = this.cart;
                    const prevNama   = this.namaPembeli;
                    const prevMetode = this.metodeBayar;
                    const prevTotal  = this.totalHarga;
                    const prevItems  = this.totalItems;

                    this.cart        = saved.cart;
                    this.namaPembeli = saved.nama;
                    this.metodeBayar = saved.metode;
                    this.totalHarga  = saved.total;
                    this.totalItems  = Object.values(saved.cart).reduce((a, i) => a + i.qty, 0);

                    await this.submitForm(true);

                    if (this.showModal) {
                        this.savedCarts.splice(idx, 1);
                        this._persistSaved();
                    } else {
                        this.cart        = prevCart;
                        this.namaPembeli = prevNama;
                        this.metodeBayar = prevMetode;
                        this.totalHarga  = prevTotal;
                        this.totalItems  = prevItems;
                    }
                },

                _persistSaved() {
                    try {
                        localStorage.setItem('snack_saved_carts', JSON.stringify(this.savedCarts));
                    } catch(e) {}
                },

                async submitForm(skipValidasi = false) {
                    if (!skipValidasi) {
                        if (!this.namaPembeli.trim()) {
                            this.showNameWarning = true;
                            this.shakeName = true;
                            setTimeout(() => { this.shakeName = false; this.showNameWarning = false; }, 2500);
                            return;
                        }
                        if (this.totalItems === 0) return;
                    }

                    this.loading = true;
                    this.showNameWarning = false;

                    const payload = {
                        nama_pembeli: this.namaPembeli.trim(),
                        metode_bayar: this.metodeBayar,
                        items: Object.entries(this.cart)
                            .filter(([, item]) => item.qty > 0)
                            .map(([id, item]) => ({
                                product_id: id,
                                jumlah: item.qty
                            })),
                        _token: document.querySelector('meta[name="csrf-token"]').content
                    };

                    try {
                        const response = await fetch("{{ route('transaksi.store') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': payload._token
                            },
                            body: JSON.stringify(payload)
                        });

                        if (response.ok) {
                            if (this.loadedFromSaved !== null) {
                                this.savedCarts.splice(this.loadedFromSaved, 1);
                                this._persistSaved();
                                this.loadedFromSaved = null;
                            }
                            this.showModal = true;
                            this.drawerOpen = false;
                        } else {
                            const err = await response.json().catch(() => ({}));
                            alert('Transaksi gagal: ' + (err.message || response.statusText));
                        }
                    } catch (error) {
                        console.error('Fetch error:', error);
                        alert('Gagal terhubung ke server. Periksa koneksi kamu.');
                    } finally {
                        this.loading = false;
                    }
                },

                resetPage() {
                    window.location.reload();
                }
            }
        }
    </script>
</body>
</html>