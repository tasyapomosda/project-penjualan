{{--
    PARTIAL: cart-panel.blade.php
    Lokasi: resources/views/catalog/partials/cart-panel.blade.php
    Di-include di sidebar desktop dan bottom drawer mobile
    $context = 'desktop' | 'mobile'
--}}
@php
    $isDesktop  = ($context ?? 'desktop') === 'desktop';
    $px         = $isDesktop ? 'px-6 pb-6'   : 'px-5 pb-5';
    $scrollH    = $isDesktop ? ''             : 'max-h-[45vh]';
    $totalSize  = $isDesktop ? 'text-2xl'     : 'text-lg';
@endphp

{{-- ── TAB: Keranjang Aktif ── --}}
<div x-show="activeTab === 'current'" class="flex flex-col flex-1 overflow-hidden {{ $px }}">
    <div class="flex-1 overflow-y-auto custom-scrollbar pr-1 mb-3 {{ $scrollH }} min-h-[100px]">
        <template x-if="totalItems === 0">
            <div class="flex flex-col items-center justify-center h-36 opacity-20 italic">
                <i data-lucide="shopping-basket" class="w-10 h-10 mb-2"></i>
                <p class="text-xs">Belum ada barang dipilih</p>
            </div>
        </template>
        <div class="space-y-3">
            <template x-for="[id, item] in Object.entries(cart)" :key="id">
                <div x-show="item.qty > 0"
                     class="bg-slate-900/50 rounded-2xl p-4 border border-slate-800">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-sm font-bold text-white tracking-tight" x-text="item.name"></span>
                        <button @click="hapusTotal(id)"
                                class="text-slate-600 hover:text-red-400 transition-all ml-2 flex-shrink-0">
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

    <div class="pt-3 border-t border-slate-800 space-y-2.5 flex-shrink-0">
        {{-- Total --}}
        <div class="flex justify-between items-end">
            <div>
                <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Total Bayar</p>
                <h3 class="{{ $totalSize }} font-black text-white" x-text="formatRupiah(totalHarga)">Rp0</h3>
            </div>
            <span class="bg-purple-600/20 text-purple-400 text-[10px] font-bold px-3 py-1 rounded-full border border-purple-500/30"
                  x-text="totalItems + ' Items'"></span>
        </div>

        {{-- Metode Bayar --}}
        <div class="custom-dropdown" x-data="{ open: false }">
            <button type="button" @click="open = !open" @click.outside="open = false"
                class="w-full bg-slate-900 border border-slate-700 rounded-2xl px-4 py-3 text-xs text-white font-bold flex items-center justify-between cursor-pointer hover:border-slate-500 transition-all">
                <span x-text="metodeBayar === 'qris' ? 'QRIS' : 'Cash'"></span>
                <svg :class="open ? 'rotate-180' : ''" class="transition-transform w-3.5 h-3.5 text-slate-400 flex-shrink-0"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div x-show="open" x-cloak class="custom-dropdown-menu">
                    <div class="custom-dropdown-option" :class="metodeBayar === 'cash' ? 'selected' : ''" @click="metodeBayar = 'cash'; open = false"> Cash</div>
                    <div class="custom-dropdown-option" :class="metodeBayar === 'qris' ? 'selected' : ''" @click="metodeBayar = 'qris'; open = false"> QRIS</div>
                </div>
        </div>

        {{-- Warning nama --}}
        <p x-show="showNameWarning" x-cloak
           class="text-red-400 text-[10px] font-bold text-center tracking-widest uppercase">
            ⚠️ Isi nama pembeli terlebih dahulu!
        </p>

        {{-- Tombol aksi --}}
        <div class="flex gap-2">
            <button @click="simpanKeranjang()"
                    :disabled="totalItems === 0"
                    title="Draft Transaksi"
                    class="flex-shrink-0 bg-slate-700 hover:bg-slate-600 border border-slate-600 px-4 py-3 rounded-xl text-white disabled:opacity-30 transition-all flex items-center gap-2 active:scale-95">
                <i data-lucide="bookmark" class="w-4 h-4"></i>
                @if($isDesktop)<span class="text-xs font-bold uppercase tracking-widest">Draft</span>@endif
            </button>
            <button @click="submitForm()"
                    :disabled="totalItems === 0 || loading"
                    class="flex-1 bg-gradient-to-r from-purple-600 to-blue-600 py-3 rounded-xl text-white font-black text-xs uppercase tracking-wide shadow-xl disabled:opacity-30 disabled:grayscale transition-all flex items-center justify-center gap-2 active:scale-95">
                <template x-if="!loading"><span>{{ $isDesktop ? 'Konfirmasi Pembelian' : 'Selesaikan Pembelian' }}</span></template>
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

{{-- ── TAB: Keranjang Disimpan ── --}}
<div x-show="activeTab === 'saved'" class="flex flex-col flex-1 overflow-hidden {{ $px }}">
    <div class="flex-1 overflow-y-auto custom-scrollbar pr-1 {{ $scrollH }}">
        <template x-if="savedCarts.length === 0">
            <div class="flex flex-col items-center justify-center h-36 opacity-20 italic">
                <i data-lucide="bookmark" class="w-10 h-10 mb-2"></i>
                <p class="text-xs">Belum ada keranjang disimpan</p>
            </div>
        </template>
        <div class="space-y-3 mt-1">
            <template x-for="(saved, idx) in savedCarts" :key="idx">
                <div class="bg-slate-900/60 rounded-2xl p-4 border border-slate-700 hover:border-purple-500/40 transition-all">
                    {{-- Header saved cart --}}
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

                    {{-- Item list --}}
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

                    {{-- Metode + Total --}}
                    <div class="flex justify-between items-center mb-3 pt-2 border-t border-slate-800 gap-2">
                        <div class="custom-dropdown flex-1" x-data="{ open: false }">
                            <button type="button" @click="open = !open" @click.outside="open = false"
                                class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2 py-1.5 text-[10px] text-white font-bold flex items-center justify-between cursor-pointer hover:border-slate-500 transition-all">
                                <span x-text="saved.metode === 'qris' ? 'QRIS' : 'Cash'"></span>
                                <svg :class="open ? 'rotate-180' : ''" class="transition-transform w-3 h-3 text-slate-400"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                                </button>
                                <div x-show="open" x-cloak class="custom-dropdown-menu">
                                    <div class="custom-dropdown-option" :class="saved.metode === 'cash' ? 'selected' : ''" @click="saved.metode = 'cash'; open = false; _persistSaved()"> Cash</div>
                                    <div class="custom-dropdown-option" :class="saved.metode === 'qris' ? 'selected' : ''" @click="saved.metode = 'qris'; open = false; _persistSaved()"> QRIS</div>
                                </div>
                        </div>
                        <span class="text-sm font-black text-white flex-shrink-0"
                              x-text="formatRupiah(saved.total)"></span>
                    </div>

                    {{-- Tombol Edit / Bayar --}}
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