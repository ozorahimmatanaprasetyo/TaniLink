<div class="space-y-6" x-data="{
    deliveryStatus: 'assigned',
    startPickup() {
        this.deliveryStatus = 'on_way_to_farm';
        notify('Menuju Lahan Pak Raden di Ambarawa.', 'info');
    },
    confirmLoaded() {
        this.deliveryStatus = 'cargo_loaded';
        notify('Muatan 500 kg Cabai terverifikasi! Menuju Grand Hotel Malang.', 'success');
    },
    completeDelivery() {
        this.deliveryStatus = 'delivered';
        notify('Pengantaran sukses! Komoditas diterima oleh pihak hotel.', 'success');
    }
}">

    <!-- Profile Header Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 sm:p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-500 to-[#05C793] flex items-center justify-center text-white text-lg font-bold shadow-md shadow-amber-500/20">
                <i class="fa-solid fa-truck-fast"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-lg font-black text-slate-900">Mas Budi Santoso</h3>
                    <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-extrabold uppercase">
                        Kurir Resmi
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-0.5">Pickup L300 (N 8241 AB) • Malang Raya</p>
            </div>
        </div>

        <div class="px-4 py-2 rounded-2xl bg-slate-50 border border-slate-100 text-left sm:text-right">
            <span class="text-[10px] text-slate-400 font-bold block uppercase">Status Penjemputan</span>
            <span class="text-xs font-black text-slate-800 flex items-center gap-1.5 mt-0.5">
                <span class="w-2 h-2 rounded-full" :class="deliveryStatus === 'delivered' ? 'bg-[#05C793]' : 'bg-amber-500 animate-pulse'"></span>
                <span x-text="deliveryStatus === 'assigned' ? 'Terjadwal (H-14)' : (deliveryStatus === 'on_way_to_farm' ? 'Menuju Lahan' : (deliveryStatus === 'cargo_loaded' ? 'Dalam Perjalanan' : 'Selesai Terkirim'))"></span>
            </span>
        </div>
    </div>

    <!-- 4 Clean Metric Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Jarak Rute</span>
            <div class="text-lg sm:text-2xl font-black text-slate-900 mt-1">18 <span class="text-sm font-bold text-slate-400">km</span></div>
            <span class="text-[11px] font-semibold text-[#05C793] mt-1 block">Rute terpendek geolokasi</span>
        </div>

        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Waktu Tempuh</span>
            <div class="text-lg sm:text-2xl font-black text-slate-900 mt-1">45 <span class="text-sm font-bold text-slate-400">Menit</span></div>
            <span class="text-[11px] font-semibold text-cyan-600 mt-1 block">Bebas antrean lahan</span>
        </div>

        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Muatan Segar</span>
            <div class="text-lg sm:text-2xl font-black text-slate-900 mt-1">500 <span class="text-sm font-bold text-slate-400">kg</span></div>
            <span class="text-[11px] text-slate-500 mt-1 block">25 Krat Standar</span>
        </div>

        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Ongkos Angkut</span>
            <div class="text-lg sm:text-2xl font-black text-slate-900 mt-1">Rp 450.000</div>
            <span class="text-[11px] text-slate-500 mt-1 block">Dijamin via TaniLink</span>
        </div>
    </div>

    <!-- Main Grid: Map & Manifest -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left 7 Cols: Map Preview -->
        <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-between">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h4 class="font-extrabold text-sm text-slate-900">Rute Jemput Panen Langsung</h4>
                    <p class="text-xs text-slate-400">Ambarawa &rarr; Grand Hotel Malang</p>
                </div>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-[#05C793]">GPS Aktif</span>
            </div>

            <!-- Stylized Minimal Map Box -->
            <div class="h-64 sm:h-72 w-full bg-slate-900 relative overflow-hidden flex items-center justify-center">
                <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#05c793_1px,transparent_1px)] [background-size:16px_16px]"></div>
                
                <svg class="absolute inset-0 w-full h-full pointer-events-none" viewBox="0 0 700 350" preserveAspectRatio="none">
                    <path d="M 120 270 Q 350 180, 580 80" fill="none" stroke="#05C793" stroke-width="4" stroke-dasharray="8 6" class="animate-pulse"></path>
                </svg>

                <div class="absolute bottom-6 left-8 text-center">
                    <div class="w-8 h-8 rounded-xl bg-[#05C793] text-white flex items-center justify-center text-xs font-bold mx-auto shadow-md">
                        <i class="fa-solid fa-leaf"></i>
                    </div>
                    <span class="mt-1 bg-slate-950/80 text-white text-[9px] font-bold px-2 py-0.5 rounded block">Lahan Pak Raden</span>
                </div>

                <div class="absolute top-6 right-8 text-center">
                    <div class="w-8 h-8 rounded-xl bg-cyan-600 text-white flex items-center justify-center text-xs font-bold mx-auto shadow-md">
                        <i class="fa-solid fa-hotel"></i>
                    </div>
                    <span class="mt-1 bg-slate-950/80 text-white text-[9px] font-bold px-2 py-0.5 rounded block">Grand Hotel</span>
                </div>
            </div>

            <!-- Action Button Bar -->
            <div class="p-4 sm:p-5 bg-slate-50 flex items-center justify-between gap-3">
                <span class="text-xs text-slate-500 font-medium">Jadwal: <strong>24 Ags, 06:30 WIB</strong></span>

                <div>
                    <template x-if="deliveryStatus === 'assigned'">
                        <button @click="startPickup" class="px-5 py-2.5 rounded-xl bg-brand-gradient hover:opacity-95 text-white font-extrabold text-xs shadow-sm cursor-pointer">
                            Mulai Penjemputan
                        </button>
                    </template>
                    <template x-if="deliveryStatus === 'on_way_to_farm'">
                        <button @click="confirmLoaded" class="px-5 py-2.5 rounded-xl bg-[#05C793] text-white font-extrabold text-xs shadow-sm cursor-pointer">
                            Konfirmasi Muatan di Lahan
                        </button>
                    </template>
                    <template x-if="deliveryStatus === 'cargo_loaded'">
                        <button @click="completeDelivery" class="px-5 py-2.5 rounded-xl bg-cyan-600 text-white font-extrabold text-xs shadow-sm cursor-pointer">
                            Selesai Pengantaran
                        </button>
                    </template>
                    <template x-if="deliveryStatus === 'delivered'">
                        <span class="px-3.5 py-1.5 rounded-xl bg-emerald-100 text-emerald-800 font-bold text-xs">
                            <i class="fa-solid fa-check mr-1"></i> Terkirim Sukses
                        </span>
                    </template>
                </div>
            </div>
        </div>

        <!-- Right 5 Cols: Manifest -->
        <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-100 shadow-sm p-5 sm:p-6 space-y-4 text-xs">
            <h4 class="font-extrabold text-sm text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-clipboard-check text-[#05C793]"></i>
                Manifest Muatan
            </h4>

            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                <div class="flex justify-between">
                    <span class="text-slate-400">Komoditas</span>
                    <strong class="text-slate-800">Cabai Rawit Merah (500 kg)</strong>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Wadah</span>
                    <strong class="text-slate-800">25 Krat Standar @ 20 kg</strong>
                </div>
                <div class="flex justify-between border-t border-slate-200/60 pt-2">
                    <span class="text-slate-400">Asal</span>
                    <span class="font-semibold text-slate-700">Lahan Pak Raden, Ambarawa</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Tujuan</span>
                    <span class="font-semibold text-slate-700">Grand Hotel Malang</span>
                </div>
            </div>

            <div class="p-3.5 rounded-2xl bg-emerald-50/60 border border-emerald-100 text-slate-600 leading-relaxed text-[11px]">
                <strong class="text-[#05C793] block font-bold mb-0.5">Penjemputan Tepat Waktu</strong>
                Kurir langsung menuju koordinat lahan saat panen selesai dipetik, tanpa waktu tunggu lama.
            </div>
        </div>

    </div>

</div>
