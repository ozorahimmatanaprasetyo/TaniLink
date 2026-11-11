@extends('layouts.sidebar')

@section('title', 'Pesanan Saya - TaniLink')

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
.orders-headline {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 14px;
}
.orders-metrics-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 20px;
}
.orders-toolbar {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
}
.orders-toolbar input {
    flex: 1;
    min-width: 180px;
}
.orders-tabs-scroll {
    display: flex;
    align-items: center;
    gap: 6px;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    padding: 4px;
    margin-bottom: 18px;
    background: #EBF2EE;
    border-radius: 12px;
    border: 1px solid #DCE6E1;
}
.orders-tabs-scroll::-webkit-scrollbar {
    display: none;
}
.orders-tab-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 700;
    color: #556B62;
    background: transparent;
    border: none;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.15s ease;
    text-decoration: none;
    font-family: inherit;
}
.orders-tab-pill:hover {
    color: #172B29;
    background: rgba(255, 255, 255, 0.6);
}
.orders-tab-pill.active {
    background: #ffffff;
    color: #0A765A;
    font-weight: 850;
    box-shadow: 0 2px 6px rgba(10, 118, 90, 0.08);
}
.orders-tab-badge {
    font-size: 10px;
    font-weight: 800;
    padding: 2px 6px;
    border-radius: 6px;
    background: #D8E5DF;
    color: #485E55;
    line-height: 1.2;
}
.orders-tab-pill.active .orders-tab-badge {
    background: #E8F8F1;
    color: #0A765A;
}

/* Card Order Shopee Style */
.consumer-order-card {
    border: 1px solid #E5ECEA;
    border-radius: 14px;
    background: #ffffff;
    padding: 16px;
    margin-bottom: 12px;
    transition: box-shadow 0.15s, border-color 0.15s;
}
.consumer-order-card:hover {
    border-color: #BEE3D5;
    box-shadow: 0 4px 14px rgba(10, 118, 90, 0.05);
}
.consumer-order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 12px;
    border-bottom: 1px solid #F0F4F2;
    gap: 10px;
    flex-wrap: wrap;
}
.consumer-order-farmer {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 800;
    color: #172B29;
}
.consumer-order-farmer-icon {
    width: 26px;
    height: 26px;
    border-radius: 8px;
    background: #DCF8EB;
    color: #007B55;
    display: grid;
    place-items: center;
    font-size: 12px;
}
.consumer-order-body {
    display: flex;
    gap: 14px;
    padding: 14px 0;
    border-bottom: 1px solid #F0F4F2;
    align-items: center;
}
.consumer-order-img-box {
    width: 72px;
    height: 72px;
    border-radius: 10px;
    overflow: hidden;
    position: relative;
    background: #F0FDF4;
    flex-shrink: 0;
    border: 1px solid #E5ECEA;
}
.consumer-order-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.consumer-order-info {
    flex: 1;
    min-width: 0;
}
.consumer-order-title {
    font-size: 13.5px;
    font-weight: 850;
    color: #172B29;
    margin: 0 0 4px;
    line-height: 1.3;
}
.consumer-order-meta {
    font-size: 11px;
    color: #71877F;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
}
.consumer-order-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 12px;
    gap: 10px;
    flex-wrap: wrap;
}
.consumer-order-total-box {
    display: flex;
    align-items: baseline;
    gap: 6px;
}
.consumer-order-total-label {
    font-size: 11px;
    color: #8A9A94;
}
.consumer-order-total-val {
    font-size: 15px;
    font-weight: 900;
    color: #087A59;
}
.consumer-order-actions {
    display: flex;
    gap: 8px;
    align-items: center;
}

