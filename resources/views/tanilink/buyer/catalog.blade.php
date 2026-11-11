@extends('layouts.sidebar')

@section('title', 'Marketplace Komoditas - TaniLink')

@section('sidebar_nav')
<a href="{{ route('portal.pembeli') }}" class="nav-btn {{ request()->routeIs('portal.pembeli') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-chart-pie"></i></span> Beranda
</a>
<a href="{{ route('pembeli.katalog') }}" class="nav-btn {{ request()->routeIs('pembeli.katalog*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-store"></i></span> Marketplace
</a>
<a href="{{ route('pembeli.pesanan') }}" class="nav-btn {{ request()->routeIs('pembeli.pesanan*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-bag-shopping"></i></span> Pesanan Saya
</a>
<a href="{{ route('pembeli.profil') }}" class="nav-btn {{ request()->routeIs('pembeli.profil*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-user"></i></span> Profil Konsumen
</a>
@endsection

@push('styles')
<style>
.catalog-filter-card {
    padding: 20px 22px;
    margin-bottom: 22px;
}
.catalog-filter-row1 {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 14px;
    margin-bottom: 14px;
}
.catalog-filter-row2 {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)) auto;
    gap: 14px;
    align-items: flex-end;
}
.catalog-filter-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

/* ── Responsive Product Grid ── */
/* Desktop (>1024px): 4 Kolom */
.catalog-product-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
}

/* Tablet (768px - 1024px): 3 Kolom */
@media (max-width: 1024px) {
    .catalog-product-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }
}

/* Small Tablet (641px - 768px): 2 Kolom */
@media (max-width: 768px) {
    .catalog-product-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }
}

