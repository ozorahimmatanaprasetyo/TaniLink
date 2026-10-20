@extends('layouts.sidebar')

@section('title', 'Dashboard Gapoktan - TaniLink')

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
    TaniLink &rsaquo; Gapoktan &rsaquo; <strong>Dashboard</strong>
@endsection

@section('content')

    {{-- Headline Section --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:26px;">
        <div>
            <div style="font-size:10px; font-weight:900; color:#049874; text-transform:uppercase; letter-spacing:1.6px; margin-bottom:6px;">TaniLink / Gapoktan</div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">
                Selamat datang kembali, {{ $petani->name }} 👋
            </h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">
                Pantau hasil panen, kontrak, dan progres tanaman dalam satu tempat.
            </p>
        </div>
        <a href="{{ route('petani.komoditas.create') }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 16px; border-radius:11px; background:#05C793; color:#053B2D; font-size:12px; font-weight:800; text-decoration:none;">
            + Tambah Komoditas
        </a>
    </div>

    {{-- 4 Metric Cards --}}
    <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:16px; margin-bottom:20px;">
        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Listing Aktif</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">♧</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ str_pad($myListings->count(), 2, '0', STR_PAD_LEFT) }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Jumlah komoditas ditawarkan</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Total Kapasitas</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">▥</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ number_format($totalCropKg, 0, ',', '.') }} kg
            </div>
            <div style="font-size:10px; color:#7B9187;">Estimasi hasil panen total</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Pesanan Masuk</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">▤</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ str_pad($activeOrders->count(), 2, '0', STR_PAD_LEFT) }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Kontrak berjalan</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>DP Diterima</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">◉</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                Rp {{ number_format($dpReceived, 0, ',', '.') }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Modal awal 30% kontrak</div>
        </div>
    </div>

    {{-- Notice Banner --}}
    <div style="padding:14px 18px; display:flex; align-items:flex-start; gap:11px; border-radius:12px; background:#FFF8EA; border:1px solid #F6E3B9; color:#7F631E; font-size:11px; line-height:1.6; margin-bottom:20px;">
        <span>ⓘ</span>
        <span><b>Demo keuangan:</b> Perhitungan DP 30% dan escrow 70% pada prototype ini adalah simulasi perencanaan, bukan bukti uang telah diterima atau diamankan.</span>
    </div>

    {{-- Grid 2 Kolom --}}
    <div style="display:grid; grid-template-columns:minmax(0, 1.6fr) minmax(300px, 1fr); gap:18px;">
        
        {{-- Kiri: Pesanan Terbaru --}}
        <div class="card" style="padding:22px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
                <div>
                    <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Pesanan Terbaru</h2>
                    <p style="font-size:11px; color:#91A19B; margin:0;">Lihat permintaan dan tindak lanjut kontrak.</p>
                </div>
                <a href="{{ route('petani.pesanan') }}" style="color:#07946C; font-size:11px; font-weight:850; text-decoration:none;">
                    Lihat semua →
                </a>
            </div>

            @if($activeOrders->isEmpty())
            <div style="text-align:center; padding:40px 10px; color:#8A9D95;">
                <div style="font-size:32px; margin-bottom:8px;">📦</div>
                <div style="font-size:13px; font-weight:700;">Belum Ada Pesanan</div>
                <p style="font-size:11px; margin-top:4px;">Pesanan dari Horeka akan muncul di sini setelah komoditas dibooking.</p>
            </div>
            @else
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; text-align:left;">
                    <thead>
                        <tr>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">PESANAN</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">PEMBELI</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">NILAI</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">STATUS</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeOrders->take(4) as $order)
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="font-size:11px; font-weight:650; padding:14px 10px;">
                                <b style="font-weight:850; color:#183B30;">{{ $order->commodityListing->commodity_name ?? 'Komoditas' }}</b>
                                <div style="font-size:10px; color:#85958F; margin-top:2px;">{{ $order->order_code }} • {{ number_format($order->quantity_kg, 0, ',', '.') }} kg</div>
                            </td>
                            <td style="font-size:11px; font-weight:650; color:#172B29; padding:14px 10px;">
                                {{ $order->buyer->name ?? 'Pembeli' }}
                            </td>
                            <td style="font-size:11px; font-weight:800; color:#0A765A; padding:14px 10px;">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td style="font-size:11px; padding:14px 10px;">
                                <span style="display:inline-flex; align-items:center; padding:4px 8px; border-radius:6px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:10px;">
                                    {{ $order->status === 'dp_cair' ? 'DP Cair' : ucfirst($order->status) }}
                                </span>
                            </td>
                            <td style="font-size:11px; padding:14px 10px;">
                                <a href="{{ route('petani.pesanan.show', $order->id) }}" style="color:#07946C; font-weight:850; text-decoration:none;">Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- Kanan: Progres Tanam (Monitoring) --}}
        @php
            $featuredOrder = $activeOrders->first();
        @endphp
        <div class="card" style="padding:22px;">
            <div style="margin-bottom:16px;">
                <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Progres Tanam</h2>
                <p style="font-size:11px; color:#91A19B; margin:0;">
                    {{ $featuredOrder ? 'Pesanan ' . $featuredOrder->order_code : 'Monitoring tanaman' }}
                </p>
            </div>

            @if($featuredOrder)
            <div>
                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:10px;">
                    {{ $featuredOrder->current_stage ?? 'Fase Pertumbuhan' }}
                </span>

                <div style="display:flex; justify-content:space-between; margin:20px 0 8px; font-size:12px; font-weight:750;">
                    <span style="color:#172B29;">Menuju panen</span>
                    <span style="color:#0A9370; font-weight:850;">{{ $featuredOrder->stage_percentage ?? 65 }}%</span>
                </div>

                {{-- Progress track --}}
                <div style="height:8px; border-radius:12px; background:#E8F2ED; overflow:hidden; margin-bottom:18px;">
                    <div style="height:100%; border-radius:12px; background:linear-gradient(90deg, #05C793, #14AAB1); width:{{ $featuredOrder->stage_percentage ?? 65 }}%;"></div>
                </div>

                <div style="height:1px; background:#E5ECEA; margin:16px 0;"></div>

                {{-- Timeline Stages --}}
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <div style="display:flex; align-items:center; justify-content:space-between; padding:8px 0; border-bottom:1px solid #EEF2F0; font-size:11px;">
                        <span>🌱 &nbsp; Penyemaian</span>
                        <span style="color:#0C8D65; font-weight:750; font-size:10px;">Selesai</span>
                    </div>
                    <div style="display:flex; align-items:center; justify-content:space-between; padding:8px 0; border-bottom:1px solid #EEF2F0; font-size:11px;">
                        <span>🌿 &nbsp; Pertumbuhan Vegetatif</span>
                        <span style="color:#0C8D65; font-weight:750; font-size:10px;">Selesai</span>
                    </div>
                    <div style="display:flex; align-items:center; justify-content:space-between; padding:8px 0; border-bottom:1px solid #EEF2F0; font-size:11px;">
                        <span>🌼 &nbsp; Pembungaan & Buah</span>
                        <span style="color:#0B8BAA; font-weight:750; font-size:10px;">Berlangsung</span>
                    </div>
                    <div style="display:flex; align-items:center; justify-content:space-between; padding:8px 0; font-size:11px;">
                        <span>🌶️ &nbsp; Panen & Sortir</span>
                        <span style="color:#91A19B; font-weight:750; font-size:10px;">Menunggu</span>
                    </div>
                </div>

                <div style="margin-top:20px;">
                    <a href="{{ route('petani.pesanan.show', $featuredOrder->id) }}" style="display:block; text-align:center; padding:10px; border-radius:11px; background:#EBF9F2; color:#078362; font-size:12px; font-weight:800; text-decoration:none;">
                        Update & Foto Progres →
                    </a>
                </div>
            </div>
            @else
            <div style="text-align:center; padding:40px 10px; color:#8A9D95;">
                <p style="font-size:12px;">Belum ada pesanan aktif untuk dimonitor.</p>
            </div>
            @endif
        </div>

    </div>

@endsection
