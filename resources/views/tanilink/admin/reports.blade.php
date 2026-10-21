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
<a href="{{ route('admin.bantuan') }}" class="nav-btn {{ request()->routeIs('admin.bantuan*') ? 'active' : '' }}">
    <span class="nav-icon">❓</span> Kelola Pusat Bantuan
</a>
@endsection

@section('content')

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:26px;">
        <div>
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
            <div style="font-weight:850; font-size:23px; letter-spacing:-0.8px; margin:12px 0 6px; color:#172B29;">
                Rp {{ number_format($totalGmv, 0, ',', '.') }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Seluruh perputaran kontrak</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>DP Modal Tani (30%)</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">🌱</span>
            </div>
            <div style="font-weight:850; font-size:23px; letter-spacing:-0.8px; margin:12px 0 6px; color:#0A765A;">
                Rp {{ number_format($totalDpDisbursed, 0, ',', '.') }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Tersalurkan ke produsen</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Escrow Aktif (70%)</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#F1EFFB; color:#7257CA; font-size:16px;">🔒</span>
            </div>
            <div style="font-weight:850; font-size:23px; letter-spacing:-0.8px; margin:12px 0 6px; color:#6953B5;">
                Rp {{ number_format($totalEscrowLocked, 0, ',', '.') }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Dana jaminan pelunasan</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Tingkat Kepatuhan Kontrak</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E8F9FC; color:#0B8BAA; font-size:16px;">🏆</span>
            </div>
            <div style="font-weight:850; font-size:23px; letter-spacing:-0.8px; margin:12px 0 6px; color:#172B29;">
                98.5%
            </div>
            <div style="font-size:10px; color:#7B9187;">Terkirim sesuai spesifikasi</div>
        </div>
    </div>

    {{-- Grid 2 Kolom --}}
    <div style="display:grid; grid-template-columns:minmax(0, 1.5fr) minmax(320px, 1fr); gap:18px; margin-bottom:24px;">
        
        {{-- Kiri: Tren Bulanan with Hover Tooltip --}}
        <div class="card" style="padding:22px;">
            <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Tren Volume Kontrak Bulanan</h2>
            <p style="font-size:11px; color:#91A19B; margin:0 0 20px;">Arahkan kursor pada batang untuk melihat nominal lengkap & tonase per bulan tanpa pembulatan singkatan.</p>

            <div style="height:210px; display:grid; grid-template-columns:repeat(7, 1fr); gap:14px; padding:20px 12px 10px; border-bottom:1px solid #E5ECEA; margin-bottom:20px;">
                @php
                    $monthsData = [
                        ['Apr', 45, '45.000 kg', 1125000000],
                        ['Mei', 65, '65.000 kg', 1625000000],
                        ['Jun', 52, '52.000 kg', 1300000000],
                        ['Jul', 80, '80.000 kg', 2000000000],
                        ['Agt', 70, '70.000 kg', 1750000000],
                        ['Sep', 95, '95.000 kg', 2375000000],
                        ['Okt', 85, '85.000 kg', 2125000000]
                    ];
                @endphp
                @foreach($monthsData as $m)
                <div style="display:flex; flex-direction:column; justify-content:flex-end; align-items:center; height:100%; position:relative;">
                    <div x-data="{ hovered: false }"
                         @mouseenter="hovered = true"
                         @mouseleave="hovered = false"
                         style="width:100%; max-width:44px; height:{{ round($m[1] * 1.55) }}px; background:linear-gradient(180deg, #7257CA, #A797E8); border-radius:8px 8px 0 0; position:relative; cursor:pointer; transition:transform 0.15s, filter 0.15s;"
                         onmouseover="this.style.transform='scaleY(1.05)'; this.style.filter='brightness(1.1)';"
                         onmouseout="this.style.transform='scaleY(1)'; this.style.filter='none';">
                        
                        {{-- Tooltip only on hover --}}
                        <div x-show="hovered" x-cloak
                             style="position:absolute; bottom:calc(100% + 8px); left:50%; transform:translateX(-50%); background:#1E293B; color:#fff; padding:7px 12px; border-radius:8px; font-size:11px; white-space:nowrap; z-index:50; pointer-events:none; box-shadow:0 8px 24px rgba(0,0,0,0.28); text-align:center;">
                            <div style="font-weight:800; color:#A78BFA;">{{ $m[0] }}: {{ $m[2] }}</div>
                            <div style="font-size:10px; color:#E2E8F0; margin-top:2px;">Rp {{ number_format($m[3], 0, ',', '.') }}</div>
                            <div style="position:absolute; top:100%; left:50%; transform:translateX(-50%); border:5px solid transparent; border-top-color:#1E293B;"></div>
                        </div>
                    </div>
                    <span style="font-size:11px; font-weight:750; color:#85958F; margin-top:8px; text-align:center; display:block;">{{ $m[0] }}</span>
                </div>
                @endforeach
            </div>

            <div style="display:flex; justify-content:space-between; font-size:11.5px; color:#85958F; flex-wrap:wrap; gap:8px;">
                <span>Total Komoditas Terdaftar: <b style="color:#172B29;">{{ $allListings->count() }} listing</b></span>
                <span>Total Luas Lahan: <b style="color:#0A765A;">{{ number_format($allListings->sum('land_area_m2'), 0, ',', '.') }} m²</b></span>
            </div>
        </div>

        {{-- Kanan: Distribusi Peran Pengguna --}}
        <div class="card" style="padding:22px;">
            <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Distribusi Komposisi Pengguna</h2>
            <p style="font-size:11px; color:#91A19B; margin:0 0 20px;">Keseimbangan antara penawaran (petani) dan permintaan (konsumen).</p>

            <div style="display:flex; flex-direction:column; gap:16px;">
                <div>
                    <div style="display:flex; justify-content:space-between; font-size:12px; font-weight:750; margin-bottom:6px;">
                        <span>Petani (Supply)</span>
                        <b style="color:#0C8D65;">{{ $allUsers->where('role', 'petani')->count() }} akun</b>
                    </div>
                    <div style="height:10px; border-radius:10px; background:#E8F2ED; overflow:hidden;">
                        <div style="height:100%; border-radius:10px; background:#05C793; width:{{ $allUsers->count() > 0 ? ($allUsers->where('role', 'petani')->count() / $allUsers->count()) * 100 : 50 }}%;"></div>
                    </div>
                </div>

                <div>
                    <div style="display:flex; justify-content:space-between; font-size:12px; font-weight:750; margin-bottom:6px;">
                        <span>Konsumen (Demand)</span>
                        <b style="color:#0B8BAA;">{{ $allUsers->where('role', 'pembeli')->count() }} akun</b>
                    </div>
                    <div style="height:10px; border-radius:10px; background:#E8F9FC; overflow:hidden;">
                        <div style="height:100%; border-radius:10px; background:#06B6D4; width:{{ $allUsers->count() > 0 ? ($allUsers->where('role', 'pembeli')->count() / $allUsers->count()) * 100 : 50 }}%;"></div>
                    </div>
                </div>

                <div style="padding:14px; border-radius:12px; background:#F8FCFA; border:1px solid #DFEAE5; font-size:11px; color:#50695E; line-height:1.6; margin-top:8px;">
                    💡 <b>Catatan Operasional:</b> Rasio penawaran dan permintaan dalam kondisi ideal untuk mendukung stabilitas harga komoditas pangan segar tanpa spekulasi tengkulak.
                </div>
            </div>
        </div>

    </div>

@endsection
