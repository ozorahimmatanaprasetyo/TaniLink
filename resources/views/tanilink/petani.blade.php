@extends('layouts.sidebar')

@section('title', 'Dashboard Petani - TaniLink')

@push('styles')
<style>
    /* ── Responsive Petani Dashboard ── */
    .petani-headline {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 24px;
        gap: 16px;
    }
    .petani-metrics-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }
    .petani-metric-card {
        padding: 18px 20px;
        transition: transform 0.15s, box-shadow 0.15s;
    }
    .petani-content-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.6fr) minmax(300px, 1fr);
        gap: 18px;
    }
    .desktop-orders-table {
        display: block;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .mobile-orders-list {
        display: none;
    }

    /* Tablet (<= 992px) */
    @media (max-width: 992px) {
        .petani-metrics-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        .petani-content-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
    }

    /* Mobile Phones (<= 640px) */
    @media (max-width: 640px) {
        .petani-headline {
            flex-direction: column;
            align-items: stretch;
            gap: 14px;
            margin-bottom: 18px;
        }
        .petani-headline h1 {
            font-size: 21px !important;
            letter-spacing: -0.6px !important;
            margin-bottom: 4px !important;
        }
        .petani-headline p {
            font-size: 11.5px !important;
        }
        .petani-headline a {
            width: 100%;
            justify-content: center;
            box-sizing: border-box;
            padding: 11px 16px !important;
        }

        .petani-metrics-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 16px;
        }
        .petani-metric-card {
            padding: 14px 12px !important;
            border-radius: 14px !important;
        }
        .petani-metric-header {
            font-size: 10px !important;
        }
        .petani-metric-icon {
            width: 30px !important;
            height: 30px !important;
            font-size: 13px !important;
            border-radius: 9px !important;
        }
        .petani-metric-val {
            font-size: 19px !important;
            margin: 8px 0 4px !important;
            letter-spacing: -0.5px !important;
        }
        .petani-metric-sub {
            font-size: 9.5px !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .desktop-orders-table {
            display: none !important;
        }
        .mobile-orders-list {
            display: flex !important;
            flex-direction: column;
            gap: 10px;
        }
    }
</style>
@endpush

@section('sidebar_nav')
<a href="{{ route('portal.petani') }}" class="nav-btn {{ request()->routeIs('portal.petani') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-chart-pie"></i></span> Beranda
</a>
<a href="{{ route('petani.komoditas') }}" class="nav-btn {{ request()->routeIs('petani.komoditas*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-seedling"></i></span> Komoditas Saya
</a>
<a href="{{ route('petani.pesanan') }}" class="nav-btn {{ request()->routeIs('petani.pesanan*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-clipboard-list"></i></span> Pesanan Masuk
</a>
<a href="{{ route('petani.profil') }}" class="nav-btn {{ request()->routeIs('petani.profil') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-user"></i></span> Profil Petani
</a>
@endsection

@section('content')
<div x-data="{ showUpdateModal: false, selectedOrderId: '{{ $activeOrders->first()->id ?? '' }}' }">

    {{-- Headline Section --}}
    <div class="petani-headline">
        <div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">
                Selamat datang kembali, {{ $petani->name }}!
            </h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">
                Pantau hasil panen, kontrak, dan progres tanaman dalam satu tempat.
            </p>
        </div>
        <a href="{{ route('petani.komoditas.create') }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 18px; border-radius:11px; background:#05C793; color:#053B2D; font-size:12px; font-weight:800; text-decoration:none; box-shadow:0 3px 10px rgba(5,199,147,0.2); flex-shrink:0;">
            <i class="fa-solid fa-plus"></i> Tambah Komoditas
        </a>
    </div>

    {{-- 4 Metric Cards --}}
    <div class="petani-metrics-grid">
        <div class="card petani-metric-card">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;" class="petani-metric-header">
                <span>Komoditas Aktif</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:15px;" class="petani-metric-icon"><i class="fa-solid fa-seedling"></i></span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;" class="petani-metric-val">
                {{ str_pad($myListings->count(), 2, '0', STR_PAD_LEFT) }}
            </div>
            <div style="font-size:10px; color:#7B9187;" class="petani-metric-sub">Jumlah komoditas ditawarkan</div>
        </div>

        <div class="card petani-metric-card">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;" class="petani-metric-header">
                <span>Total Kapasitas</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:15px;" class="petani-metric-icon"><i class="fa-solid fa-weight-hanging"></i></span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;" class="petani-metric-val">
                {{ number_format($totalCropKg, 0, ',', '.') }} kg
            </div>
            <div style="font-size:10px; color:#7B9187;" class="petani-metric-sub">Estimasi hasil panen total</div>
        </div>

        <div class="card petani-metric-card">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;" class="petani-metric-header">
                <span>Pesanan Masuk</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:15px;" class="petani-metric-icon"><i class="fa-solid fa-clipboard-list"></i></span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;" class="petani-metric-val">
                {{ str_pad($activeOrders->count(), 2, '0', STR_PAD_LEFT) }}
            </div>
            <div style="font-size:10px; color:#7B9187;" class="petani-metric-sub">Pesanan berjalan</div>
        </div>

        <div class="card petani-metric-card">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;" class="petani-metric-header">
                <span>Nilai Pesanan</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:15px;" class="petani-metric-icon"><i class="fa-solid fa-receipt"></i></span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;" class="petani-metric-val">
                Rp {{ number_format($dpReceived, 0, ',', '.') }}
            </div>
            <div style="font-size:10px; color:#7B9187;" class="petani-metric-sub">Akumulasi pesanan aktif</div>
        </div>
    </div>

    {{-- Info Card --}}
    <div style="padding:14px 18px; display:flex; align-items:flex-start; gap:11px; border-radius:12px; background:#F8FCFA; border:1px solid #DCECE5; color:#254D3F; font-size:11px; line-height:1.6; margin-bottom:20px;">
        <span style="color:#0C8D65; flex-shrink:0; margin-top:2px;"><i class="fa-solid fa-circle-info"></i></span>
        <span><b>Alur Pemenuhan Pesanan:</b> Pesanan dari konsumen masuk dengan status menunggu konfirmasi. Setelah Anda menyetujui, Anda dapat memperbarui progres budidaya di setiap tahap hingga panen siap diserahterimakan.</span>
    </div>

    {{-- Grid 2 Kolom --}}
    <div class="petani-content-grid">
        
        {{-- Kiri: Pesanan Terbaru --}}
        <div class="card" style="padding:22px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
                <div>
                    <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Pesanan Terbaru</h2>
                    <p style="font-size:11px; color:#91A19B; margin:0;">Lihat permintaan dan tindak lanjut pesanan.</p>
                </div>
                <a href="{{ route('petani.pesanan') }}" style="display:inline-flex; align-items:center; gap:6px; padding:6px 12px; border-radius:8px; background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D; font-size:11px; font-weight:800; text-decoration:none;">
                    Lihat Semua
                </a>
            </div>

            @if($activeOrders->isEmpty())
            <div style="text-align:center; padding:40px 10px; color:#8A9D95;">
                <div style="font-size:32px; margin-bottom:8px; color:#A7D7C5;"><i class="fa-solid fa-box-open"></i></div>
                <div style="font-size:13px; font-weight:700;">Belum Ada Pesanan</div>
                <p style="font-size:11px; margin-top:4px;">Pesanan dari konsumen akan muncul di sini setelah komoditas dibooking.</p>
            </div>
            @else
            {{-- Desktop Table View (>= 640px) --}}
            <div class="desktop-orders-table">
                <table style="width:100%; border-collapse:collapse; text-align:left; min-width:480px;">
                    <thead>
                        <tr>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">PESANAN</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">PEMBELI</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">NILAI</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">STATUS</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px; text-align:right;">AKSI</th>
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
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td style="font-size:11px; padding:14px 10px; text-align:right;">
                                <a href="{{ route('petani.pesanan.show', $order->id) }}"
                                   style="display:inline-block; padding:5px 12px; border-radius:7px; background:#EBF9F2; border:1px solid #C6EEDC; color:#078362; font-weight:800; font-size:11px; text-decoration:none;">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Orders Card List (< 640px) --}}
            <div class="mobile-orders-list">
                @foreach($activeOrders->take(4) as $order)
                <div style="border:1px solid #EEF2F0; border-radius:12px; padding:12px 14px; background:#F8FCFA; display:flex; flex-direction:column; gap:8px;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:8px;">
                        <div>
                            <b style="font-size:13px; font-weight:850; color:#183B30;">{{ $order->commodityListing->commodity_name ?? 'Komoditas' }}</b>
                            <div style="font-size:10.5px; color:#7B9187; margin-top:2px;">{{ $order->order_code }} &bull; {{ number_format($order->quantity_kg, 0, ',', '.') }} kg</div>
                        </div>
                        <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:10px; white-space:nowrap; flex-shrink:0;">
                            {{ $order->status_label }}
                        </span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; padding-top:8px; border-top:1px dashed #E5ECEA;">
                        <div>
                            <div style="font-size:10px; color:#8A9A94;">Pembeli: <b style="color:#172B29;">{{ $order->buyer->name ?? 'Pembeli' }}</b></div>
                            <div style="font-size:13px; font-weight:850; color:#0A765A; margin-top:1px;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                        </div>
                        <a href="{{ route('petani.pesanan.show', $order->id) }}"
                           style="display:inline-flex; align-items:center; gap:4px; padding:6px 14px; border-radius:8px; background:#EBF9F2; border:1px solid #C6EEDC; color:#078362; font-weight:800; font-size:11.5px; text-decoration:none;">
                            Detail <i class="fa-solid fa-arrow-right" style="font-size:10px;"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Kanan: Informasi Batch & Manajemen Perkembangan Tanaman --}}
        <div class="card" style="padding:22px; display:flex; flex-direction:column; justify-content:space-between; gap:16px;">
            <div>
                <div style="margin-bottom:16px;">
                    <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Perkembangan Tanaman</h2>
                    <p style="font-size:11px; color:#91A19B; margin:0;">
                        Dokumentasi dikelola terpusat berdasarkan Batch Panen komoditas Anda.
                    </p>
                </div>

                <div style="background:#F8FCFA; border:1px solid #DFEAE5; border-radius:12px; padding:16px;">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                        <span style="width:32px; height:32px; border-radius:8px; background:#E9F9F2; color:#0C8D65; display:grid; place-items:center; font-size:15px; flex-shrink:0;">
                            <i class="fa-solid fa-leaf"></i>
                        </span>
                        <div>
                            <div style="font-size:12px; font-weight:800; color:#172B29;">Sistem Batch Terpusat</div>
                            <div style="font-size:10.5px; color:#6B8077;">Satu update foto &amp; catatan melayani semua pemesan di batch tersebut.</div>
                        </div>
                    </div>
                    <p style="font-size:11px; color:#4E655C; margin:0; line-height:1.5;">
                        Untuk memperbarui perkembangan atau melihat riwayat foto lahan, buka daftar komoditas dan pilih batch yang sedang berjalan.
                    </p>
                </div>
            </div>

            <div>
                <a href="{{ route('petani.komoditas') }}" class="btn btn-primary" style="display:flex; align-items:center; justify-content:center; gap:8px; width:100%; text-decoration:none; padding:12px; box-sizing:border-box;">
                    <i class="fa-solid fa-sprout"></i> Kelola Perkembangan Tanaman
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
