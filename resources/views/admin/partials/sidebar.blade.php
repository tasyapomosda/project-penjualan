{{--
    ADMIN SIDEBAR & MOBILE NAVIGATION
    Fitur:
    1. Scrollable (overflow-y-auto sidebar-scroll) agar semua menu dapat diakses pada layar pendek/laptop.
    2. Minimalis (Collapsible): tombol di desktop untuk mengecilkan sidebar jadi icon-only (w-20), memperluas area dashboard.
    3. Fleksibel Mobile: off-canvas drawer dengan backdrop blur & mobile topbar di handphone.
    4. State collapse tersimpan di localStorage.
--}}

@php
    $isActive = function($routeName) {
        return request()->routeIs($routeName)
            ? 'bg-indigo-50 dark:bg-purple-600/20 text-indigo-600 dark:text-purple-400 font-bold shadow-sm'
            : 'text-slate-400 hover:text-indigo-600 hover:bg-indigo-50/50 dark:hover:text-white dark:hover:bg-slate-800 font-semibold';
    };
@endphp

<!-- CSS untuk Collapsible & Responsive Mobile -->
<style>
    /* Transisi Halus */
    #adminSidebar {
        transition: width 0.28s cubic-bezier(0.4, 0, 0.2, 1), transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
    }
    #mainContent {
        transition: margin-left 0.28s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Custom Scrollbar Khusus Navigasi */
    .sidebar-scroll::-webkit-scrollbar {
        width: 4px;
    }
    .sidebar-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .sidebar-scroll::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 9999px;
    }
    .sidebar-scroll::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }

    /* ── MODE MINIMALIS / COLLAPSED (DESKTOP) ── */
    @media (min-width: 768px) {
        body.sidebar-collapsed #adminSidebar {
            width: 5rem !important; /* w-20 = 80px */
        }
        body.sidebar-collapsed #mainContent {
            margin-left: 5rem !important;
        }
        body.sidebar-collapsed .sidebar-text {
            display: none !important;
        }
        body.sidebar-collapsed .sidebar-brand-full {
            display: none !important;
        }
        body.sidebar-collapsed .sidebar-brand-mini {
            display: flex !important;
        }
        body.sidebar-collapsed .sidebar-nav-item {
            justify-content: center !important;
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
            border-radius: 1rem !important;
        }
        body.sidebar-collapsed .sidebar-section-title {
            display: none !important;
        }
        body.sidebar-collapsed .sidebar-section-divider {
            display: block !important;
        }
        body.sidebar-collapsed #btnCollapseDesktop i {
            transform: rotate(180deg);
        }
    }

    /* ── MODE MOBILE (HANDPHONE < 768px) ── */
    @media (max-width: 767px) {
        #adminSidebar {
            transform: translateX(-100%);
            width: 17.5rem !important; /* 280px */
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
        }
        body.mobile-sidebar-open #adminSidebar {
            transform: translateX(0) !important;
        }
        body.mobile-sidebar-open #adminSidebarBackdrop {
            display: block !important;
        }
        #mainContent {
            margin-left: 0 !important;
            padding: 1rem !important;
            width: 100% !important;
            min-height: calc(100vh - 60px) !important;
            overflow-y: visible !important;
        }
        body {
            overflow-y: auto !important;
            height: auto !important;
            min-height: 100vh !important;
            display: block !important;
        }
    }
</style>

<!-- Script inisialisasi awal (mencegah flickering tampilan di desktop) -->
<script>
    (function() {
        try {
            if (localStorage.getItem('admin_sidebar_collapsed') === 'true' && window.innerWidth >= 768) {
                document.body.classList.add('sidebar-collapsed');
            }
        } catch(e) {}
    })();
</script>

<!-- BACKDROP MOBILE OVERLAY -->
<div id="adminSidebarBackdrop"
     onclick="closeAdminSidebar()"
     class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden md:hidden transition-opacity"></div>

<!-- HEADER KHUSUS MOBILE (HANDPHONE) -->
<header class="md:hidden bg-white/95 dark:bg-[#1e293b]/95 backdrop-blur border-b border-slate-100 dark:border-slate-800 px-4 py-3 sticky top-0 z-30 flex items-center justify-between w-full shadow-sm">
    <div class="flex items-center gap-3">
        <button type="button"
                onclick="openAdminSidebar()"
                class="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 active:scale-95 transition-all"
                title="Buka Menu">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
        <div class="flex items-center gap-2">
            <div class="bg-[#6366f1] p-1.5 rounded-xl text-white shadow-sm">
                <i data-lucide="cookie" class="w-4 h-4"></i>
            </div>
            <div>
                <span class="text-sm font-extrabold text-slate-800 dark:text-white leading-none block">Showcase</span>
                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Admin Panel</span>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-2">
        <a href="{{ url('/') }}" target="_blank"
           class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 px-3 py-1.5 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all active:scale-95">
            <i data-lucide="store" class="w-3.5 h-3.5"></i>
            <span>Showcase</span>
        </a>
    </div>