/* Mobile Devices (<= 640px): 2 Kolom Shopee Style */
@media (max-width: 640px) {
    .mobile-filter-toggle {
        display: flex !important;
    }
    .catalog-filter-card {
        padding: 12px 14px !important;
        margin-bottom: 14px !important;
        border-radius: 14px !important;
    }
    .catalog-filter-row1 {
        grid-template-columns: 1fr;
        gap: 10px;
        margin-bottom: 10px;
    }
    .catalog-filter-row2 {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    .catalog-filter-actions {
        width: 100%;
        gap: 8px;
        margin-top: 4px;
    }
    .catalog-filter-actions button {
        flex: 1;
        justify-content: center;
        padding: 9px 12px !important;
        font-size: 11.5px !important;
        border-radius: 9px !important;
    }

    /* 2 Kolom Persis Shopee */
    .catalog-product-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 10px !important;
    }

    .catalog-header-title {
        font-size: 20px !important;
        margin-bottom: 4px !important;
    }
    .catalog-header-desc {
        font-size: 11.5px !important;
    }

    .catalog-card-compact {
        border-radius: 12px !important;
    }
    /* 1:1 Aspect Ratio Shopee-style Image */
    .catalog-card-image-box {
        width: 100% !important;
        aspect-ratio: 1 / 1 !important;
        height: auto !important;
    }
    .catalog-card-content {
        padding: 10px 9px 8px !important;
    }
    .catalog-card-badge {
        font-size: 7.5px !important;
        padding: 2px 6px !important;
        border-radius: 5px !important;
        top: 6px !important;
        left: 6px !important;
    }
    .catalog-card-category {
        font-size: 8.5px !important;
    }
    .catalog-card-title {
        font-size: 12.5px !important;
        line-height: 1.3 !important;
        height: 32px !important;
        white-space: normal !important;
        display: -webkit-box !important;
        -webkit-line-clamp: 2 !important;
        -webkit-box-orient: vertical !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        margin: 2px 0 3px !important;
    }
    .catalog-card-meta {
        font-size: 10px !important;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .catalog-card-price-row {
        flex-direction: column !important;
        align-items: flex-start !important;
        margin: 4px 0 !important;
        gap: 1px !important;
    }
    .catalog-card-price {
        font-size: 14px !important;
        line-height: 1.2 !important;
    }
    .catalog-card-stock {
        font-size: 9.5px !important;
        color: #71877F !important;
    }
    .catalog-card-harvest {
        font-size: 9px !important;
        margin-bottom: 6px !important;
        line-height: 1.25 !important;
    }
    .catalog-card-desc {
        display: none !important; /* Shopee card grid tidak menampilkan paragraf panjang agar card proporsional */
    }
    .catalog-card-actions {
        padding: 0 9px 10px !important;
        gap: 5px !important;
    }
    .catalog-card-actions a {
        padding: 7px 6px !important;
        font-size: 10.5px !important;
        border-radius: 7px !important;
    }
    .catalog-card-actions .btn-detail {
        display: none !important; /* Pada tampilan Shopee 2 kolom, fokus pada 1 tombol utama pre-order */
    }
}

/* HP Sangat Kecil (<= 375px) */
@media (max-width: 375px) {
    .catalog-product-grid {
        gap: 8px !important;
    }
    .catalog-card-content {
        padding: 8px 7px 6px !important;
    }
    .catalog-card-title {
        font-size: 11.5px !important;
        height: 30px !important;
    }
    .catalog-card-price {
        font-size: 13.5px !important;
    }
    .catalog-card-actions {
        padding: 0 7px 8px !important;
    }
    .catalog-card-actions a {
        font-size: 10px !important;
        padding: 6px 4px !important;
    }
}
</style>
@endpush

@section('content')
<div x-data="{
    search: '{{ request('search', '') }}',
    kategori: '{{ request('category', '') }}',
    minPriceInput: '{{ request('min_price', '') }}',
    maxPriceInput: '{{ request('max_price', '') }}',
    startDateInput: '{{ request('start_date', '') }}',
    endDateInput: '{{ request('end_date', '') }}',

    appliedMinPrice: '{{ request('min_price', '') }}',
    appliedMaxPrice: '{{ request('max_price', '') }}',
    appliedStartDate: '{{ request('start_date', '') }}',
    appliedEndDate: '{{ request('end_date', '') }}',
    priceError: '',
    dateError: '',
    visibleCount: 0,
    filterOpen: false,

    get hasActiveFilters() {
        return !!(this.kategori || this.appliedMinPrice || this.appliedMaxPrice || this.appliedStartDate || this.appliedEndDate);
    },

    formatRupiah(val) {
        if (!val) return '';
        let num = val.toString().replace(/[^0-9]/g, '');
        return num ? 'Rp ' + parseInt(num, 10).toLocaleString('id-ID') : '';
    },

    parseNumber(val) {
        if (!val) return null;
        let num = val.toString().replace(/[^0-9]/g, '');
        return num ? parseInt(num, 10) : null;
    },

    applyFilter() {
        this.priceError = '';
        this.dateError = '';

        let minP = this.parseNumber(this.minPriceInput);
        let maxP = this.parseNumber(this.maxPriceInput);

        if (minP !== null && maxP !== null && minP > maxP) {
            this.priceError = 'Harga minimum tidak boleh lebih besar dari harga maksimum';
            return;
        }

        if (this.startDateInput && this.endDateInput && this.startDateInput > this.endDateInput) {
            this.dateError = 'Tanggal awal tidak boleh melebihi tanggal akhir';
            return;
        }

        this.appliedMinPrice = minP !== null ? minP : '';
        this.appliedMaxPrice = maxP !== null ? maxP : '';
        this.appliedStartDate = this.startDateInput;
        this.appliedEndDate = this.endDateInput;

        this.$nextTick(() => { this.updateCount(); });
    },

    resetFilter() {
        this.search = '';
        this.kategori = '';
        this.minPriceInput = '';
        this.maxPriceInput = '';
        this.startDateInput = '';
        this.endDateInput = '';
        this.appliedMinPrice = '';
        this.appliedMaxPrice = '';
        this.appliedStartDate = '';
        this.appliedEndDate = '';
        this.priceError = '';
        this.dateError = '';

        this.$nextTick(() => { this.updateCount(); });
    },

    matches(item) {
        let nameMatch = this.search === '' || item.name.includes(this.search.toLowerCase());
        let catMatch = this.kategori === '' || item.kategori === this.kategori;

        let priceMatch = true;
        let minP = this.appliedMinPrice !== '' ? parseFloat(this.appliedMinPrice) : null;
        let maxP = this.appliedMaxPrice !== '' ? parseFloat(this.appliedMaxPrice) : null;

        if (minP !== null && maxP !== null) {
            priceMatch = item.harga >= minP && item.harga <= maxP;
        } else if (minP !== null) {
            priceMatch = item.harga >= minP;
        } else if (maxP !== null) {
            priceMatch = item.harga <= maxP;
        }

        let dateMatch = true;
        if (this.appliedStartDate && this.appliedEndDate) {
            dateMatch = item.date >= this.appliedStartDate && item.date <= this.appliedEndDate;
        } else if (this.appliedStartDate) {
            dateMatch = item.date >= this.appliedStartDate;
        } else if (this.appliedEndDate) {
            dateMatch = item.date <= this.appliedEndDate;
        }

        return nameMatch && catMatch && priceMatch && dateMatch;
    },

    updateCount() {
        let items = this.$el.querySelectorAll('[data-listing]');
        let count = 0;
        items.forEach(el => {
            if (el.style.display !== 'none') count++;
        });
        this.visibleCount = count;
    },

    init() {
        this.$nextTick(() => { this.updateCount(); });
    }
}">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div style="background:#ECFDF8; border:1px solid #6EE7B7; color:#065F46; padding:12px 16px; border-radius:12px; font-size:13px; font-weight:600; display:flex; align-items:center; gap:10px; margin-bottom:18px;">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Page Header --}}
    <div style="margin-bottom:18px;">
        <h1 class="catalog-header-title" style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Marketplace Komoditas</h1>
        <p class="catalog-header-desc" style="font-size:12px; color:#8A9A94; margin:0;">Cari hasil panen segar langsung dari petani lokal dengan kepastian harga dan jadwal panen.</p>
    </div>

    {{-- Search / Filter Card (Collapsible on Mobile) --}}
    <div class="card catalog-filter-card">
        {{-- Row 1: Search & Filter Toggle --}}
        <div style="display:flex; gap:10px; align-items:center; margin-bottom:12px;">
            {{-- Search Bar (Full Width) --}}
            <div style="position:relative; flex:1;">
                <i class="fa-solid fa-search" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#80908D; font-size:13px;"></i>
                <input type="text" x-model="search" @input="$nextTick(() => updateCount())" placeholder="Cari nama komoditas, varietas..."
                       class="catalog-input-compact"
                       style="width:100%; height:42px; padding:9px 14px 9px 38px; border:1.5px solid #E5ECEA; border-radius:11px; font-size:12.5px; color:#172B29; font-family:inherit; outline:none; transition:border-color 0.15s; box-sizing:border-box;"
                       @focus="$el.style.borderColor='#05C793'"
                       @blur="$el.style.borderColor='#E5ECEA'">
            </div>

            {{-- Mobile Collapsible Filter Toggle Button --}}
            <button type="button" @click="filterOpen = !filterOpen"
                    class="mobile-filter-toggle"
                    style="display:none; height:42px; padding:0 14px; border-radius:11px; border:1.5px solid #E5ECEA; background:#fff; color:#172B29; font-size:12px; font-weight:750; align-items:center; gap:6px; cursor:pointer; flex-shrink:0;">
                <i class="fa-solid fa-sliders" :style="hasActiveFilters ? 'color:#05C793;' : 'color:#5C736A;'"></i>
                <span>Filter</span>
                <span x-show="hasActiveFilters" style="width:7px; height:7px; border-radius:50%; background:#05C793;"></span>
            </button>
        </div>

        {{-- Collapsible Content: Kategori, Harga, Tanggal Panen, dan Aksi --}}
        <div class="catalog-filter-collapsible" x-show="filterOpen || window.innerWidth > 640" x-transition>
            {{-- Kategori & Rentang Harga/Tanggal --}}
            <div class="catalog-filter-row1" style="margin-bottom:12px;">
                {{-- Kategori Dropdown --}}
                <div>
                    <label class="catalog-label-compact" style="display:block; font-size:11px; font-weight:750; color:#495D56; margin-bottom:5px;">
                        Kategori Komoditas
                    </label>
                    <select x-model="kategori" @change="$nextTick(() => updateCount())"
                            class="catalog-input-compact"
                            style="width:100%; height:40px; padding:8px 12px; border:1.5px solid #E5ECEA; border-radius:10px; font-size:12px; color:#172B29; font-family:inherit; outline:none; background:white; cursor:pointer; box-sizing:border-box;">
                        <option value="">Semua Kategori</option>
                        @foreach($catalog->pluck('category')->unique()->filter() as $kat)
                        <option value="{{ $kat }}">{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Rentang Harga (Rp/kg) --}}
                <div>
                    <label class="catalog-label-compact" style="display:block; font-size:11px; font-weight:750; color:#495D56; margin-bottom:5px;">
                        Rentang Harga (Rp/kg)
                    </label>
                    <div style="display:flex; align-items:center; gap:6px;">
                        <input type="number" x-model="minPriceInput" placeholder="Min" min="0" step="500"
                               class="catalog-input-compact"
                               style="width:100%; height:40px; padding:8px 10px; border:1.5px solid #E5ECEA; border-radius:10px; font-size:12px; color:#172B29; outline:none; box-sizing:border-box;">
                        <span style="color:#80908D; font-weight:700; font-size:11px;">–</span>
                        <input type="number" x-model="maxPriceInput" placeholder="Maks" min="0" step="500"
                               class="catalog-input-compact"
                               style="width:100%; height:40px; padding:8px 10px; border:1.5px solid #E5ECEA; border-radius:10px; font-size:12px; color:#172B29; outline:none; box-sizing:border-box;">
                    </div>
                    <template x-if="priceError">
                        <div style="font-size:10px; color:#DC2626; font-weight:700; margin-top:3px;" x-text="priceError"></div>
                    </template>
                </div>
            </div>

            {{-- Row 2: Rentang Tanggal Panen + Aksi --}}
            <div class="catalog-filter-row2">
                {{-- Estimasi Panen --}}
                <div>
                    <label class="catalog-label-compact" style="display:block; font-size:11px; font-weight:750; color:#495D56; margin-bottom:5px;">
                        Estimasi Tanggal Panen
                    </label>
                    <div style="display:flex; align-items:center; gap:6px;">
                        <input type="date" x-model="startDateInput"
                               class="catalog-input-compact"
                               style="width:100%; height:40px; padding:8px 8px; border:1.5px solid #E5ECEA; border-radius:10px; font-size:11.5px; color:#172B29; outline:none; background:white; cursor:pointer; box-sizing:border-box;">
                        <span style="color:#80908D; font-weight:700; font-size:11px;">–</span>
                        <input type="date" x-model="endDateInput"
                               class="catalog-input-compact"
                               style="width:100%; height:40px; padding:8px 8px; border:1.5px solid #E5ECEA; border-radius:10px; font-size:11.5px; color:#172B29; outline:none; background:white; cursor:pointer; box-sizing:border-box;">
                    </div>
                    <template x-if="dateError">
                        <div style="font-size:10px; color:#DC2626; font-weight:700; margin-top:3px;" x-text="dateError"></div>
                    </template>
                </div>

                {{-- Action Buttons --}}
                <div class="catalog-filter-actions">
                    <button type="button" @click="applyFilter()"
                            style="padding:10px 18px; border-radius:10px; background:#05C793; border:none; color:#053B2D; font-size:12px; font-weight:850; cursor:pointer; display:inline-flex; align-items:center; gap:6px; box-shadow:0 2px 6px rgba(5,199,147,0.25);">
                        <i class="fa-solid fa-filter"></i> Terapkan
                    </button>
                    <button type="button" @click="resetFilter()"
                            style="padding:10px 14px; border-radius:10px; background:#F1F5F3; border:1px solid #DFE8E4; color:#4B6358; font-size:12px; font-weight:750; cursor:pointer; display:inline-flex; align-items:center; gap:5px;">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Product Grid: Only Available Stocks are shown --}}
    @php
        $availableListings = $catalog->filter(function($item) {
            return $item->available_kg > 0 && $item->status !== 'archived';
        });
    @endphp

    <div class="catalog-product-grid">
        @forelse($availableListings as $listing)
        @php
            $imgSrc = $listing->resolved_image_url;
            $harvestDateYmd = \Carbon\Carbon::parse($listing->harvest_estimated_date)->format('Y-m-d');
        @endphp
        <div data-listing
             class="catalog-card-compact"
             x-show="matches({
                 name: '{{ strtolower(addslashes($listing->commodity_name . ' ' . ($listing->variety ?? ''))) }}',
                 kategori: '{{ addslashes($listing->category) }}',
                 harga: {{ (float)$listing->price_per_kg }},
                 date: '{{ $harvestDateYmd }}'
             })"
             style="border: 1px solid #E5ECEA; border-radius: 16px; background: white; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">

            <div>
                {{-- Image / Photo 1:1 Aspect Ratio Shopee Style --}}
                <div class="catalog-card-image-box" style="height: 140px; display: flex; align-items: center; justify-content: center; position: relative; background: #F0FDF4; overflow: hidden;">
                    <img src="{{ $imgSrc }}"
                         alt="{{ $listing->commodity_name }}"
                         onerror="this.onerror=null; this.src='{{ asset('images/products/default-vegetable.jpg') }}';"
                         style="width:100%; height:100%; object-fit:cover; position:absolute; inset:0; transition: transform 0.25s ease;"
                         onmouseover="this.style.transform='scale(1.04)';"
                         onmouseout="this.style.transform='scale(1)';">
                    <span class="catalog-card-badge" style="position:absolute; top:8px; left:8px; font-size:8.5px; font-weight:900; background:rgba(255,255,255,0.95); border-radius:6px; padding:2px 7px; box-shadow:0 1px 4px rgba(0,0,0,0.1); z-index:1; color:#0C8D65;">
                        Tersedia
                    </span>
                </div>

                {{-- Info --}}
                <div class="catalog-card-content" style="padding: 14px;">
                    <div class="catalog-card-category" style="font-size: 9.5px; color: #80908D; text-transform:uppercase; font-weight:800;">{{ $listing->category ?? 'Komoditas' }}</div>
                    <h3 class="catalog-card-title" style="font-size: 14.5px; font-weight: 850; margin: 3px 0 2px; color: #172B29;">
                        {{ $listing->commodity_name }}
                    </h3>
                    <div class="catalog-card-meta" style="font-size: 10.5px; color: #80908D;">
                        {{ $listing->variety ? $listing->variety . ' • ' : '' }}{{ $listing->location ?? 'Malang' }}
                    </div>

                    <div class="catalog-card-price-row" style="display:flex; align-items:baseline; justify-content:space-between; margin: 8px 0 5px;">
                        <div class="catalog-card-price" style="font-weight: 900; color: #0A765A;">
                            Rp {{ number_format($listing->price_per_kg, 0, ',', '.') }}
                            <span style="font-size: 10px; font-weight: 500; color: #80908D;">/kg</span>
                        </div>
                        <div class="catalog-card-stock" style="font-size:10.5px; font-weight:700; color:#172B29;">Stok: {{ number_format($listing->available_kg, 0, ',', '.') }} kg</div>
                    </div>

                    <div class="catalog-card-harvest" style="font-size:9.5px; color:#80908D; margin-bottom: 8px;">
                        Est. Panen: <b style="color:#172B29;">{{ \Carbon\Carbon::parse($listing->harvest_estimated_date)->format('d M Y') }}</b>
                    </div>

                    @if(!empty($listing->description))
                    <div class="catalog-card-desc" style="font-size:11px; color:#556C62; line-height:1.45; margin-bottom:10px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;" title="{{ $listing->description }}">
                        {{ $listing->description }}
                    </div>
                    @endif
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="catalog-card-actions" style="padding: 0 14px 14px; display:flex; flex-direction:column; gap:8px;">
                <a href="{{ route('pembeli.komoditas.show', $listing->id) }}"
                   class="btn-detail"
                   style="display:flex; align-items:center; justify-content:center; gap:5px; width:100%; text-align:center; padding:8px; border-radius:9px; border:1px solid #DFE8E5; background:#fff; color:#365249; font-size:11.5px; font-weight:800; text-decoration:none;">
                    Lihat Detail
                </a>
                <a href="{{ route('pembeli.preorder', $listing->id) }}"
                   style="display:flex; align-items:center; justify-content:center; gap:6px; width:100%; text-align:center; padding:9px; border-radius:9px; background:#05C793; color:#053B2D; font-size:11.5px; font-weight:850; text-decoration:none; box-shadow:0 2px 8px rgba(5,199,147,0.25);">
                    <i class="fa-solid fa-cart-shopping"></i> Pesan
                </a>
            </div>
        </div>
        @empty
        <div style="grid-column: 1 / -1; background: white; border: 1px solid #E5ECEA; border-radius: 16px; padding: 60px; text-align: center; color: #80908D;">
            <i class="fa-solid fa-wheat-awn" style="font-size:38px; color:#A9CBBB; margin-bottom:12px; display:block;"></i>
            <div style="font-size: 14px; font-weight: 700; color: #172B29;">Belum Ada Komoditas Tersedia</div>
            <div style="font-size: 12px; margin-top: 5px;">Saat petani mempublikasikan tanaman dengan stok tersedia, komoditas akan muncul di sini.</div>
        </div>
        @endforelse

        {{-- Dynamic Empty State when filters yield 0 results --}}
        @if($availableListings->isNotEmpty())
        <div x-cloak x-show="visibleCount === 0"
             style="grid-column: 1 / -1; background: white; border: 1.5px dashed #DFE8E4; border-radius: 16px; padding: 50px 20px; text-align: center; color: #80908D;">
            <i class="fa-solid fa-magnifying-glass" style="font-size:32px; color:#A9CBBB; margin-bottom:10px; display:block;"></i>
            <div style="font-size: 14px; font-weight: 800; color: #172B29;">Tidak Ada Komoditas yang Cocok</div>
            <div style="font-size: 12px; color: #6A8177; margin: 6px 0 16px;">Coba ubah kata kunci pencarian, rentang harga, atau rentang tanggal panen Anda.</div>
            <button type="button" @click="resetFilter()"
                    style="padding: 8px 16px; border-radius: 9px; background: #05C793; border: none; color: #053B2D; font-size: 12px; font-weight: 800; cursor: pointer;">
                Reset Filter
            </button>
        </div>
        @endif
    </div>

</div>
@endsection
