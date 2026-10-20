@extends('layouts.sidebar')

@section('title', 'Pesanan Saya - TaniLink')

@section('sidebar_nav')
<a href="{{ route('portal.pembeli') }}" class="nav-btn {{ request()->routeIs('portal.pembeli') ? 'active' : '' }}">
    <span class="nav-icon">▦</span> Dashboard
</a>
<a href="{{ route('pembeli.katalog') }}" class="nav-btn {{ request()->routeIs('pembeli.katalog*') ? 'active' : '' }}">
    <span class="nav-icon">▧</span> Marketplace
</a>
<a href="{{ route('pembeli.pesanan*') }}" class="nav-btn {{ request()->routeIs('pembeli.pesanan*') ? 'active' : '' }}">
    <span class="nav-icon">▤</span> Pesanan Saya
</a>
@endsection

@section('breadcrumb')
    TaniLink &rsaquo; Horeka &rsaquo; <strong>Pesanan Saya</strong>
@endsection

@section('content')
<div x-data="{ tab: 'all', search: '' }">

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:26px;">
        <div>
            <div style="font-size:10px; font-weight:900; color:#049874; text-transform:uppercase; letter-spacing:1.6px; margin-bottom:6px;">TaniLink / Horeka</div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Pesanan Saya</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">Pantau status pre-order, kepastian pasokan panen, dan rekening escrow bersama.</p>
        </div>
        <a href="{{ route('pembeli.katalog') }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 16px; border-radius:11px; background:#05C793; color:#053B2D; font-size:12px; font-weight:800; text-decoration:none;">
            ＋ Buat Pre-Order
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
                <span>Nilai Permintaan</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">▥</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                Rp {{ number_format($totalPreOrderValue, 0, ',', '.') }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Escrow terkunci: Rp {{ number_format($escrowHeld, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Orders Card --}}
    <div class="card" style="padding:24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:12px;">
            <div>
                <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Semua Pesanan</h2>
                <p style="font-size:11px; color:#91A19B; margin:0;">Klik pantau untuk melihat riwayat progres tanaman dan estimasi pengiriman.</p>
            </div>

            {{-- Tabs Filter --}}
            <div style="display:flex; gap:8px;">
                <button @click="tab = 'all'"
                        :style="tab === 'all' ? 'background:#E5FAF1; color:#078868; border-color:#C0EEDA;' : 'background:#fff; color:#789188; border-color:#E5ECEA;'"
                        style="border:1px solid; border-radius:9px; padding:7px 14px; font-size:11px; font-weight:800; cursor:pointer;">
                    Semua
                </button>
                <button @click="tab = 'active'"
                        :style="tab === 'active' ? 'background:#E5FAF1; color:#078868; border-color:#C0EEDA;' : 'background:#fff; color:#789188; border-color:#E5ECEA;'"
                        style="border:1px solid; border-radius:9px; padding:7px 14px; font-size:11px; font-weight:800; cursor:pointer;">
                    Aktif
                </button>
                <button @click="tab = 'done'"
                        :style="tab === 'done' ? 'background:#E5FAF1; color:#078868; border-color:#C0EEDA;' : 'background:#fff; color:#789188; border-color:#E5ECEA;'"
                        style="border:1px solid; border-radius:9px; padding:7px 14px; font-size:11px; font-weight:800; cursor:pointer;">
                    Selesai
                </button>
            </div>
        </div>

        @if($myOrders->isEmpty())
        <div style="text-align:center; padding:50px 20px; color:#8A9D95;">
            <div style="font-size:42px; margin-bottom:10px;">📋</div>
            <h3 style="font-size:15px; font-weight:800; color:#172B29; margin-bottom:4px;">Belum Ada Pesanan</h3>
            <p style="font-size:12px; margin-bottom:16px;">Kunjungi Marketplace untuk memilih komoditas dan ajukan pre-order.</p>
            <a href="{{ route('pembeli.katalog') }}" style="display:inline-block; padding:10px 18px; border-radius:11px; background:#05C793; color:#053B2D; font-size:12px; font-weight:800; text-decoration:none;">
                Jelajahi Marketplace ↗
            </a>
        </div>
        @else
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:left;">
                <thead>
                    <tr>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">KODE</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">KOMODITAS</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">GAPOKTAN</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">QTY</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">NILAI</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">STATUS</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($myOrders as $order)
                    @php
                        $isDone = ($order->status === 'selesai');
                    @endphp
                    <tr x-show="tab === 'all' || (tab === 'active' && !{{ $isDone ? 'true' : 'false' }}) || (tab === 'done' && {{ $isDone ? 'true' : 'false' }})"
                        style="border-bottom:1px solid #EEF2F0;">
                        <td style="font-size:11px; font-weight:800; color:#183B30; padding:15px 10px;">
                            {{ $order->order_code }}
                        </td>
                        <td style="font-size:11px; padding:15px 10px;">
                            <b style="font-weight:850; color:#183B30;">{{ $order->commodityListing->commodity_name ?? 'Komoditas' }}</b>
                            <div style="font-size:10px; color:#85958F;">{{ $order->commodityListing->variety ?? '-' }}</div>
                        </td>
                        <td style="font-size:11px; font-weight:650; color:#172B29; padding:15px 10px;">
                            {{ $order->farmer->name ?? 'Petani' }}
                            <div style="font-size:10px; color:#85958F;">{{ $order->farmer->organization ?? '-' }}</div>
                        </td>
                        <td style="font-size:11px; font-weight:650; padding:15px 10px;">
                            {{ number_format($order->quantity_kg, 0, ',', '.') }} kg
                        </td>
                        <td style="font-size:11px; font-weight:800; color:#0A765A; padding:15px 10px;">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                        <td style="font-size:11px; padding:15px 10px;">
                            @if($order->status === 'selesai')
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#E9F9F2; color:#0C8D65; font-weight:800; font-size:10px;">Selesai</span>
                            @else
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:10px;">DP Simulasi</span>
                            @endif
                        </td>
                        <td style="font-size:11px; padding:15px 10px;">
                            <a href="{{ route('pembeli.tracking', $order->id) }}" style="color:#07946C; font-weight:850; text-decoration:none;">
                                Pantau ↗
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

