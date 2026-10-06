@extends('layouts.app')

@section('title', 'Portal Petani (Produsen) - TaniLink')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6 sm:space-y-8" x-data="{
    activeTab: 'pesanan', // 'pesanan' or 'jualan'
    showNewListingModal: false,
    showProgressModal: false,
    selectedOrderId: {{ $activeOrders->first()->id ?? 'null' }}
}">

    <!-- Top Farmer Profile & Metrics Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 sm:p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-brand-gradient flex items-center justify-center text-white text-lg font-bold shadow-md shadow-emerald-500/20">
                <i class="fa-solid fa-person-digging"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-extrabold text-slate-900">{{ $petani->name }}</h2>
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-[#05C793] border border-emerald-200 text-[10px] font-extrabold uppercase">
                        Produsen Terverifikasi
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-0.5">{{ $petani->organization }} • {{ $petani->location }}</p>
            </div>
        </div>

        <!-- 2 Main Tabs for Petani -->
        <div class="flex items-center p-1 bg-slate-100 rounded-2xl text-xs font-bold w-full sm:w-auto">
            <button @click="activeTab = 'pesanan'" 
                    :class="activeTab === 'pesanan' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                    class="flex-1 sm:flex-initial flex items-center justify-center gap-2 px-4 py-2 rounded-xl transition-all cursor-pointer">
                <i class="fa-solid fa-clipboard-check text-[#05C793]"></i>
                <span>Pesanan & Rencana Tanam ({{ $activeOrders->count() }})</span>
            </button>
            <button @click="activeTab = 'jualan'" 
                    :class="activeTab === 'jualan' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                    class="flex-1 sm:flex-initial flex items-center justify-center gap-2 px-4 py-2 rounded-xl transition-all cursor-pointer">
                <i class="fa-solid fa-store text-cyan-600"></i>
                <span>Jualan Saya / Input Awal ({{ $myListings->count() }})</span>
            </button>
        </div>
    </div>

    <!-- 4 Clean Metric Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">DP Cair di Dompet</span>
            <div class="text-lg sm:text-2xl font-black text-slate-900 mt-1">Rp {{ number_format($dpReceived, 0, ',', '.') }}</div>
            <span class="text-[11px] font-semibold text-[#05C793] flex items-center gap-1 mt-1">
                <i class="fa-solid fa-check"></i> Modal awal tanpa hutang
            </span>
        </div>

        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Escrow Terkunci</span>
            <div class="text-lg sm:text-2xl font-black text-slate-900 mt-1">Rp {{ number_format($escrowLocked, 0, ',', '.') }}</div>
            <span class="text-[11px] font-semibold text-cyan-600 flex items-center gap-1 mt-1">
                <i class="fa-solid fa-lock"></i> Dijamin aman di TaniLink
            </span>
        </div>

        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Komoditas Terkontrak</span>
            <div class="text-lg sm:text-2xl font-black text-slate-900 mt-1">{{ number_format($activeOrders->sum('quantity_kg'), 0, ',', '.') }} <span class="text-sm font-bold text-slate-400">kg</span></div>
            <span class="text-[11px] text-slate-500 font-medium block mt-1">Dari {{ $activeOrders->count() }} pesanan masuk</span>
        </div>

        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Kapasitas Lahan Siap</span>
            <div class="text-lg sm:text-2xl font-black text-slate-900 mt-1">{{ number_format($totalCropKg, 0, ',', '.') }} <span class="text-sm font-bold text-slate-400">kg</span></div>
            <span class="text-[11px] text-slate-500 font-medium block mt-1">{{ $myListings->count() }} listing diterbitkan</span>
        </div>
    </div>

    <!-- TAB 1: PESANAN MASUK & RENCANA TANAM (SETELAH ADA PESANAN) -->
    <div x-show="activeTab === 'pesanan'" class="space-y-6">
        @if($activeOrders->count() > 0)
            @foreach($activeOrders as $order)
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-5 sm:p-7 space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-[#05C793] border border-emerald-200 text-[10px] font-extrabold uppercase">
                                    {{ $order->order_code }} • Terkontrak Aktif
                                </span>
                                <span class="text-xs text-slate-400">• Dipesan oleh <strong>{{ $order->buyer->name }}</strong></span>
                            </div>
                            <h3 class="text-xl font-extrabold text-slate-900 mt-1.5">
                                {{ $order->commodityListing->commodity_name }} ({{ number_format($order->quantity_kg, 0, ',', '.') }} kg)
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">
                                Harga Kontrak Tetap: <strong>Rp {{ number_format($order->price_per_kg, 0, ',', '.') }}/kg</strong> • Total: <strong class="text-[#05C793]">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                            </p>
                        </div>

                        <div>
                            <button @click="selectedOrderId = {{ $order->id }}; showProgressModal = true" 
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold shadow-md shadow-emerald-600/20 cursor-pointer transition-transform hover:scale-102">
                                <i class="fa-solid fa-camera"></i>
                                <span>Update Foto & Progres Tanaman</span>
                            </button>
                        </div>
                    </div>

                    <!-- Progress Bar & Current Stage -->
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1.5">
                            <span class="text-slate-800 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-[#05C793] animate-pulse"></span>
                                Tahap Saat Ini: <span class="text-[#05C793]">{{ $order->current_stage }}</span>
                            </span>
                            <span class="text-[#05C793]">{{ $order->stage_percentage }}% Menuju Panen</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-brand-gradient h-full rounded-full transition-all duration-500" style="width: {{ $order->stage_percentage }}%"></div>
                        </div>
                    </div>

                    <!-- Timeline Foto Lapangan dari Petani -->
                    <div>
                        <h4 class="font-extrabold text-sm text-slate-900 mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-images text-[#05C793]"></i>
                            Riwayat Dokumentasi Lapangan (Terkirim ke Pembeli)
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            @foreach($order->plantingProgress as $prog)
                                <div class="rounded-2xl border border-slate-100 overflow-hidden bg-slate-50/60">
                                    <div class="h-36 w-full bg-slate-100 overflow-hidden relative">
                                        <img src="{{ $prog->photo_url }}" alt="{{ $prog->stage_name }}" class="w-full h-full object-cover">
                                        <span class="absolute bottom-2 left-2 px-2 py-0.5 rounded bg-black/60 text-white text-[9px] font-bold backdrop-blur-sm">
                                            {{ $prog->reported_at ? $prog->reported_at->diffForHumans() : 'Baru saja' }}
                                        </span>
                                    </div>
                                    <div class="p-3">
                                        <div class="font-bold text-xs text-slate-900">{{ $prog->stage_name }}</div>
                                        <p class="text-[11px] text-slate-500 mt-1 line-clamp-2">{{ $prog->note }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="bg-white rounded-3xl border border-slate-100 p-8 text-center space-y-3">
                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mx-auto">
                    <i class="fa-solid fa-seedling"></i>
                </div>
                <h4 class="font-extrabold text-base text-slate-900">Belum Ada Pesanan yang Masuk</h4>
                <p class="text-xs text-slate-400 max-w-md mx-auto">
                    Saat pembeli melakukan booking pre-order dari penawaran jualan Anda, pesanan akan muncul di sini beserta rencana tanamnya.
                </p>
            </div>
        @endif
    </div>

    <!-- TAB 2: INPUT AWAL JUALAN PETANI (LISTING PENAWARAN KE MARKETPLACE) -->
    <div x-show="activeTab === 'jualan'" class="space-y-6">
        
        <div class="flex items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-extrabold text-slate-900">Penawaran Jualan Saya</h3>
                <p class="text-xs text-slate-400">Komoditas yang Anda tawarkan ke pasar sebelum ada pembeli</p>
            </div>

            <button @click="showNewListingModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-[#05C793] hover:bg-[#04b383] text-white text-xs font-bold shadow-md shadow-emerald-500/20 cursor-pointer transition-all">
                <i class="fa-solid fa-plus"></i>
                <span>+ Input Jualan Baru</span>
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($myListings as $listing)
                <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="h-40 w-full bg-slate-100 relative overflow-hidden">
                            <img src="{{ str_starts_with($listing->image_url ?? '', '/storage') ? asset($listing->image_url) : $listing->image_url }}" alt="{{ $listing->commodity_name }}" class="w-full h-full object-cover">
                            <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[10px] font-bold shadow-sm backdrop-blur-md {{ $listing->status === 'booked' ? 'bg-[#05C793] text-white' : 'bg-white/90 text-slate-800' }}">
                                {{ $listing->status === 'booked' ? 'Terkontrak Pembeli' : 'Tersedia di Marketplace' }}
                            </span>
                        </div>
                        <div class="p-4 space-y-2">
                            <h4 class="font-extrabold text-base text-slate-900">{{ $listing->commodity_name }}</h4>
                            <p class="text-xs text-slate-400">{{ $listing->variety }} • Lahan {{ number_format($listing->land_area_m2, 0, ',', '.') }} m²</p>
                            
                            <div class="grid grid-cols-2 gap-2 p-3 rounded-2xl bg-slate-50 text-xs">
                                <div>
                                    <span class="text-slate-400 block text-[10px]">Harga Patokan</span>
                                    <strong class="text-slate-800">Rp {{ number_format($listing->price_per_kg, 0, ',', '.') }}/kg</strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[10px]">Kapasitas Panen</span>
                                    <strong class="text-[#05C793]">{{ number_format($listing->estimated_harvest_kg, 0, ',', '.') }} kg</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 pt-0 text-[11px] text-slate-400 flex items-center justify-between border-t border-slate-50 mt-3 pt-3">
                        <span>Target Panen: <strong>{{ \Carbon\Carbon::parse($listing->harvest_estimated_date)->format('d M Y') }}</strong></span>
                        <span class="text-[#05C793] font-bold"><i class="fa-solid fa-check"></i> Aktif</span>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <!-- MODAL 1: INPUT AWAL JUALAN KOMODITAS (PETANI) -->
    <div x-cloak x-show="showNewListingModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl relative" @click.away="showNewListingModal = false">
            <button @click="showNewListingModal = false" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            <h3 class="text-lg font-extrabold text-slate-900 mb-1">Input Penawaran Jualan Baru</h3>
            <p class="text-xs text-slate-400 mb-5">Komoditas ini akan tayang di Marketplace untuk dipesan pembeli</p>

            <form action="{{ route('petani.jualan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5 text-xs">
                @csrf
                
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Foto Komoditas</label>
                    <div class="relative">
                        <input type="file" name="image" accept="image/*" class="w-full p-2 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:border-[#05C793] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-extrabold file:bg-emerald-50 file:text-[#05C793] hover:file:bg-emerald-100">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Komoditas</label>
                    <input type="text" name="commodity_name" placeholder="Contoh: Cabai Rawit Merah, Bawang Merah" required class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:border-[#05C793]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kategori</label>
                        <select name="category" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:border-[#05C793]">
                            <option value="Sayuran">Sayuran Segar</option>
                            <option value="Cabai & Bumbu">Cabai & Bumbu</option>
                            <option value="Palawija">Palawija</option>
                            <option value="Buah">Buah-buahan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Varietas / Benih</label>
                        <input type="text" name="variety" placeholder="Contoh: Ori 212 / Servo F1" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:border-[#05C793]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Luas Lahan Siap Tanam (m²)</label>
                        <input type="number" name="land_area_m2" placeholder="2000" required class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:border-[#05C793]">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Estimasi Hasil Panen (Kg)</label>
                        <input type="number" name="estimated_harvest_kg" placeholder="500" required class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:border-[#05C793]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Harga Patokan Tetap (Rp/kg)</label>
                        <input type="number" name="price_per_kg" placeholder="25000" step="500" required class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:border-[#05C793]">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Estimasi Tanggal Panen</label>
                        <input type="date" name="harvest_estimated_date" required class="w-full p-2 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:border-[#05C793]">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Catatan Tambahan (Opsional)</label>
                    <textarea name="description" rows="2" placeholder="Informasi kondisi lahan atau keunggulan bibit..." class="w-full p-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#05C793]"></textarea>
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" @click="showNewListingModal = false" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold shadow-md shadow-emerald-600/20">Terbitkan Jualan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: UPDATE FOTO PROGRES TANAMAN -->
    <div x-cloak x-show="showProgressModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-sm w-full p-5 shadow-2xl relative" @click.away="showProgressModal = false">
            <button @click="showProgressModal = false" class="absolute top-4 right-4 text-slate-400">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            <h4 class="font-extrabold text-base text-slate-900 mb-1">Update Foto Progres Tanaman</h4>
            <p class="text-xs text-slate-400 mb-4">Laporan ini langsung tampil di dashboard pembeli</p>

            <form action="{{ route('petani.progress.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <input type="hidden" name="pre_order_id" :value="selectedOrderId">

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Pilih Tahap Tanaman</label>
                    <select name="stage_name" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:border-[#05C793]">
                        <option value="Fase Benih & Penyemaian">Fase Benih & Penyemaian</option>
                        <option value="Fase Vegetatif & Pertunasan">Fase Vegetatif & Pertunasan</option>
                        <option value="Fase Pembungaan & Bakal Buah (60%)">Fase Pembungaan & Bakal Buah (60%)</option>
                        <option value="Fase Pematangan & Siap Panen">Fase Pematangan & Siap Panen</option>
                    </select>
                </div>

                <div class="w-full h-36 rounded-2xl bg-slate-100 overflow-hidden relative border border-slate-200">
                    <img src="https://images.unsplash.com/photo-1592417817098-8f3d6910985b?auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover">
                    <span class="absolute bottom-2 left-2 bg-black/60 text-white text-[9px] font-bold px-2 py-0.5 rounded">
                        Simulasi Kamera Lapangan
                    </span>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Catatan Kondisi Lapangan</label>
                    <textarea name="note" rows="2" placeholder="Kondisi tanaman sehat, pupuk lanjutan lancar..." required class="w-full p-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#05C793]"></textarea>
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" @click="showProgressModal = false" class="flex-1 py-2.5 rounded-xl border border-slate-200 font-bold text-slate-600">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold shadow-md shadow-emerald-600/20">Kirim Update</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
