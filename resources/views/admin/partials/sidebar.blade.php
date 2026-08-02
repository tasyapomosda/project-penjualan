<aside class="w-72 bg-white border-r border-slate-100 flex flex-col p-8 z-20 h-screen fixed left-0 top-0">
    <div class="flex items-center gap-3 mb-12">
        <div class="bg-[#6366f1] p-2 rounded-xl shadow-lg shadow-indigo-100">
            <i data-lucide="cookie" class="text-white w-6 h-6"></i>
        </div>
        <div>
            <span class="text-xl font-extrabold text-slate-800 tracking-tight leading-none block">Showcase</span>
            <p class="text-slate-400 text-[10px] uppercase tracking-widest font-bold">Snack Admin</p>
        </div>
    </div>

    <nav class="flex-1 space-y-2">
        @php
            $isActive = function($routeName) {
                return request()->routeIs($routeName)
                    ? 'bg-indigo-50 text-indigo-600 font-bold shadow-sm'
                    : 'text-slate-400 hover:text-indigo-600 hover:bg-indigo-50/50 font-semibold';
            };
        @endphp

        <p class="text-slate-400 text-[10px] uppercase tracking-widest font-bold px-3 mb-2">Menu</p>

        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all {{ $isActive('admin.dashboard') }}">
            <i data-lucide="layout-grid" class="w-5 h-5"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.harian') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all {{ $isActive('admin.harian') }}">
            <i data-lucide="calendar" class="w-5 h-5"></i>
            <span>Transaksi Harian</span>
        </a>

        <a href="{{ route('admin.stok') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all {{ $isActive('admin.stok') }}">
            <i data-lucide="package" class="w-5 h-5"></i>
            <span>Stok Barang</span>
        </a>

        <a href="{{ route('admin.pendapatan') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all {{ $isActive('admin.pendapatan') }}">
            <i data-lucide="banknote" class="w-5 h-5"></i>
            <span>Rekap Pendapatan</span>
        </a>

        <a href="{{ route('admin.hutang') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all {{ $isActive('admin.hutang') }}">
            <i data-lucide="book-open" class="w-5 h-5"></i>
            <span>Rekap Hutang</span>
        </a>

        {{-- ── Laporan Penjualan (baru) ── --}}
        <a href="{{ route('admin.laporan') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all {{ $isActive('admin.laporan') }}">
            <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
            <span>Laporan Penjualan</span>
        </a>

    </nav>

    <form action="{{ route('logout') }}" method="POST" class="mt-auto">
        @csrf
        <button type="submit" class="flex items-center gap-4 px-4 py-4 w-full rounded-2xl text-rose-500 hover:bg-rose-50 transition-all font-bold">
            <i data-lucide="log-out" class="w-5 h-5"></i>
            <span>Keluar</span>
        </button>
    </form>
</aside>