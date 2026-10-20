@extends('layouts.sidebar')

@section('title', 'Laporan & Statistik - Admin TaniLink')

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
    TaniLink &rsaquo; Admin &rsaquo; <strong>Laporan & Analitik</strong>
@endsection

@section('content')

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:26px;">
        <div>
            <div style="font-size:10px; font-weight:900; color:#7257CA; text-transform:uppercase; letter-spacing:1.6px; margin-bottom:6px;">TaniLink / Admin Central</div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Laporan & Analitik</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">Pertumbuhan ekosistem pertanian, kepatuhan pemenuhan kontrak, dan tata kelola dana escrow.</p>
        </div>
    </div>

    {{-- 4 Metric Cards --}}
    <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:16px; margin-bottom:24px;">
        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Total GMV Kumulatif</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#FFF5E8; color:#BA7A1F; font-size:16px;">📈</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                Rp {{ number_format($totalGmv, 0, ',', '.') }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Seluruh perputaran kontrak</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>DP Modal Tani (30%)</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">🌱</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#0A765A;">
                Rp {{ number_format($totalDpDisbursed, 0, ',', '.') }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Tersalurkan ke produsen</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Escrow Aktif (70%)</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#F1EFFB; color:#7257CA; font-size:16px;">🔒</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#6953B5;">
                Rp {{ number_format($totalEscrowLocked, 0, ',', '.') }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Dana jaminan pelunasan</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Tingkat Kepatuhan Kontrak</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E8F9FC; color:#0B8BAA; font-size:16px;">🏆</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                98.5%
            </div>
            <div style="font-size:10px; color:#7B9187;">Terkirim sesuai spesifikasi</div>
        </div>
    </div>

    {{-- Grid 2 Kolom --}}
    <div style="display:grid; grid-template-columns:minmax(0, 1.5fr) minmax(320px, 1fr); gap:18px; margin-bottom:24px;">
        
        {{-- Kiri: Tren Bulanan --}}
        <div class="card" style="padding:22px;">
            <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Tren Volume Kontrak Bulanan</h2>
            <p style="font-size:11px; color:#91A19B; margin:0 0 20px;">Perbandingan kuantitas dan nilai transaksi sepanjang musim tanam.</p>

            <div style="height:175px; display:flex; align-items:flex-end; gap:16px; padding:0 12px 14px; border-bottom:1px solid #E5ECEA; margin-bottom:28px;">
                @php
                    $months = [
                        ['Apr', 45], ['Mei', 65], ['Jun', 52],
                        ['Jul', 80], ['Agt', 70], ['Sep', 95], ['Okt', 85]
                    ];
                @endphp
                @foreach($months as $m)
                <div style="flex:1; height:{{ $m[1] }}%; background:linear-gradient(180deg, #7257CA, #B7A8EA); border-radius:8px 8px 0 0; position:relative; min-height:12px;">
                    <span style="position:absolute; top:-20px; left:50%; transform:translateX(-50%); font-size:10px; font-weight:800; color:#513B9B;">{{ $m[1] }}jt</span>
                    <small style="position:absolute; bottom:-22px; left:50%; transform:translateX(-50%); color:#9AA9A3; font-size:10px; font-weight:700;">{{ $m[0] }}</small>
                </div>
                @endforeach
            </div>

            <div style="display:flex; justify-content:space-between; font-size:11px; color:#85958F;">
                <span>Total Komoditas Terdaftar: <b style="color:#172B29;">{{ $allListings->count() }} listing</b></span>
                <span>Total Luas Lahan: <b style="color:#0A765A;">{{ number_format($allListings->sum('land_area_m2'), 0, ',', '.') }} m²</b></span>
            </div>
        </div>

        {{-- Kanan: Distribusi Peran Pengguna --}}
        <div class="card" style="padding:22px;">
            <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Distribusi Komposisi Pengguna</h2>
            <p style="font-size:11px; color:#91A19B; margin:0 0 20px;">Keseimbangan antara supply (petani) dan demand (Horeka).</p>

            <div style="display:flex; flex-direction:column; gap:16px;">
                <div>
                    <div style="display:flex; justify-content:space-between; font-size:12px; font-weight:750; margin-bottom:6px;">
                        <span>Petani / Gapoktan (Supply)</span>
                        <b style="color:#0C8D65;">{{ $allUsers->where('role', 'petani')->count() }} akun</b>
                    </div>
                    <div style="height:10px; border-radius:10px; background:#E8F2ED; overflow:hidden;">
                        <div style="height:100%; border-radius:10px; background:#05C793; width:{{ $allUsers->count() > 0 ? ($allUsers->where('role', 'petani')->count() / $allUsers->count()) * 100 : 50 }}%;"></div>
                    </div>
                </div>

                <div>
                    <div style="display:flex; justify-content:space-between; font-size:12px; font-weight:750; margin-bottom:6px;">
                        <span>Horeka & Pembeli (Demand)</span>
                        <b style="color:#0B8BAA;">{{ $allUsers->where('role', 'pembeli')->count() }} akun</b>
                    </div>
                    <div style="height:10px; border-radius:10px; background:#E8F9FC; overflow:hidden;">
                        <div style="height:100%; border-radius:10px; background:#06B6D4; width:{{ $allUsers->count() > 0 ? ($allUsers->where('role', 'pembeli')->count() / $allUsers->count()) * 100 : 50 }}%;"></div>
                    </div>
                </div>

                <div>
                    <div style="display:flex; justify-content:space-between; font-size:12px; font-weight:750; margin-bottom:6px;">
                        <span>Administrator Platform</span>
                        <b style="color:#6953B5;">{{ $allUsers->where('role', 'admin')->count() }} akun</b>
                    </div>
                    <div style="height:10px; border-radius:10px; background:#F1EFFB; overflow:hidden;">
                        <div style="height:100%; border-radius:10px; background:#7257CA; width:{{ $allUsers->count() > 0 ? ($allUsers->where('role', 'admin')->count() / $allUsers->count()) * 100 : 10 }}%;"></div>
                    </div>
                </div>
            </div>

            <div style="margin-top:24px; padding:12px; border-radius:10px; background:#F7FAF9; font-size:11px; color:#50695E; line-height:1.6;">
                🌱 <b>Rasio supply & demand:</b> Ekosistem memiliki pasokan komoditas yang seimbang untuk menjamin stabilitas harga pasar tanpa tengkulak.
            </div>
        </div>

    </div>

    {{-- Bottom Table: Ringkasan Pesanan --}}
    <div class="card" style="padding:22px;">
        <h2 style="font-size:15px; font-weight:850; margin:0 0 16px; color:#172B29;">Ringkasan Pesanan Terkini</h2>
        
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:left;">
                <thead>
                    <tr>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">KODE</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">KOMODITAS</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">GAPOKTAN</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">PEMBELI</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">NILAI</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allOrders->take(5) as $o)
                    <tr style="border-bottom:1px solid #EEF2F0;">
                        <td style="font-size:11px; font-weight:800; color:#183B30; padding:14px 10px;">{{ $o->order_code }}</td>
                        <td style="font-size:11px; padding:14px 10px;">{{ $o->commodityListing->commodity_name ?? 'Komoditas' }}</td>
                        <td style="font-size:11px; color:#172B29; padding:14px 10px;">{{ $o->farmer->name ?? 'Petani' }}</td>
                        <td style="font-size:11px; color:#172B29; padding:14px 10px;">{{ $o->buyer->name ?? 'Pembeli' }}</td>
                        <td style="font-size:11px; font-weight:800; color:#0A765A; padding:14px 10px;">Rp {{ number_format($o->total_amount, 0, ',', '.') }}</td>
                        <td style="font-size:11px; padding:14px 10px;">
                            @if($o->status === 'selesai')
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#E9F9F2; color:#0C8D65; font-weight:800; font-size:10px;">Selesai</span>
                            @else
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:10px;">DP Cair</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

