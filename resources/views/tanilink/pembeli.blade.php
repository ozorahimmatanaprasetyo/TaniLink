@extends('layouts.sidebar')

@section('title', 'Dashboard Konsumen - TaniLink')

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

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:20px;">
        <div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">
                Selamat datang, {{ $pembeli->name }} 👋
            </h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">
                Temukan pasokan berkualitas langsung dari petani lokal dengan kontrak harga tetap.
            </p>
        </div>
    </div>

    {{-- Hero Banner --}}
    <div style="display:flex; justify-content:space-between; align-items:center; background:linear-gradient(110deg, #0B644B, #0BA87B); border-radius:20px; min-height:170px; padding:28px 36px; position:relative; overflow:hidden; color:white; margin-bottom:20px; box-shadow:0 8px 24px rgba(11,100,75,0.15);">
        <div style="position:relative; z-index:2; max-width:560px;">
            <h2 style="font-size:22px; font-weight:850; letter-spacing:-0.7px; margin:0 0 10px; line-height:1.3;">
                Pasokan segar berkualitas, terencana sejak sebelum masa tanam.
            </h2>
            <p style="font-size:12px; opacity:0.9; line-height:1.7; margin:0 0 16px;">
                Dapatkan kepastian stok dapur usaha dan konsumsi Anda melalui pesanan langsung ke kelompok tani mitra TaniLink.
            </p>
            <a href="{{ route('pembeli.katalog') }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 20px; border-radius:11px; background:#fff; color:#053B2D; font-size:12px; font-weight:850; text-decoration:none; box-shadow:0 4px 12px rgba(0,0,0,0.12);">
                Jelajahi Marketplace Komoditas
            </a>
        </div>
        <div style="line-height:1; filter:drop-shadow(0 14px 10px rgba(3,53,34,0.3)); z-index:2; user-select:none; color:rgba(255,255,255,0.85); font-size:80px;">
            <i class="fa-solid fa-seedling"></i>
        </div>
    </div>

    {{-- 3 Metric Cards --}}
    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; margin-bottom:24px;">
        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Pesanan Berjalan</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:15px;"><i class="fa-solid fa-bag-shopping"></i></span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ str_pad($myOrders->count(), 2, '0', STR_PAD_LEFT) }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Aktif dalam pemantauan tanam</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Total Nilai Pesanan</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:15px;"><i class="fa-solid fa-receipt"></i></span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                Rp {{ number_format($totalPreOrderValue, 0, ',', '.') }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Total komitmen pasokan</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Pesanan Selesai</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:15px;"><i class="fa-solid fa-circle-check"></i></span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ str_pad($myOrders->where('status', 'selesai')->count(), 2, '0', STR_PAD_LEFT) }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Hasil panen telah diterima</div>
        </div>
    </div>

    {{-- Grid 2 Kolom --}}
    <div style="display:grid; grid-template-columns:minmax(0, 1.6fr) minmax(300px, 1fr); gap:18px;">
        
        {{-- Kiri: Komoditas Pilihan --}}
        <div class="card" style="padding:22px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
                <div>
                    <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Komoditas Pilihan</h2>
                    <p style="font-size:11px; color:#91A19B; margin:0;">Rencanakan pembelian berdasarkan jadwal panen.</p>
                </div>
                <a href="{{ route('pembeli.katalog') }}" style="display:inline-flex; align-items:center; gap:6px; padding:6px 12px; border-radius:8px; background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D; font-size:11px; font-weight:800; text-decoration:none;">
                    Buka Marketplace
                </a>
            </div>

            <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:14px;">
                @foreach($catalog->take(3) as $item)
                @php
                    $imgUrl = $item->image_url ?? '';
                @endphp
                <div style="border:1px solid #E5ECEA; border-radius:16px; background:#fff; overflow:hidden; display:flex; flex-direction:column; justify-content:space-between;">
                    <div style="height:115px; display:grid; place-items:center; font-size:44px; background:linear-gradient(145deg, #FFE6D9, #FFF3E9); position:relative; overflow:hidden;">
                        @if($imgUrl)
                        <img src="{{ str_starts_with($imgUrl, 'http') ? $imgUrl : asset($imgUrl) }}" alt="{{ $item->commodity_name }}" style="width:100%; height:100%; object-fit:cover; position:absolute; inset:0;">
                        @else
                        <i class="fa-solid fa-leaf" style="font-size:36px; color:#10B981;"></i>
                        @endif
                        <span style="position:absolute; top:8px; left:8px; font-size:9px; font-weight:900; background:#fff; border-radius:6px; padding:3px 7px; box-shadow:0 1px 3px rgba(0,0,0,0.08);">
                            {{ $item->status === 'available' ? 'Tersedia' : 'Pesan Sebelum Panen' }}
                        </span>
                    </div>
                    <div style="padding:14px;">
                        <div style="font-size:9px; color:#80908D; text-transform:uppercase; font-weight:800;">{{ $item->category }}</div>
                        <h3 style="font-size:13px; font-weight:850; margin:4px 0 2px; color:#172B29; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $item->commodity_name }}
                        </h3>
                        <div style="font-size:10px; color:#80908D;">{{ $item->variety ?? '-' }} • {{ $item->location ?? 'Malang' }}</div>
                        
                        <div style="font-size:15px; font-weight:900; color:#0A765A; margin:8px 0 6px;">
                            Rp {{ number_format($item->price_per_kg, 0, ',', '.') }}
                            <span style="font-size:10px; font-weight:500; color:#80908D;">/kg</span>
                        </div>

                        <div style="font-size:10px; color:#80908D; margin-bottom:10px;">
                            Est. Panen: <b style="color:#172B29;">{{ \Carbon\Carbon::parse($item->harvest_estimated_date)->format('d M Y') }}</b>
                        </div>

                        @if(!empty($item->description))
                        <div style="font-size:10.5px; color:#556C62; line-height:1.4; margin-bottom:12px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;" title="{{ $item->description }}">
                            {{ $item->description }}
                        </div>
                        @endif

                        <a href="{{ route('pembeli.komoditas.show', $item->id) }}"
                           style="display:block; text-align:center; padding:8px; border-radius:9px; background:#05C793; color:#053B2D; font-size:11px; font-weight:800; text-decoration:none; box-shadow:0 2px 8px rgba(5,199,147,0.2);">
                            Lihat Detail
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Kanan: Pesanan Terbaru --}}
        <div class="card" style="padding:22px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
                <div>
                    <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Pesanan Terbaru</h2>
                    <p style="font-size:11px; color:#91A19B; margin:0;">Status suplai terkini Anda.</p>
                </div>
                <a href="{{ route('pembeli.pesanan') }}" style="display:inline-flex; align-items:center; gap:6px; padding:6px 12px; border-radius:8px; background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D; font-size:11px; font-weight:800; text-decoration:none;">
                    Lihat Semua
                </a>
            </div>

            @if($myOrders->isEmpty())
            <div style="text-align:center; padding:40px 10px; color:#8A9D95;">
                <i class="fa-solid fa-cart-shopping" style="font-size:32px; color:#A9CBBB; margin-bottom:8px; display:block;"></i>
                <div style="font-size:13px; font-weight:700;">Belum Ada Pesanan</div>
                <p style="font-size:11px; margin-top:4px;">Kunjungi Marketplace untuk melakukan pemesanan komoditas pertama Anda.</p>
            </div>
            @else
            <div style="display:flex; flex-direction:column; gap:12px;">
                @foreach($myOrders->take(4) as $order)
                <div style="display:flex; gap:12px; padding-bottom:12px; border-bottom:1px solid #EEF3F0; align-items:center;">
                    <div style="width:30px; height:30px; border-radius:50%; background:#E5FAF1; color:#069873; display:grid; place-items:center; font-size:12px; flex-shrink:0;">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <div style="flex:1;">
                        <b style="font-size:12px; color:#172B29;">{{ $order->commodityListing->commodity_name ?? 'Komoditas' }}</b>
                        <div style="font-size:10px; color:#8A9994; margin-top:2px;">
                            {{ $order->order_code }} &bull; {{ number_format($order->quantity_kg, 0, ',', '.') }} kg &bull; Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </div>
                        <div style="margin-top:4px;">
                            <span style="display:inline-flex; align-items:center; padding:2px 7px; border-radius:5px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:9px;">
                                {{ $order->status_label }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('pembeli.tracking', $order->id) }}"
                       style="display:inline-block; padding:5px 12px; border-radius:7px; background:#EBF9F2; border:1px solid #C6EEDC; color:#078362; font-weight:800; font-size:11px; text-decoration:none; white-space:nowrap;">
                        Pantau
                    </a>
                </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>

@endsection
