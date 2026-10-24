@extends('layouts.sidebar')

@section('title', 'Pesanan Saya - TaniLink')

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
<div x-data="{ tab: 'all', search: '' }">

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:26px;">
        <div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Pesanan Saya</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">Pantau status pre-order, kepastian pasokan panen, dan rekening escrow bersama.</p>
        </div>
        <a href="{{ route('pembeli.katalog') }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 16px; border-radius:11px; background:#05C793; color:#053B2D; font-size:12px; font-weight:800; text-decoration:none; box-shadow:0 3px 10px rgba(5,199,147,0.2);">
            ＋ Cari Komoditas Lain
        </a>
    </div>

    {{-- 3 Metric Cards --}}
    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; margin-bottom:24px;">
        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Total Pesanan</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">▤</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ str_pad($myOrders->count(), 2, '0', STR_PAD_LEFT) }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Seluruh riwayat kontrak</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Aktif Dipantau</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">◉</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ str_pad($myOrders->where('status', '!=', 'selesai')->count(), 2, '0', STR_PAD_LEFT) }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Fase pertumbuhan tanaman</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Total Komitmen Dana</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">▥</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                Rp {{ number_format($myOrders->sum('total_amount'), 0, ',', '.') }}
            </div>
            <div style="font-size:10px; color:#7B9187;">DP 30% + Escrow 70%</div>
        </div>
    </div>

    {{-- Orders Card --}}
    <div class="card" style="padding:24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:12px;">
            <div>
                <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Daftar Kontrak Pre-Order</h2>
                <p style="font-size:11px; color:#91A19B; margin:0;">Pantau progres fase tanam komoditas langsung dari lahan petani.</p>
            </div>

            <div style="display:flex; gap:10px;">
                <input type="text" x-model="search" placeholder="⌕ Cari kode atau komoditas..."
                       style="padding:9px 13px; border:1px solid #E5ECEA; border-radius:10px; font-size:12px; color:#50695E; background:#fff; min-width:200px; outline:0;">
            </div>
        </div>

        @if($myOrders->isEmpty())
        <div style="text-align:center; padding:50px 20px; color:#8A9D95;">
            <div style="font-size:42px; margin-bottom:10px;">🛒</div>
            <h3 style="font-size:15px; font-weight:800; color:#172B29; margin-bottom:4px;">Belum Ada Pesanan Aktif</h3>
            <p style="font-size:12px; margin-top:4px;">Kunjungi Marketplace untuk mulai memesan komoditas langsung ke petani.</p>
            <a href="{{ route('pembeli.katalog') }}" style="display:inline-block; margin-top:14px; padding:10px 18px; border-radius:11px; background:#05C793; color:#053B2D; font-size:12px; font-weight:800; text-decoration:none;">
                Buka Marketplace
            </a>
        </div>
        @else
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:left;">
                <thead>
                    <tr>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">KODE PESANAN</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">KOMODITAS</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">PETANI MITRA</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">QTY</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">TOTAL</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">STATUS</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px; text-align:right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($myOrders as $order)
                    @php
                        $searchTarget = strtolower($order->order_code . ' ' . ($order->commodityListing->commodity_name ?? '') . ' ' . ($order->commodityListing->farmer->name ?? ''));
                    @endphp
                    <tr x-show="search === '' || '{{ $searchTarget }}'.includes(search.toLowerCase())"
                        style="border-bottom:1px solid #EEF2F0;">
                        <td style="font-size:11px; font-weight:800; color:#183B30; padding:15px 10px;">
                            {{ $order->order_code }}
                        </td>
                        <td style="font-size:11px; padding:15px 10px;">
                            <b style="font-weight:850; color:#183B30;">{{ $order->commodityListing->commodity_name ?? 'Komoditas' }}</b>
                            <div style="font-size:10px; color:#85958F;">{{ $order->commodityListing->variety ?? '-' }}</div>
                        </td>
                        <td style="font-size:11px; font-weight:650; color:#172B29; padding:15px 10px;">
                            <div>{{ $order->farmer->name ?? ($order->commodityListing->farmer->name ?? 'Petani Mitra') }}</div>
                            <div style="font-size:10px; color:#85958F;">{{ $order->farmer->organization ?? ($order->commodityListing->location ?? 'Malang') }}</div>
                            @php
                                $fPhone = $order->farmer->phone ?? ($order->commodityListing->farmer->phone ?? null);
                            @endphp
                            @if($fPhone)
                            <div style="font-size:10px; color:#0A765A; font-weight:700; margin-top:2px;">
                                <i class="fa-brands fa-whatsapp"></i> {{ $fPhone }}
                            </div>
                            @endif
                        </td>
                        <td style="font-size:11px; font-weight:650; padding:15px 10px;">
                            {{ number_format($order->quantity_kg, 0, ',', '.') }} kg
                        </td>
                        <td style="font-size:11px; font-weight:800; color:#0A765A; padding:15px 10px;">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                        <td style="font-size:11px; padding:15px 10px;">
                            @if($order->status === 'menunggu_konfirmasi')
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#FFF8EA; color:#B45309; font-weight:800; font-size:10px;">Menunggu Konfirmasi</span>
                            @elseif($order->status === 'dikonfirmasi')
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#EFF6FF; color:#1D4ED8; font-weight:800; font-size:10px;">Dikonfirmasi</span>
                            @elseif(in_array($order->status, ['dp_cair', 'tanam_berjalan']))
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:10px;">Budidaya Berjalan</span>
                            @elseif($order->status === 'siap_panen')
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#E9FBF3; color:#0A765A; font-weight:800; font-size:10px;">Siap Panen ✓</span>
                            @elseif($order->status === 'dalam_pengiriman')
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#F0FDF4; color:#15803D; font-weight:800; font-size:10px;">Dalam Pengiriman</span>
                            @elseif($order->status === 'selesai')
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#E9F9F2; color:#0C8D65; font-weight:800; font-size:10px;">Selesai</span>
                            @elseif($order->status === 'dibatalkan')
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#FEE2E2; color:#991B1B; font-weight:800; font-size:10px;">Ditolak / Batal</span>
                            @else
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#F3F4F6; color:#6B7280; font-weight:800; font-size:10px;">{{ $order->status }}</span>
                            @endif
                        </td>
                        <td style="font-size:11px; padding:15px 10px; text-align:right;">
                            <a href="{{ route('pembeli.tracking', $order->id) }}"
                               style="display:inline-block; padding:5px 12px; border-radius:7px; background:#EBF9F2; border:1px solid #C6EEDC; color:#078362; font-weight:800; font-size:11px; text-decoration:none;">
                                Pantau Progres ↗
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>
@endsection
