<div class="space-y-6" x-data="{
    activeTab: 'tracker',
    searchQuery: '',
    selectedCategory: 'all',
    showBookingModal: false,
    selectedItem: null,
    bookingKg: 200,
    catalog: [
        {
            id: 1,
            commodity: 'Cabai Rawit Merah (Ori 212)',
            category: 'bumbu',
            farmer: 'Pak Raden (Ambarawa)',
            location: 'Malang (18 km)',
            total_kg: 500,
            available_kg: 0,
            price_kg: 25000,
            harvest_date: '24 Ags 2026',
            status: 'Terkontrak',
            isContracted: true,
            img: 'https://images.unsplash.com/photo-1588252303782-cb80119abd6d?auto=format&fit=crop&w=600&q=80'
        },
        {
            id: 2,
            commodity: 'Tomat Merah Unggul',
            category: 'sayur',
            farmer: 'Poktan Candi Makmur',
            location: 'Sidoarjo (28 km)',
            total_kg: 300,
            available_kg: 50,
            price_kg: 12000,
            harvest_date: '10 Sep 2026',
            status: 'Sisa 50 kg',
            isContracted: false,
            img: 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=600&q=80'
        },
        {
            id: 3,
            commodity: 'Bawang Merah Super',
            category: 'bumbu',
            farmer: 'Gapoktan Sukamaju',
            location: 'Probolinggo (45 km)',
            total_kg: 1000,
            available_kg: 600,
            price_kg: 28000,
            harvest_date: '01 Okt 2026',
            status: 'Tersedia',
            isContracted: false,
            img: 'https://images.unsplash.com/photo-1618512496248-a07fe83aa8cb?auto=format&fit=crop&w=600&q=80'
        },
        {
            id: 4,
            commodity: 'Jagung Manis Madu',
            category: 'palawija',
            farmer: 'Bapak Slamet (Pujon)',
            location: 'Batu (22 km)',
            total_kg: 2000,
            available_kg: 1200,
            price_kg: 8500,
            harvest_date: '15 Okt 2026',
            status: 'Tersedia',
            isContracted: false,
            img: 'https://images.unsplash.com/photo-1551754655-cd27e38d2076?auto=format&fit=crop&w=600&q=80'
        }
    ],
    openBooking(item) {
        this.selectedItem = item;
        this.bookingKg = item.available_kg > 0 ? item.available_kg : 100;
        this.showBookingModal = true;
    },
    confirmBooking() {
        this.showBookingModal = false;
        notify('Pre-Order berhasil dikonfirmasi! DP 30% diamankan di Escrow TaniLink.', 'success');
    }
}">

    <!-- Profile Header Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 sm:p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-cyan-600 to-[#05C793] flex items-center justify-center text-white text-lg font-bold shadow-md shadow-cyan-500/20">
                <i class="fa-solid fa-hotel"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-lg font-black text-slate-900">Grand Hotel Malang</h3>
                    <span class="px-2.5 py-0.5 rounded-full bg-cyan-50 text-cyan-700 border border-cyan-200 text-[10px] font-extrabold uppercase">
                        Purchasing F&B
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-0.5">Maya Anggraini • Pasokan Bahan Baku Terkontrak</p>
            </div>
        </div>

        <!-- Subtabs -->
        <div class="flex items-center p-1 bg-slate-100 rounded-2xl text-xs font-bold w-full sm:w-auto">
            <button @click="activeTab = 'tracker'" :class="activeTab === 'tracker' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="flex-1 sm:flex-initial px-3.5 py-2 rounded-xl transition-all cursor-pointer">
                Lacak Progres
            </button>
            <button @click="activeTab = 'catalog'" :class="activeTab === 'catalog' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="flex-1 sm:flex-initial px-3.5 py-2 rounded-xl transition-all cursor-pointer">
                Katalog Pasokan
            </button>
            <button @click="activeTab = 'escrow'" :class="activeTab === 'escrow' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="flex-1 sm:flex-initial px-3.5 py-2 rounded-xl transition-all cursor-pointer">
                Escrow
            </button>
        </div>
    </div>

    <!-- 4 Clean Metric Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Kontrak Aktif</span>
            <div class="text-lg sm:text-2xl font-black text-slate-900 mt-1">500 <span class="text-sm font-bold text-slate-400">kg</span></div>
            <span class="text-[11px] font-semibold text-[#05C793] mt-1 block">Cabai Rawit (Pak Raden)</span>
        </div>

        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Harga Terkunci</span>
            <div class="text-lg sm:text-2xl font-black text-slate-900 mt-1">Rp 25.000 <span class="text-xs font-bold text-slate-400">/kg</span></div>
            <span class="text-[11px] font-semibold text-cyan-600 mt-1 block">Bebas fluktuasi pasar</span>
        </div>

        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Dana Escrow</span>
            <div class="text-lg sm:text-2xl font-black text-slate-900 mt-1">Rp 12.500.000</div>
            <span class="text-[11px] text-slate-500 mt-1 block">DP 30% Cair • 70% Aman</span>
        </div>

        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Waktu Panen</span>
            <div class="text-lg sm:text-2xl font-black text-slate-900 mt-1">H - 14 <span class="text-sm font-bold text-slate-400">Hari</span></div>
            <span class="text-[11px] text-slate-500 mt-1 block">24 Agustus 2026</span>
        </div>
    </div>

    <!-- TAB 1: TRACKER PROGRES -->
    <div x-show="activeTab === 'tracker'" class="space-y-6">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-5 sm:p-7 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                <div>
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-[#05C793] border border-emerald-200 text-[10px] font-extrabold uppercase">
                        Kontrak Berjalan
                    </span>
                    <h4 class="text-xl font-black text-slate-900 mt-1">Cabai Rawit Merah (500 kg)</h4>
                    <p class="text-xs text-slate-400">Lahan Pak Raden, Ambarawa • 18 km ke Dapur Hotel</p>
                </div>
                <div class="text-left sm:text-right">
                    <span class="text-[11px] text-slate-400 font-semibold block uppercase">Status Saat Ini</span>
                    <span class="text-base font-black text-[#05C793]">Fase Pembungaan 60%</span>
                </div>
            </div>

            <!-- 4 Visual Stepper Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="p-3.5 rounded-2xl bg-emerald-50/60 border border-emerald-100">
                    <span class="text-[10px] font-bold text-[#05C793] block">12 Mei</span>
                    <h5 class="font-extrabold text-xs text-slate-900 mt-0.5">1. Benih & Semai</h5>
                    <span class="inline-block mt-2 px-2 py-0.5 rounded text-[9px] font-extrabold bg-[#05C793] text-white">SELESAI</span>
                </div>

                <div class="p-3.5 rounded-2xl bg-emerald-50/60 border border-emerald-100">
                    <span class="text-[10px] font-bold text-[#05C793] block">15 Juni</span>
                    <h5 class="font-extrabold text-xs text-slate-900 mt-0.5">2. Vegetatif</h5>
                    <span class="inline-block mt-2 px-2 py-0.5 rounded text-[9px] font-extrabold bg-[#05C793] text-white">SELESAI</span>
                </div>

                <div class="p-3.5 rounded-2xl bg-brand-gradient text-white shadow-sm">
                    <span class="text-[10px] font-bold text-white/80 block">Aktif (H-14)</span>
                    <h5 class="font-extrabold text-xs text-white mt-0.5">3. Bunga (60%)</h5>
                    <span class="inline-block mt-2 px-2 py-0.5 rounded text-[9px] font-extrabold bg-white text-slate-900">BERJALAN</span>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="text-[10px] font-bold text-slate-400 block">24 Ags</span>
                    <h5 class="font-extrabold text-xs text-slate-700 mt-0.5">4. Panen Raya</h5>
                    <span class="inline-block mt-2 px-2 py-0.5 rounded text-[9px] font-extrabold bg-slate-200 text-slate-600">MENUNGGU</span>
                </div>
            </div>

            <!-- Dokumentasi Foto Lapangan -->
            <div class="pt-2">
                <h5 class="font-extrabold text-sm text-slate-900 mb-3">Foto Lapangan Terverifikasi dari Petani</h5>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-3 rounded-2xl border border-slate-100 bg-slate-50/50 flex gap-3">
                        <img src="https://images.unsplash.com/photo-1592417817098-8f3d6910985b?auto=format&fit=crop&w=400&q=80" class="w-24 h-20 rounded-xl object-cover">
                        <div>
                            <span class="text-[10px] font-bold text-[#05C793] block">Kemarin, 08:30 WIB</span>
                            <h6 class="font-bold text-xs text-slate-900">Pembungaan & Buah 60%</h6>
                            <p class="text-[11px] text-slate-500 mt-0.5">Tanaman tumbuh subur, buah memerah merata.</p>
                        </div>
                    </div>

                    <div class="p-3 rounded-2xl border border-slate-100 bg-slate-50/50 flex gap-3">
                        <img src="https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&w=400&q=80" class="w-24 h-20 rounded-xl object-cover">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 block">15 Juni 2026</span>
                            <h6 class="font-bold text-xs text-slate-900">Pertunasan Vegetatif</h6>
                            <p class="text-[11px] text-slate-500 mt-0.5">Tunas produktif sehat tanpa hama patek.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 2: KATALOG PASOKAN -->
    <div x-show="activeTab === 'catalog'" class="space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <template x-for="item in catalog" :key="item.id">
                <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="h-36 w-full bg-slate-100 relative">
                            <img :src="item.img" class="w-full h-full object-cover">
                            <span class="absolute top-2.5 right-2.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold"
                                  :class="item.isContracted ? 'bg-[#05C793] text-white' : 'bg-white/90 text-slate-800'"
                                  x-text="item.status"></span>
                        </div>
                        <div class="p-4">
                            <h5 class="font-black text-sm text-slate-900 truncate" x-text="item.commodity"></h5>
                            <p class="text-[11px] text-slate-400 mt-0.5" x-text="item.farmer + ' • ' + item.location"></p>
                            <div class="mt-3 flex justify-between p-2.5 rounded-xl bg-slate-50 text-xs">
                                <div>
                                    <span class="text-slate-400 block text-[10px]">Harga</span>
                                    <strong class="text-slate-800" x-text="'Rp ' + item.price_kg.toLocaleString('id-ID') + '/kg'"></strong>
                                </div>
                                <div class="text-right">
                                    <span class="text-slate-400 block text-[10px]">Panen</span>
                                    <strong class="text-[#05C793]" x-text="item.harvest_date"></strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 pt-0">
                        <template x-if="item.isContracted">
                            <button @click="activeTab = 'tracker'" class="w-full py-2 rounded-xl bg-slate-100 text-xs font-bold text-slate-700 hover:bg-slate-200 cursor-pointer">
                                Lacak Progres
                            </button>
                        </template>
                        <template x-if="!item.isContracted">
                            <button @click="openBooking(item)" class="w-full py-2 rounded-xl bg-[#05C793] hover:bg-[#04b383] text-white text-xs font-bold shadow-sm cursor-pointer">
                                Booking & Kunci Harga
                            </button>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- TAB 3: ESCROW -->
    <div x-show="activeTab === 'escrow'" class="bg-white rounded-3xl border border-slate-100 shadow-sm p-5 sm:p-7 space-y-4">
        <div>
            <h4 class="font-extrabold text-base text-slate-900">Rekening Bersama Escrow TaniLink</h4>
            <p class="text-xs text-slate-400">Total Dana: <strong>Rp 12.500.000</strong></p>
        </div>

        <div class="space-y-3 text-xs">
            <div class="p-3.5 rounded-2xl bg-emerald-50/60 border border-emerald-100 flex justify-between items-center">
                <div>
                    <strong class="text-slate-900 block">1. Down Payment 30% (Rp 3.750.000)</strong>
                    <span class="text-slate-500">Modal awal benih & pupuk petani</span>
                </div>
                <span class="px-2 py-0.5 rounded text-[9px] font-extrabold bg-[#05C793] text-white">CAIR</span>
            </div>

            <div class="p-3.5 rounded-2xl bg-cyan-50/60 border border-cyan-100 flex justify-between items-center">
                <div>
                    <strong class="text-slate-900 block">2. Fase Perawatan 20% (Rp 2.500.000)</strong>
                    <span class="text-slate-500">Perawatan kalium & pembungaan</span>
                </div>
                <span class="px-2 py-0.5 rounded text-[9px] font-extrabold bg-cyan-600 text-white">TERKUNCI</span>
            </div>

            <div class="p-3.5 rounded-2xl bg-amber-50/60 border border-amber-100 flex justify-between items-center">
                <div>
                    <strong class="text-slate-900 block">3. Pelunasan Panen 50% (Rp 6.250.000)</strong>
                    <span class="text-slate-500">Dicairkan saat cabai tiba di dapur hotel</span>
                </div>
                <span class="px-2 py-0.5 rounded text-[9px] font-extrabold bg-amber-500 text-white">TERKUNCI</span>
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div x-cloak x-show="showBookingModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-sm w-full p-5 shadow-2xl relative" @click.away="showBookingModal = false">
            <button @click="showBookingModal = false" class="absolute top-4 right-4 text-slate-400">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            <h4 class="font-extrabold text-base text-slate-900 mb-1">Booking Pre-Order</h4>
            <p class="text-xs text-slate-400 mb-3" x-text="selectedItem ? selectedItem.commodity : ''"></p>

            <template x-if="selectedItem">
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Volume (kg)</label>
                        <input type="number" x-model="bookingKg" class="w-full p-2 rounded-xl border border-slate-200 font-bold focus:outline-none focus:border-[#05C793]">
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 space-y-1">
                        <div class="flex justify-between">
                            <span>Total Nilai:</span>
                            <strong x-text="'Rp ' + (bookingKg * selectedItem.price_kg).toLocaleString('id-ID')"></strong>
                        </div>
                        <div class="flex justify-between text-[#05C793] font-bold">
                            <span>DP 30% Escrow:</span>
                            <span x-text="'Rp ' + Math.round(bookingKg * selectedItem.price_kg * 0.3).toLocaleString('id-ID')"></span>
                        </div>
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button @click="showBookingModal = false" class="flex-1 py-2 rounded-xl border border-slate-200 font-bold text-slate-600">Batal</button>
                        <button @click="confirmBooking" class="flex-1 py-2 rounded-xl bg-[#05C793] text-white font-bold shadow-md">Konfirmasi</button>
                    </div>
                </div>
            </template>
        </div>
    </div>

</div>
