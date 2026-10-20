@extends('layouts.sidebar')

@section('title', 'Marketplace Komoditas - TaniLink')

@section('sidebar_nav')
<a href="{{ route('portal.pembeli') }}" class="nav-btn {{ request()->routeIs('portal.pembeli') ? 'selected' : '' }}">
    <span class="nav-icon">▦</span> Dashboard
</a>
<a href="{{ route('pembeli.katalog') }}" class="nav-btn {{ request()->routeIs('pembeli.katalog*') ? 'selected' : '' }}">
    <span class="nav-icon">▧</span> Marketplace
</a>
<a href="{{ route('pembeli.pesanan') }}" class="nav-btn {{ request()->routeIs('pembeli.pesanan*') ? 'selected' : '' }}">
    <span class="nav-icon">▤</span> Pesanan Saya
</a>
@endsection

@section('breadcrumb')
    TaniLink &rsaquo; <strong>Marketplace Komoditas</strong>
@endsection

@section('content')
<div x-data="{
    search: '',
    kategori: '',
    status: '',
    showBookingModal: false,
    selectedListing: null,
    selectedName: '',
    selectedPrice: 0,
    selectedMax: 0,
    qty: 1,
    needDate: '',
    notes: '',
    confirmed: false,
    get subtotal() { return (this.qty * this.selectedPrice).toLocaleString('id-ID'); },
    get dp() { return Math.round(this.qty * this.selectedPrice * 0.3).toLocaleString('id-ID'); },
    get escrow() { return Math.round(this.qty * this.selectedPrice * 0.7).toLocaleString('id-ID'); }
}" class="space-y-5">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div style="background:#ECFDF8; border:1px solid #6EE7B7; color:#065F46; padding:12px 16px; border-radius:12px; font-size:13px; font-weight:600; display:flex; align-items:center; gap:10px;">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Page Header --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:18px;">
        <div>
            <div style="font-size:10px; font-weight:900; color:#049874; text-transform:uppercase; letter-spacing:1.6px; margin-bottom:6px;">TaniLink / Horeka</div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Marketplace Komoditas</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">Cari hasil panen berdasarkan harga, lokasi, kuantitas, dan rencana waktu panen.</p>
        </div>
        <a href="{{ route('pembeli.pesanan') }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 16px; border-radius:11px; border:1px solid #DFE8E5; background:#fff; color:#365249; font-size:12px; font-weight:800; text-decoration:none;">
            Pesanan Saya ↗
        </a>
    </div>

    {{-- Search / Filter Card --}}
    <div class="card" style="padding: 18px 22px;">
        <div style="display: grid; grid-template-columns: 1fr 160px 160px; gap: 12px; align-items: center;">
            {{-- Search --}}
            <div style="position: relative;">
                <i class="fa-solid fa-search" style="position:absolute; left:13px; top:50%; transform:translateY(-50%); color:#80908D; font-size:12px;"></i>
                <input type="text" x-model="search" placeholder="Cari komoditas, varietas, lokasi..."
                       style="width:100%; padding:10px 14px 10px 36px; border:1.5px solid #E5ECEA; border-radius:11px; font-size:13px; color:#172B29; font-family:inherit; outline:none; transition:border-color 0.15s;"
                       @focus="$el.style.borderColor='#05C793'"
                       @blur="$el.style.borderColor='#E5ECEA'">
            </div>
            {{-- Kategori --}}
            <select x-model="kategori"
                    style="padding:10px 14px; border:1.5px solid #E5ECEA; border-radius:11px; font-size:13px; color:#172B29; font-family:inherit; outline:none; background:white; cursor:pointer;">
                <option value="">Semua Kategori</option>
                @foreach($catalog->pluck('category')->unique()->filter() as $kat)
                <option value="{{ $kat }}">{{ $kat }}</option>
                @endforeach
            </select>
            {{-- Status --}}
            <select x-model="status"
                    style="padding:10px 14px; border:1.5px solid #E5ECEA; border-radius:11px; font-size:13px; color:#172B29; font-family:inherit; outline:none; background:white; cursor:pointer;">
                <option value="">Semua Status</option>
                <option value="available">Tersedia</option>
                <option value="booked">Pre-Order</option>
            </select>
        </div>
    </div>

    {{-- Hasil Count --}}
    <div style="font-size: 13px; color: #80908D; font-weight: 600;">
        <span x-text="document.querySelectorAll('[data-listing]').length"></span>
        Komoditas ditemukan
    </div>

    {{-- Product Grid --}}
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
        @forelse($catalog as $listing)
        @php
            $categoryEmojis = [
                'Sayuran' => '🥬', 'Buah' => '🍊', 'Rempah' => '🌶️',
                'Biji-bijian' => '🌾', 'Umbi' => '🥕', 'Kacang' => '🫘',
                'Herbal' => '🌿', 'Palawija' => '🌽',
            ];
            $emoji = $categoryEmojis[$listing->category] ?? '🌱';
            $imgUrl = $listing->image_url ?? '';
            $hasImage = !empty($imgUrl);
        @endphp
        <div data-listing
             data-name="{{ strtolower($listing->commodity_name . ' ' . ($listing->variety ?? '') . ' ' . ($listing->location ?? '')) }}"
             data-kategori="{{ $listing->category }}"
             data-status="{{ $listing->status }}"
             x-show="
                (search === '' || $el.dataset.name.includes(search.toLowerCase())) &&
                (kategori === '' || $el.dataset.kategori === kategori) &&
                (status === '' || $el.dataset.status === status)
             "
             style="border: 1px solid #E5ECEA; border-radius: 16px; background: white; overflow: hidden; transition: all 0.2s;"
             @mouseenter="$el.style.transform='translateY(-2px)'; $el.style.boxShadow='0 8px 24px rgba(0,0,0,0.08)'"
             @mouseleave="$el.style.transform=''; $el.style.boxShadow=''">

            {{-- Image / Emoji --}}
            <div style="height: 135px; display: flex; align-items: center; justify-content: center; font-size: 48px; position: relative; background: linear-gradient(145deg, #FFE6D9, #FFF3E9);">
                @if($hasImage)
                    <img src="{{ str_starts_with($imgUrl, '/storage') ? asset($imgUrl) : $imgUrl }}"
                         alt="{{ $listing->commodity_name }}"
                         style="width:100%; height:100%; object-fit:cover; position:absolute; inset:0;">
                @else
                    {{ $emoji }}
                @endif
                <span style="position:absolute; top:10px; left:10px; font-size:9px; font-weight:900; background:white; border-radius:8px; padding:4px 8px; box-shadow:0 1px 4px rgba(0,0,0,0.1); z-index:1;">
                    {{ $listing->status === 'available' ? 'Tersedia' : 'Pre-Order' }}
                </span>
            </div>

            {{-- Info --}}
            <div style="padding: 14px;">
                <div style="font-size: 10px; color: #80908D;">{{ $listing->category ?? 'Komoditas' }}</div>
                <h3 style="font-size: 13px; font-weight: 850; margin: 5px 0 3px; color: #172B29; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    {{ $listing->commodity_name }}
                </h3>
                <div style="font-size: 10px; color: #80908D; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    {{ $listing->variety ?? '-' }} • {{ $listing->location ?? 'Indonesia' }}
                </div>

                <div style="display:flex; align-items:baseline; justify-content:space-between; margin-top: 10px;">
                    <div style="font-size: 16px; font-weight: 900; color: #0A765A;">
                        Rp {{ number_format($listing->price_per_kg, 0, ',', '.') }}
                        <span style="font-size: 10px; font-weight: 400; color: #80908D;">/kg</span>
                    </div>
                    <div style="font-size:10px; color:#80908D;">{{ number_format($listing->available_kg, 0, ',', '.') }} kg</div>
                </div>

                <div style="font-size:10px; color:#80908D; margin: 5px 0 12px;">
                    Panen: {{ \Carbon\Carbon::parse($listing->harvest_estimated_date)->format('d M Y') }}
                </div>

                @if($listing->status === 'available' && $listing->available_kg > 0)
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <a href="{{ route('pembeli.komoditas.show', $listing->id) }}"
                       style="display:block; width:100%; text-align:center; padding:8px; border-radius:10px; border:1px solid #DFE8E5; background:#fff; color:#365249; font-size:11px; font-weight:800; text-decoration:none;">
                        Lihat detail ↗
                    </a>
                    <a href="{{ route('pembeli.preorder', $listing->id) }}"
                       style="display:block; width:100%; text-align:center; padding:8px; border-radius:10px; border:none; background:#05C793; color:#053B2D; font-size:11px; font-weight:800; text-decoration:none;">
                        Ajukan Pre-Order
                    </a>
                </div>
                @else
                <div style="display:block; width:100%; padding:9px; border-radius:11px; border:1px solid #E5ECEA; background:#F5F8F7; color:#80908D; font-size:12px; font-weight:700; text-align:center;">
                    Tidak Tersedia
                </div>
                @endif
            </div>
        </div>
        @empty
        <div style="grid-column: span 4; background: white; border: 1px solid #E5ECEA; border-radius: 16px; padding: 60px; text-align: center; color: #80908D;">
            <div style="font-size: 40px; margin-bottom: 12px;">🌾</div>
            <div style="font-size: 14px; font-weight: 700; color: #172B29;">Marketplace Kosong</div>
            <div style="font-size: 12px; margin-top: 5px;">Belum ada komoditas yang ditawarkan petani saat ini.</div>
        </div>
        @endforelse
    </div>

    {{-- BOOKING MODAL --}}
    <div x-cloak x-show="showBookingModal"
         style="position:fixed; inset:0; z-index:50; display:flex; align-items:center; justify-content:center; padding:16px; background:rgba(23,43,41,0.5); backdrop-filter:blur(4px);"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div style="background: white; border-radius: 22px; max-width: 420px; width: 100%; padding: 28px; box-shadow: 0 24px 80px rgba(0,0,0,0.2); position: relative;"
             @click.away="showBookingModal = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            <button @click="showBookingModal = false"
                    style="position:absolute; top:16px; right:16px; width:32px; height:32px; border-radius:9px; border:1px solid #E5ECEA; background:white; cursor:pointer; font-size:14px; color:#80908D; display:flex; align-items:center; justify-content:center;">
                ✕
            </button>

            {{-- Modal Header --}}
            <div style="margin-bottom: 20px;">
                <div style="width:42px; height:42px; border-radius:12px; background:#05C793; display:flex; align-items:center; justify-content:center; color:#053B2D; font-size:18px; margin-bottom:12px;">
                    🛒
                </div>
                <h4 style="font-size:16px; font-weight:900; color:#172B29; margin:0 0 4px;" x-text="selectedName"></h4>
                <p style="font-size:12px; color:#80908D; margin:0;">
                    Rp <span x-text="selectedPrice.toLocaleString('id-ID')"></span>/kg
                    &bull; Stok: <span x-text="selectedMax.toLocaleString('id-ID')"></span> kg
                </p>
            </div>

            <form method="POST" action="{{ route('pembeli.booking.store') }}" class="space-y-4" style="display:flex; flex-direction:column; gap:14px;">
                @csrf
                <input type="hidden" name="listing_id" :value="selectedListing">

                {{-- Jumlah --}}
                <div>
                    <label style="display:block; font-size:12px; font-weight:700; color:#172B29; margin-bottom:6px;">Jumlah Pesanan (kg)</label>
                    <input type="number" name="quantity_kg" x-model="qty" min="1" :max="selectedMax" required
                           style="width:100%; padding:11px 14px; border:1.5px solid #E5ECEA; border-radius:11px; font-size:15px; font-weight:800; color:#172B29; font-family:inherit; outline:none;"
                           @focus="$el.style.borderColor='#05C793'"
                           @blur="$el.style.borderColor='#E5ECEA'"
                           placeholder="Masukkan jumlah...">
                    <div style="display:flex; justify-content:space-between; margin-top:6px; font-size:11px; color:#80908D;">
                        <span>Maks: <strong style="color:#07876B;" x-text="selectedMax.toLocaleString('id-ID')"></strong> kg</span>
                        <span>Total: <strong style="color:#07876B;">Rp <span x-text="subtotal"></span></strong></span>
                    </div>
                </div>

                {{-- Need Date --}}
                <div>
                    <label style="display:block; font-size:12px; font-weight:700; color:#172B29; margin-bottom:6px;">Tanggal Kebutuhan</label>
                    <input type="date" name="need_date" x-model="needDate"
                           style="width:100%; padding:11px 14px; border:1.5px solid #E5ECEA; border-radius:11px; font-size:13px; color:#172B29; font-family:inherit; outline:none;"
                           @focus="$el.style.borderColor='#05C793'"
                           @blur="$el.style.borderColor='#E5ECEA'">
                </div>

                {{-- Catatan --}}
                <div>
                    <label style="display:block; font-size:12px; font-weight:700; color:#172B29; margin-bottom:6px;">Catatan (opsional)</label>
                    <textarea name="notes" x-model="notes" rows="2"
                              style="width:100%; padding:11px 14px; border:1.5px solid #E5ECEA; border-radius:11px; font-size:13px; color:#172B29; font-family:inherit; outline:none; resize:none;"
                              @focus="$el.style.borderColor='#05C793'"
                              @blur="$el.style.borderColor='#E5ECEA'"
                              placeholder="Preferensi kualitas, kemasan, dll..."></textarea>
                </div>

                {{-- Ringkasan harga --}}
                <div style="background:#F5F8F7; border-radius:12px; padding:14px; border:1px solid #E5ECEA;">
                    <div style="font-size:11px; font-weight:800; color:#172B29; margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">Ringkasan Pembayaran</div>
                    <div style="display:flex; justify-content:space-between; font-size:12px; margin-bottom:5px;">
                        <span style="color:#80908D;">Total Nilai</span>
                        <strong style="color:#172B29;">Rp <span x-text="subtotal"></span></strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:12px; margin-bottom:5px;">
                        <span style="color:#80908D;">DP (30%)</span>
                        <strong style="color:#07876B;">Rp <span x-text="dp"></span></strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:12px; padding-top:5px; border-top:1px solid #E5ECEA; margin-top:5px;">
                        <span style="color:#80908D;">Escrow (70%)</span>
                        <strong style="color:#7257CA;">Rp <span x-text="escrow"></span></strong>
                    </div>
                </div>

                {{-- Notice --}}
                <div style="background:#FEF3C7; border:1px solid #FDE68A; border-radius:10px; padding:10px 14px; font-size:11px; color:#92400E; display:flex; gap:8px; align-items:flex-start;">
                    <i class="fa-solid fa-info-circle" style="margin-top:1px; flex-shrink:0;"></i>
                    <span>30% DP langsung ke petani sebagai modal benih. 70% escrow terkunci sampai barang diterima.</span>
                </div>

                {{-- Konfirmasi checkbox --}}
                <label style="display:flex; align-items:flex-start; gap:10px; cursor:pointer;">
                    <input type="checkbox" x-model="confirmed" style="width:16px; height:16px; margin-top:2px; flex-shrink:0; accent-color:#05C793;">
                    <span style="font-size:12px; color:#172B29; font-weight:500; line-height:1.5;">
                        Saya setuju dengan syarat pre-order dan memahami mekanisme escrow TaniLink.
                    </span>
                </label>

                <div style="display:flex; gap:10px; padding-top:4px;">
                    <button type="button" @click="showBookingModal = false"
                            style="flex:0 0 90px; padding:12px; border-radius:11px; border:1.5px solid #E5ECEA; background:white; font-size:12px; font-weight:700; color:#172B29; cursor:pointer; font-family:inherit;">
                        Batal
                    </button>
                    <button type="submit" :disabled="!confirmed || qty < 1"
                            style="flex:1; padding:12px; border-radius:11px; border:none; background:#05C793; color:#053B2D; font-size:12px; font-weight:800; cursor:pointer; font-family:inherit; display:flex; align-items:center; justify-content:center; gap:7px; transition:opacity 0.15s;"
                            :style="!confirmed || qty < 1 ? 'opacity:0.5; cursor:not-allowed;' : ''">
                        <i class="fa-solid fa-check"></i>
                        <span>Konfirmasi Booking</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
