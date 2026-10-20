@extends('layouts.sidebar')

@section('title', 'Komoditas Saya - TaniLink')

@section('sidebar_nav')
<a href="{{ route('portal.petani') }}" class="nav-btn {{ request()->routeIs('portal.petani') ? 'active' : '' }}">
    <span class="nav-icon">▦</span> Dashboard
</a>
<a href="{{ route('petani.komoditas') }}" class="nav-btn {{ request()->routeIs('petani.komoditas') ? 'active' : '' }}">
    <span class="nav-icon">♧</span> Komoditas Saya
</a>
<a href="{{ route('petani.komoditas.create') }}" class="nav-btn {{ request()->routeIs('petani.komoditas.create') ? 'active' : '' }}">
    <span class="nav-icon">⊕</span> Tambah Komoditas
</a>
<a href="{{ route('petani.pesanan') }}" class="nav-btn {{ request()->routeIs('petani.pesanan*') ? 'active' : '' }}">
    <span class="nav-icon">▤</span> Pesanan Masuk
</a>
<a href="{{ route('petani.profil') }}" class="nav-btn {{ request()->routeIs('petani.profil') ? 'active' : '' }}">
    <span class="nav-icon">♙</span> Profil Gapoktan
</a>
@endsection

@section('breadcrumb')
    TaniLink &rsaquo; Gapoktan &rsaquo; <strong>Komoditas Saya</strong>
@endsection

@section('content')

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:26px;">
        <div>
            <div style="font-size:10px; font-weight:900; color:#049874; text-transform:uppercase; letter-spacing:1.6px; margin-bottom:6px;">TaniLink / Gapoktan</div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Komoditas Saya</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">Kelola seluruh penawaran komoditas, stok alokasi, dan estimasi panen.</p>
        </div>
        <a href="{{ route('petani.komoditas.create') }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 16px; border-radius:11px; background:#05C793; color:#053B2D; font-size:12px; font-weight:800; text-decoration:none;">
            + Tambah Komoditas
        </a>
    </div>

    {{-- 3 Metric Cards --}}
    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; margin-bottom:24px;">
        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Jumlah Listing</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">♧</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">{{ $myListings->count() }}</div>
            <div style="font-size:10px; color:#7B9187;">Komoditas aktif terdaftar</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Kapasitas Estimasi</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">▥</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">{{ number_format($totalCropKg, 0, ',', '.') }} kg</div>
            <div style="font-size:10px; color:#7B9187;">Bukan stok fisik aktual</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>DP Diterima (Simulasi)</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">◉</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">Rp {{ number_format($dpReceived, 0, ',', '.') }}</div>
            <div style="font-size:10px; color:#7B9187;">Modal awal 30% kontrak</div>
        </div>
    </div>

    {{-- Listings Table Card --}}
    <div class="card" style="padding:24px;">
        <div style="margin-bottom:18px;">
            <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Daftar Komoditas</h2>
            <p style="font-size:11px; color:#91A19B; margin:0;">Setiap harga dan jumlah dapat berubah mengikuti penawaran kelompok tani.</p>
        </div>

        @if($myListings->isEmpty())
        <div style="text-align:center; padding:50px 20px; color:#8A9D95;">
            <div style="font-size:42px; margin-bottom:10px;">🌾</div>
            <h3 style="font-size:15px; font-weight:800; color:#172B29; margin-bottom:4px;">Belum Ada Komoditas</h3>
            <p style="font-size:12px; margin-bottom:16px;">Publikasikan penawaran tanaman Anda agar pembeli Horeka dapat mengajukan pre-order.</p>
            <a href="{{ route('petani.komoditas.create') }}" style="display:inline-block; padding:10px 18px; border-radius:11px; background:#05C793; color:#053B2D; font-size:12px; font-weight:800; text-decoration:none;">
                + Tambah Komoditas Baru
            </a>
        </div>
        @else
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:left;">
                <thead>
                    <tr>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">KOMODITAS</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">HARGA / KG</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">TERSEDIA</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">EST. PANEN</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">STATUS</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($myListings as $listing)
                    <tr style="border-bottom:1px solid #EEF2F0;">
                        <td style="font-size:11px; font-weight:650; padding:15px 10px;">
                            <b style="font-weight:850; color:#183B30;">{{ $listing->commodity_name }}</b>
                            <div style="font-size:10px; color:#85958F; margin-top:2px;">{{ $listing->variety ?? '-' }} • {{ $listing->location ?? 'Malang' }}</div>
                        </td>
                        <td style="font-size:11px; font-weight:800; color:#0A765A; padding:15px 10px;">
                            Rp {{ number_format($listing->price_per_kg, 0, ',', '.') }}
                        </td>
                        <td style="font-size:11px; font-weight:650; padding:15px 10px;">
                            {{ number_format($listing->available_kg, 0, ',', '.') }} kg
                        </td>
                        <td style="font-size:11px; font-weight:650; padding:15px 10px;">
                            {{ $listing->harvest_estimated_date ? \Carbon\Carbon::parse($listing->harvest_estimated_date)->format('d M Y') : '-' }}
                        </td>
                        <td style="font-size:11px; padding:15px 10px;">
                            @if($listing->status === 'booked' || $listing->available_kg <= 0)
                                <span style="display:inline-flex; align-items:center; padding:5px 9px; border-radius:7px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:10px;">Terkontrak</span>
                            @else
                                <span style="display:inline-flex; align-items:center; padding:5px 9px; border-radius:7px; background:#E9F9F2; color:#0C8D65; font-weight:800; font-size:10px;">Aktif</span>
                            @endif
                        </td>
                        <td style="font-size:11px; padding:15px 10px;">
                            <a href="{{ route('petani.pesanan') }}" style="color:#07946C; font-weight:850; text-decoration:none;">Kelola ↗</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

@endsection
