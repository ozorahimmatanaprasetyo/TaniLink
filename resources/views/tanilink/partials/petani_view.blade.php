<div class="space-y-6" x-data="{
    showUploadModal: false,
    showNewPlanModal: false,
    commodity: 'Cabai Rawit Merah',
    landArea: 2500,
    harvestKg: 500,
    pricePerKg: 25000,
    harvestDate: '2026-08-24',
    calcTotal() { return this.harvestKg * this.pricePerKg; },
    calcDp() { return Math.round(this.calcTotal() * 0.3); },
    photoNote: '',
    photos: [
        {
            stage: 'Fase Pembungaan (60%)',
            time: 'Kemarin, 08:30 WIB',
            notes: 'Tanaman sehat merata, buah mulai terbentuk. Pengairan dan pupuk lanjutan lancar.',
            img: 'https://images.unsplash.com/photo-1592417817098-8f3d6910985b?auto=format&fit=crop&w=600&q=80'
        },
        {
            stage: 'Fase Vegetatif',
            time: '15 Juni 2026',
            notes: 'Pertumbuhan daun dan tunas produktif optimal tanpa hama.',
            img: 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&w=600&q=80'
        }
    ],
    submitPhoto() {
        if (!this.photoNote) this.photoNote = 'Kondisi tanaman optimal dan terawat baik.';
        this.photos.unshift({
            stage: 'Update Foto Baru',
            time: 'Baru saja',
            notes: this.photoNote,
            img: 'https://images.unsplash.com/photo-1588252303782-cb80119abd6d?auto=format&fit=crop&w=600&q=80'
        });
        this.showUploadModal = false;
        this.photoNote = '';
        notify('Foto progres tanaman berhasil diperbarui!', 'success');
    },
    submitNewPlan() {
        this.showNewPlanModal = false;
        notify('Rencana tanam berhasil diterbitkan ke katalog pre-order!', 'success');
    }
}">

    <!-- Profile Header Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 sm:p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-brand-gradient flex items-center justify-center text-white text-lg font-bold shadow-md shadow-emerald-500/20">
                <i class="fa-solid fa-person-digging"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-lg font-black text-slate-900">Pak Raden</h3>
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-[#05C793] border border-emerald-200 text-[10px] font-extrabold uppercase">
                        Petani Mitra
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-0.5">Gapoktan Ambarawa, Malang</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <button @click="showUploadModal = true" class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl bg-[#05C793] hover:bg-[#04b383] text-white text-xs font-bold shadow-md shadow-emerald-500/20 transition-all cursor-pointer">
                <i class="fa-solid fa-camera"></i>
                <span>Update Foto</span>
            </button>
            <button @click="showNewPlanModal = true" class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all cursor-pointer">
                <i class="fa-solid fa-plus text-[#05C793]"></i>
                <span>Rencana Tanam</span>
            </button>
        </div>
    </div>

    <!-- 4 Clean Metric Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">DP Diterima (30%)</span>
            <div class="text-lg sm:text-2xl font-black text-slate-900 mt-1">Rp 3.750.000</div>
            <span class="text-[11px] font-semibold text-[#05C793] flex items-center gap-1 mt-1">
                <i class="fa-solid fa-check"></i> Modal awal cair
            </span>
        </div>

        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Escrow Terkunci</span>
            <div class="text-lg sm:text-2xl font-black text-slate-900 mt-1">Rp 8.750.000</div>
            <span class="text-[11px] font-semibold text-cyan-600 flex items-center gap-1 mt-1">
                <i class="fa-solid fa-lock"></i> Aman di rekening
            </span>
        </div>

        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Komoditas Aktif</span>
            <div class="text-lg sm:text-2xl font-black text-slate-900 mt-1">500 <span class="text-sm font-bold text-slate-400">kg</span></div>
            <span class="text-[11px] text-slate-500 font-medium block mt-1 truncate">Cabai Rawit Merah</span>
        </div>

        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Target Panen</span>
            <div class="text-lg sm:text-2xl font-black text-slate-900 mt-1">H - 14 <span class="text-sm font-bold text-slate-400">Hari</span></div>
            <span class="text-[11px] text-slate-500 font-medium block mt-1">24 Agustus 2026</span>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left 8 Cols: Active Contract & Photos -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Contract Card -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-5 sm:p-7 space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                    <div>
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-[#05C793] border border-emerald-200 text-[10px] font-extrabold uppercase">
                            Kontrak Berjalan
                        </span>
                        <h4 class="text-xl font-black text-slate-900 mt-1">Cabai Rawit Merah (500 kg)</h4>
                        <p class="text-xs text-slate-400">Pembeli: <strong>Grand Hotel Malang</strong> (Rp 25.000/kg tetap)</p>
                    </div>
                    <div class="text-left sm:text-right">
                        <span class="text-[11px] text-slate-400 font-semibold block uppercase">Nilai Kontrak</span>
                        <span class="text-xl font-black text-[#05C793]">Rp 12.500.000</span>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1.5">
                        <span class="text-slate-700">Fase Pembungaan (60%)</span>
                        <span class="text-[#05C793]">65% Menuju Panen</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                        <div class="bg-brand-gradient h-full rounded-full" style="width: 65%"></div>
                    </div>
                </div>

                <!-- Big Clean CTA Button -->
                <button @click="showUploadModal = true" class="w-full py-3.5 rounded-2xl bg-brand-gradient hover:opacity-95 text-white font-extrabold text-xs sm:text-sm shadow-md shadow-emerald-500/20 flex items-center justify-center gap-2 cursor-pointer transition-transform hover:scale-[1.01]">
                    <i class="fa-solid fa-camera text-base"></i>
                    <span>Ambil Foto & Update Progres</span>
                </button>
            </div>

            <!-- Feed Foto Lapangan -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-5 sm:p-7">
                <h4 class="font-extrabold text-base text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-images text-[#05C793]"></i>
                    Foto Kondisi Lapangan Terkini
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <template x-for="(item, idx) in photos" :key="idx">
                        <div class="rounded-2xl border border-slate-100 overflow-hidden bg-slate-50/50">
                            <img :src="item.img" :alt="item.stage" class="w-full h-36 object-cover">
                            <div class="p-3.5">
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="font-bold text-slate-900" x-text="item.stage"></span>
                                    <span class="text-slate-400" x-text="item.time"></span>
                                </div>
                                <p class="text-xs text-slate-500 mt-1" x-text="item.notes"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

        </div>

        <!-- Right 4 Cols: Compact Form Rencana Tanam -->
        <div class="lg:col-span-4 space-y-6">
            
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-5 sm:p-6 space-y-4">
                <div>
                    <h4 class="font-extrabold text-base text-slate-900">Input Rencana Tanam</h4>
                    <p class="text-xs text-slate-400">Terbitkan kuota pre-order ke katalog</p>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Komoditas</label>
                        <select x-model="commodity" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:border-[#05C793]">
                            <option value="Cabai Rawit Merah">Cabai Rawit Merah</option>
                            <option value="Tomat Merah Unggul">Tomat Merah Unggul</option>
                            <option value="Bawang Merah Super">Bawang Merah Super</option>
                            <option value="Jagung Manis">Jagung Manis</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="block font-bold text-slate-600 mb-1">Lahan (m²)</label>
                            <input type="number" x-model="landArea" class="w-full p-2.5 rounded-xl border border-slate-200 font-semibold focus:outline-none focus:border-[#05C793]">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-600 mb-1">Panen (kg)</label>
                            <input type="number" x-model="harvestKg" class="w-full p-2.5 rounded-xl border border-slate-200 font-semibold focus:outline-none focus:border-[#05C793]">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="block font-bold text-slate-600 mb-1">Harga/kg (Rp)</label>
                            <input type="number" x-model="pricePerKg" step="500" class="w-full p-2.5 rounded-xl border border-slate-200 font-semibold focus:outline-none focus:border-[#05C793]">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-600 mb-1">Tgl Panen</label>
                            <input type="date" x-model="harvestDate" class="w-full p-2 rounded-xl border border-slate-200 font-semibold text-[11px] focus:outline-none focus:border-[#05C793]">
                        </div>
                    </div>

                    <!-- Live Calculation Card -->
                    <div class="p-3.5 rounded-2xl bg-emerald-50/60 border border-emerald-100 space-y-1.5">
                        <div class="flex justify-between text-slate-600">
                            <span>Estimasi Nilai:</span>
                            <strong class="text-slate-900" x-text="'Rp ' + calcTotal().toLocaleString('id-ID')"></strong>
                        </div>
                        <div class="flex justify-between text-[#05C793] font-bold border-t border-emerald-100 pt-1.5">
                            <span>Modal DP 30%:</span>
                            <span x-text="'Rp ' + calcDp().toLocaleString('id-ID')"></span>
                        </div>
                    </div>

                    <button @click="submitNewPlan" class="w-full py-3 rounded-xl bg-[#05C793] hover:bg-[#04b383] text-white font-extrabold text-xs shadow-md shadow-emerald-500/20 cursor-pointer transition-all">
                        Terbitkan Rencana Tanam
                    </button>
                </div>
            </div>

        </div>

    </div>

    <!-- Modal Upload Foto -->
    <div x-cloak x-show="showUploadModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-sm w-full p-5 shadow-2xl relative" @click.away="showUploadModal = false">
            <button @click="showUploadModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            <h4 class="font-extrabold text-base text-slate-900 mb-1">Update Foto Progres</h4>
            <p class="text-xs text-slate-400 mb-3">Cabai Rawit Merah (500 kg)</p>

            <div class="w-full h-40 rounded-2xl bg-slate-100 overflow-hidden relative mb-3">
                <img src="https://images.unsplash.com/photo-1592417817098-8f3d6910985b?auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover">
            </div>

            <textarea x-model="photoNote" rows="2" placeholder="Tuliskan catatan kondisi tanaman..." class="w-full p-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#05C793] mb-4"></textarea>

            <div class="flex gap-2">
                <button @click="showUploadModal = false" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600">Batal</button>
                <button @click="submitPhoto" class="flex-1 py-2.5 rounded-xl bg-[#05C793] text-white text-xs font-bold shadow-md">Kirim Update</button>
            </div>
        </div>
    </div>

</div>
