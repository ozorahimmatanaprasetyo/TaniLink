@extends('layouts.sidebar')

@section('title', 'Marketplace Komoditas - TaniLink')

@section('sidebar_nav')
<a href="{{ route('portal.pembeli') }}" class="nav-btn {{ request()->routeIs('portal.pembeli') ? 'active' : '' }}">
    <span class="nav-icon">▦</span> Dashboard
</a>
<a href="{{ route('pembeli.katalog') }}" class="nav-btn {{ request()->routeIs('pembeli.katalog*') ? 'active' : '' }}">
    <span class="nav-icon">▧</span> Marketplace
</a>
<a href="{{ route('pembeli.pesanan') }}" class="nav-btn {{ request()->routeIs('pembeli.pesanan*') ? 'active' : '' }}">
    <span class="nav-icon">▤</span> Pesanan Saya
</a>
<a href="{{ route('pembeli.profil') }}" class="nav-btn {{ request()->routeIs('pembeli.profil*') ? 'active' : '' }}">
    <span class="nav-icon">♙</span> Profil Konsumen
</a>
@endsection

@section('content')
<div x-data="{
    search: '',
    kategori: ''
}">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div style="background:#ECFDF8; border:1px solid #6EE7B7; color:#065F46; padding:12px 16px; border-radius:12px; font-size:13px; font-weight:600; display:flex; align-items:center; gap:10px; margin-bottom:18px;">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Page Header (No redundant Pesanan Saya button) --}}
    <div style="margin-bottom:18px;">
        <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Marketplace Komoditas</h1>
        <p style="font-size:12px; color:#8A9A94; margin:0;">Cari hasil panen segar langsung dari petani lokal dengan kepastian harga dan jadwal panen.</p>
    </div>

    {{-- Search / Filter Card --}}
    <div class="card" style="padding: 18px 22px; margin-bottom: 20px;">
        <div style="display: grid; grid-template-columns: 1fr 180px; gap: 14px; align-items: center;">
            {{-- Search --}}
            <div style="position: relative;">
                <i class="fa-solid fa-search" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#80908D; font-size:13px;"></i>
                <input type="text" x-model="search" placeholder="Cari komoditas, varietas benih, lokasi kebun..."
                       style="width:100%; padding:10px 14px 10px 38px; border:1.5px solid #E5ECEA; border-radius:11px; font-size:13px; color:#172B29; font-family:inherit; outline:none; transition:border-color 0.15s;"
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
        </div>
    </div>

    {{-- Product Grid: Only Available Stocks are shown --}}
    @php
        $availableListings = $catalog->filter(function($item) {
            return $item->available_kg > 0 && $item->status !== 'archived';
        });
    @endphp

    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
        @forelse($availableListings as $listing)
        @php
            $categoryEmojis = [
                'Sayuran' => '🥬', 'Buah' => '🍊', 'Rempah' => '🌶️',
                'Biji-bijian' => '🌾', 'Umbi' => '🥕', 'Kacang' => '🫘',
                'Herbal' => '🌿', 'Palawija' => '🌽', 'Cabai & Bumbu' => '🌶️'
            ];
            $emoji = $categoryEmojis[$listing->category] ?? '🌱';
            $imgUrl = $listing->image_url ?? '';
            $hasImage = !empty($imgUrl);
        @endphp
        <div data-listing
             data-name="{{ strtolower($listing->commodity_name . ' ' . ($listing->variety ?? '') . ' ' . ($listing->location ?? '')) }}"
             data-kategori="{{ $listing->category }}"
             x-show="
                (search === '' || $el.dataset.name.includes(search.toLowerCase())) &&
                (kategori === '' || $el.dataset.kategori === kategori)
             "
             style="border: 1px solid #E5ECEA; border-radius: 16px; background: white; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">

            <div>
                {{-- Image / Emoji --}}
                <div style="height: 135px; display: flex; align-items: center; justify-content: center; font-size: 48px; position: relative; background: linear-gradient(145deg, #FFE6D9, #FFF3E9);">
                    @if($hasImage)
                        <img src="{{ str_starts_with($imgUrl, 'http') ? $imgUrl : asset($imgUrl) }}"
                             alt="{{ $listing->commodity_name }}"
                             style="width:100%; height:100%; object-fit:cover; position:absolute; inset:0;">
                    @else
                        {{ $emoji }}
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
                        {{ $listing->variety ?? '-' }} • {{ $listing->location ?? 'Malang' }}
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
                    Lihat Detail ↗
                </a>
                <a href="{{ route('pembeli.preorder', $listing->id) }}"
                   style="display:flex; align-items:center; justify-content:center; gap:6px; width:100%; text-align:center; padding:9px; border-radius:9px; background:#05C793; color:#053B2D; font-size:11.5px; font-weight:800; text-decoration:none; box-shadow:0 2px 8px rgba(5,199,147,0.25);">
                    <i class="fa-solid fa-cart-shopping"></i> Ajukan Pre-Order ↗
                </a>
            </div>
        </div>
        @empty
        <div style="grid-column: span 4; background: white; border: 1px solid #E5ECEA; border-radius: 16px; padding: 60px; text-align: center; color: #80908D;">
            <div style="font-size: 40px; margin-bottom: 12px;">🌾</div>
            <div style="font-size: 14px; font-weight: 700; color: #172B29;">Belum Ada Komoditas Tersedia</div>
            <div style="font-size: 12px; margin-top: 5px;">Saat petani mempublikasikan tanaman dengan stok tersedia, komoditas akan muncul di sini.</div>
        </div>
        @endforelse
    </div>

</div>
@endsection
