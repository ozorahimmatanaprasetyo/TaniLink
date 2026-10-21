@extends('layouts.sidebar')

@section('title', $listing->commodity_name . ' - Detail Komoditas - TaniLink')

@section('sidebar_nav')
<a href="{{ route('portal.pembeli') }}" class="nav-btn {{ request()->routeIs('portal.pembeli') ? 'active' : '' }}">
    <span class="nav-icon">▦</span> Dashboard
</a>
<a href="{{ route('pembeli.katalog') }}" class="nav-btn {{ request()->routeIs('pembeli.katalog*') || request()->routeIs('pembeli.komoditas*') ? 'active' : '' }}">
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
@php
    $emojis = ['Sayuran' => '🍅', 'Cabai & Bumbu' => '🌶️', 'Palawija' => '🌽', 'Buah' => '🍊'];
    $emoji = $emojis[$listing->category] ?? '🌱';
    $imgUrl = $listing->image_url ?? '';
    $hasImage = !empty($imgUrl);
@endphp

    {{-- Breadcrumb back --}}
    <div style="font-size:12px; color:#8A9D95; margin-bottom:20px;">
        <a href="{{ route('pembeli.katalog') }}" style="color:#07946C; font-weight:800; text-decoration:none;">
            ← Kembali ke Marketplace
        </a>
        <span style="margin:0 6px;">/</span>
        <span>{{ $listing->category }}</span>
        <span style="margin:0 6px;">/</span>
        <b style="color:#172B29;">{{ $listing->commodity_name }}</b>
    </div>

    {{-- Grid 2 Kolom --}}
    <div style="display:grid; grid-template-columns:minmax(0, 1.4fr) minmax(340px, 1fr); gap:24px;">
        
        {{-- Kiri: Galeri & Info Produksi --}}
        <div style="display:flex; flex-direction:column; gap:20px;">
            
            {{-- Foto Utama --}}
            <div class="card" style="padding:16px;">
                <div style="height:360px; border-radius:14px; background:linear-gradient(145deg, #FFE6D9, #FFF3E9); display:grid; place-items:center; font-size:140px; position:relative; overflow:hidden;">
                    @if($hasImage)
                        <img src="{{ str_starts_with($imgUrl, 'http') ? $imgUrl : asset($imgUrl) }}"
                             alt="{{ $listing->commodity_name }}"
                             style="width:100%; height:100%; object-fit:cover; position:absolute; inset:0;">
                    @else
                        {{ $emoji }}
                    @endif
                </div>

                {{-- Thumbnails --}}
                <div style="display:flex; gap:10px; margin-top:12px;">
                    <div style="height:70px; width:88px; border-radius:10px; background:#FFE3D8; display:grid; place-items:center; font-size:32px; border:2px solid #05C793; cursor:pointer;">
                        {{ $emoji }}
                    </div>
                    <div style="height:70px; width:88px; border-radius:10px; background:#E7F7DF; display:grid; place-items:center; font-size:32px; opacity:0.75; cursor:pointer;">
                        🌱
                    </div>
                    <div style="height:70px; width:88px; border-radius:10px; background:#F0EDE2; display:grid; place-items:center; font-size:32px; opacity:0.75; cursor:pointer;">
                        🌼
                    </div>
                </div>
            </div>

            {{-- Informasi Produksi --}}
            <div class="card" style="padding:22px;">
                <h2 style="font-size:15px; font-weight:850; margin:0 0 16px; color:#172B29;">Informasi Budidaya & Lahan</h2>
                
                <table style="width:100%; font-size:12px; border-collapse:collapse;">
                    <tbody>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:10px 0; color:#8A9994;">Varietas / Bibit</td>
                            <td style="padding:10px 0; text-align:right; font-weight:750; color:#172B29;">{{ $listing->variety ?? 'Unggul Lokal' }}</td>
                        </tr>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:10px 0; color:#8A9994;">Luas Area Lahan</td>
                            <td style="padding:10px 0; text-align:right; font-weight:750; color:#172B29;">{{ number_format($listing->land_area_m2, 0, ',', '.') }} m²</td>
                        </tr>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:10px 0; color:#8A9994;">Kategori Hasil Panen</td>
                            <td style="padding:10px 0; text-align:right; font-weight:750; color:#172B29;">{{ $listing->category }}</td>
                        </tr>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:10px 0; color:#8A9994;">Lokasi Pertanian</td>
                            <td style="padding:10px 0; text-align:right; font-weight:750; color:#172B29;">{{ $listing->location ?? 'Malang' }}</td>
                        </tr>
                        <tr>
                            <td style="padding:10px 0; color:#8A9994;">Estimasi Siap Panen</td>
                            <td style="padding:10px 0; text-align:right; font-weight:800; color:#0A765A;">
                                {{ $listing->harvest_estimated_date ? \Carbon\Carbon::parse($listing->harvest_estimated_date)->format('d F Y') : '-' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

        {{-- Kanan: Detail Penawaran & Action Pre-Order --}}
        <div style="display:flex; flex-direction:column; gap:20px;">
            
            <div class="card" style="padding:26px;">
                <span style="display:inline-flex; align-items:center; padding:4px 10px; border-radius:7px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:10px;">
                    Rencana Panen / Pre-Order
                </span>

                <h1 style="font-size:28px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:14px 0 6px;">
                    {{ $listing->commodity_name }}
                </h1>
                
                <div style="font-size:11px; color:#8A9994;">
                    Varietas {{ $listing->variety ?? '-' }} &bull; Ditawarkan oleh <strong>{{ $listing->farmer->organization ?? ($listing->farmer->name ?? 'Gapoktan Mitra') }}</strong>
                </div>

                @if(!empty($listing->description))
                <div style="margin: 14px 0; padding: 12px 14px; border-radius: 10px; background: #F8FCFA; border: 1px solid #DFEAE5; font-size: 12px; color: #365448; line-height: 1.6;">
                    <strong style="color: #172B29; display:block; margin-bottom:4px; font-size: 11px; text-transform:uppercase; letter-spacing:0.5px;">Deskripsi Tanaman:</strong>
                    {{ $listing->description }}
                </div>
                @endif

                <div style="font-size:32px; font-weight:900; color:#087A59; margin:20px 0 6px;">
                    Rp {{ number_format($listing->price_per_kg, 0, ',', '.') }}
                    <span style="font-size:12px; font-weight:500; color:#8A9994;">/kg</span>
                </div>

                <div style="height:1px; background:#E5ECEA; margin:18px 0;"></div>

                {{-- Tabel Rincian Stok --}}
                <table style="width:100%; font-size:11px; border-collapse:collapse; margin-bottom:18px;">
                    <tbody>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:8px 0; color:#8A9994;">Estimasi Hasil Panen</td>
                            <td style="padding:8px 0; text-align:right; font-weight:750; color:#172B29;">{{ number_format($listing->estimated_harvest_kg, 0, ',', '.') }} kg</td>
                        </tr>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:8px 0; color:#8A9994;">Alokasi Tersedia Dipesan</td>
                            <td style="padding:8px 0; text-align:right; font-weight:800; color:#0A765A;">{{ number_format($listing->available_kg, 0, ',', '.') }} kg</td>
                        </tr>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:8px 0; color:#8A9994;">Skema Pembayaran</td>
                            <td style="padding:8px 0; text-align:right; font-weight:750; color:#172B29;">30% DP Modal + 70% Escrow</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0; color:#8A9994;">Jadwal Pengiriman</td>
                            <td style="padding:8px 0; text-align:right; font-weight:750; color:#172B29;">Langsung saat panen</td>
                        </tr>
                    </tbody>
                </table>

                {{-- Notice --}}
                <div style="padding:12px 14px; border-radius:10px; background:#EEF9FB; border:1px solid #CCEBF2; color:#287B8A; font-size:11px; line-height:1.6; margin-bottom:20px;">
                    ⓘ <b>Pemberitahuan Pre-Order:</b> Jadwal panen bersifat perkiraan dan kuantitas akan dikonfirmasi oleh petani. Tidak sama dengan stok gudang ritel siap kirim.
                </div>

                {{-- Aksi --}}
                @if($listing->status === 'available' && $listing->available_kg > 0)
                <div style="display:flex; gap:10px;">
                    <a href="{{ route('pembeli.preorder', $listing->id) }}"
                       style="flex:1; text-align:center; padding:12px 20px; border-radius:11px; background:#05C793; color:#053B2D; font-size:13px; font-weight:850; text-decoration:none; box-shadow:0 3px 12px rgba(5,199,147,0.3);">
                        Ajukan Pre-Order →
                    </a>
                </div>
                @else
                <div style="padding:12px; border-radius:11px; background:#F5F8F7; border:1px solid #E5ECEA; color:#8A9994; text-align:center; font-size:12px; font-weight:700;">
                    Komoditas Ini Telah Terkontrak Penuh
                </div>
                @endif
            </div>

            {{-- Profil Penjual --}}
            <div class="card" style="padding:22px;">
                <h2 style="font-size:15px; font-weight:850; margin:0 0 14px; color:#172B29;">Profil Produsen</h2>
                
                <div style="display:flex; align-items:center; gap:14px;">
                    <div style="width:48px; height:48px; border-radius:14px; background:#DCF8EB; color:#007B55; display:grid; place-items:center; font-size:22px; font-weight:850; flex-shrink:0;">
                        🌱
                    </div>
                    <div>
                        <b style="font-size:13px; color:#172B29;">{{ $listing->farmer->organization ?? ($listing->farmer->name ?? 'Gapoktan') }}</b>
                        <div style="font-size:11px; color:#8A9994; margin-top:2px;">{{ $listing->location ?? 'Malang, Jawa Timur' }}</div>
                        <div style="font-size:10px; color:#0C8D65; font-weight:750; margin-top:4px;">✓ Produsen Terverifikasi TaniLink</div>
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection

