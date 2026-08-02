function cartSystem() {
    return {
        namaPembeli: '',
        search: '',
        cart: {},
        totalHarga: 0,
        totalItems: 0,

        add(id, name, price) {
            // Pastikan price adalah angka murni
            const p = Number(price);
            if (!this.cart[id]) {
                this.cart[id] = { name: name, price: p, qty: 0 };
            }
            this.cart[id].qty++;
            this.calculate();
        },

        remove(id) {
            if (this.cart[id]) {
                this.cart[id].qty--;
                if (this.cart[id].qty <= 0) {
                    this.hapusTotal(id);
                } else {
                    this.calculate();
                }
            }
        },

        // INI PEMBERSIH UTAMANYA
        hapusTotal(id) {
            // 1. Hapus dari objek
            delete this.cart[id];
            
            // 2. Trik Pamungkas: Re-assign objek agar Alpine "bangun"
            // Kita buat salinan baru yang benar-benar bersih dari ID tersebut
            this.cart = Object.assign({}, this.cart); 
            
            // 3. Hitung ulang total
            this.calculate();
        },

        calculate() {
            let total = 0;
            let items = 0;

            // Looping hanya pada item yang VALID
            Object.keys(this.cart).forEach(id => {
                const item = this.cart[id];
                if (item && item.qty > 0) {
                    total += (Number(item.price) * Number(item.qty));
                    items += Number(item.qty);
                }
            });

            // Pastikan hasil akhirnya angka, kalau error kasih 0
            this.totalHarga = isNaN(total) ? 0 : total;
            this.totalItems = isNaN(items) ? 0 : items;

            this.$nextTick(() => { lucide.createIcons(); });
        },

        formatRupiah(number) {
            const val = Number(number);
            if (isNaN(val)) return 'Rp 0';
            return new Intl.NumberFormat('id-ID', { 
                style: 'currency', 
                currency: 'IDR', 
                minimumFractionDigits: 0 
            }).format(val);
        },

        count(id) {
            return (this.cart[id] && this.cart[id].qty) ? this.cart[id].qty : 0;
        }
    }

    function selesaikanPembelian() {
    // 1. Ambil data pesanan kamu
    // 2. Kirim via Fetch/AJAX ke Controller
    fetch('/proses-transaksi', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(dataPesanan)
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            tampilkanNotifSukses();
        }
    });
}

    function tampilkanNotifSukses() {
        const modal = document.getElementById('modalSukses');
        const content = document.getElementById('modalContent');
        
        modal.classList.remove('hidden');
        // Animasi sedikit biar smooth
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function tutupModalSukses() {
        // Refresh halaman atau reset keranjang belanja
        window.location.reload(); 
    }
}