@media (max-width: 768px) {
    .orders-headline {
        flex-direction: column;
        align-items: flex-start;
    }
    .orders-headline a {
        width: 100%;
        justify-content: center;
    }
    .orders-metrics-grid {
        grid-template-columns: 1fr 1fr;
    }
    .orders-metrics-grid > div:last-child {
        grid-column: span 2;
    }
}
@media (max-width: 480px) {
    .orders-metrics-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 10px !important;
        margin-bottom: 16px !important;
    }
    .orders-metrics-grid > div:last-child {
        grid-column: span 2 !important;
    }
    .orders-metrics-grid .card {
        padding: 12px 14px !important;
        border-radius: 14px !important;
    }
    .orders-headline h1 {
        font-size: 20px !important;
    }
    .consumer-order-card {
        padding: 12px !important;
        border-radius: 12px !important;
    }
    .consumer-order-img-box {
        width: 62px;
        height: 62px;
    }
    .consumer-order-title {
        font-size: 12.5px !important;
    }
    .consumer-order-footer {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
    }
    .consumer-order-total-box {
        justify-content: space-between;
    }
    .consumer-order-actions {
        width: 100%;
    }
    .consumer-order-actions a {
        width: 100%;
        text-align: center;
        justify-content: center;
        padding: 9px 12px !important;
        font-size: 11.5px !important;
    }
}
</style>
@endpush

@section('content')
@php
    $countAll = $allOrders->count();
    $countWaitingPayment = $allOrders->whereIn('status', ['menunggu_pembayaran_dp'])->count();
    $countWaitingConfirm = $allOrders->whereIn('status', ['menunggu_konfirmasi', 'menunggu_verifikasi_dp'])->count();
    $countProcessing = $allOrders->whereIn('status', ['pesanan_aktif', 'dikonfirmasi', 'dp_cair', 'tanam_berjalan', 'sedang_berlangsung', 'siap_panen'])->count();
    $countShipping = $allOrders->where('status', 'dalam_pengiriman')->count();
    $countCompleted = $allOrders->where('status', 'selesai')->count();
    $countCancelled = $allOrders->where('status', 'dibatalkan')->count();
@endphp

