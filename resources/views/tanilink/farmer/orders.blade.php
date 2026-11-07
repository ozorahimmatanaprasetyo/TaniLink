@extends('layouts.sidebar')

@section('title', 'Pesanan Masuk - TaniLink')

@section('sidebar_nav')
<a href="{{ route('portal.petani') }}" class="nav-btn {{ request()->routeIs('portal.petani') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-chart-pie"></i></span> Beranda
</a>
<a href="{{ route('petani.komoditas') }}" class="nav-btn {{ request()->routeIs('petani.komoditas*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-seedling"></i></span> Komoditas Saya
</a>
<a href="{{ route('petani.pesanan') }}" class="nav-btn {{ request()->routeIs('petani.pesanan*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-clipboard-list"></i></span> Pesanan Masuk
</a>
<a href="{{ route('petani.profil') }}" class="nav-btn {{ request()->routeIs('petani.profil') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-user"></i></span> Profil Petani
</a>
@endsection

@push('styles')
<style>
    .orders-headline {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 14px;
    }
    .orders-metrics-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    .orders-toolbar {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .orders-toolbar input,
    .orders-toolbar select {
        flex: 1;
        min-width: 140px;
    }
    .history-toolbar {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin: 0;
    }
    .history-toolbar input,
    .history-toolbar select {
        flex: 1;
        min-width: 140px;
    }

    /* Mobile order card */
    .order-card-mobile {
        display: none;
    }
    .order-table-desktop {
        display: block;
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
        .orders-toolbar {
            flex-direction: column;
        }
        .orders-toolbar input,
        .orders-toolbar select {
            width: 100%;
        }
        .history-toolbar {
            flex-direction: column;
        }
        .history-toolbar input,
        .history-toolbar select,
        .history-toolbar button {
            width: 100%;
        }
        .order-card-mobile {
            display: flex;
        }
        .order-table-desktop {
            display: none;
        }
    }
    @media (max-width: 480px) {
        .orders-metrics-grid {
            grid-template-columns: 1fr;
        }
        .orders-metrics-grid > div:last-child {
            grid-column: span 1;
        }
    }
</style>
@endpush

@section('content')
<div x-data="{ 
    tab: '{{ request('tab', (request('q') || request('status')) ? 'history' : 'active') }}', 
    search: '', 
    filterStatus: '' 
}">

    {{-- Headline --}}
    <div class="orders-headline">
        <div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Pesanan Masuk</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">Kelola permintaan pre-order, kontrak aktif, dan arsip riwayat pesanan.</p>
        </div>
        @if($activeOrders->isNotEmpty())
        <a href="{{ route('petani.komoditas') }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 16px; border-radius:11px; background:#05C793; color:#053B2D; font-size:12px; font-weight:800; text-decoration:none; box-shadow:0 3px 10px rgba(5,199,147,0.2);">
            <i class="fa-solid fa-leaf"></i> Kelola Batch & Perkembangan
        </a>
        @endif
    </div>

    {{-- 3 Metric Cards --}}
    <div class="orders-metrics-grid">
        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Pesanan Aktif</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:15px;"><i class="fa-solid fa-clipboard-list"></i></span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ str_pad($activeOrders->count(), 2, '0', STR_PAD_LEFT) }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Perlu diproses / sedang berjalan</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Jumlah Panen yang Dipesan</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:15px;"><i class="fa-solid fa-weight-hanging"></i></span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ number_format($totalKg, 0, ',', '.') }} kg
            </div>
            <div style="font-size:10px; color:#7B9187;">Komitmen panen aktif</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Riwayat Selesai &amp; Arsip</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E0F2FE; color:#0284C7; font-size:15px;"><i class="fa-solid fa-clock-rotate-left"></i></span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29; white-space:nowrap;">
                {{ str_pad($historyOrders->count(), 2, '0', STR_PAD_LEFT) }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Pesanan selesai atau dibatalkan</div>
        </div>
    </div>

    </div>

    {{-- Segmented Navigation Tabs --}}
    <div class="segmented-tabs-wrapper">
        <button type="button" @click="tab = 'active'"
                class="segmented-tab-btn" :class="{ 'active': tab === 'active' }">
            <i class="fa-solid fa-list-check segmented-tab-icon"></i>
            <span>Pesanan Aktif</span>
            <span class="segmented-tab-badge">
                {{ $activeOrders->count() }}
            </span>
        </button>

        <button type="button" @click="tab = 'history'"
                class="segmented-tab-btn" :class="{ 'active': tab === 'history' }">
            <i class="fa-solid fa-clock-rotate-left segmented-tab-icon"></i>
            <span>Riwayat Pesanan</span>
            <span class="segmented-tab-badge">
                {{ $historyOrders->count() }}
            </span>
        </button>
    </div>

    {{-- TAB 1: PESANAN AKTIF --}}
    <div x-show="tab === 'active'" class="card" style="padding:24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:12px;">
            <div>
                <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Daftar Pesanan Aktif</h2>
                <p style="font-size:11px; color:#91A19B; margin:0;">Status kontrak berjalan dan jadwal pemenuhan panen.</p>
            </div>

            {{-- Toolbar --}}
            <div class="orders-toolbar">
                <input type="text" x-model="search" placeholder="⌕ Cari kode, produk, pembeli..."
                       style="padding:9px 13px; border:1px solid #E5ECEA; border-radius:10px; font-size:12px; color:#50695E; background:#fff; outline:0;">
                <select x-model="filterStatus"
                        style="padding:9px 13px; border:1px solid #E5ECEA; border-radius:10px; font-size:12px; color:#50695E; background:#fff; outline:0;">
                    <option value="">Semua status aktif</option>
                    <option value="menunggu_konfirmasi">Menunggu Konfirmasi</option>
                    <option value="menunggu_pembayaran_dp">Menunggu DP</option>
                    <option value="menunggu_verifikasi_dp">Periksa Bukti DP</option>
                    <option value="pesanan_aktif">Pesanan Aktif</option>
                    <option value="siap_panen">Siap Panen</option>
                    <option value="dalam_pengiriman">Dalam Pengiriman</option>
                </select>
            </div>
        </div>

        @if($activeOrders->isEmpty())
        <div style="text-align:center; padding:50px 20px; color:#8A9D95;">
            <div style="width:54px; height:54px; border-radius:16px; background:#F2F8F5; color:#05C793; display:inline-flex; align-items:center; justify-content:center; font-size:24px; margin-bottom:12px;">
                <i class="fa-solid fa-clipboard-list"></i>
            </div>
            <h3 style="font-size:15px; font-weight:800; color:#172B29; margin-bottom:4px;">Tidak Ada Pesanan Aktif</h3>
            <p style="font-size:12px; margin-top:4px;">Saat ini semua pesanan telah terselesaikan atau belum ada pesanan baru.</p>
        </div>
        @else

        {{-- DESKTOP TABLE --}}
        <div class="order-table-desktop" style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:left;">
                <thead>
                    <tr>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">KODE PESANAN</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">KOMODITAS</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">PEMBELI</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">QTY</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">TOTAL</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">STATUS</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px; text-align:right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activeOrders as $order)
                    @php
                        $searchTarget = strtolower($order->order_code . ' ' . ($order->commodityListing->commodity_name ?? '') . ' ' . ($order->buyer->name ?? ''));
                    @endphp
                    <tr x-show="(search === '' || '{{ $searchTarget }}'.includes(search.toLowerCase())) && (filterStatus === '' || '{{ $order->status }}' === filterStatus)"
                        style="border-bottom:1px solid #EEF2F0;">
                        <td style="font-size:11px; font-weight:800; color:#183B30; padding:15px 10px;">
                            {{ $order->order_code }}
                        </td>
                        <td style="font-size:11px; padding:15px 10px;">
                            <b style="font-weight:850; color:#183B30;">{{ $order->commodityListing->commodity_name ?? 'Komoditas' }}</b>
                            <div style="font-size:10px; color:#85958F;">{{ $order->commodityListing->variety ?? '-' }}</div>
                        </td>
                        <td style="font-size:11px; font-weight:650; color:#172B29; padding:15px 10px;">
                            <div>{{ $order->buyer->name ?? 'Pembeli' }}</div>
                            <div style="font-size:10px; color:#85958F;">{{ $order->buyer->organization ?? '-' }}</div>
                            @if($order->buyer && $order->buyer->phone)
                            <div style="font-size:10px; color:#0A765A; font-weight:700; margin-top:2px;">
                                <i class="fa-brands fa-whatsapp"></i> {{ $order->buyer->phone }}
                            </div>
                            @endif
                        </td>
                        <td style="font-size:11px; font-weight:650; padding:15px 10px;">
                            {{ number_format($order->quantity_kg, 0, ',', '.') }} kg
                        </td>
                        <td style="font-size:11px; font-weight:800; color:#0A765A; padding:15px 10px; white-space:nowrap;">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                        <td style="font-size:11px; padding:15px 10px;">
                            @if($order->status === 'menunggu_konfirmasi')
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#FFF8EA; color:#B45309; font-weight:800; font-size:10px;">Menunggu Konfirmasi</span>
                            @elseif($order->status === 'menunggu_pembayaran_dp')
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#FEF3C7; color:#92400E; font-weight:800; font-size:10px;">Menunggu DP</span>
                            @elseif($order->status === 'menunggu_verifikasi_dp')
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#DBEAFE; color:#1E40AF; font-weight:800; font-size:10px;">Menunggu Pemeriksaan DP</span>
                            @elseif(in_array($order->status, ['pesanan_aktif', 'dikonfirmasi']))
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#EFF6FF; color:#1D4ED8; font-weight:800; font-size:10px;">Pesanan Aktif</span>
                            @elseif(in_array($order->status, ['dp_cair', 'tanam_berjalan', 'sedang_berlangsung']))
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:10px;">Sedang Berlangsung</span>
                            @elseif($order->status === 'siap_panen')
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#E9FBF3; color:#0A765A; font-weight:800; font-size:10px;">Siap Panen</span>
                            @elseif($order->status === 'dalam_pengiriman')
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#F0FDF4; color:#15803D; font-weight:800; font-size:10px;">Dalam Pengiriman</span>
                            @else
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#F3F4F6; color:#6B7280; font-weight:800; font-size:10px;">{{ $order->status_label }}</span>
                            @endif
                        </td>
                        <td style="font-size:11px; padding:15px 10px; text-align:right;">
                            <div style="display:inline-flex; gap:6px; align-items:center;">
                                @if($order->status === 'menunggu_konfirmasi')
                                <form action="{{ route('petani.pesanan.confirm', $order->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Terima pengajuan pre-order ini?')"
                                            style="padding:5px 10px; border-radius:7px; background:#05C793; border:none; color:#053B2D; font-weight:850; font-size:11px; cursor:pointer;">
                                        Terima
                                    </button>
                                </form>
                                <form action="{{ route('petani.pesanan.reject', $order->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Tolak pengajuan pre-order ini? Stok akan dikembalikan.')"
                                            style="padding:5px 10px; border-radius:7px; background:#FEE2E2; border:1px solid #FCA5A5; color:#991B1B; font-weight:800; font-size:11px; cursor:pointer;">
                                        Tolak
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('petani.pesanan.show', $order->id) }}"
                                   style="display:inline-block; padding:5px 12px; border-radius:7px; background:#EBF9F2; border:1px solid #C6EEDC; color:#078362; font-weight:800; font-size:11px; text-decoration:none;">
                                    Detail
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- MOBILE CARDS --}}
        <div class="order-card-mobile" style="flex-direction:column; gap:10px;">
            @foreach($activeOrders as $order)
            @php
                $searchTarget = strtolower($order->order_code . ' ' . ($order->commodityListing->commodity_name ?? '') . ' ' . ($order->buyer->name ?? ''));
            @endphp
            <div x-show="(search === '' || '{{ $searchTarget }}'.includes(search.toLowerCase())) && (filterStatus === '' || '{{ $order->status }}' === filterStatus)"
                 style="border:1px solid #EEF2F0; border-radius:12px; padding:14px; background:#FAFCFB;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:8px;">
                    <div>
                        <div style="font-size:11px; font-weight:800; color:#183B30;">{{ $order->order_code }}</div>
                        <div style="font-size:12px; font-weight:850; color:#172B29; margin-top:2px;">{{ $order->commodityListing->commodity_name ?? 'Komoditas' }}</div>
                        <div style="font-size:10px; color:#85958F;">{{ $order->commodityListing->variety ?? '' }}</div>
                    </div>
                    <div style="text-align:right;">
                        @if($order->status === 'menunggu_konfirmasi')
                            <span style="display:inline-flex; padding:3px 8px; border-radius:5px; background:#FFF8EA; color:#B45309; font-weight:800; font-size:10px;">Menunggu Konfirmasi</span>
                        @elseif($order->status === 'menunggu_pembayaran_dp')
                            <span style="display:inline-flex; padding:3px 8px; border-radius:5px; background:#FEF3C7; color:#92400E; font-weight:800; font-size:10px;">Menunggu DP</span>
                        @elseif($order->status === 'menunggu_verifikasi_dp')
                            <span style="display:inline-flex; padding:3px 8px; border-radius:5px; background:#DBEAFE; color:#1E40AF; font-weight:800; font-size:10px;">Periksa DP</span>
                        @elseif(in_array($order->status, ['pesanan_aktif', 'dikonfirmasi']))
                            <span style="display:inline-flex; padding:3px 8px; border-radius:5px; background:#EFF6FF; color:#1D4ED8; font-weight:800; font-size:10px;">Pesanan Aktif</span>
                        @elseif($order->status === 'siap_panen')
                            <span style="display:inline-flex; padding:3px 8px; border-radius:5px; background:#E9FBF3; color:#0A765A; font-weight:800; font-size:10px;">Siap Panen</span>
                        @elseif($order->status === 'dalam_pengiriman')
                            <span style="display:inline-flex; padding:3px 8px; border-radius:5px; background:#F0FDF4; color:#15803D; font-weight:800; font-size:10px;">Dalam Pengiriman</span>
                        @else
                            <span style="display:inline-flex; padding:3px 8px; border-radius:5px; background:#F3F4F6; color:#6B7280; font-weight:800; font-size:10px;">{{ $order->status_label }}</span>
                        @endif
                    </div>
                </div>
                <div style="display:flex; gap:12px; font-size:11px; color:#50695E; margin-bottom:10px; flex-wrap:wrap;">
                    <span><i class="fa-solid fa-user" style="color:#A3B9B2;"></i> {{ $order->buyer->name ?? 'Pembeli' }}</span>
                    <span><i class="fa-solid fa-weight-hanging" style="color:#A3B9B2;"></i> {{ number_format($order->quantity_kg, 0, ',', '.') }} kg</span>
                    <span style="font-weight:800; color:#0A765A;"><i class="fa-solid fa-tag" style="color:#A3B9B2;"></i> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    @if($order->status === 'menunggu_konfirmasi')
                    <form action="{{ route('petani.pesanan.confirm', $order->id) }}" method="POST" style="margin:0; flex:1;">
                        @csrf
                        <button type="submit" onclick="return confirm('Terima pengajuan pre-order ini?')"
                                style="width:100%; padding:8px; border-radius:8px; background:#05C793; border:none; color:#053B2D; font-weight:850; font-size:12px; cursor:pointer;">
                            <i class="fa-solid fa-check"></i> Terima
                        </button>
                    </form>
                    <form action="{{ route('petani.pesanan.reject', $order->id) }}" method="POST" style="margin:0; flex:1;">
                        @csrf
                        <button type="submit" onclick="return confirm('Tolak pesanan ini?')"
                                style="width:100%; padding:8px; border-radius:8px; background:#FEE2E2; border:1px solid #FCA5A5; color:#991B1B; font-weight:800; font-size:12px; cursor:pointer;">
                            <i class="fa-solid fa-times"></i> Tolak
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('petani.pesanan.show', $order->id) }}"
                       style="flex:1; display:flex; align-items:center; justify-content:center; padding:8px; border-radius:8px; background:#EBF9F2; border:1px solid #C6EEDC; color:#078362; font-weight:800; font-size:12px; text-decoration:none;">
                        <i class="fa-solid fa-eye"></i>&nbsp; Detail
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        @endif
    </div>

    {{-- TAB 2: RIWAYAT PESANAN --}}
    <div x-show="tab === 'history'" class="card" style="padding:24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:12px;">
            <div>
                <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Riwayat &amp; Arsip Pesanan</h2>
                <p style="font-size:11px; color:#91A19B; margin:0;">Daftar transaksi yang telah selesai diserahkan atau dibatalkan.</p>
            </div>

            <form method="GET" action="{{ route('petani.pesanan') }}" class="history-toolbar">
                <input type="hidden" name="tab" value="history">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="⌕ Cari kode, pembeli, komoditas..."
                       style="padding:9px 13px; border:1px solid #E5ECEA; border-radius:10px; font-size:12px; color:#50695E; background:#fff; min-width:220px; outline:0;">
                <select name="status" onchange="this.form.submit()"
                        style="padding:9px 13px; border:1px solid #E5ECEA; border-radius:10px; font-size:12px; color:#50695E; background:#fff; outline:0;">
                    <option value="">Semua riwayat</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>Ditolak / Batal</option>
                </select>
                <button type="submit" class="btn btn-secondary" style="font-size:12px; padding:8px 14px;">Filter</button>
            </form>
        </div>

        @if($historyOrders->isEmpty())
        <div style="text-align:center; padding:50px 20px; color:#8A9D95;">
            <div style="width:54px; height:54px; border-radius:16px; background:#F2F8F5; color:#05C793; display:inline-flex; align-items:center; justify-content:center; font-size:24px; margin-bottom:12px;">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <h3 style="font-size:15px; font-weight:800; color:#172B29; margin-bottom:4px;">Belum Ada Riwayat Pesanan</h3>
            <p style="font-size:12px; margin-top:4px;">Pesanan yang telah selesai atau dibatalkan akan diarsipkan secara permanen di sini.</p>
        </div>
        @else
        {{-- DESKTOP TABLE --}}
        <div class="order-table-desktop" style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:left;">
                <thead>
                    <tr>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">KODE PESANAN</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">KOMODITAS &amp; BATCH</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">PEMBELI</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">QTY &amp; TOTAL</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">TANGGAL / WAKTU</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">STATUS AKHIR</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px; text-align:right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($historyOrders as $hOrder)
                    <tr style="border-bottom:1px solid #EEF2F0;">
                        <td style="font-size:11px; font-weight:800; color:#183B30; padding:15px 10px;">
                            {{ $hOrder->order_code }}
                        </td>
                        <td style="font-size:11px; padding:15px 10px;">
                            <b style="font-weight:850; color:#183B30;">{{ $hOrder->commodityListing->commodity_name ?? 'Komoditas' }}</b>
                            <div style="font-size:10px; color:#0C8D65;">{{ $hOrder->harvestBatch->batch_name ?? 'Batch' }}</div>
                        </td>
                        <td style="font-size:11px; font-weight:650; color:#172B29; padding:15px 10px;">
                            <div>{{ $hOrder->buyer->name ?? 'Pembeli' }}</div>
                            <div style="font-size:10px; color:#85958F;">{{ $hOrder->buyer->organization ?? '-' }}</div>
                        </td>
                        <td style="font-size:11px; padding:15px 10px;">
                            <div style="font-weight:800; color:#172B29;">{{ number_format($hOrder->quantity_kg, 0, ',', '.') }} kg</div>
                            <div style="font-size:10.5px; color:#0A765A; font-weight:700;">Rp {{ number_format($hOrder->total_amount, 0, ',', '.') }}</div>
                        </td>
                        <td style="font-size:10.5px; color:#50695E; padding:15px 10px;">
                            <div>Dibuat: {{ $hOrder->created_at ? $hOrder->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') : '-' }} WIB</div>
                            @if($hOrder->completed_at)
                                <div style="color:#0C8D65; font-weight:700;">Selesai: {{ $hOrder->completed_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} WIB</div>
                            @elseif($hOrder->rejected_at)
                                <div style="color:#991B1B; font-weight:700;">Ditolak: {{ $hOrder->rejected_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} WIB</div>
                            @endif
                        </td>
                        <td style="font-size:11px; padding:15px 10px;">
                            @if($hOrder->status === 'selesai')
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#D1FAE5; color:#065F46; font-weight:800; font-size:10px;">Selesai</span>
                            @elseif($hOrder->status === 'dibatalkan')
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#FEE2E2; color:#991B1B; font-weight:800; font-size:10px;">Ditolak / Batal</span>
                            @else
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#F3F4F6; color:#6B7280; font-weight:800; font-size:10px;">{{ $hOrder->status_label }}</span>
                            @endif
                        </td>
                        <td style="font-size:11px; padding:15px 10px; text-align:right;">
                            <div style="display:inline-flex; gap:6px; align-items:center;">
                                <a href="{{ route('petani.pesanan.history', $hOrder->id) }}"
                                   style="display:inline-block; padding:5px 12px; border-radius:7px; background:#EBF9F2; border:1px solid #C6EEDC; color:#078362; font-weight:800; font-size:11px; text-decoration:none;">
                                    Lihat Riwayat
                                </a>
                                <a href="{{ route('petani.pesanan.pdf', $hOrder->id) }}"
                                   title="Unduh PDF"
                                   style="display:inline-block; padding:5px 10px; border-radius:7px; background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D; font-weight:800; font-size:11px; text-decoration:none;">
                                    <i class="fa-solid fa-file-pdf"></i> PDF
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- MOBILE CARDS FOR HISTORY --}}
        <div class="order-card-mobile" style="flex-direction:column; gap:10px;">
            @foreach($historyOrders as $hOrder)
            <div style="border:1px solid #EEF2F0; border-radius:12px; padding:14px; background:#FAFCFB;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:8px;">
                    <div>
                        <div style="font-size:11px; font-weight:800; color:#183B30;">{{ $hOrder->order_code }}</div>
                        <div style="font-size:12px; font-weight:850; color:#172B29; margin-top:2px;">{{ $hOrder->commodityListing->commodity_name ?? 'Komoditas' }}</div>
                        <div style="font-size:10px; color:#0C8D65;">{{ $hOrder->harvestBatch->batch_name ?? 'Batch' }}</div>
                    </div>
                    <div style="text-align:right;">
                        @if($hOrder->status === 'selesai')
                            <span style="display:inline-flex; padding:3px 8px; border-radius:5px; background:#D1FAE5; color:#065F46; font-weight:800; font-size:10px;">Selesai</span>
                        @elseif($hOrder->status === 'dibatalkan')
                            <span style="display:inline-flex; padding:3px 8px; border-radius:5px; background:#FEE2E2; color:#991B1B; font-weight:800; font-size:10px;">Ditolak / Batal</span>
                        @else
                            <span style="display:inline-flex; padding:3px 8px; border-radius:5px; background:#F3F4F6; color:#6B7280; font-weight:800; font-size:10px;">{{ $hOrder->status_label }}</span>
                        @endif
                    </div>
                </div>
                <div style="display:flex; gap:12px; font-size:11px; color:#50695E; margin-bottom:8px; flex-wrap:wrap;">
                    <span><i class="fa-solid fa-user" style="color:#A3B9B2;"></i> {{ $hOrder->buyer->name ?? 'Pembeli' }}</span>
                    <span><i class="fa-solid fa-weight-hanging" style="color:#A3B9B2;"></i> {{ number_format($hOrder->quantity_kg, 0, ',', '.') }} kg</span>
                    <span style="font-weight:800; color:#0A765A;"><i class="fa-solid fa-tag" style="color:#A3B9B2;"></i> Rp {{ number_format($hOrder->total_amount, 0, ',', '.') }}</span>
                </div>
                <div style="font-size:10.5px; color:#788D85; margin-bottom:10px;">
                    <div>Dibuat: {{ $hOrder->created_at ? $hOrder->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') : '-' }} WIB</div>
                    @if($hOrder->completed_at)
                        <div style="color:#0C8D65; font-weight:700;">Selesai: {{ $hOrder->completed_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} WIB</div>
                    @elseif($hOrder->rejected_at)
                        <div style="color:#991B1B; font-weight:700;">Ditolak: {{ $hOrder->rejected_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} WIB</div>
                    @endif
                </div>
                <div style="display:flex; gap:8px;">
                    <a href="{{ route('petani.pesanan.history', $hOrder->id) }}"
                       style="flex:1; display:flex; align-items:center; justify-content:center; padding:8px; border-radius:8px; background:#EBF9F2; border:1px solid #C6EEDC; color:#078362; font-weight:800; font-size:12px; text-decoration:none;">
                        <i class="fa-solid fa-clock-rotate-left"></i>&nbsp; Lihat Riwayat
                    </a>
                    <a href="{{ route('petani.pesanan.pdf', $hOrder->id) }}"
                       style="display:flex; align-items:center; justify-content:center; padding:8px 14px; border-radius:8px; background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D; font-weight:800; font-size:12px; text-decoration:none;">
                        <i class="fa-solid fa-file-pdf"></i>&nbsp; PDF
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>
@endsection
