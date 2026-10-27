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
        <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Marketplace Komoditas</h1>
        <p style="font-size:12px; color:#8A9A94; margin:0;">Cari hasil panen segar langsung dari petani lokal dengan kepastian harga dan jadwal panen.</p>
    </div>

    {{-- Search / Filter Card --}}
    <div class="card" style="padding: 20px 22px; margin-bottom: 22px;">
        {{-- Row 1: Search & Category --}}
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 14px; margin-bottom: 14px;">
            {{-- Search --}}
            <div style="position: relative;">
                <i class="fa-solid fa-search" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#80908D; font-size:13px;"></i>
                <input type="text" x-model="search" @input="$nextTick(() => updateCount())" placeholder="Cari nama komoditas, varietas..."
                       style="width:100%; padding:10px 14px 10px 38px; border:1.5px solid #E5ECEA; border-radius:11px; font-size:12.5px; color:#172B29; font-family:inherit; outline:none; transition:border-color 0.15s;"
                       @focus="$el.style.borderColor='#05C793'"
                       @blur="$el.style.borderColor='#E5ECEA'">
            </div>
            {{-- Kategori --}}
            <select x-model="kategori" @change="$nextTick(() => updateCount())"
                    style="width:100%; padding:10px 14px; border:1.5px solid #E5ECEA; border-radius:11px; font-size:12.5px; color:#172B29; font-family:inherit; outline:none; background:white; cursor:pointer;">
                <option value="">Semua Kategori</option>
                @foreach($catalog->pluck('category')->unique()->filter() as $kat)
                <option value="{{ $kat }}">{{ $kat }}</option>
                @endforeach
            </select>
        </div>

        {{-- Row 2: Rentang Harga & Rentang Tanggal Panen + Aksi --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)) auto; gap: 14px; align-items: flex-end;">
            
            {{-- Filter Rentang Harga (Shopee/Sports Station style) --}}
            <div>
                <label style="display:block; font-size:11px; font-weight:750; color:#495D56; margin-bottom:6px;">
                    Rentang Harga (Rp/kg)
                </label>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="position: relative; flex: 1;">
                        <input type="number" x-model="minPriceInput" placeholder="Harga Minimum"
                               min="0" step="500"
                               style="width:100%; padding:9px 12px; border:1.5px solid #E5ECEA; border-radius:10px; font-size:12px; color:#172B29; outline:none; transition:border-color 0.15s;"
                               @focus="$el.style.borderColor='#05C793'"
                               @blur="$el.style.borderColor='#E5ECEA'">
                    </div>
                    <span style="color:#80908D; font-weight:700; font-size:12px;">–</span>
                    <div style="position: relative; flex: 1;">
                        <input type="number" x-model="maxPriceInput" placeholder="Harga Maksimum"
                               min="0" step="500"
                               style="width:100%; padding:9px 12px; border:1.5px solid #E5ECEA; border-radius:10px; font-size:12px; color:#172B29; outline:none; transition:border-color 0.15s;"
                               @focus="$el.style.borderColor='#05C793'"
                               @blur="$el.style.borderColor='#E5ECEA'">
                    </div>
                </div>
                <template x-if="minPriceInput || maxPriceInput">
                    <div style="font-size:10.5px; color:#0A765A; font-weight:700; margin-top:4px;">
                        <span x-text="minPriceInput ? formatRupiah(minPriceInput) : 'Rp 0'"></span>
                        <span> – </span>
                        <span x-text="maxPriceInput ? formatRupiah(maxPriceInput) : 'Tak terbatas'"></span>
                    </div>
                </template>
                <template x-if="priceError">
                    <div style="font-size:10.5px; color:#DC2626; font-weight:700; margin-top:4px;" x-text="priceError"></div>
                </template>
            </div>

            {{-- Filter Rentang Tanggal Panen --}}
            <div>
                <label style="display:block; font-size:11px; font-weight:750; color:#495D56; margin-bottom:6px;">
                    Estimasi Tanggal Panen
                </label>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="position: relative; flex: 1;">
                        <input type="date" x-model="startDateInput"
                               style="width:100%; padding:9px 10px; border:1.5px solid #E5ECEA; border-radius:10px; font-size:12px; color:#172B29; outline:none; background:white; cursor:pointer; transition:border-color 0.15s;"
                               @focus="$el.style.borderColor='#05C793'"
                               @blur="$el.style.borderColor='#E5ECEA'">
                    </div>
                    <span style="color:#80908D; font-weight:700; font-size:12px;">–</span>
                    <div style="position: relative; flex: 1;">
                        <input type="date" x-model="endDateInput"
                               style="width:100%; padding:9px 10px; border:1.5px solid #E5ECEA; border-radius:10px; font-size:12px; color:#172B29; outline:none; background:white; cursor:pointer; transition:border-color 0.15s;"
                               @focus="$el.style.borderColor='#05C793'"
                               @blur="$el.style.borderColor='#E5ECEA'">
                    </div>
                </div>
                <template x-if="dateError">
                    <div style="font-size:10.5px; color:#DC2626; font-weight:700; margin-top:4px;" x-text="dateError"></div>
                </template>
            </div>

            {{-- Tombol Aksi: Terapkan & Reset --}}
            <div style="display: flex; align-items: center; gap: 8px;">
                <button type="button" @click="applyFilter()"
                        style="padding: 10px 18px; border-radius: 10px; background: #05C793; border: none; color: #053B2D; font-size: 12px; font-weight: 850; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(5,199,147,0.25); transition: background 0.15s;"
                        onmouseover="this.style.background='#04b384'" onmouseout="this.style.background='#05C793'">
                    <i class="fa-solid fa-filter"></i> Terapkan Filter
                </button>
                <button type="button" @click="resetFilter()"
                        style="padding: 10px 14px; border-radius: 10px; background: #F1F5F3; border: 1px solid #DFE8E4; color: #4B6358; font-size: 12px; font-weight: 750; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; transition: background 0.15s;"
                        onmouseover="this.style.background='#E5ECE8'" onmouseout="this.style.background='#F1F5F3'">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </button>
            </div>

        </div>
    </div>

    {{-- Product Grid: Only Available Stocks are shown --}}
    @php
        $availableListings = $catalog->filter(function($item) {
            return $item->available_kg > 0 && $item->status !== 'archived';
        });
    @endphp

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 16px;">
        @forelse($availableListings as $listing)
        @php
            $imgUrl = $listing->image_url ?? '';
            $hasImage = !empty($imgUrl);
            $harvestDateYmd = \Carbon\Carbon::parse($listing->harvest_estimated_date)->format('Y-m-d');
        @endphp
        <div data-listing
             x-show="matches({
                 name: '{{ strtolower(addslashes($listing->commodity_name . ' ' . ($listing->variety ?? ''))) }}',
                 kategori: '{{ addslashes($listing->category) }}',
                 harga: {{ (float)$listing->price_per_kg }},
                 date: '{{ $harvestDateYmd }}'
             })"
             style="border: 1px solid #E5ECEA; border-radius: 16px; background: white; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">

            <div>
                {{-- Image / Emoji --}}
                <div style="height: 135px; display: flex; align-items: center; justify-content: center; font-size: 48px; position: relative; background: linear-gradient(145deg, #FFE6D9, #FFF3E9);">
                    @if($hasImage)
                        <img src="{{ str_starts_with($imgUrl, 'http') ? $imgUrl : asset($imgUrl) }}"
                             alt="{{ $listing->commodity_name }}"
                             style="width:100%; height:100%; object-fit:cover; position:absolute; inset:0;">
                    @else
                        <i class="fa-solid fa-leaf" style="font-size:42px; color:#10B981;"></i>
                    @endif
                    <span style="position:absolute; top:10px; left:10px; font-size:9px; font-weight:900; background:white; border-radius:8px; padding:3px 8px; box-shadow:0 1px 4px rgba(0,0,0,0.1); z-index:1; color:#0C8D65;">
                        Tersedia
                    </span>
                </div>

                {{-- Info --}}
                <div style="padding: 14px;">
                    <div style="font-size: 10px; color: #80908D; text-transform:uppercase; font-weight:800;">{{ $listing->category ?? 'Komoditas' }}</div>
                    <h3 style="font-size: 14px; font-weight: 850; margin: 4px 0 2px; color: #172B29; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $listing->commodity_name }}
                    </h3>
                    <div style="font-size: 10.5px; color: #80908D; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $listing->variety ? $listing->variety . ' • ' : '' }}{{ $listing->location ?? 'Malang' }}
                    </div>

                    <div style="display:flex; align-items:baseline; justify-content:space-between; margin: 10px 0 6px;">
                        <div style="font-size: 16px; font-weight: 900; color: #0A765A;">
                            Rp {{ number_format($listing->price_per_kg, 0, ',', '.') }}
                            <span style="font-size: 10px; font-weight: 500; color: #80908D;">/kg</span>
                        </div>
                        <div style="font-size:11px; font-weight:700; color:#172B29;">{{ number_format($listing->available_kg, 0, ',', '.') }} kg</div>
                    </div>

                    <div style="font-size:10px; color:#80908D; margin-bottom: 10px;">
                        Est. Panen: <b style="color:#172B29;">{{ \Carbon\Carbon::parse($listing->harvest_estimated_date)->format('d M Y') }}</b>
                    </div>

                    @if(!empty($listing->description))
                    <div style="font-size:11px; color:#556C62; line-height:1.45; margin-bottom:12px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;" title="{{ $listing->description }}">
                        {{ $listing->description }}
                    </div>
                    @endif
                </div>
            </div>

            {{-- Action Buttons --}}
            <div style="padding: 0 14px 14px; display:flex; flex-direction:column; gap:8px;">
                <a href="{{ route('pembeli.komoditas.show', $listing->id) }}"
                   style="display:flex; align-items:center; justify-content:center; gap:5px; width:100%; text-align:center; padding:8px; border-radius:9px; border:1px solid #DFE8E5; background:#fff; color:#365249; font-size:11.5px; font-weight:800; text-decoration:none;">
                    Lihat Detail
                </a>
                <a href="{{ route('pembeli.preorder', $listing->id) }}"
                   style="display:flex; align-items:center; justify-content:center; gap:6px; width:100%; text-align:center; padding:9px; border-radius:9px; background:#05C793; color:#053B2D; font-size:11.5px; font-weight:800; text-decoration:none; box-shadow:0 2px 8px rgba(5,199,147,0.25);">
                    <i class="fa-solid fa-cart-shopping"></i> Pesan Sebelum Panen
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
