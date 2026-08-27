{{--
    PARTIAL: cart-tabs.blade.php
    Lokasi: resources/views/catalog/partials/cart-tabs.blade.php
    Di-include di sidebar desktop dan bottom drawer mobile
--}}
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