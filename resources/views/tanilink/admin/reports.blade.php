@extends('layouts.sidebar')

@section('title', 'Laporan & Statistik - Admin TaniLink')

@section('sidebar_nav')
<a href="{{ route('portal.admin') }}" class="nav-btn {{ request()->routeIs('portal.admin') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-chart-pie"></i></span> Beranda Admin
</a>
<a href="{{ route('admin.pengguna') }}" class="nav-btn {{ request()->routeIs('admin.pengguna*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-users"></i></span> Kelola Pengguna
</a>
<a href="{{ route('admin.komoditas') }}" class="nav-btn {{ request()->routeIs('admin.komoditas*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-boxes-stacked"></i></span> Kelola Komoditas
</a>
<a href="{{ route('admin.pesanan') }}" class="nav-btn {{ request()->routeIs('admin.pesanan*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-receipt"></i></span> Pesanan &amp; Transaksi
</a>
<a href="{{ route('admin.laporan') }}" class="nav-btn {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-chart-line"></i></span> Laporan &amp; Statistik
</a>
<a href="{{ route('admin.bantuan') }}" class="nav-btn {{ request()->routeIs('admin.bantuan*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-circle-question"></i></span> Kelola Pusat Bantuan
</a>
@endsection

@section('content')
<div x-data="{
    periodType: '{{ $filters['period_type'] }}',
    reportType: '{{ $filters['report_type'] }}'
}">

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
        <div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Laporan &amp; Statistik Platform</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">Pantau performa ekosistem per periode, visualisasi tren transaksi, dan ekspor laporan PDF komprehensif.</p>
        </div>
    </div>

    {{-- 1. Filter Card --}}
    <div class="card" style="padding:22px; margin-bottom:24px;">
        <form method="GET" action="{{ route('admin.laporan') }}" id="reportFilterForm">
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px; align-items:flex-end;">
                
                {{-- Periode Selector --}}
                <div>
                    <label style="display:block; font-size:11px; font-weight:750; color:#50695E; margin-bottom:6px;">Tipe Periode</label>
                    <select name="period_type" x-model="periodType"
                            style="width:100%; padding:9px 12px; border:1px solid #E5ECEA; border-radius:10px; font-size:12px; color:#172B29; background:#fff; outline:0;">
                        <option value="bulanan">Bulanan</option>
                        <option value="tahunan">Tahunan</option>
                        <option value="rentang">Rentang Khusus</option>
                    </select>
                </div>

                {{-- Month (Bulanan) --}}
                <div x-show="periodType === 'bulanan'">
                    <label style="display:block; font-size:11px; font-weight:750; color:#50695E; margin-bottom:6px;">Bulan</label>
                    <select name="month"
                            style="width:100%; padding:9px 12px; border:1px solid #E5ECEA; border-radius:10px; font-size:12px; color:#172B29; background:#fff; outline:0;">
                        @php
                            $months = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                        @endphp
                        @foreach($months as $num => $name)
                            <option value="{{ $num }}" {{ $filters['month'] == $num ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Year (Bulanan & Tahunan) --}}
                <div x-show="periodType === 'bulanan' || periodType === 'tahunan'">
                    <label style="display:block; font-size:11px; font-weight:750; color:#50695E; margin-bottom:6px;">Tahun</label>
                    <select name="year"
                            style="width:100%; padding:9px 12px; border:1px solid #E5ECEA; border-radius:10px; font-size:12px; color:#172B29; background:#fff; outline:0;">
                        @for($y = 2024; $y <= 2030; $y++)
                            <option value="{{ $y }}" {{ $filters['year'] == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                {{-- Start Date (Rentang) --}}
                <div x-show="periodType === 'rentang'" style="display:none;">
                    <label style="display:block; font-size:11px; font-weight:750; color:#50695E; margin-bottom:6px;">Tanggal Awal</label>
                    <input type="date" name="start_date" value="{{ $filters['start_date'] }}"
                           style="width:100%; padding:8.5px 12px; border:1px solid #E5ECEA; border-radius:10px; font-size:12px; color:#172B29; background:#fff; outline:0;">
                </div>

                {{-- End Date (Rentang) --}}
                <div x-show="periodType === 'rentang'" style="display:none;">
                    <label style="display:block; font-size:11px; font-weight:750; color:#50695E; margin-bottom:6px;">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ $filters['end_date'] }}"
                           style="width:100%; padding:8.5px 12px; border:1px solid #E5ECEA; border-radius:10px; font-size:12px; color:#172B29; background:#fff; outline:0;">
                </div>

                {{-- Jenis Laporan --}}
                <div>
                    <label style="display:block; font-size:11px; font-weight:750; color:#50695E; margin-bottom:6px;">Jenis Laporan</label>
                    <select name="report_type" x-model="reportType"
                            style="width:100%; padding:9px 12px; border:1px solid #E5ECEA; border-radius:10px; font-size:12px; color:#172B29; background:#fff; outline:0;">
                        <option value="keseluruhan">1. Laporan Keseluruhan</option>
                        <option value="pesanan">2. Laporan Pesanan &amp; Transaksi</option>
                        <option value="pembayaran">3. Laporan Pembayaran DP</option>
                        <option value="komoditas">4. Laporan Komoditas &amp; Panen</option>
                        <option value="pengguna">5. Laporan Pengguna</option>
                    </select>
                </div>

            </div>

            {{-- Action Buttons --}}
            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:20px; padding-top:16px; border-top:1px solid #EEF2F0; flex-wrap:wrap; gap:12px;">
                <div style="font-size:11.5px; color:#85958F;">
                    <i class="fa-solid fa-calendar-check" style="color:#0A765A; margin-right:4px;"></i>
                    Filter aktif: <b>{{ $filters['period_type'] === 'tahunan' ? 'Tahun ' . $filters['year'] : ($filters['period_type'] === 'rentang' ? ($filters['start_date'] ?: 'Awal') . ' s/d ' . ($filters['end_date'] ?: 'Akhir') : ($months[$filters['month']] ?? '') . ' ' . $filters['year']) }}</b>
                </div>

                <div style="display:flex; gap:10px; align-items:center;">
                    <a href="{{ route('admin.laporan') }}" 
                       style="padding:9px 16px; border-radius:10px; background:#F8FCFA; border:1px solid #DCECE5; color:#50695E; font-weight:750; font-size:12px; text-decoration:none;">
                        Reset Filter
                    </a>

                    <button type="submit"
                            style="padding:9px 18px; border-radius:10px; background:#05C793; border:0; color:#fff; font-weight:800; font-size:12px; cursor:pointer;">
                        <i class="fa-solid fa-filter" style="margin-right:5px;"></i> Terapkan Filter
                    </button>

                    <a href="{{ route('admin.laporan.pdf', request()->query()) }}" 
                       target="_blank"
                       style="padding:9px 18px; border-radius:10px; background:#0A765A; border:0; color:#fff; font-weight:800; font-size:12px; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                        <i class="fa-solid fa-file-pdf"></i> Unduh Laporan PDF
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- 2. 4 Metric Cards Berdasarkan Jenis Laporan --}}
    <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:16px; margin-bottom:24px;">
        @if($filters['report_type'] === 'pembayaran')
            <div class="card" style="padding:19px 20px;">
                <div style="font-size:11px; color:#7B8C86; font-weight:750; margin-bottom:6px;">Total Nilai Pesanan Sah</div>
                <div style="font-weight:850; font-size:22px; color:#172B29;">{{ $stats['total_order_value_formatted'] }}</div>
                <div style="font-size:10px; color:#7B9187; margin-top:4px;">Pesanan diajukan pada periode</div>
            </div>
            <div class="card" style="padding:19px 20px;">
                <div style="font-size:11px; color:#7B8C86; font-weight:750; margin-bottom:6px;">DP 30% Terkonfirmasi Petani</div>
                <div style="font-weight:850; font-size:22px; color:#0A765A;">{{ $stats['confirmed_dp_formatted'] }}</div>
                <div style="font-size:10px; color:#7B9187; margin-top:4px;">Diverifikasi diterima oleh petani</div>
            </div>
            <div class="card" style="padding:19px 20px;">
                <div style="font-size:11px; color:#7B8C86; font-weight:750; margin-bottom:6px;">DP Menunggu Pemeriksaan</div>
                <div style="font-weight:850; font-size:22px; color:#BA7A1F;">{{ $stats['unverified_dp_formatted'] }}</div>
                <div style="font-size:10px; color:#7B9187; margin-top:4px;">Bukti transfer diunggah</div>
            </div>
            <div class="card" style="padding:19px 20px;">
                <div style="font-size:11px; color:#7B8C86; font-weight:750; margin-bottom:6px;">Pelunasan Terkonfirmasi</div>
                <div style="font-weight:850; font-size:22px; color:#172B29;">{{ $stats['final_payment_formatted'] }}</div>
                <div style="font-size:10px; color:#7B9187; margin-top:4px;">Saat serah terima / tuntas</div>
            </div>
        @elseif($filters['report_type'] === 'pesanan')
            <div class="card" style="padding:19px 20px;">
                <div style="font-size:11px; color:#7B8C86; font-weight:750; margin-bottom:6px;">Total Pesanan Diajukan</div>
                <div style="font-weight:850; font-size:24px; color:#172B29;">{{ $stats['orders_submitted'] }}</div>
                <div style="font-size:10px; color:#7B9187; margin-top:4px;">Seluruh pengajuan pada periode</div>
            </div>
            <div class="card" style="padding:19px 20px;">
                <div style="font-size:11px; color:#7B8C86; font-weight:750; margin-bottom:6px;">Menunggu Konfirmasi</div>
                <div style="font-weight:850; font-size:24px; color:#BA7A1F;">{{ $stats['orders_pending'] }}</div>
                <div style="font-size:10px; color:#7B9187; margin-top:4px;">Belum direspon petani</div>
            </div>
            <div class="card" style="padding:19px 20px;">
                <div style="font-size:11px; color:#7B8C86; font-weight:750; margin-bottom:6px;">Pesanan Aktif</div>
                <div style="font-weight:850; font-size:24px; color:#0B8BAA;">{{ $stats['orders_active'] }}</div>
                <div style="font-size:10px; color:#7B9187; margin-top:4px;">Budidaya atau pengiriman</div>
            </div>
            <div class="card" style="padding:19px 20px;">
                <div style="font-size:11px; color:#7B8C86; font-weight:750; margin-bottom:6px;">Selesai / Batal</div>
                <div style="font-weight:850; font-size:24px; color:#0A765A;">{{ $stats['orders_completed'] }} <span style="font-size:14px; color:#991B1B;">/ {{ $stats['orders_cancelled'] }}</span></div>
                <div style="font-size:10px; color:#7B9187; margin-top:4px;">Tuntas terpenuhi / ditolak</div>
            </div>
        @elseif($filters['report_type'] === 'komoditas')
            <div class="card" style="padding:19px 20px;">
                <div style="font-size:11px; color:#7B8C86; font-weight:750; margin-bottom:6px;">Komoditas Baru</div>
                <div style="font-weight:850; font-size:24px; color:#172B29;">{{ $stats['new_listings'] }}</div>
                <div style="font-size:10px; color:#7B9187; margin-top:4px;">Diterbitkan pada periode</div>
            </div>
            <div class="card" style="padding:19px 20px;">
                <div style="font-size:11px; color:#7B8C86; font-weight:750; margin-bottom:6px;">Batch Panen Baru</div>
                <div style="font-weight:850; font-size:24px; color:#0B8BAA;">{{ $stats['new_batches'] }}</div>
                <div style="font-size:10px; color:#7B9187; margin-top:4px;">Siklus tanam baru dibuka</div>
            </div>
            <div class="card" style="padding:19px 20px;">
                <div style="font-size:11px; color:#7B8C86; font-weight:750; margin-bottom:6px;">Perkiraan Panen Total</div>
                <div style="font-weight:850; font-size:22px; color:#0A765A;">{{ number_format($stats['total_estimated_harvest'], 0, ',', '.') }} kg</div>
                <div style="font-size:10px; color:#7B9187; margin-top:4px;">Kapasitas listing aktif</div>
            </div>
            <div class="card" style="padding:19px 20px;">
                <div style="font-size:11px; color:#7B8C86; font-weight:750; margin-bottom:6px;">Volume Dipesan</div>
                <div style="font-weight:850; font-size:22px; color:#172B29;">{{ number_format($stats['total_ordered_kg'], 0, ',', '.') }} kg</div>
                <div style="font-size:10px; color:#7B9187; margin-top:4px;">Alokasi kontrak periode ini</div>
            </div>
        @elseif($filters['report_type'] === 'pengguna')
            <div class="card" style="padding:19px 20px;">
                <div style="font-size:11px; color:#7B8C86; font-weight:750; margin-bottom:6px;">Petani Baru Terdaftar</div>
                <div style="font-weight:850; font-size:24px; color:#0A765A;">{{ $stats['new_farmers'] }}</div>
                <div style="font-size:10px; color:#7B9187; margin-top:4px;">Bergabung pada periode</div>
            </div>
            <div class="card" style="padding:19px 20px;">
                <div style="font-size:11px; color:#7B8C86; font-weight:750; margin-bottom:6px;">Konsumen Baru Terdaftar</div>
                <div style="font-weight:850; font-size:24px; color:#0B8BAA;">{{ $stats['new_buyers'] }}</div>
                <div style="font-size:10px; color:#7B9187; margin-top:4px;">Bergabung pada periode</div>
            </div>
            <div class="card" style="padding:19px 20px;">
                <div style="font-size:11px; color:#7B8C86; font-weight:750; margin-bottom:6px;">Total Akun Petani</div>
                <div style="font-weight:850; font-size:24px; color:#172B29;">{{ $stats['total_farmers'] }}</div>
                <div style="font-size:10px; color:#7B9187; margin-top:4px;">Kumulatif hingga periode</div>
            </div>
            <div class="card" style="padding:19px 20px;">
                <div style="font-size:11px; color:#7B8C86; font-weight:750; margin-bottom:6px;">Total Akun Konsumen</div>
                <div style="font-weight:850; font-size:24px; color:#172B29;">{{ $stats['total_buyers'] }}</div>
                <div style="font-size:10px; color:#7B9187; margin-top:4px;">Kumulatif hingga periode</div>
            </div>
        @else
            {{-- Keseluruhan --}}
            <div class="card" style="padding:19px 20px;">
                <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                    <span>Total Nilai Pesanan Sah</span>
                    <span style="width:32px; height:32px; display:grid; place-items:center; border-radius:10px; background:#FFF5E8; color:#BA7A1F; font-size:14px;"><i class="fa-solid fa-receipt"></i></span>
                </div>
                <div style="font-weight:850; font-size:22px; margin:10px 0 4px; color:#172B29;">{{ $stats['total_order_value_formatted'] }}</div>
                <div style="font-size:10px; color:#7B9187;">Periode aktif (bukan batal)</div>
            </div>
            <div class="card" style="padding:19px 20px;">
                <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                    <span>DP Dikonfirmasi Petani</span>
                    <span style="width:32px; height:32px; display:grid; place-items:center; border-radius:10px; background:#E9FBF3; color:#139B78; font-size:14px;"><i class="fa-solid fa-circle-check"></i></span>
                </div>
                <div style="font-weight:850; font-size:22px; margin:10px 0 4px; color:#0A765A;">{{ $stats['confirmed_dp_formatted'] }}</div>
                <div style="font-size:10px; color:#7B9187;">DP 30% terverifikasi</div>
            </div>
            <div class="card" style="padding:19px 20px;">
                <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                    <span>Pesanan Masuk</span>
                    <span style="width:32px; height:32px; display:grid; place-items:center; border-radius:10px; background:#E8F9FC; color:#0B8BAA; font-size:14px;"><i class="fa-solid fa-basket-shopping"></i></span>
                </div>
                <div style="font-weight:850; font-size:23px; margin:10px 0 4px; color:#172B29;">{{ $stats['orders_submitted'] }}</div>
                <div style="font-size:10px; color:#7B9187;">{{ $stats['orders_active'] }} aktif &bull; {{ $stats['orders_completed'] }} selesai</div>
            </div>
            <div class="card" style="padding:19px 20px;">
                <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                    <span>Mitra Baru</span>
                    <span style="width:32px; height:32px; display:grid; place-items:center; border-radius:10px; background:#F1EFFB; color:#7257CA; font-size:14px;"><i class="fa-solid fa-users"></i></span>
                </div>
                <div style="font-weight:850; font-size:23px; margin:10px 0 4px; color:#6953B5;">+{{ $stats['new_farmers'] + $stats['new_buyers'] }}</div>
                <div style="font-size:10px; color:#7B9187;">{{ $stats['new_farmers'] }} petani &bull; {{ $stats['new_buyers'] }} konsumen</div>
            </div>
        @endif
    </div>

    {{-- 3 & 4. 4 Grafik Utama Interaktif --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(420px, 1fr)); gap:18px; margin-bottom:24px;">
        
        {{-- A. Grafik Batang — Jumlah Pesanan --}}
        <div class="card" style="padding:22px;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px;">
                <div>
                    <h2 style="font-size:14px; font-weight:850; color:#172B29; margin:0 0 4px;">Jumlah Pengajuan Pesanan</h2>
                    <p style="font-size:11px; color:#85958F; margin:0;">Berdasarkan tanggal pengajuan pre-order.</p>
                </div>
                <span style="font-size:10.5px; font-weight:750; color:#0A765A; background:#E9FBF3; padding:3px 9px; border-radius:6px;">
                    Total: {{ $chartData['donut']['total'] }}
                </span>
            </div>
            <div style="position:relative; height:240px; width:100%;">
                <canvas id="chartOrdersBar"></canvas>
            </div>
        </div>

        {{-- B. Diagram Donut — Distribusi Status Pesanan --}}
        <div class="card" style="padding:22px;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px;">
                <div>
                    <h2 style="font-size:14px; font-weight:850; color:#172B29; margin:0 0 4px;">Distribusi Status Pesanan</h2>
                    <p style="font-size:11px; color:#85958F; margin:0;">Pengelompokan status operasional dalam periode.</p>
                </div>
                <span style="font-size:10.5px; font-weight:750; color:#0B8BAA; background:#E8F9FC; padding:3px 9px; border-radius:6px;">
                    4 Kategori
                </span>
            </div>
            <div style="position:relative; height:240px; width:100%; display:flex; align-items:center; justify-content:center;">
                @if($chartData['has_orders'])
                    <canvas id="chartStatusDonut"></canvas>
                @else
                    <div style="text-align:center; color:#85958F; font-size:11px;">
                        <i class="fa-solid fa-chart-pie" style="font-size:26px; color:#D1DCD7; margin-bottom:6px; display:block;"></i>
                        Belum ada pesanan pada periode ini
                    </div>
                @endif
            </div>
        </div>

        {{-- C. Grafik Garis — Tren Nilai Pesanan --}}
        <div class="card" style="padding:22px;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px;">
                <div>
                    <h2 style="font-size:14px; font-weight:850; color:#172B29; margin:0 0 4px;">Tren Akumulasi Nilai Pesanan</h2>
                    <p style="font-size:11px; color:#85958F; margin:0;">Hanya menghitung pesanan sah non-batal (bukan pendapatan platform).</p>
                </div>
                <span style="font-size:10.5px; font-weight:750; color:#BA7A1F; background:#FFF5E8; padding:3px 9px; border-radius:6px;">
                    {{ $stats['total_order_value_formatted'] }}
                </span>
            </div>
            <div style="position:relative; height:240px; width:100%;">
                <canvas id="chartRevenueLine"></canvas>
            </div>
        </div>

        {{-- D. Grafik Batang Horizontal — Top 5 Komoditas Paling Banyak Dipesan --}}
        <div class="card" style="padding:22px;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px;">
                <div>
                    <h2 style="font-size:14px; font-weight:850; color:#172B29; margin:0 0 4px;">Komoditas Terbanyak Dipesan</h2>
                    <p style="font-size:11px; color:#85958F; margin:0;">Top 5 volume kontrak kebutuhan panen (kg) lintas batch.</p>
                </div>
                <span style="font-size:10.5px; font-weight:750; color:#6953B5; background:#F1EFFB; padding:3px 9px; border-radius:6px;">
                    Top 5
                </span>
            </div>
            <div style="position:relative; height:240px; width:100%; display:flex; align-items:center; justify-content:center;">
                @if($chartData['has_commodities'])
                    <canvas id="chartTopCommodities"></canvas>
                @else
                    <div style="text-align:center; color:#85958F; font-size:11px;">
                        <i class="fa-solid fa-wheat-awn" style="font-size:26px; color:#D1DCD7; margin-bottom:6px; display:block;"></i>
                        Belum ada pesanan komoditas pada periode ini
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- 5. Detail Data Card Sesuai Filter --}}
    <div class="card" style="padding:24px;">
        @if(in_array($filters['report_type'], ['keseluruhan', 'pesanan', 'pembayaran']))
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
                <div>
                    <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Daftar Transaksi Pre-Order ({{ $allOrders->count() }} Transaksi)</h2>
                    <p style="font-size:11px; color:#91A19B; margin:0;">Transaksi yang diajukan dalam periode terpilih.</p>
                </div>
            </div>

            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; text-align:left;">
                    <thead>
                        <tr>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">KODE</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">TANGGAL</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">KOMODITAS &amp; BATCH</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">PETANI</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">KONSUMEN</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">TOTAL NILAI</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">STATUS DP</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">STATUS PESANAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allOrders as $order)
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="font-size:11px; font-weight:800; color:#183B30; padding:14px 10px;">{{ $order->order_code }}</td>
                            <td style="font-size:10.5px; color:#50695E; padding:14px 10px;">{{ $order->created_at ? $order->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y') : '-' }}</td>
                            <td style="font-size:11px; padding:14px 10px;">
                                <b style="font-weight:850; color:#183B30;">{{ $order->commodityListing->commodity_name ?? 'Komoditas' }}</b>
                                <div style="font-size:10px; color:#0A765A; margin-top:2px;">{{ $order->harvestBatch->batch_name ?? 'Batch' }} &bull; {{ number_format($order->quantity_kg, 0, ',', '.') }} kg</div>
                            </td>
                            <td style="font-size:11px; color:#172B29; padding:14px 10px;">
                                <div style="font-weight:700;">{{ $order->farmer->name ?? 'Petani' }}</div>
                                <div style="font-size:10px; color:#85958F;">{{ $order->farmer->organization ?? '-' }}</div>
                            </td>
                            <td style="font-size:11px; color:#172B29; padding:14px 10px;">
                                <div style="font-weight:700;">{{ $order->buyer->name ?? 'Pembeli' }}</div>
                                <div style="font-size:10px; color:#85958F;">{{ $order->buyer->organization ?? '-' }}</div>
                            </td>
                            <td style="font-size:11px; font-weight:800; color:#0A765A; padding:14px 10px;">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td style="font-size:11px; padding:14px 10px;">
                                @if($order->dp_confirmed_at)
                                    <span style="display:inline-flex; align-items:center; gap:4px; padding:3px 8px; border-radius:6px; background:#D1FAE5; color:#065F46; font-weight:800; font-size:10px;">
                                        <i class="fa-solid fa-check"></i> DP Lunas
                                    </span>
                                @elseif($order->dp_uploaded_at)
                                    <span style="display:inline-flex; align-items:center; gap:4px; padding:3px 8px; border-radius:6px; background:#DBEAFE; color:#1E40AF; font-weight:800; font-size:10px;">
                                        Pemeriksaan
                                    </span>
                                @elseif($order->status === 'menunggu_pembayaran_dp')
                                    <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#FEF3C7; color:#92400E; font-weight:800; font-size:10px;">
                                        Menunggu DP
                                    </span>
                                @else
                                    <span style="font-size:10.5px; color:#85958F;">-</span>
                                @endif
                            </td>
                            <td style="font-size:11px; padding:14px 10px;">
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:{{ $order->status === 'selesai' ? '#D1FAE5' : ($order->status === 'dibatalkan' ? '#FEE2E2' : '#EFF6FF') }}; color:{{ $order->status === 'selesai' ? '#065F46' : ($order->status === 'dibatalkan' ? '#991B1B' : '#1D4ED8') }}; font-weight:800; font-size:10px;">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align:center; padding:30px; font-size:11px; color:#85958F;">
                                Belum ada data transaksi pada periode yang dipilih.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        @elseif($filters['report_type'] === 'komoditas')
            <div style="margin-bottom:18px;">
                <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Kapasitas Komoditas Lahan</h2>
                <p style="font-size:11px; color:#91A19B; margin:0;">Daftar listing komoditas dan estimasi produksi petani.</p>
            </div>

            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; text-align:left;">
                    <thead>
                        <tr>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">KOMODITAS</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">PETANI</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">KATEGORI</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">LUAS LAHAN</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">ESTIMASI PANEN</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">HARGA / KG</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allListings as $lst)
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="font-size:11px; font-weight:800; color:#183B30; padding:14px 10px;">{{ $lst->commodity_name }}</td>
                            <td style="font-size:11px; color:#172B29; padding:14px 10px;">{{ $lst->farmer->name ?? 'Petani' }}</td>
                            <td style="font-size:11px; color:#50695E; padding:14px 10px;">{{ $lst->category }}</td>
                            <td style="font-size:11px; color:#172B29; padding:14px 10px;">{{ number_format($lst->land_area_m2, 0, ',', '.') }} m²</td>
                            <td style="font-size:11px; font-weight:700; color:#0A765A; padding:14px 10px;">{{ number_format($lst->estimated_harvest_kg, 0, ',', '.') }} kg</td>
                            <td style="font-size:11px; color:#172B29; padding:14px 10px;">Rp {{ number_format($lst->price_per_kg, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:30px; font-size:11px; color:#85958F;">Belum ada komoditas terdaftar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        @elseif($filters['report_type'] === 'pengguna')
            <div style="margin-bottom:18px;">
                <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Mitra Terdaftar</h2>
                <p style="font-size:11px; color:#91A19B; margin:0;">Daftar petani produsen dan konsumen pengguna platform.</p>
            </div>

            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; text-align:left;">
                    <thead>
                        <tr>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">NAMA PENGGUNA</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">PERAN</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">ORGANISASI / USAHA</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">LOKASI</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">TERDAFTAR SEJAK</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allUsers as $u)
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="font-size:11px; font-weight:800; color:#183B30; padding:14px 10px;">
                                {{ $u->name }}
                                <div style="font-size:9.5px; color:#85958F; font-weight:normal;">{{ $u->email }}</div>
                            </td>
                            <td style="font-size:11px; padding:14px 10px;">
                                <span style="display:inline-block; padding:3px 8px; border-radius:6px; background:{{ $u->role === 'petani' ? '#E9FBF3' : '#E8F9FC' }}; color:{{ $u->role === 'petani' ? '#0A765A' : '#0B8BAA' }}; font-weight:800; font-size:10px;">
                                    {{ ucfirst($u->role) }}
                                </span>
                            </td>
                            <td style="font-size:11px; color:#172B29; padding:14px 10px;">{{ $u->organization ?? '-' }}</td>
                            <td style="font-size:11px; color:#50695E; padding:14px 10px;">{{ $u->location ?? '-' }}</td>
                            <td style="font-size:10.5px; color:#50695E; padding:14px 10px;">{{ $u->created_at ? $u->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y') : '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:30px; font-size:11px; color:#85958F;">Belum ada pengguna terdaftar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
{{-- Load Chart.js dari CDN resmi yang ringan & kompatibel --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const rawChartData = @json($chartData);

    // Konfigurasi Standar Font dan Warna TaniLink
    Chart.defaults.font.family = "'Plus Jakarta Sans', -apple-system, sans-serif";
    Chart.defaults.font.size = 11;
    Chart.defaults.color = '#7B8C86';

    const brandColors = {
        primary: '#05C793',
        primaryDark: '#0A765A',
        cyan: '#06B6D4',
        amber: '#BA7A1F',
        amberBg: '#FEF3C7',
        danger: '#DC2626',
        dangerBg: '#FEE2E2',
        purple: '#7257CA',
        purpleBg: '#F1EFFB',
        border: '#E5ECEA'
    };

    // Objek penyimpan instance grafik agar tidak duplikat saat inisialisasi ulang
    window.taniLinkCharts = window.taniLinkCharts || {};

    function destroyChart(id) {
        if (window.taniLinkCharts[id]) {
            window.taniLinkCharts[id].destroy();
            delete window.taniLinkCharts[id];
        }
    }

    // -------------------------------------------------------------
    // A. Grafik Batang — Jumlah Pengajuan Pesanan
    // -------------------------------------------------------------
    const ctxOrders = document.getElementById('chartOrdersBar');
    if (ctxOrders) {
        destroyChart('chartOrdersBar');
        window.taniLinkCharts['chartOrdersBar'] = new Chart(ctxOrders, {
            type: 'bar',
            data: {
                labels: rawChartData.timeline_labels,
                datasets: [{
                    label: 'Jumlah Pesanan',
                    data: rawChartData.timeline_orders,
                    backgroundColor: 'rgba(5, 199, 147, 0.75)',
                    hoverBackgroundColor: '#05C793',
                    borderColor: '#05C793',
                    borderWidth: 1,
                    borderRadius: 6,
                    maxBarThickness: 34
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#172B29',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return ` ${context.parsed.y} pesanan diajukan`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#85958F' }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0,
                            color: '#85958F'
                        },
                        grid: { color: '#F0F4F2' }
                    }
                }
            }
        });
    }

    // -------------------------------------------------------------
    // B. Diagram Donut — Distribusi Status Pesanan
    // -------------------------------------------------------------
    const ctxDonut = document.getElementById('chartStatusDonut');
    if (ctxDonut && rawChartData.has_orders) {
        destroyChart('chartStatusDonut');
        window.taniLinkCharts['chartStatusDonut'] = new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: rawChartData.donut.labels,
                datasets: [{
                    data: rawChartData.donut.counts,
                    backgroundColor: [
                        '#F59E0B', // Menunggu (Amber)
                        '#06B6D4', // Aktif Berjalan (Cyan)
                        '#05C793', // Selesai (Emerald)
                        '#EF4444'  // Dibatalkan (Red)
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 12,
                            color: '#50695E',
                            font: { size: 10.5, weight: '600' }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#172B29',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const total = rawChartData.donut.total;
                                const val = context.parsed;
                                const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                return ` ${context.label}: ${val} pesanan (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // -------------------------------------------------------------
    // C. Grafik Garis — Tren Akumulasi Nilai Pesanan
    // -------------------------------------------------------------
    const ctxRevenue = document.getElementById('chartRevenueLine');
    if (ctxRevenue) {
        destroyChart('chartRevenueLine');
        window.taniLinkCharts['chartRevenueLine'] = new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: rawChartData.timeline_labels,
                datasets: [{
                    label: 'Nilai Pesanan Sah',
                    data: rawChartData.timeline_values,
                    borderColor: '#0A765A',
                    backgroundColor: 'rgba(10, 118, 90, 0.08)',
                    borderWidth: 2.2,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#0A765A',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#172B29',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return ` Nilai Pesanan: Rp ${new Intl.NumberFormat('id-ID').format(context.parsed.y)}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#85958F' }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + ' jt';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000).toFixed(0) + ' rb';
                                }
                                return 'Rp ' + value;
                            },
                            color: '#85958F'
                        },
                        grid: { color: '#F0F4F2' }
                    }
                }
            }
        });
    }

    // -------------------------------------------------------------
    // D. Grafik Batang Horizontal — Top 5 Komoditas Terbanyak Dipesan
    // -------------------------------------------------------------
    const ctxTop = document.getElementById('chartTopCommodities');
    if (ctxTop && rawChartData.has_commodities) {
        destroyChart('chartTopCommodities');
        window.taniLinkCharts['chartTopCommodities'] = new Chart(ctxTop, {
            type: 'bar',
            data: {
                labels: rawChartData.top_commodities.labels,
                datasets: [{
                    label: 'Total Dipesan (kg)',
                    data: rawChartData.top_commodities.kg,
                    backgroundColor: 'rgba(114, 87, 202, 0.75)',
                    hoverBackgroundColor: '#7257CA',
                    borderColor: '#7257CA',
                    borderWidth: 1,
                    borderRadius: 6,
                    maxBarThickness: 22
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#172B29',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return ` Dipesan: ${new Intl.NumberFormat('id-ID').format(context.parsed.x)} kg`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' kg';
                            },
                            color: '#85958F'
                        },
                        grid: { color: '#F0F4F2' }
                    },
                    y: {
                        grid: { display: false },
                        ticks: {
                            color: '#172B29',
                            font: { weight: '600' }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush

@push('scripts')
{{-- Load Chart.js dari CDN resmi yang ringan & kompatibel --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const rawChartData = @json($chartData);

    // Konfigurasi Standar Font dan Warna TaniLink
    Chart.defaults.font.family = "'Plus Jakarta Sans', -apple-system, sans-serif";
    Chart.defaults.font.size = 11;
    Chart.defaults.color = '#7B8C86';

    const brandColors = {
        primary: '#05C793',
        primaryDark: '#0A765A',
        cyan: '#06B6D4',
        amber: '#BA7A1F',
        amberBg: '#FEF3C7',
        danger: '#DC2626',
        dangerBg: '#FEE2E2',
        purple: '#7257CA',
        purpleBg: '#F1EFFB',
        border: '#E5ECEA'
    };

    // Objek penyimpan instance grafik agar tidak duplikat saat inisialisasi ulang
    window.taniLinkCharts = window.taniLinkCharts || {};

    function destroyChart(id) {
        if (window.taniLinkCharts[id]) {
            window.taniLinkCharts[id].destroy();
            delete window.taniLinkCharts[id];
        }
    }

    // -------------------------------------------------------------
    // A. Grafik Batang — Jumlah Pengajuan Pesanan
    // -------------------------------------------------------------
    const ctxOrders = document.getElementById('chartOrdersBar');
    if (ctxOrders) {
        destroyChart('chartOrdersBar');
        window.taniLinkCharts['chartOrdersBar'] = new Chart(ctxOrders, {
            type: 'bar',
            data: {
                labels: rawChartData.timeline_labels,
                datasets: [{
                    label: 'Jumlah Pesanan',
                    data: rawChartData.timeline_orders,
                    backgroundColor: 'rgba(5, 199, 147, 0.75)',
                    hoverBackgroundColor: '#05C793',
                    borderColor: '#05C793',
                    borderWidth: 1,
                    borderRadius: 6,
                    maxBarThickness: 34
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#172B29',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return ` ${context.parsed.y} pesanan diajukan`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#85958F' }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0,
                            color: '#85958F'
                        },
                        grid: { color: '#F0F4F2' }
                    }
                }
            }
        });
    }

    // -------------------------------------------------------------
    // B. Diagram Donut — Distribusi Status Pesanan
    // -------------------------------------------------------------
    const ctxDonut = document.getElementById('chartStatusDonut');
    if (ctxDonut && rawChartData.has_orders) {
        destroyChart('chartStatusDonut');
        window.taniLinkCharts['chartStatusDonut'] = new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: rawChartData.donut.labels,
                datasets: [{
                    data: rawChartData.donut.counts,
                    backgroundColor: [
                        '#F59E0B', // Menunggu (Amber)
                        '#06B6D4', // Aktif Berjalan (Cyan)
                        '#05C793', // Selesai (Emerald)
                        '#EF4444'  // Dibatalkan (Red)
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 12,
                            color: '#50695E',
                            font: { size: 10.5, weight: '600' }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#172B29',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const total = rawChartData.donut.total;
                                const val = context.parsed;
                                const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                return ` ${context.label}: ${val} pesanan (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // -------------------------------------------------------------
    // C. Grafik Garis — Tren Akumulasi Nilai Pesanan
    // -------------------------------------------------------------
    const ctxRevenue = document.getElementById('chartRevenueLine');
    if (ctxRevenue) {
        destroyChart('chartRevenueLine');
        window.taniLinkCharts['chartRevenueLine'] = new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: rawChartData.timeline_labels,
                datasets: [{
                    label: 'Nilai Pesanan Sah',
                    data: rawChartData.timeline_values,
                    borderColor: '#0A765A',
                    backgroundColor: 'rgba(10, 118, 90, 0.08)',
                    borderWidth: 2.2,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#0A765A',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#172B29',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return ` Nilai Pesanan: Rp ${new Intl.NumberFormat('id-ID').format(context.parsed.y)}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#85958F' }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + ' jt';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000).toFixed(0) + ' rb';
                                }
                                return 'Rp ' + value;
                            },
                            color: '#85958F'
                        },
                        grid: { color: '#F0F4F2' }
                    }
                }
            }
        });
    }

    // -------------------------------------------------------------
    // D. Grafik Batang Horizontal — Top 5 Komoditas Terbanyak Dipesan
    // -------------------------------------------------------------
    const ctxTop = document.getElementById('chartTopCommodities');
    if (ctxTop && rawChartData.has_commodities) {
        destroyChart('chartTopCommodities');
        window.taniLinkCharts['chartTopCommodities'] = new Chart(ctxTop, {
            type: 'bar',
            data: {
                labels: rawChartData.top_commodities.labels,
                datasets: [{
                    label: 'Total Dipesan (kg)',
                    data: rawChartData.top_commodities.kg,
                    backgroundColor: 'rgba(114, 87, 202, 0.75)',
                    hoverBackgroundColor: '#7257CA',
                    borderColor: '#7257CA',
                    borderWidth: 1,
                    borderRadius: 6,
                    maxBarThickness: 22
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#172B29',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return ` Dipesan: ${new Intl.NumberFormat('id-ID').format(context.parsed.x)} kg`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' kg';
                            },
                            color: '#85958F'
                        },
                        grid: { color: '#F0F4F2' }
                    },
                    y: {
                        grid: { display: false },
                        ticks: {
                            color: '#172B29',
                            font: { weight: '600' }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush

