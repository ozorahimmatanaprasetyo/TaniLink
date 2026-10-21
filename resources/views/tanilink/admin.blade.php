@extends('layouts.sidebar')

@section('title', 'Dashboard Admin - TaniLink Central')

@section('sidebar_nav')
<a href="{{ route('portal.admin') }}" class="nav-btn {{ request()->routeIs('portal.admin') ? 'active' : '' }}">
    <span class="nav-icon">▦</span> Dashboard Admin
</a>
<a href="{{ route('admin.pengguna') }}" class="nav-btn {{ request()->routeIs('admin.pengguna*') ? 'active' : '' }}">
    <span class="nav-icon">♧</span> Kelola Pengguna
</a>
<a href="{{ route('admin.komoditas') }}" class="nav-btn {{ request()->routeIs('admin.komoditas*') ? 'active' : '' }}">
    <span class="nav-icon">▤</span> Kelola Komoditas
</a>
<a href="{{ route('admin.pesanan') }}" class="nav-btn {{ request()->routeIs('admin.pesanan*') ? 'active' : '' }}">
    <span class="nav-icon">▣</span> Pesanan & Escrow
</a>
<a href="{{ route('admin.laporan') }}" class="nav-btn {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
    <span class="nav-icon">▥</span> Laporan & Statistik
</a>
<a href="{{ route('admin.bantuan') }}" class="nav-btn {{ request()->routeIs('admin.bantuan*') ? 'active' : '' }}">
    <span class="nav-icon">❓</span> Kelola Pusat Bantuan
</a>
@endsection

@section('content')

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:20px;">
        <div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Ringkasan Operasional</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">Pantau aktivitas pengguna, penawaran komoditas, dan simulasi kontrak TaniLink.</p>
        </div>
        <a href="{{ route('admin.laporan') }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 16px; border-radius:11px; background:#7257CA; color:#fff; font-size:12px; font-weight:800; text-decoration:none; box-shadow:0 3px 10px rgba(114,87,202,0.25);">
            Lihat Laporan Lengkap ↗
        </a>
    </div>

    {{-- 4 Metric Cards --}}
    <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:16px; margin-bottom:20px;">
        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Pengguna Terdaftar</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#F1EFFB; color:#7257CA; font-size:16px;">👥</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ $allUsers->count() }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Petani & Konsumen aktif</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Komoditas Aktif</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">▤</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ $allListings->count() }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Penawaran di marketplace</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Pesanan Pre-Order</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E8F9FC; color:#0B8BAA; font-size:16px;">▦</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ $allOrders->count() }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Seluruh status kontrak</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Nilai GMV Platform</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#FFF5E8; color:#BA7A1F; font-size:16px;">▥</span>
            </div>
            <div style="font-weight:850; font-size:23px; letter-spacing:-0.8px; margin:12px 0 6px; color:#172B29;">
                Rp {{ number_format($totalGmv, 0, ',', '.') }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Escrow locked: Rp {{ number_format($totalEscrowLocked, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Grid 2 Kolom --}}
    <div style="display:grid; grid-template-columns:minmax(0, 1.6fr) minmax(300px, 1fr); gap:18px;">
        
        {{-- Kiri: Tren Pre-Order Bar Chart Pure CSS with HOVER TOOLTIP (Not static text) --}}
        <div class="card" style="padding:22px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
                <div>
                    <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Tren Volume Pre-Order</h2>
                    <p style="font-size:11px; color:#91A19B; margin:0;">Arahkan kursor pada batang untuk melihat nominal lengkap & tonase per bulan.</p>
                </div>
                <span style="font-size:11px; font-weight:800; color:#07946C; background:#EBF9F2; padding:4px 9px; border-radius:8px;">+18.4% MoM</span>
            </div>

            {{-- Rock-Solid CSS Grid Bar Chart with Tooltips on Hover --}}
            <div style="height:210px; display:grid; grid-template-columns:repeat(7, 1fr); gap:14px; padding:20px 12px 10px; border-bottom:1px solid #E5ECEA; margin-bottom:20px;">
                @php
                    $monthsData = [
                        ['Apr', 45, '45 Ton', 1125000000],
                        ['Mei', 65, '65 Ton', 1625000000],
                        ['Jun', 52, '52 Ton', 1300000000],
                        ['Jul', 80, '80 Ton', 2000000000],
                        ['Agt', 70, '70 Ton', 1750000000],
                        ['Sep', 95, '95 Ton', 2375000000],
                        ['Okt', 85, '85 Ton', 2125000000]
                    ];
                @endphp
                @foreach($monthsData as $m)
                <div style="display:flex; flex-direction:column; justify-content:flex-end; align-items:center; height:100%; position:relative;">
                    <div x-data="{ hovered: false }"
                         @mouseenter="hovered = true"
                         @mouseleave="hovered = false"
                         style="width:100%; max-width:44px; height:{{ round($m[1] * 1.55) }}px; background:linear-gradient(180deg, #05C793, #7257CA); border-radius:8px 8px 0 0; position:relative; cursor:pointer; transition:transform 0.15s, filter 0.15s;"
                         onmouseover="this.style.transform='scaleY(1.05)'; this.style.filter='brightness(1.1)';"
                         onmouseout="this.style.transform='scaleY(1)'; this.style.filter='none';">
                        
                        {{-- Tooltip only on hover --}}
                        <div x-show="hovered" x-cloak
                             style="position:absolute; bottom:calc(100% + 8px); left:50%; transform:translateX(-50%); background:#1E293B; color:#fff; padding:7px 12px; border-radius:8px; font-size:11px; white-space:nowrap; z-index:50; pointer-events:none; box-shadow:0 8px 24px rgba(0,0,0,0.28); text-align:center;">
                            <div style="font-weight:800; color:#34D399;">{{ $m[0] }}: {{ $m[2] }}</div>
                            <div style="font-size:10px; color:#E2E8F0; margin-top:2px;">Rp {{ number_format($m[3], 0, ',', '.') }}</div>
                            <div style="position:absolute; top:100%; left:50%; transform:translateX(-50%); border:5px solid transparent; border-top-color:#1E293B;"></div>
                        </div>
                    </div>
                    <span style="font-size:11px; font-weight:750; color:#85958F; margin-top:8px; text-align:center; display:block;">{{ $m[0] }}</span>
                </div>
                @endforeach
            </div>

            <div style="display:flex; justify-content:space-between; font-size:11.5px; color:#85958F; flex-wrap:wrap; gap:8px;">
                <span>Total Escrow Diamankan: <b style="color:#6953B5;">Rp {{ number_format($totalEscrowLocked, 0, ',', '.') }}</b></span>
                <span>Total DP Tersalurkan: <b style="color:#0C8D65;">Rp {{ number_format($totalDpDisbursed, 0, ',', '.') }}</b></span>
            </div>
        </div>

        {{-- Kanan: Pesanan Terkini --}}
        <div class="card" style="padding:22px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
                <div>
                    <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Pesanan Terkini</h2>
                    <p style="font-size:11px; color:#91A19B; margin:0;">Status transaksi escrow platform.</p>
                </div>
                <a href="{{ route('admin.pesanan') }}" style="display:inline-flex; align-items:center; gap:4px; padding:5px 12px; border-radius:8px; background:#F1EFFB; border:1px solid #D8D1F5; color:#7257CA; font-size:11px; font-weight:800; text-decoration:none;">
                    Kelola →
                </a>
            </div>

            <div style="display:flex; flex-direction:column; gap:12px;">
                @forelse($allOrders->take(4) as $o)
                <div style="display:flex; justify-content:space-between; align-items:center; padding-bottom:12px; border-bottom:1px solid #EEF3F0;">
                    <div>
                        <b style="font-size:12px; color:#172B29;">{{ $o->order_code }}</b>
                        <div style="font-size:10px; color:#8A9994; margin-top:2px;">
                            {{ $o->commodityListing->commodity_name ?? 'Komoditas' }} &bull; {{ $o->buyer->name ?? 'Konsumen' }}
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <b style="font-size:12px; color:#0A765A;">Rp {{ number_format($o->total_amount, 0, ',', '.') }}</b>
                        <div style="margin-top:2px;">
                            @if($o->status === 'selesai')
                                <span style="display:inline-flex; align-items:center; padding:2px 7px; border-radius:5px; background:#E9F9F2; color:#0C8D65; font-weight:800; font-size:9px;">Selesai</span>
                            @else
                                <span style="display:inline-flex; align-items:center; padding:2px 7px; border-radius:5px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:9px;">DP Cair</span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <p style="font-size:12px; color:#8A9D95; text-align:center; padding:20px 0;">Belum ada pesanan masuk.</p>
                @endforelse
            </div>
        </div>

    </div>

@endsection
