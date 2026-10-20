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
@endsection

@section('breadcrumb')
    TaniLink &rsaquo; Admin &rsaquo; <strong>Ringkasan Operasional</strong>
@endsection

@section('content')

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:20px;">
        <div>
            <div style="font-size:10px; font-weight:900; color:#7257CA; text-transform:uppercase; letter-spacing:1.6px; margin-bottom:6px;">TaniLink / Admin Central</div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Ringkasan Operasional</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">Pantau aktivitas pengguna, penawaran komoditas, dan simulasi kontrak TaniLink.</p>
        </div>
        <a href="{{ route('admin.laporan') }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 16px; border-radius:11px; background:#7257CA; color:#fff; font-size:12px; font-weight:800; text-decoration:none;">
            Lihat Laporan ↗
        </a>
    </div>

    {{-- Notice --}}
    <div style="padding:14px 18px; display:flex; align-items:flex-start; gap:11px; border-radius:12px; background:#F1EFFB; border:1px solid #D8D1F5; color:#513B9B; font-size:11px; line-height:1.6; margin-bottom:20px;">
        <span>ⓘ</span>
        <span><b>Data operasional:</b> Nilai DP, escrow, GMV, dan rilis dana adalah instrumen tata kelola transaksi pertanian transparan TaniLink.</span>
    </div>

    {{-- 4 Metric Cards --}}
    <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:16px; margin-bottom:20px;">
        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Pengguna Terdaftar</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#F1EFFB; color:#7257CA; font-size:16px;">♧</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ $allUsers->count() }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Gapoktan & Horeka aktif</div>
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
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                Rp {{ number_format($totalGmv, 0, ',', '.') }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Escrow locked: Rp {{ number_format($totalEscrowLocked, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Grid 2 Kolom --}}
    <div style="display:grid; grid-template-columns:minmax(0, 1.6fr) minmax(300px, 1fr); gap:18px;">
        
        {{-- Kiri: Tren Pre-Order Bar Chart Pure CSS --}}
        <div class="card" style="padding:22px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
                <div>
                    <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Tren Volume Pre-Order</h2>
                    <p style="font-size:11px; color:#91A19B; margin:0;">Ilustrasi volume transaksi komoditas 7 bulan terakhir.</p>
                </div>
                <span style="font-size:11px; font-weight:800; color:#07946C;">+18.4% MoM</span>
            </div>

            {{-- Pure CSS Bar Chart --}}
            <div style="height:175px; display:flex; align-items:flex-end; gap:16px; padding:0 12px 14px; border-bottom:1px solid #E5ECEA; margin-bottom:28px;">
                @php
                    $months = [
                        ['Apr', 45], ['Mei', 65], ['Jun', 52],
                        ['Jul', 80], ['Agt', 70], ['Sep', 95], ['Okt', 85]
                    ];
                @endphp
                @foreach($months as $m)
                <div style="flex:1; height:{{ $m[1] }}%; background:linear-gradient(180deg, #05C793, #9BE8CF); border-radius:8px 8px 0 0; position:relative; min-height:12px;">
                    <span style="position:absolute; top:-20px; left:50%; transform:translateX(-50%); font-size:10px; font-weight:800; color:#07876B;">{{ $m[1] }}</span>
                    <small style="position:absolute; bottom:-22px; left:50%; transform:translateX(-50%); color:#9AA9A3; font-size:10px; font-weight:700;">{{ $m[0] }}</small>
                </div>
                @endforeach
            </div>

            <div style="display:flex; justify-content:space-between; font-size:11px; color:#85958F;">
                <span>Total Escrow Diamankan: <b style="color:#6953B5;">Rp {{ number_format($totalEscrowLocked, 0, ',', '.') }}</b></span>
                <span>DP Tersalurkan: <b style="color:#0C8D65;">Rp {{ number_format($totalDpDisbursed, 0, ',', '.') }}</b></span>
            </div>
        </div>

        {{-- Kanan: Pesanan Terkini --}}
        <div class="card" style="padding:22px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
                <div>
                    <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Pesanan Terkini</h2>
                    <p style="font-size:11px; color:#91A19B; margin:0;">Status transaksi escrow platform.</p>
                </div>
                <a href="{{ route('admin.pesanan') }}" style="color:#7257CA; font-size:11px; font-weight:850; text-decoration:none;">
                    Kelola →
                </a>
            </div>

            <div style="display:flex; flex-direction:column; gap:12px;">
                @forelse($allOrders->take(4) as $o)
                <div style="display:flex; justify-content:space-between; align-items:center; padding-bottom:12px; border-bottom:1px solid #EEF3F0;">
                    <div>
                        <b style="font-size:12px; color:#172B29;">{{ $o->order_code }}</b>
                        <div style="font-size:10px; color:#8A9994; margin-top:2px;">
                            {{ $o->commodityListing->commodity_name ?? 'Komoditas' }} &bull; {{ $o->buyer->name ?? 'Pembeli' }}
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
                <p style="font-size:12px; color:#8A9D95; text-align:center;">Belum ada pesanan masuk.</p>
                @endforelse
            </div>
        </div>

    </div>

@endsection