</header>

<!-- SIDEBAR ASIDE -->
<aside id="adminSidebar"
       class="w-72 bg-white dark:bg-[#1e293b] border-r border-slate-100 dark:border-slate-800 flex flex-col z-50 h-screen fixed left-0 top-0 transition-colors">

    <!-- HEADER SIDEBAR (LOGO & TOGGLE MINIMALIS) -->
    <div class="p-5 border-b border-slate-50 dark:border-slate-800/80 flex items-center justify-between shrink-0">
        <!-- Brand Logo Full (Mode Normal) -->
        <div class="sidebar-brand-full flex items-center gap-3">
            <div class="bg-[#6366f1] dark:bg-purple-600 p-2 rounded-xl shadow-lg shadow-indigo-100 dark:shadow-purple-900/50 text-white shrink-0">
                <i data-lucide="cookie" class="w-6 h-6"></i>
            </div>
            <div class="overflow-hidden">
                <span class="text-lg font-extrabold text-slate-800 dark:text-white tracking-tight leading-none block truncate">Showcase</span>
                <p class="text-slate-400 dark:text-slate-500 text-[10px] uppercase tracking-widest font-bold mt-0.5">Snack Admin</p>
            </div>
        </div>

        <!-- Brand Logo Mini (Mode Minimalis Collapsed) -->
        <div class="sidebar-brand-mini hidden items-center justify-center w-full">
            <div class="bg-[#6366f1] dark:bg-purple-600 p-2 rounded-xl shadow-md text-white shrink-0" title="Showcase Snack Admin">
                <i data-lucide="cookie" class="w-5 h-5"></i>
            </div>
        </div>

        <div class="flex items-center gap-1">
            <!-- Tombol Minimalis/Collapse (Desktop Only) -->
            <button id="btnCollapseDesktop"
                    type="button"
                    onclick="toggleAdminSidebar()"
                    title="Perkecil / Perluas Sidebar"
                    class="hidden md:flex p-2 rounded-xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50/80 dark:hover:text-white dark:hover:bg-slate-800 transition-all active:scale-95">
                <i data-lucide="panel-left-close" class="w-5 h-5 transition-transform duration-300"></i>
            </button>

            <!-- Tombol Tutup (Mobile Only) -->
            <button type="button"
                    onclick="closeAdminSidebar()"
                    title="Tutup Menu"
                    class="md:hidden p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all active:scale-95">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
    </div>

    <!-- AREA MENU NAVIGASI (BISA DI-SCROLL KEBAWAH) -->
    <nav class="flex-1 overflow-y-auto sidebar-scroll p-4 space-y-1.5 min-h-0">

        <!-- Section: Menu -->
        <p class="sidebar-section-title text-slate-400 dark:text-slate-500 text-[10px] uppercase tracking-widest font-bold px-3 pt-1 pb-1">Menu</p>
        <hr class="sidebar-section-divider hidden border-slate-100 dark:border-slate-800 my-2">

        <!-- 1. Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
           title="Dashboard"
           class="sidebar-nav-item flex items-center gap-3.5 px-3.5 py-3 rounded-2xl transition-all {{ $isActive('admin.dashboard') }}">
            <i data-lucide="layout-grid" class="w-5 h-5 shrink-0"></i>
            <span class="sidebar-text text-sm truncate">Dashboard</span>
        </a>

        <!-- 2. Transaksi Harian -->
        <a href="{{ route('admin.harian') }}"
           title="Transaksi Harian"
           class="sidebar-nav-item flex items-center gap-3.5 px-3.5 py-3 rounded-2xl transition-all {{ $isActive('admin.harian') }}">
            <i data-lucide="calendar" class="w-5 h-5 shrink-0"></i>
            <span class="sidebar-text text-sm truncate">Transaksi Harian</span>
        </a>

        <!-- 3. Stok Barang -->
        <a href="{{ route('admin.stok') }}"
           title="Stok Barang"
           class="sidebar-nav-item flex items-center gap-3.5 px-3.5 py-3 rounded-2xl transition-all {{ $isActive('admin.stok') }}">
            <i data-lucide="package" class="w-5 h-5 shrink-0"></i>
            <span class="sidebar-text text-sm truncate">Stok Barang</span>
        </a>

        <!-- 4. Rekap Pendapatan -->
        <a href="{{ route('admin.pendapatan') }}"
           title="Rekap Pendapatan"
           class="sidebar-nav-item flex items-center gap-3.5 px-3.5 py-3 rounded-2xl transition-all {{ $isActive('admin.pendapatan') }}">
            <i data-lucide="banknote" class="w-5 h-5 shrink-0"></i>
            <span class="sidebar-text text-sm truncate">Rekap Pendapatan</span>
        </a>

        <!-- 5. Rekap Hutang -->
        <a href="{{ route('admin.hutang') }}"
           title="Rekap Hutang"
           class="sidebar-nav-item flex items-center gap-3.5 px-3.5 py-3 rounded-2xl transition-all {{ $isActive('admin.hutang') }}">
            <i data-lucide="book-open" class="w-5 h-5 shrink-0"></i>
            <span class="sidebar-text text-sm truncate">Rekap Hutang</span>
        </a>

        <!-- 6. Laporan Penjualan -->
        <a href="{{ route('admin.laporan') }}"
           title="Laporan Penjualan"
           class="sidebar-nav-item flex items-center gap-3.5 px-3.5 py-3 rounded-2xl transition-all {{ $isActive('admin.laporan') }}">
            <i data-lucide="bar-chart-2" class="w-5 h-5 shrink-0"></i>
            <span class="sidebar-text text-sm truncate">Laporan Penjualan</span>
        </a>

        <!-- Section: Akun -->
        <p class="sidebar-section-title text-slate-400 dark:text-slate-500 text-[10px] uppercase tracking-widest font-bold px-3 pt-4 pb-1">Akun</p>
        <hr class="sidebar-section-divider hidden border-slate-100 dark:border-slate-800 my-2">

        <!-- 7. Buka Showcase -->
        <a href="{{ url('/') }}" target="_blank"
           title="Buka Showcase"
           class="sidebar-nav-item flex items-center gap-3.5 px-3.5 py-3 rounded-2xl transition-all text-slate-400 hover:text-indigo-600 hover:bg-indigo-50/50 dark:hover:text-white dark:hover:bg-slate-800 font-semibold">
            <i data-lucide="store" class="w-5 h-5 shrink-0"></i>
            <span class="sidebar-text text-sm truncate">Buka Showcase</span>
            <i data-lucide="external-link" class="sidebar-text w-3.5 h-3.5 ml-auto opacity-50 shrink-0"></i>
        </a>

        <!-- 8. Kelola Staf -->
        <a href="{{ route('admin.staf') }}"
           title="Kelola Staf"
           class="sidebar-nav-item flex items-center gap-3.5 px-3.5 py-3 rounded-2xl transition-all {{ $isActive('admin.staf*') }}">
            <i data-lucide="users" class="w-5 h-5 shrink-0"></i>
            <span class="sidebar-text text-sm truncate">Kelola Staf</span>
        </a>

        <!-- 9. Ganti Password -->
        <a href="{{ route('password.edit') }}"
           title="Ganti Password"
           class="sidebar-nav-item flex items-center gap-3.5 px-3.5 py-3 rounded-2xl transition-all {{ $isActive('password.edit') }}">
            <i data-lucide="key-round" class="w-5 h-5 shrink-0"></i>
            <span class="sidebar-text text-sm truncate">Ganti Password</span>
        </a>

    </nav>

    <!-- FOOTER SIDEBAR (LOGOUT PINNED DI BAWAH) -->
    <div class="p-3 border-t border-slate-100 dark:border-slate-800/80 shrink-0 bg-white dark:bg-[#1e293b]">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                    title="Keluar / Logout"
                    class="sidebar-nav-item flex items-center gap-3.5 px-3.5 py-3 w-full rounded-2xl text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-all font-bold text-sm active:scale-95">
                <i data-lucide="log-out" class="w-5 h-5 shrink-0"></i>
                <span class="sidebar-text truncate">Keluar</span>
            </button>
        </form>
    </div>