<div x-data="{ 
    currentTab: '{{ request('status_tab', 'all') }}',
    search: '{{ request('q', '') }}',

    matchesFilter(order) {
        // 1. Text Search Filter
        if (this.search !== '') {
            let s = this.search.toLowerCase();
            let target = (order.code + ' ' + order.commodity + ' ' + order.farmer).toLowerCase();
            if (!target.includes(s)) return false;
        }

        // 2. Status Tab Filter
        if (this.currentTab === 'all') return true;
        if (this.currentTab === 'waiting_payment') return order.status === 'menunggu_pembayaran_dp';
        if (this.currentTab === 'waiting_confirm') return ['menunggu_konfirmasi', 'menunggu_verifikasi_dp'].includes(order.status);
        if (this.currentTab === 'processing') return ['pesanan_aktif', 'dikonfirmasi', 'dp_cair', 'tanam_berjalan', 'sedang_berlangsung', 'siap_panen'].includes(order.status);
        if (this.currentTab === 'shipping') return order.status === 'dalam_pengiriman';
        if (this.currentTab === 'completed') return order.status === 'selesai';
        if (this.currentTab === 'cancelled') return order.status === 'dibatalkan';
        return true;
    }
}">

    {{-- Headline --}}
    <div class="orders-headline">
        <div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Pesanan Saya</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">Pantau status pemesanan, pembayaran DP, progres budidaya kebun, dan riwayat panen.</p>
        </div>
        <a href="{{ route('pembeli.katalog') }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 16px; border-radius:11px; background:#05C793; color:#053B2D; font-size:12px; font-weight:800; text-decoration:none; box-shadow:0 3px 10px rgba(5,199,147,0.2);">
            <i class="fa-solid fa-plus"></i> Jelajahi Marketplace
        </a>
    </div>

    {{-- 3 Metric Cards --}}
    <div class="orders-metrics-grid">
        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Pesanan Aktif</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:15px;"><i class="fa-solid fa-seedling"></i></span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ str_pad($activeOrders->count(), 2, '0', STR_PAD_LEFT) }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Dalam masa pemenuhan / budidaya</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Nilai Pesanan Aktif</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:15px;"><i class="fa-solid fa-receipt"></i></span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29; white-space:nowrap;">
                Rp {{ number_format($totalPreOrderValue, 0, ',', '.') }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Nilai kontrak aktif berjalan</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Pesanan Selesai</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E0F2FE; color:#0284C7; font-size:15px;"><i class="fa-solid fa-circle-check"></i></span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ str_pad($countCompleted, 2, '0', STR_PAD_LEFT) }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Hasil panen berhasil diserahterimakan</div>
        </div>
    </div>

    {{-- Kategori / Status Tabs Shopee Style (Horizontal Scroll on Mobile) --}}
    <div class="orders-tabs-scroll">
        <button type="button" @click="currentTab = 'all'"
                class="orders-tab-pill" :class="{ 'active': currentTab === 'all' }">
            <span>Semua</span>
            <span class="orders-tab-badge">{{ $countAll }}</span>
        </button>

        <button type="button" @click="currentTab = 'waiting_payment'"
                class="orders-tab-pill" :class="{ 'active': currentTab === 'waiting_payment' }">
            <span>Menunggu Pembayaran</span>
            @if($countWaitingPayment > 0)
                <span class="orders-tab-badge">{{ $countWaitingPayment }}</span>
            @endif
        </button>

        <button type="button" @click="currentTab = 'waiting_confirm'"
                class="orders-tab-pill" :class="{ 'active': currentTab === 'waiting_confirm' }">
            <span>Menunggu Konfirmasi</span>
            @if($countWaitingConfirm > 0)
                <span class="orders-tab-badge">{{ $countWaitingConfirm }}</span>
            @endif
        </button>

        <button type="button" @click="currentTab = 'processing'"
                class="orders-tab-pill" :class="{ 'active': currentTab === 'processing' }">
            <span>Diproses</span>
            @if($countProcessing > 0)
                <span class="orders-tab-badge">{{ $countProcessing }}</span>
            @endif
        </button>

        <button type="button" @click="currentTab = 'shipping'"
                class="orders-tab-pill" :class="{ 'active': currentTab === 'shipping' }">
            <span>Dalam Pengiriman</span>
            @if($countShipping > 0)
                <span class="orders-tab-badge">{{ $countShipping }}</span>
            @endif
        </button>

        <button type="button" @click="currentTab = 'completed'"
                class="orders-tab-pill" :class="{ 'active': currentTab === 'completed' }">
            <span>Selesai</span>
            @if($countCompleted > 0)
                <span class="orders-tab-badge">{{ $countCompleted }}</span>
            @endif
        </button>

        <button type="button" @click="currentTab = 'cancelled'"
                class="orders-tab-pill" :class="{ 'active': currentTab === 'cancelled' }">
            <span>Dibatalkan</span>
            @if($countCancelled > 0)
                <span class="orders-tab-badge">{{ $countCancelled }}</span>
            @endif
        </button>
    </div>

    {{-- Search Toolbar --}}
    <div style="margin-bottom: 16px;">
        <div style="position:relative; max-width:480px;">
            <i class="fa-solid fa-search" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#80908D; font-size:13px;"></i>
            <input type="text" x-model="search" placeholder="Cari kode pesanan, komoditas, petani..."
                   style="width:100%; height:40px; padding:8px 14px 8px 38px; border:1.5px solid #E5ECEA; border-radius:10px; font-size:12px; color:#172B29; font-family:inherit; outline:none; transition:border-color 0.15s; box-sizing:border-box;">
        </div>
    </div>

    {{-- DAFTAR KARTU PESANAN (SHOPEE STYLE) --}}
    @if($allOrders->isEmpty())
        {{-- EMPTY STATE: BELUM ADA PESANAN SAMA SEKALI --}}
        <div class="card" style="padding: 60px 20px; text-align: center;">
            <div style="width: 72px; height: 72px; border-radius: 20px; background: #E9FBF3; color: #0C8D65; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; margin-bottom: 16px;">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <h2 style="font-size: 18px; font-weight: 850; color: #172B29; margin: 0 0 6px;">Belum Ada Pesanan</h2>
            <p style="font-size: 12px; color: #8A9A94; max-width: 380px; margin: 0 auto 20px; line-height: 1.6;">
                Anda belum pernah mengajukan pesanan komoditas. Jelajahi katalog dan pesan hasil panen langsung dari petani terpercaya.
            </p>
            <a href="{{ route('pembeli.katalog') }}"
               style="display: inline-flex; align-items: center; gap: 8px; padding: 11px 22px; border-radius: 11px; background: #05C793; color: #053B2D; font-size: 12.5px; font-weight: 850; text-decoration: none; box-shadow: 0 3px 12px rgba(5,199,147,0.25);">
                <i class="fa-solid fa-store"></i> Mulai Belanja
            </a>
        </div>
    @else
        <div>
            @foreach($allOrders as $order)
            @php
                $comm = $order->commodityListing;
                $imgSrc = $comm ? $comm->resolved_image_url : asset('images/products/placeholder-sayuran.jpg');
                $farmerName = $order->farmer->name ?? ($comm->farmer->name ?? 'Petani Mitra');
                $orderData = [
                    'code' => $order->order_code,
                    'commodity' => $comm->commodity_name ?? 'Komoditas',
                    'farmer' => $farmerName,
                    'status' => $order->status,
                ];
            @endphp
            <div class="consumer-order-card"
                 x-show="matchesFilter({{ json_encode($orderData) }})"
                 x-transition>
                
                {{-- Header Kartu: Petani & Status --}}
                <div class="consumer-order-header">
                    <div class="consumer-order-farmer">
                        <span class="consumer-order-farmer-icon">
                            <i class="fa-solid fa-user-tie"></i>
                        </span>
                        <span>{{ $farmerName }}</span>
                        <span style="font-size:10px; color:#A2AEAA; font-weight:500;">&bull;</span>
                        <span style="font-size:11px; color:#71877F; font-weight:600;">{{ $order->order_code }}</span>
                    </div>

                    {{-- Status Badge --}}
                    <div>
                        @if($order->status === 'menunggu_konfirmasi')
                            <span style="display:inline-flex; align-items:center; padding:3px 9px; border-radius:6px; background:#FFF8EA; color:#B45309; font-weight:800; font-size:10.5px;">
                                <i class="fa-regular fa-clock" style="margin-right:4px;"></i> Menunggu Konfirmasi
                            </span>
                        @elseif($order->status === 'menunggu_pembayaran_dp')
                            <span style="display:inline-flex; align-items:center; padding:3px 9px; border-radius:6px; background:#FEF3C7; color:#92400E; font-weight:800; font-size:10.5px;">
                                <i class="fa-solid fa-wallet" style="margin-right:4px;"></i> Menunggu Pembayaran DP
                            </span>
                        @elseif($order->status === 'menunggu_verifikasi_dp')
                            <span style="display:inline-flex; align-items:center; padding:3px 9px; border-radius:6px; background:#DBEAFE; color:#1E40AF; font-weight:800; font-size:10.5px;">
                                <i class="fa-solid fa-magnifying-glass" style="margin-right:4px;"></i> Pemeriksaan Pembayaran DP
                            </span>
                        @elseif(in_array($order->status, ['pesanan_aktif', 'dikonfirmasi', 'dp_cair', 'tanam_berjalan', 'sedang_berlangsung']))
                            <span style="display:inline-flex; align-items:center; padding:3px 9px; border-radius:6px; background:#EFF6FF; color:#1D4ED8; font-weight:800; font-size:10.5px;">
                                <i class="fa-solid fa-seedling" style="margin-right:4px;"></i> Masa Budidaya / Berjalan
                            </span>
                        @elseif($order->status === 'siap_panen')
                            <span style="display:inline-flex; align-items:center; padding:3px 9px; border-radius:6px; background:#E9FBF3; color:#0A765A; font-weight:800; font-size:10.5px;">
                                <i class="fa-solid fa-box-open" style="margin-right:4px;"></i> Siap Panen ✓
                            </span>
                        @elseif($order->status === 'dalam_pengiriman')
                            <span style="display:inline-flex; align-items:center; padding:3px 9px; border-radius:6px; background:#F0FDF4; color:#15803D; font-weight:800; font-size:10.5px;">
                                <i class="fa-solid fa-truck" style="margin-right:4px;"></i> Dalam Pengiriman
                            </span>
                        @elseif($order->status === 'selesai')
                            <span style="display:inline-flex; align-items:center; padding:3px 9px; border-radius:6px; background:#D1FAE5; color:#065F46; font-weight:800; font-size:10.5px;">
                                <i class="fa-solid fa-circle-check" style="margin-right:4px;"></i> Selesai
                            </span>
                        @elseif($order->status === 'dibatalkan')
                            <span style="display:inline-flex; align-items:center; padding:3px 9px; border-radius:6px; background:#FEE2E2; color:#991B1B; font-weight:800; font-size:10.5px;">
                                <i class="fa-solid fa-circle-xmark" style="margin-right:4px;"></i> Dibatalkan / Ditolak
                            </span>
                        @else
                            <span style="display:inline-flex; align-items:center; padding:3px 9px; border-radius:6px; background:#F3F4F6; color:#6B7280; font-weight:800; font-size:10.5px;">
                                {{ $order->status_label }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Body Kartu: Gambar & Info Komoditas --}}
                <div class="consumer-order-body">
                    <div class="consumer-order-img-box">
                        <img src="{{ $imgSrc }}"
                             alt="{{ $comm->commodity_name ?? 'Komoditas' }}"
                             class="consumer-order-img"
                             onerror="this.onerror=null; this.src='{{ asset('images/products/placeholder-sayuran.jpg') }}';">
                    </div>
                    <div class="consumer-order-info">
                        <h3 class="consumer-order-title">
                            {{ $comm->commodity_name ?? 'Komoditas' }}
                        </h3>
                        <div class="consumer-order-meta">
                            <span>Varietas: <b>{{ $comm->variety ?? '-' }}</b></span>
                            <span>&bull;</span>
                            <span>Jumlah: <b>{{ number_format($order->quantity_kg, 0, ',', '.') }} kg</b></span>
                            <span>&bull;</span>
                            <span>@ Rp {{ number_format($order->price_per_kg, 0, ',', '.') }}/kg</span>
                        </div>
                        <div style="font-size:10.5px; color:#8A9A94; margin-top:4px;">
                            Dipesan pada: {{ $order->created_at ? $order->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') : '-' }} WIB
                        </div>
                    </div>
                </div>

                {{-- Footer Kartu: Total Belanja & Tombol Tindakan --}}
                <div class="consumer-order-footer">
                    <div class="consumer-order-total-box">
                        <span class="consumer-order-total-label">Total Pesanan:</span>
                        <span class="consumer-order-total-val">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        @if($order->status === 'menunggu_pembayaran_dp')
                            <span style="font-size:10.5px; color:#D97706; font-weight:700;">(DP 30%: Rp {{ number_format($order->dp_amount, 0, ',', '.') }})</span>
                        @endif
                    </div>

                    <div class="consumer-order-actions">
                        {{-- Tombol Detail / Pantau --}}
                        @if(in_array($order->status, ['selesai', 'dibatalkan']))
                            <a href="{{ route('pembeli.pesanan.history', $order->id) }}"
                               class="btn btn-secondary"
                               style="padding:7px 14px; font-size:11.5px; border-radius:9px; min-height:auto;">
                                <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Lengkap
                            </a>
                            <a href="{{ route('pembeli.pesanan.pdf', $order->id) }}"
                               class="btn btn-secondary"
                               style="padding:7px 12px; font-size:11.5px; border-radius:9px; min-height:auto;"
                               title="Unduh Invoice PDF">
                                <i class="fa-solid fa-file-pdf" style="color:#DC2626;"></i> PDF
                            </a>
                        @else
                            <a href="{{ route('pembeli.tracking', $order->id) }}"
                               class="btn btn-primary"
                               style="padding:7px 16px; font-size:11.5px; border-radius:9px; min-height:auto;">
                                @if($order->status === 'menunggu_pembayaran_dp')
                                    <i class="fa-solid fa-upload"></i> Bayar / Unggah DP
                                @else
                                    <i class="fa-solid fa-eye"></i> Lihat Detail Pesanan
                                @endif
                            </a>
                        @endif
                    </div>
                </div>

            </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
