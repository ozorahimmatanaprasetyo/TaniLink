@extends('layouts.sidebar')

@section('title', 'Pantau Pesanan ' . $order->order_code . ' - TaniLink')

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

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:20px;">
        <div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">
                Pantau Pesanan {{ $order->order_code }}
            </h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">
                {{ $order->commodityListing->commodity_name ?? 'Komoditas' }} &bull; {{ number_format($order->quantity_kg, 0, ',', '.') }} kg &bull; Petani {{ $order->farmer->name ?? 'Mitra Petani' }}
            </p>
        </div>
        <a href="{{ route('pembeli.pesanan') }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 16px; border-radius:11px; border:1px solid #DFE8E5; background:#fff; color:#365249; font-size:12px; font-weight:800; text-decoration:none;">
            ← Semua Pesanan
        </a>
    </div>

    {{-- Notice --}}
    <div style="padding:14px 18px; display:flex; align-items:flex-start; gap:11px; border-radius:12px; background:#FFF8EA; border:1px solid #F6E3B9; color:#7F631E; font-size:11px; line-height:1.6; margin-bottom:20px;">
        <span>ⓘ</span>
        <span><b>Sistem Escrow Terjamin:</b> DP 30% telah disalurkan untuk pembiayaan bibit dan pupuk petani. Saldo 70% diamankan di rekening escrow bersama TaniLink dan hanya diteruskan setelah barang panen diterima.</span>
    </div>

    {{-- Grid 2 Kolom --}}
    <div style="display:grid; grid-template-columns:minmax(0, 1.5fr) minmax(320px, 1fr); gap:20px;">
        
        {{-- Kiri Stack --}}
        <div style="display:flex; flex-direction:column; gap:18px;">
            
            {{-- Card Perkembangan Tanaman --}}
            <div class="card" style="padding:24px;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
                    <div>
                        <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Perkembangan Tanaman di Lahan</h2>
                        <p style="font-size:11px; color:#91A19B; margin:0;">Informasi lapangan langsung dari kelompok tani mitra.</p>
                    </div>
                    <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:10px;">
                        {{ $order->stage_percentage ?? 65 }}% menuju panen
                    </span>
                </div>

                <div style="display:flex; justify-content:space-between; margin:18px 0 8px; font-size:12px; font-weight:750;">
                    <span style="color:#172B29;">Tahap Saat Ini: <b style="color:#0C8D65;">{{ $order->current_stage ?? 'Fase Pembungaan' }}</b></span>
                    <span style="color:#0A9370; font-weight:850;">{{ $order->stage_percentage ?? 65 }}%</span>
                </div>

                <div style="height:8px; border-radius:12px; background:#E8F2ED; overflow:hidden; margin-bottom:20px;">
                    <div style="height:100%; border-radius:12px; background:linear-gradient(90deg, #05C793, #14AAB1); width:{{ $order->stage_percentage ?? 65 }}%;"></div>
                </div>

                <div style="height:1px; background:#E5ECEA; margin:16px 0;"></div>

                {{-- Dynamic Timeline 4 Fase --}}
                <div style="display:flex; flex-direction:column; gap:16px;">
                    @foreach($order->phase_timeline as $ph)
                    <div style="display:flex; gap:14px; align-items:flex-start;">
                        <div style="height:30px; width:30px; min-width:30px; border-radius:50%; display:grid; place-items:center; font-size:14px;
                            {{ $ph['status'] === 'selesai' ? 'background:#E5F9EF; color:#069873;' : ($ph['status'] === 'berlangsung' ? 'background:#E8F9FC; color:#0B8BAA; border:2px solid #05C793;' : 'background:#F5F8F7; color:#91A19B;') }}">
                            {{ $ph['icon'] }}
                        </div>
                        <div style="flex:1;">
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <b style="font-size:12.5px; color:#172B29;">{{ $ph['name'] }}</b>
                                @if($ph['status'] === 'selesai')
                                    <span style="font-size:10px; font-weight:800; color:#0C8D65; background:#E9F9F2; padding:2px 8px; border-radius:5px;">Selesai</span>
                                @elseif($ph['status'] === 'berlangsung')
                                    <span style="font-size:10px; font-weight:800; color:#0B8BAA; background:#E8F9FC; padding:2px 8px; border-radius:5px;">Sedang Berlangsung</span>
                                @else
                                    <span style="font-size:10px; font-weight:750; color:#91A19B; background:#F5F8F7; padding:2px 8px; border-radius:5px;">Menunggu</span>
                                @endif
                            </div>
                            <p style="font-size:11px; color:#6B8077; line-height:1.5; margin:4px 0 0;">{{ $ph['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Card Logistik Pengiriman with Real Map & Pinpoint Coordinates --}}
            <div class="card" style="padding:24px;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
                    <div>
                        <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Peta Lokasi Lahan &amp; Koordinat Kebun</h2>
                        <p style="font-size:11px; color:#91A19B; margin:0;">Titik lokasi kebun petani mitra untuk koordinasi penjemputan hasil panen.</p>
                    </div>
                    @if($order->distance_km)
                    <span style="display:inline-flex; align-items:center; gap:5px; padding:5px 10px; border-radius:8px; background:#E9FBF3; border:1px solid #C5EEDC; color:#0A7E5E; font-size:11.5px; font-weight:850;">
                        🚗 ± {{ $order->distance_km }} km dari lokasi Anda
                    </span>
                    @endif
                </div>

                {{-- Real Map Embed with Precise Coordinates if available --}}
                @php
                    $farmerLat = $order->commodityListing->latitude ?? ($order->farmer->latitude ?? null);
                    $farmerLng = $order->commodityListing->longitude ?? ($order->farmer->longitude ?? null);
                    $farmLoc = $order->commodityListing->location ?? ($order->farmer->location ?? 'Malang, Jawa Timur');
                    
                    if ($farmerLat && $farmerLng) {
                        $mapQuery = $farmerLat . ',' . $farmerLng;
                        $gmapsUrl = "https://www.google.com/maps?q={$farmerLat},{$farmerLng}";
                    } else {
                        $mapQuery = urlencode($farmLoc);
                        $gmapsUrl = "https://www.google.com/maps/search/?api=1&query=" . urlencode($farmLoc);
                    }
                @endphp

                <div style="border-radius:14px; overflow:hidden; border:1px solid #DFEAE5; margin-bottom:14px; box-shadow:0 2px 10px rgba(0,0,0,0.04);">
                    <iframe width="100%" height="230" style="border:0; display:block;" loading="lazy" allowfullscreen
                            src="https://maps.google.com/maps?q={{ $mapQuery }}&t=&z=14&ie=UTF8&iwloc=&output=embed">
                    </iframe>
                </div>

                <div style="display:flex; justify-content:space-between; align-items:center; background:#F8FCFA; border:1px solid #DFEAE5; border-radius:11px; padding:12px 16px; font-size:11.5px;">
                    <div>
                        <div style="color:#254D3F; font-weight:800;">
                            📍 {{ $farmLoc }}
                        </div>
                        @if($farmerLat && $farmerLng)
                        <div style="color:#6A867A; font-size:10.5px; margin-top:2px;">
                            Koordinat Presisi: <code>{{ $farmerLat }}, {{ $farmerLng }}</code>
                        </div>
                        @endif
                    </div>
                    <a href="{{ $gmapsUrl }}" target="_blank"
                       style="padding:6px 12px; border-radius:8px; background:#05C793; color:#053B2D; font-weight:850; text-decoration:none; font-size:11px; box-shadow:0 2px 6px rgba(5,199,147,0.3); white-space:nowrap;">
                        Buka di Google Maps ↗
                    </a>
                </div>
            </div>

            {{-- Card Foto Dokumentasi Lapangan dari Petani --}}
            <div class="card" style="padding:24px;">
                <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Dokumentasi Lapangan Realtime</h2>
                <p style="font-size:11px; color:#91A19B; margin:0 0 16px;">Foto dan catatan kondisi tanaman yang dilaporkan langsung oleh petani.</p>

                @if($order->plantingProgress->isEmpty())
                <div style="text-align:center; padding:30px 10px; color:#8A9D95;">
                    <p style="font-size:12px;">Petani belum mengunggah foto progres terbaru.</p>
                </div>
                @else
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(240px, 1fr)); gap:14px;">
                    @foreach($order->plantingProgress as $prog)
                    <div style="border:1px solid #E5ECEA; border-radius:12px; overflow:hidden; background:#fff;">
                        @if($prog->photo_url)
                        <div style="height:140px; overflow:hidden; background:#F5F8F7;">
                            <img src="{{ str_starts_with($prog->photo_url, 'http') ? $prog->photo_url : asset($prog->photo_url) }}"
                                 alt="{{ $prog->stage_name }}"
                                 style="width:100%; height:100%; object-fit:cover;">
                        </div>
                        @endif
                        <div style="padding:12px;">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                                <b style="font-size:11.5px; color:#172B29;">{{ $prog->stage_name }}</b>
                                <span style="font-size:9.5px; color:#8A9D95;">{{ $prog->reported_at ? $prog->reported_at->diffForHumans() : 'Baru saja' }}</span>
                            </div>
                            <p style="font-size:11px; color:#50695E; line-height:1.5; margin:0;">
                                {{ $prog->note }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>

        {{-- Kanan Stack --}}
        <div style="display:flex; flex-direction:column; gap:18px;">
            
            {{-- Card Ringkasan Pre-Order --}}
            <div class="card" style="padding:22px;">
                <h2 style="font-size:15px; font-weight:850; margin:0 0 16px; color:#172B29;">Ringkasan Pre-Order</h2>
                
                <table style="width:100%; font-size:11px; border-collapse:collapse;">
                    <tbody>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:10px 0; color:#8A9994;">Kuantitas</td>
                            <td style="padding:10px 0; text-align:right; font-weight:800; color:#172B29;">
                                {{ number_format($order->quantity_kg, 0, ',', '.') }} kg
                            </td>
                        </tr>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:10px 0; color:#8A9994;">Harga Patokan / kg</td>
                            <td style="padding:10px 0; text-align:right; font-weight:800; color:#172B29;">
                                Rp {{ number_format($order->price_per_kg, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:10px 0; color:#8A9994;">Total Nilai Kontrak</td>
                            <td style="padding:10px 0; text-align:right; font-weight:850; font-size:13px; color:#0A765A;">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:10px 0; color:#8A9994;">DP 30% (Modal Petani)</td>
                            <td style="padding:10px 0; text-align:right; font-weight:800; color:#0C8D65;">
                                Rp {{ number_format($order->dp_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:10px 0; color:#8A9994;">Escrow 70% (Terkunci Aman)</td>
                            <td style="padding:10px 0; text-align:right; font-weight:800; color:#6953B5;">
                                Rp {{ number_format($order->escrow_locked_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Card Titik Serah Terima --}}
            <div class="card" style="padding:22px;">
                <h2 style="font-size:15px; font-weight:850; margin:0 0 16px; color:#172B29;">Titik Serah Terima Panen</h2>
                
                <table style="width:100%; font-size:11px; border-collapse:collapse;">
                    <tbody>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:10px 0; color:#8A9994;">Kontak Petani Mitra</td>
                            <td style="padding:10px 0; text-align:right; font-weight:750; color:#172B29;">
                                {{ $order->farmer->name ?? 'Petani Mitra' }} &bull; {{ $order->farmer->organization ?? '-' }}
                                @if($order->farmer && $order->farmer->phone)
                                <div style="margin-top:3px;">
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->farmer->phone) }}" target="_blank"
                                       style="color:#0C8D65; font-size:10.5px; text-decoration:none; font-weight:800; display:inline-flex; align-items:center; gap:4px;">
                                        <i class="fa-brands fa-whatsapp"></i> {{ $order->farmer->phone }}
                                    </a>
                                </div>
                                @endif
                            </td>
                        </tr>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:10px 0; color:#8A9994;">Lokasi Kebun Panen (Asal)</td>
                            <td style="padding:10px 0; text-align:right; font-weight:750; color:#172B29;">
                                {{ $order->commodityListing->location ?? ($order->farmer->location ?? 'Malang, Jawa Timur') }}
                            </td>
                        </tr>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:10px 0; color:#8A9994;">Tujuan Konsumen (Tujuan)</td>
                            <td style="padding:10px 0; text-align:right; font-weight:750; color:#172B29;">
                                {{ $order->buyer->location ?? ($order->buyer->organization ?? 'Lokasi Konsumen') }}
                            </td>
                        </tr>
                        @if($order->distance_km)
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:10px 0; color:#8A9994;">Estimasi Jarak Tempuh</td>
                            <td style="padding:10px 0; text-align:right; font-weight:850; color:#0A7E5E;">
                                ± {{ $order->distance_km }} km (rute langsung)
                            </td>
                        </tr>
                        @endif
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:10px 0; color:#8A9994;">Metode Penyerahan</td>
                            <td style="padding:10px 0; text-align:right; font-weight:750; color:#078362;">
                                Pengambilan Mandiri di Lahan Kebun
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:10px 0; color:#8A9994;">Status Jadwal</td>
                            <td style="padding:10px 0; text-align:right;">
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#FFF5E8; color:#BA7A1F; font-weight:800; font-size:10px;">
                                    {{ $order->current_stage_status === 'selesai' && $order->current_stage === 'Fase Pematangan & Siap Panen' ? 'Siap Diambil' : 'Dalam Masa Tanam' }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

    </div>

@endsection

