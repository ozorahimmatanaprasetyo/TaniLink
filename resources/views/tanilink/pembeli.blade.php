@extends('layouts.sidebar')

@section('title', 'Dashboard Horeka - TaniLink')

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
@endsection

@section('breadcrumb')
    TaniLink &rsaquo; Horeka &rsaquo; <strong>Dashboard</strong>
@endsection

@section('content')

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:20px;">
        <div>
            <div style="font-size:10px; font-weight:900; color:#049874; text-transform:uppercase; letter-spacing:1.6px; margin-bottom:6px;">TaniLink / Horeka</div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">
                Selamat datang, {{ $pembeli->name }} 👋
            </h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">
                Temukan pasokan berkualitas langsung dari petani lokal dengan kontrak harga tetap.
            </p>
        </div>
        <a href="{{ route('pembeli.katalog') }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 16px; border-radius:11px; background:#05C793; color:#053B2D; font-size:12px; font-weight:800; text-decoration:none;">
            Jelajahi Katalog →
        </a>
    </div>

    {{-- Hero Banner --}}
    <div style="display:flex; justify-content:space-between; align-items:center; background:linear-gradient(110deg, #0B644B, #0BA87B); border-radius:20px; min-height:180px; padding:30px 36px; position:relative; overflow:hidden; color:white; margin-bottom:20px; box-shadow:0 8px 24px rgba(11,100,75,0.15);">
        <div style="position:relative; z-index:2; max-width:540px;">
            <h2 style="font-size:22px; font-weight:850; letter-spacing:-0.7px; margin:0 0 10px; line-height:1.3;">
                Pasokan berkualitas, terencana sejak sebelum panen.
            </h2>
            <p style="font-size:12px; opacity:0.85; line-height:1.7; margin:0 0 16px;">
                Dapatkan kepastian stok dapur hotel dan restoran Anda melalui kontrak pre-order langsung ke kelompok tani mitra.
            </p>
            <a href="{{ route('pembeli.katalog') }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 18px; border-radius:11px; background:#fff; color:#053B2D; font-size:12px; font-weight:800; text-decoration:none; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                Cari Komoditas ↗
            </a>
        </div>
        <div style="font-size:100px; line-height:1; filter:drop-shadow(0 14px 10px rgba(3,53,34,0.3)); z-index:2;">
            🌽
        </div>
    </div>

    {{-- 3 Metric Cards --}}
    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; margin-bottom:24px;">
        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Pesanan Berjalan</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">▤</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ str_pad($myOrders->count(), 2, '0', STR_PAD_LEFT) }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Aktif dalam pemantauan tanam</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Total Nilai Pre-Order</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">▥</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                Rp {{ number_format($totalPreOrderValue, 0, ',', '.') }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Total komitmen pasokan</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Escrow Terhitung (70%)</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">◉</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                Rp {{ number_format($escrowHeld, 0, ',', '.') }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Dana diamankan hingga terima barang</div>
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
                <a href="{{ route('pembeli.katalog') }}" style="color:#07946C; font-size:11px; font-weight:850; text-decoration:none;">
                    Marketplace →
                </a>
            </div>

            <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:14px;">
                @foreach($catalog->take(3) as $item)
                @php
                    $emojis = ['Sayuran' => '🍅', 'Cabai & Bumbu' => '🌶️', 'Palawija' => '🌽', 'Buah' => '🍊'];
                    $emoji = $emojis[$item->category] ?? '🧅';
                @endphp
                <div style="border:1px solid #E5ECEA; border-radius:16px; background:#fff; overflow:hidden; display:flex; flex-direction:column; justify-content:space-between;">
                    <div style="height:120px; display:grid; place-items:center; font-size:48px; background:linear-gradient(145deg, #FFE6D9, #FFF3E9); position:relative;">
                        {{ $emoji }}
                        <span style="position:absolute; top:8px; left:8px; font-size:9px; font-weight:900; background:#fff; border-radius:6px; padding:3px 7px;">
                            {{ $item->status === 'available' ? 'Tersedia' : 'Pre-Order' }}
                        </span>
                    </div>
                    <div style="padding:14px;">
                        <div style="font-size:9px; color:#80908D; text-transform:uppercase; font-weight:800;">{{ $item->category }}</div>
                        <h3 style="font-size:13px; font-weight:850; margin:4px 0 2px; color:#172B29; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $item->commodity_name }}
                        </h3>
                        <div style="font-size:10px; color:#80908D;">{{ $item->variety ?? '-' }} • {{ $item->location ?? 'Malang' }}</div>
                        
                        <div style="font-size:15px; font-weight:900; color:#0A765A; margin:10px 0 8px;">
                            Rp {{ number_format($item->price_per_kg, 0, ',', '.') }}
                            <span style="font-size:10px; font-weight:500; color:#80908D;">/kg</span>
                        </div>

                        <div style="font-size:10px; color:#80908D; margin-bottom:10px;">
                            Est. Panen: <b style="color:#172B29;">{{ \Carbon\Carbon::parse($item->harvest_estimated_date)->format('d M') }}</b>
                        </div>

                        <a href="{{ route('pembeli.komoditas.show', $item->id) }}" style="display:block; text-align:center; padding:8px; border-radius:9px; background:#05C793; color:#053B2D; font-size:11px; font-weight:800; text-decoration:none;">
                            Lihat detail ↗
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
                    <p style="font-size:11px; color:#91A19B; margin:0;">Status suplai terkini bisnis Anda.</p>
                </div>
                <a href="{{ route('pembeli.pesanan') }}" style="color:#07946C; font-size:11px; font-weight:850; text-decoration:none;">
                    Semua →
                </a>
            </div>

            @if($myOrders->isEmpty())
            <div style="text-align:center; padding:40px 10px; color:#8A9D95;">
                <div style="font-size:32px; margin-bottom:8px;">🛒</div>
                <div style="font-size:13px; font-weight:700;">Belum Ada Pesanan</div>
                <p style="font-size:11px; margin-top:4px;">Kunjungi Marketplace untuk melakukan booking komoditas pertama Anda.</p>
            </div>
            @else
            <div style="display:flex; flex-direction:column; gap:12px;">
                @foreach($myOrders->take(4) as $order)
                <div style="display:flex; gap:12px; padding-bottom:12px; border-bottom:1px solid #EEF3F0;">
                    <div style="width:28px; height:28px; border-radius:50%; background:#E5FAF1; color:#069873; display:grid; place-items:center; font-size:12px; flex-shrink:0;">
                        ▤
                    </div>
                    <div style="flex:1;">
                        <b style="font-size:12px; color:#172B29;">{{ $order->commodityListing->commodity_name ?? 'Komoditas' }}</b>
                        <div style="font-size:10px; color:#8A9994; margin-top:2px;">
                            {{ $order->order_code }} &bull; {{ number_format($order->quantity_kg, 0, ',', '.') }} kg &bull; Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </div>
                        <div style="margin-top:4px;">
                            <span style="display:inline-flex; align-items:center; padding:2px 7px; border-radius:5px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:9px;">
                                {{ $order->status === 'dp_cair' ? 'DP Disalurkan (30%)' : ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('pembeli.tracking', $order->id) }}" style="font-size:11px; font-weight:850; color:#07946C; text-decoration:none; align-self:center;">
                        Pantau ↗
                    </a>
                </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>

@endsection
