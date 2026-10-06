@extends('layouts.app')

@section('title', 'Portal Pembeli (Horeka) - TaniLink')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-5"
     x-data="{
        activeTab: 'marketplace',
        showBookingModal: false,
        selectedListing: null,
        selectedName: '',
        selectedPrice: 0,
        selectedMax: 0,
        qty: 1,
        get subtotal() { return (this.qty * this.selectedPrice).toLocaleString('id-ID'); }
     }">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-3.5 rounded-2xl flex items-center gap-3 text-sm font-semibold">
        <i class="fa-solid fa-circle-check text-emerald-500 flex-shrink-0"></i>
        {{ session('success') }}
    </div>
    @endif
    @if($errors->has('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 rounded-2xl flex items-center gap-3 text-sm font-semibold">
        <i class="fa-solid fa-circle-exclamation text-red-500 flex-shrink-0"></i>
        {{ $errors->first('error') }}
    </div>
    @endif

    {{-- Profile Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-cyan-100 text-cyan-600 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-hotel"></i>
            </div>
            <div>
                <h2 class="text-sm font-extrabold text-slate-900 leading-tight">{{ $pembeli->name }}</h2>
                <p class="text-xs text-slate-500">{{ $pembeli->organization ?? 'Horeka' }} &bull; {{ $pembeli->location ?? 'Indonesia' }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <div class="bg-slate-50 px-3 py-2 rounded-xl border border-slate-100 flex-1 sm:flex-none text-center">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Total Pre-Order</span>
                <span class="text-sm font-extrabold text-slate-900">Rp {{ number_format($totalPreOrderValue, 0, ',', '.') }}</span>
            </div>
            <div class="bg-cyan-50/50 px-3 py-2 rounded-xl border border-cyan-100 flex-1 sm:flex-none text-center">
                <span class="text-[9px] font-bold text-cyan-600/70 uppercase tracking-wider block">Escrow Aktif</span>
                <span class="text-sm font-extrabold text-cyan-700">Rp {{ number_format($escrowHeld, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="inline-flex items-center p-1 bg-slate-100 rounded-xl text-xs font-bold gap-1 w-full sm:w-auto">
        <button @click="activeTab = 'marketplace'"
                :class="activeTab === 'marketplace' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                class="flex-1 sm:flex-initial flex items-center justify-center gap-2 px-4 py-2 rounded-lg transition-all cursor-pointer">
            <i class="fa-solid fa-store text-emerald-600"></i>
            <span>Marketplace</span>
            <span class="bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded-md text-[10px] font-extrabold">{{ $catalog->count() }}</span>
        </button>
        <button @click="activeTab = 'pesanan'"
                :class="activeTab === 'pesanan' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                class="flex-1 sm:flex-initial flex items-center justify-center gap-2 px-4 py-2 rounded-lg transition-all cursor-pointer">
            <i class="fa-solid fa-clipboard-list text-cyan-600"></i>
            <span>Pesanan Aktif</span>
            <span class="bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded-md text-[10px] font-extrabold">{{ $myOrders->count() }}</span>
        </button>
    </div>

    {{-- ========================== --}}
    {{-- TAB 1: MARKETPLACE --}}
    {{-- ========================== --}}
    <div x-show="activeTab === 'marketplace'">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
            @forelse($catalog as $listing)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden group">
                {{-- Image --}}
                <div class="h-36 w-full bg-slate-100 relative overflow-hidden flex-shrink-0">
                    @php
                        $imgUrl = $listing->image_url ?? '';
                        $src = str_starts_with($imgUrl, '/storage') ? asset($imgUrl) : $imgUrl;
                        if (!$src) $src = 'https://images.unsplash.com/photo-1610348725531-843dff563e2c?auto=format&fit=crop&w=400&q=60';
                    @endphp
                    <img src="{{ $src }}" alt="{{ $listing->commodity_name }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                    {{-- Status Badge --}}
                    <span class="absolute top-2 left-2 px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase tracking-wider shadow-sm
                        {{ $listing->status === 'available' ? 'bg-emerald-600 text-white' : 'bg-slate-800 text-white' }}">
                        {{ $listing->status === 'available' ? 'Tersedia' : 'Habis' }}
                    </span>

                    {{-- Category --}}
                    <span class="absolute bottom-2 right-2 px-2 py-0.5 rounded-md bg-black/60 text-white text-[9px] font-bold backdrop-blur-sm">
                        {{ $listing->category ?? 'Komoditas' }}
                    </span>
                </div>

                {{-- Info --}}
                <div class="p-3 flex-1 flex flex-col gap-2">
                    <div>
                        <h4 class="font-extrabold text-xs text-slate-900 leading-tight mb-0.5 line-clamp-1">{{ $listing->commodity_name }}</h4>
                        <p class="text-[10px] text-slate-500 line-clamp-1">{{ $listing->variety ?? '-' }} &bull; {{ $listing->farmer->name ?? 'Petani' }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-x-2 gap-y-1.5 text-[10px]">
                        <div>
                            <span class="text-slate-400 block font-semibold">Harga/kg</span>
                            <span class="font-extrabold text-slate-800">Rp {{ number_format($listing->price_per_kg, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-semibold">Stok</span>
                            <span class="font-extrabold text-emerald-600">{{ number_format($listing->available_kg, 0, ',', '.') }} kg</span>
                        </div>
                        <div class="col-span-2">
                            <span class="text-slate-400 block font-semibold">Panen Est.</span>
                            <span class="font-extrabold text-slate-700">{{ \Carbon\Carbon::parse($listing->harvest_estimated_date)->format('d M Y') }}</span>
                        </div>
                    </div>

                    {{-- ACTION BUTTON --}}
                    <div class="mt-auto pt-1">
                        @if($listing->status === 'available' && $listing->available_kg > 0)
                        <button
                            @click="
                                selectedListing = {{ $listing->id }};
                                selectedName = '{{ addslashes($listing->commodity_name) }}';
                                selectedPrice = {{ (int)$listing->price_per_kg }};
                                selectedMax = {{ $listing->available_kg }};
                                qty = 1;
                                showBookingModal = true
                            "
                            class="w-full py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-extrabold shadow-md shadow-emerald-600/20 transition-all cursor-pointer flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-cart-plus text-xs"></i>
                            <span>Booking Pre-Order</span>
                        </button>
                        @else
                        <div class="w-full py-2.5 px-3 rounded-xl bg-slate-100 text-slate-500 text-xs font-bold text-center flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-lock text-xs"></i>
                            <span>Tidak Tersedia</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full bg-white rounded-2xl border border-slate-100 p-12 text-center shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-slate-50 text-slate-300 flex items-center justify-center text-2xl mx-auto mb-3">
                    <i class="fa-solid fa-store"></i>
                </div>
                <h4 class="font-extrabold text-sm text-slate-900">Marketplace Kosong</h4>
                <p class="text-xs text-slate-500 mt-1.5 max-w-xs mx-auto">Belum ada komoditas yang ditawarkan petani saat ini.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ========================== --}}
    {{-- TAB 2: PESANAN AKTIF --}}
    {{-- ========================== --}}
    <div x-show="activeTab === 'pesanan'" class="space-y-4">
        @forelse($myOrders as $order)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            {{-- Order Header --}}
            <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap mb-1">
                        <span class="px-2.5 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-extrabold uppercase tracking-wider">
                            {{ $order->order_code }}
                        </span>
                        <span class="text-[11px] text-slate-500">dari <strong class="text-slate-800">{{ $order->farmer->name }}</strong></span>
                    </div>
                    <h3 class="text-base font-extrabold text-slate-900 leading-tight truncate">
                        {{ $order->commodityListing->commodity_name }}
                        <span class="text-sm font-semibold text-slate-400">({{ number_format($order->quantity_kg, 0, ',', '.') }} kg)</span>
                    </h3>
                </div>
                <span class="px-3 py-1 rounded-xl text-[10px] font-bold uppercase tracking-wider self-start shrink-0
                    {{ $order->status === 'selesai' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-cyan-50 text-cyan-600 border border-cyan-100' }}">
                    {{ str_replace('_', ' ', $order->status) }}
                </span>
            </div>

            {{-- Financial Info --}}
            <div class="px-4 py-3 grid grid-cols-2 sm:grid-cols-4 gap-3 bg-slate-50/40 border-b border-slate-100 text-xs">
                <div>
                    <span class="text-slate-400 font-bold uppercase tracking-wider block mb-0.5" style="font-size:9px">Total</span>
                    <span class="font-extrabold text-slate-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
                <div>
                    <span class="text-slate-400 font-bold uppercase tracking-wider block mb-0.5" style="font-size:9px">DP (30%)</span>
                    <span class="font-bold text-emerald-700">Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</span>
                </div>
                <div>
                    <span class="text-cyan-600/70 font-bold uppercase tracking-wider block mb-0.5" style="font-size:9px">Escrow (70%)</span>
                    <span class="font-bold text-cyan-600">Rp {{ number_format($order->escrow_locked_amount, 0, ',', '.') }}</span>
                </div>
                <div>
                    <span class="text-slate-400 font-bold uppercase tracking-wider block mb-0.5" style="font-size:9px">Harga/kg</span>
                    <span class="font-bold text-slate-700">Rp {{ number_format($order->price_per_kg, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="p-4 space-y-4">
                {{-- Progress --}}
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <div class="flex justify-between text-xs font-bold mb-2">
                        <span class="text-slate-700 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse inline-block"></span>
                            {{ $order->current_stage }}
                        </span>
                        <span class="text-emerald-700 font-extrabold">{{ $order->stage_percentage }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-500 to-cyan-500 h-full rounded-full transition-all duration-1000"
                             style="width: {{ $order->stage_percentage }}%"></div>
                    </div>
                </div>

                {{-- Dokumentasi Lapangan --}}
                @if($order->plantingProgress && $order->plantingProgress->count() > 0)
                <div>
                    <h4 class="font-extrabold text-xs text-slate-700 mb-2.5 flex items-center gap-1.5">
                        <i class="fa-solid fa-camera-retro text-emerald-600"></i> Dokumentasi Lapangan
                    </h4>
                    <div class="flex gap-2 overflow-x-auto pb-1">
                        @foreach($order->plantingProgress as $prog)
                        <div class="rounded-xl border border-slate-100 overflow-hidden bg-white shadow-sm flex-shrink-0 w-36">
                            <div class="h-24 w-full bg-slate-100 overflow-hidden relative">
                                <img src="{{ $prog->photo_url }}" alt="{{ $prog->stage_name }}"
                                     class="w-full h-full object-cover">
                                <span class="absolute bottom-1 left-1 px-1.5 py-0.5 rounded bg-black/60 text-white text-[8px] font-bold">
                                    {{ $prog->reported_at ? $prog->reported_at->diffForHumans() : 'Baru' }}
                                </span>
                            </div>
                            <div class="p-2">
                                <div class="font-extrabold text-[10px] text-slate-900 line-clamp-1">{{ $prog->stage_name }}</div>
                                <p class="text-[9px] text-slate-400 mt-0.5 line-clamp-2 leading-relaxed">{{ $prog->note }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Status Penjemputan --}}
                @if($order->deliveryTracking)
                <div class="bg-cyan-50/40 rounded-xl p-3.5 border border-cyan-100">
                    <h4 class="font-extrabold text-xs text-cyan-800 mb-3 flex items-center gap-1.5">
                        <i class="fa-solid fa-truck-fast text-cyan-500"></i> Status Penjemputan
                    </h4>
                    <div class="flex items-start gap-3">
                        <div class="flex flex-col items-center pt-1 shrink-0">
                            <div class="w-3 h-3 rounded-full bg-emerald-500 ring-3 ring-emerald-50"></div>
                            <div class="w-px h-10 bg-gradient-to-b from-emerald-500 to-cyan-500"></div>
                            <div class="w-3 h-3 rounded-full bg-cyan-500 ring-3 ring-cyan-50"></div>
                        </div>
                        <div class="flex-1 space-y-3 text-xs">
                            <div>
                                <span class="text-[9px] font-bold text-emerald-700 uppercase tracking-wider block">Pickup (Lahan Petani)</span>
                                <p class="font-semibold text-slate-800 mt-0.5">{{ $order->deliveryTracking->pickup_address }}</p>
                            </div>
                            <div>
                                <span class="text-[9px] font-bold text-cyan-600 uppercase tracking-wider block">Tujuan (Lokasi Anda)</span>
                                <p class="font-semibold text-slate-800 mt-0.5">{{ $order->deliveryTracking->destination_address }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mt-3 pt-3 border-t border-cyan-100/60 text-[10px]">
                        <div>
                            <span class="text-slate-400 font-bold block mb-0.5">Jarak</span>
                            <span class="font-extrabold text-slate-800">{{ $order->deliveryTracking->distance_km }} km</span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-bold block mb-0.5">Estimasi</span>
                            <span class="font-extrabold text-slate-800">{{ $order->deliveryTracking->estimated_time }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-bold block mb-0.5">Status</span>
                            <span class="font-extrabold
                                @switch($order->deliveryTracking->status)
                                    @case('delivered') text-[#05C793] @break
                                    @case('in_transit') text-cyan-600 @break
                                    @case('pickup_in_progress') text-amber-500 @break
                                    @default text-slate-600
                                @endswitch">
                                {{ str_replace('_', ' ', ucwords($order->deliveryTracking->status, '_')) }}
                            </span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-bold block mb-0.5">Kurir</span>
                            <span class="font-extrabold text-slate-800">{{ $order->deliveryTracking->driver_name }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl border border-slate-100 p-12 text-center shadow-sm">
            <div class="w-16 h-16 rounded-2xl bg-slate-50 text-slate-300 flex items-center justify-center text-2xl mx-auto mb-3">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <h4 class="font-extrabold text-sm text-slate-900">Belum Ada Pesanan</h4>
            <p class="text-xs text-slate-500 mt-1.5 max-w-sm mx-auto">Booking komoditas dari marketplace, pesanan Anda akan tampil di sini.</p>
            <button @click="activeTab = 'marketplace'" class="mt-4 px-5 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition-colors cursor-pointer">
                Ke Marketplace
            </button>
        </div>
        @endforelse
    </div>

    {{-- ========================== --}}
    {{-- BOOKING MODAL --}}
    {{-- ========================== --}}
    <div x-cloak x-show="showBookingModal"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl relative"
             @click.away="showBookingModal = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            <button @click="showBookingModal = false" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all">
                <i class="fa-solid fa-xmark"></i>
            </button>

            {{-- Modal Header --}}
            <div class="mb-5">
                <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white mb-3 shadow-md shadow-emerald-600/20">
                    <i class="fa-solid fa-cart-plus text-sm"></i>
                </div>
                <h4 class="text-lg font-extrabold text-slate-900" x-text="selectedName"></h4>
                <p class="text-xs text-slate-500 mt-0.5">
                    Rp <span x-text="selectedPrice.toLocaleString('id-ID')"></span>/kg
                    &bull; Stok: <span x-text="selectedMax.toLocaleString('id-ID')"></span> kg
                </p>
            </div>

            <form method="POST" action="{{ route('pembeli.booking.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="listing_id" :value="selectedListing">

                <div>
                    <label class="block font-bold text-xs text-slate-700 mb-1.5">Jumlah Pesanan (kg)</label>
                    <input type="number" name="quantity_kg" x-model="qty" min="1" :max="selectedMax" required
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-base font-extrabold text-slate-900 focus:outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10 transition-all"
                           placeholder="Masukkan jumlah...">
                    <div class="flex justify-between items-center mt-2">
                        <p class="text-[11px] text-slate-500">Maks: <strong class="text-emerald-700"><span x-text="selectedMax.toLocaleString('id-ID')"></span> kg</strong></p>
                        <p class="text-[11px] font-bold text-slate-700">Total: <strong class="text-emerald-700">Rp <span x-text="subtotal"></span></strong></p>
                    </div>
                </div>

                {{-- Escrow Info --}}
                <div class="bg-amber-50 rounded-xl p-3 border border-amber-100 text-[11px] text-amber-800 font-medium flex gap-2">
                    <i class="fa-solid fa-info-circle mt-0.5 flex-shrink-0 text-amber-600"></i>
                    <span>30% DP diamankan ke petani. 70% sisanya terkunci di escrow TaniLink hingga barang diterima.</span>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="button" @click="showBookingModal = false"
                            class="w-1/3 py-3 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-100 transition-all cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                            class="w-2/3 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-extrabold shadow-md shadow-emerald-600/20 transition-all cursor-pointer flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check text-xs"></i>
                        <span>Konfirmasi Booking</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
