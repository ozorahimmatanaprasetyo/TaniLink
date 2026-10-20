@extends('layouts.sidebar')

@section('title', 'Pantau Pesanan ' . $order->order_code . ' - TaniLink')

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
    TaniLink &rsaquo; Horeka &rsaquo; <a href="{{ route('pembeli.pesanan') }}" style="color:inherit; text-decoration:none;">Pesanan</a> &rsaquo; <strong>{{ $order->order_code }}</strong>
@endsection

@section('content')

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:20px;">
        <div>
            <div style="font-size:10px; font-weight:900; color:#049874; text-transform:uppercase; letter-spacing:1.6px; margin-bottom:6px;">TaniLink / Horeka</div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">
                Pantau Pesanan {{ $order->order_code }}
            </h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">
                {{ $order->commodityListing->commodity_name ?? 'Komoditas' }} &bull; {{ number_format($order->quantity_kg, 0, ',', '.') }} kg &bull; Gapoktan {{ $order->farmer->name ?? 'Mitra Petani' }}
            </p>
        </div>
        <a href="{{ route('pembeli.pesanan') }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 16px; border-radius:11px; border:1px solid #DFE8E5; background:#fff; color:#365249; font-size:12px; font-weight:800; text-decoration:none;">
            ← Semua Pesanan
        </a>
    </div>

    {{-- Notice --}}
    <div style="padding:14px 18px; display:flex; align-items:flex-start; gap:11px; border-radius:12px; background:#FFF8EA; border:1px solid #F6E3B9; color:#7F631E; font-size:11px; line-height:1.6; margin-bottom:20px;">
        <span>ⓘ</span>
        <span><b>Mode simulasi pengiriman dan escrow:</b> nama kurir, rute, jarak, estimasi durasi serta dana escrow dalam platform adalah fitur monitoring terintegrasi untuk kelancaran suplai B2B.</span>
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

                {{-- Timeline 4 Fase --}}
                <div style="display:flex; flex-direction:column; gap:16px;">
                    <div style="display:flex; gap:14px;">
                        <div style="height:25px; width:25px; min-width:25px; border-radius:50%; display:grid; place-items:center; background:#E5F9EF; color:#069873; font-size:12px;">🌱</div>
                        <div>
                            <b style="font-size:12px; color:#172B29;">Benih & Semai</b>
                            <p style="font-size:10px; color:#8A9994; line-height:1.5; margin:3px 0 0;">Bibit varietas {{ $order->commodityListing->variety ?? 'unggul' }} mulai disemai.</p>
                        </div>
                    </div>

                    <div style="display:flex; gap:14px;">
                        <div style="height:25px; width:25px; min-width:25px; border-radius:50%; display:grid; place-items:center; background:#E5F9EF; color:#069873; font-size:12px;">🌿</div>
                        <div>
                            <b style="font-size:12px; color:#172B29;">Pertumbuhan Vegetatif</b>
                            <p style="font-size:10px; color:#8A9994; line-height:1.5; margin:3px 0 0;">Irigasi berjalan teratur dan monitoring nutrisi harian.</p>
                        </div>
                    </div>

                    <div style="display:flex; gap:14px;">
                        <div style="height:25px; width:25px; min-width:25px; border-radius:50%; display:grid; place-items:center; background:#E8F9FC; color:#0B8BAA; font-size:12px;">🌼</div>
                        <div>
                            <b style="font-size:12px; color:#172B29;">Pembungaan & Bakal Buah</b>
                            <p style="font-size:10px; color:#8A9994; line-height:1.5; margin:3px 0 0;">Bakal panen mulai terbentuk merata, kualitas terkontrol.</p>
                        </div>
                    </div>

                    <div style="display:flex; gap:14px;">
                        <div style="height:25px; width:25px; min-width:25px; border-radius:50%; display:grid; place-items:center; background:#F5F8F7; color:#91A19B; font-size:12px;">🌶️</div>
                        <div>
                            <b style="font-size:12px; color:#172B29;">Panen & Pengepakan</b>
                            <p style="font-size:10px; color:#8A9994; line-height:1.5; margin:3px 0 0;">Siap panen dan sortir sesuai spesifikasi pesanan Horeka.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Logistik Pengiriman --}}
            <div class="card" style="padding:24px;">
                <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Logistik Pengiriman</h2>
                <p style="font-size:11px; color:#91A19B; margin:0 0 16px;">Ilustrasi rute pengiriman dari lahan petani ke lokasi Anda.</p>

                {{-- Map Visual Placeholder --}}
                <div style="height:200px; background:repeating-linear-gradient(0deg, #EBF4ED 0 43px, #fff 43px 45px), repeating-linear-gradient(90deg, #DCEFE6 0 40px, #EFF7F0 40px 42px); border-radius:14px; border:1px solid #D9E8DD; display:grid; place-items:center; position:relative; overflow:hidden;">
                    <div style="position:absolute; font-size:18px; color:#079E73; transform:rotate(-15deg); letter-spacing:8px; opacity:0.6;">
                        ● ━━━ ● ━━━ ●
                    </div>
                    <span style="z-index:2; background:#fff; border:1px solid #D9E8DD; padding:10px 16px; border-radius:10px; font-size:12px; font-weight:800; color:#417760; box-shadow:0 4px 14px rgba(0,0,0,0.06);">
                        📍 {{ $order->commodityListing->location ?? 'Malang' }} → {{ $order->buyer->organization ?? 'Grand Hotel Malang' }}
                    </span>
                </div>

                {{-- 3 Info Cells --}}
                <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:12px; margin-top:16px;">
                    <div style="background:#F7FAF9; border-radius:12px; padding:12px 14px;">
                        <span style="font-size:10px; color:#889991;">Jarak estimasi</span>
                        <b style="display:block; font-size:14px; color:#172B29; margin-top:4px;">18 km</b>
                    </div>
                    <div style="background:#F7FAF9; border-radius:12px; padding:12px 14px;">
                        <span style="font-size:10px; color:#889991;">Waktu tempuh</span>
                        <b style="display:block; font-size:14px; color:#172B29; margin-top:4px;">45 menit</b>
                    </div>
                    <div style="background:#F7FAF9; border-radius:12px; padding:12px 14px;">
                        <span style="font-size:10px; color:#889991;">Kemasan krat</span>
                        <b style="display:block; font-size:14px; color:#172B29; margin-top:4px;">{{ max(1, ceil($order->quantity_kg / 20)) }} krat</b>
                    </div>
                </div>
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

            {{-- Card Rencana Pengiriman --}}
            <div class="card" style="padding:22px;">
                <h2 style="font-size:15px; font-weight:850; margin:0 0 16px; color:#172B29;">Rencana Pengiriman</h2>
                
                <table style="width:100%; font-size:11px; border-collapse:collapse;">
                    <tbody>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:10px 0; color:#8A9994;">Asal Penjemputan</td>
                            <td style="padding:10px 0; text-align:right; font-weight:750; color:#172B29;">
                                {{ $order->commodityListing->location ?? 'Ambarawa, Malang' }}
                            </td>
                        </tr>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:10px 0; color:#8A9994;">Tujuan Penerima</td>
                            <td style="padding:10px 0; text-align:right; font-weight:750; color:#172B29;">
                                {{ $order->buyer->organization ?? 'Grand Hotel Malang' }}
                            </td>
                        </tr>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:10px 0; color:#8A9994;">Kurir Mitra</td>
                            <td style="padding:10px 0; text-align:right; font-weight:750; color:#172B29;">
                                {{ $order->deliveryTracking->driver_name ?? 'Budi Santoso' }}
                            </td>
                        </tr>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:10px 0; color:#8A9994;">Kendaraan</td>
                            <td style="padding:10px 0; text-align:right; font-weight:750; color:#172B29;">
                                {{ $order->deliveryTracking->driver_vehicle ?? 'Pickup L300' }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:10px 0; color:#8A9994;">Status Logistik</td>
                            <td style="padding:10px 0; text-align:right;">
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#FFF5E8; color:#BA7A1F; font-weight:800; font-size:10px;">
                                    Terjadwal Panen
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

    </div>

@endsection