</aside>

<!-- JAVASCRIPT LOGIC UNTUK TOGGLE & RESPONSIVE -->
<script>
    function toggleAdminSidebar() {
        const body = document.body;
        const isCollapsed = body.classList.toggle('sidebar-collapsed');
        try {
            localStorage.setItem('admin_sidebar_collapsed', isCollapsed ? 'true' : 'false');
        } catch(e) {}
        if (window.lucide) {
            lucide.createIcons();
        }
    }

    function openAdminSidebar() {
        document.body.classList.add('mobile-sidebar-open');
    }

    function closeAdminSidebar() {
        document.body.classList.remove('mobile-sidebar-open');
    }

    // Tutup sidebar mobile saat menekan tombol ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAdminSidebar();
        }
    });

    // Inisialisasi ikon Lucide
    document.addEventListener('DOMContentLoaded', function() {
        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script>


<!-- JAVASCRIPT LOGIC UNTUK TOGGLE & RESPONSIVE -->
<script>
    function toggleAdminSidebar() {
        const body = document.body;
        const isCollapsed = body.classList.toggle('sidebar-collapsed');
        try {
            localStorage.setItem('admin_sidebar_collapsed', isCollapsed ? 'true' : 'false');
        } catch(e) {}
        if (window.lucide) {
            lucide.createIcons();
        }
    }

    function openAdminSidebar() {
        document.body.classList.add('mobile-sidebar-open');
    }

    function closeAdminSidebar() {
        document.body.classList.remove('mobile-sidebar-open');
    }

    // Tutup sidebar mobile saat menekan tombol ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAdminSidebar();
        }
    });

    // Inisialisasi ikon Lucide
    document.addEventListener('DOMContentLoaded', function() {
        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script>