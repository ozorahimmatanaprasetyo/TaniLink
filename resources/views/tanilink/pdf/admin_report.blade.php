<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $reportTitle }} - {{ $periodLabel }}</title>
    <style>
        @page {
            size: A4 {{ $orders->count() > 0 ? 'landscape' : 'portrait' }};
            margin: 15mm 12mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #1f2937;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #05C793;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .header table {
            width: 100%;
        }
        .brand-title {
            font-size: 18px;
            font-weight: bold;
            color: #07876B;
            margin: 0;
        }
        .brand-subtitle {
            font-size: 8.5px;
            color: #6b7280;
            margin: 2px 0 0 0;
        }
        .doc-title {
            text-align: right;
        }
        .doc-title h2 {
            font-size: 13px;
            font-weight: bold;
            color: #111827;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .doc-title p {
            font-size: 9px;
            color: #6b7280;
            margin: 2px 0 0 0;
        }
        .section-title {
            font-size: 10px;
            font-weight: bold;
            color: #07876B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 12px 0 6px 0;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 3px;
        }
        table.grid-4 {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
        }
        table.grid-4 td {
            width: 25%;
            padding: 6px 8px;
            vertical-align: top;
            border: 1px solid #e5e7eb;
            background: #fafafa;
        }
        .metric-label {
            font-size: 8px;
            color: #6b7280;
            font-weight: bold;
            text-transform: uppercase;
        }
        .metric-value {
            font-size: 13px;
            font-weight: bold;
            color: #111827;
            margin: 2px 0 1px 0;
        }
        .metric-sub {
            font-size: 7.5px;
            color: #9ca3af;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            margin-bottom: 10px;
        }
        table.data-table th {
            background-color: #f3f4f6;
            color: #374151;
            font-size: 8.5px;
            font-weight: bold;
            text-align: left;
            padding: 5px 6px;
            border: 1px solid #e5e7eb;
            text-transform: uppercase;
        }
        table.data-table td {
            padding: 5px 6px;
            border: 1px solid #e5e7eb;
            font-size: 8.5px;
        }
        table.data-table tr:nth-child(even) td {
            background-color: #fafafa;
        }

        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-success { background-color: #d1fae5; color: #065f46; }
        .badge-danger { background-color: #fee2e2; color: #991b1b; }
        .badge-info { background-color: #e0f2fe; color: #075985; }
        .badge-warning { background-color: #fef3c7; color: #92400e; }

        .disclaimer {
            margin-top: 14px;
            padding: 6px 8px;
            background-color: #f9fafb;
            border: 1px dashed #d1d5db;
            border-radius: 4px;
            font-size: 7.5px;
            color: #6b7280;
            line-height: 1.3;
        }
        .footer {
            margin-top: 12px;
            text-align: center;
            font-size: 7.5px;
            color: #9ca3af;
            border-top: 1px solid #f3f4f6;
            padding-top: 6px;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <table>
            <tr>
                <td style="width: 50%; vertical-align: middle;">
                    <img src="{{ public_path('images/tanilink-logo.png') }}" alt="TaniLink" style="height:32px; width:auto; max-width:180px; object-fit:contain; margin-bottom:4px;">
                    <div class="brand-subtitle">Platform Pre-Order &amp; Kolaborasi Komoditas Pertanian</div>
                </td>
                <td style="width: 50%; vertical-align: middle;" class="doc-title">
                    <h2>{{ $reportTitle }}</h2>
                    <p>Periode: <strong>{{ $periodLabel }}</strong></p>
                    <p>Dibuat: {{ $generatedAt }}</p>
                </td>
            </tr>
        </table>
    </div>

    {{-- B. Ringkasan Pengguna (tampil jika keseluruhan atau pengguna) --}}
    @if(in_array($reportType, ['keseluruhan', 'pengguna']))
    <div class="section-title">Ringkasan Pengguna Platform</div>
    <table class="grid-4">
        <tr>
            <td>
                <div class="metric-label">Petani Baru</div>
                <div class="metric-value">{{ $stats['new_farmers'] }}</div>
                <div class="metric-sub">Terdaftar pada periode</div>
            </td>
            <td>
                <div class="metric-label">Konsumen Baru</div>
                <div class="metric-value">{{ $stats['new_buyers'] }}</div>
                <div class="metric-sub">Terdaftar pada periode</div>
            </td>
            <td>
                <div class="metric-label">Total Petani Terdaftar</div>
                <div class="metric-value">{{ $stats['total_farmers'] }}</div>
                <div class="metric-sub">Sampai akhir periode</div>
            </td>
            <td>
                <div class="metric-label">Total Konsumen Terdaftar</div>
                <div class="metric-value">{{ $stats['total_buyers'] }}</div>
                <div class="metric-sub">Sampai akhir periode</div>
            </td>
        </tr>
    </table>
    @endif

    {{-- C. Ringkasan Komoditas & Kapasitas (tampil jika keseluruhan atau komoditas) --}}
    @if(in_array($reportType, ['keseluruhan', 'komoditas']))
    <div class="section-title">Ringkasan Komoditas &amp; Kapasitas Panen</div>
    <table class="grid-4">
        <tr>
            <td>
                <div class="metric-label">Komoditas Baru</div>
                <div class="metric-value">{{ $stats['new_listings'] }}</div>
                <div class="metric-sub">Diterbitkan pada periode</div>
            </td>
            <td>
                <div class="metric-label">Batch Panen Baru</div>
                <div class="metric-value">{{ $stats['new_batches'] }}</div>
                <div class="metric-sub">Dibuat pada periode</div>
            </td>
            <td>
                <div class="metric-label">Perkiraan Panen Total</div>
                <div class="metric-value">{{ number_format($stats['total_estimated_harvest'], 0, ',', '.') }} kg</div>
                <div class="metric-sub">Kapasitas seluruh komoditas</div>
            </td>
            <td>
                <div class="metric-label">Volume Dipesan</div>
                <div class="metric-value">{{ number_format($stats['total_ordered_kg'], 0, ',', '.') }} kg</div>
                <div class="metric-sub">Total pre-order pada periode</div>
            </td>
        </tr>
    </table>
    @endif

    {{-- D. Ringkasan Pesanan (tampil jika keseluruhan atau pesanan) --}}
    @if(in_array($reportType, ['keseluruhan', 'pesanan']))
    <div class="section-title">Ringkasan Aktivitas Pesanan</div>
    <table class="grid-4">
        <tr>
            <td>
                <div class="metric-label">Pesanan Diajukan</div>
                <div class="metric-value">{{ $stats['orders_submitted'] }}</div>
                <div class="metric-sub">Total pengajuan pada periode</div>
            </td>
            <td>
                <div class="metric-label">Menunggu Konfirmasi</div>
                <div class="metric-value">{{ $stats['orders_pending'] }}</div>
                <div class="metric-sub">Perlu respon petani</div>
            </td>
            <td>
                <div class="metric-label">Pesanan Aktif</div>
                <div class="metric-value">{{ $stats['orders_active'] }}</div>
                <div class="metric-sub">Dalam budidaya/pengiriman</div>
            </td>
            <td>
                <div class="metric-label">Selesai / Dibatalkan</div>
                <div class="metric-value">{{ $stats['orders_completed'] }} / {{ $stats['orders_cancelled'] }}</div>
                <div class="metric-sub">Tuntas / ditolak</div>
            </td>
        </tr>
    </table>
    @endif

    {{-- E. Ringkasan Finansial / Pembayaran (tampil jika keseluruhan atau pembayaran) --}}
    @if(in_array($reportType, ['keseluruhan', 'pembayaran']))
    <div class="section-title">Ringkasan Transaksi &amp; Pembayaran DP</div>
    <table class="grid-4">
        <tr>
            <td>
                <div class="metric-label">Total Nilai Pesanan Sah</div>
                <div class="metric-value">{{ $stats['total_order_value_formatted'] }}</div>
                <div class="metric-sub">Pesanan tidak dibatalkan</div>
            </td>
            <td>
                <div class="metric-label">DP 30% Terkonfirmasi</div>
                <div class="metric-value" style="color: #07876B;">{{ $stats['confirmed_dp_formatted'] }}</div>
                <div class="metric-sub">Diverifikasi oleh petani</div>
            </td>
            <td>
                <div class="metric-label">DP Menunggu Pemeriksaan</div>
                <div class="metric-value">{{ $stats['unverified_dp_formatted'] }}</div>
                <div class="metric-sub">Bukti transfer diunggah</div>
            </td>
            <td>
                <div class="metric-label">Pelunasan Terkonfirmasi</div>
                <div class="metric-value">{{ $stats['final_payment_formatted'] }}</div>
                <div class="metric-sub">Saat serah terima / selesai</div>
            </td>
        </tr>
    </table>
    @endif

    {{-- Rekap Bulanan untuk Laporan Tahunan --}}
    @if($monthlyBreakdown)
    <div class="section-title">Rekap Bulanan (Januari — Desember)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Bulan</th>
                <th style="text-align: right;">Total Pesanan</th>
                <th style="text-align: right;">Pesanan Selesai</th>
                <th style="text-align: right;">Nilai Pesanan (Rp)</th>
                <th style="text-align: right;">DP Terkonfirmasi (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyBreakdown as $mb)
            <tr>
                <td><strong>{{ $mb['month'] }}</strong></td>
                <td style="text-align: right;">{{ $mb['orders_count'] }}</td>
                <td style="text-align: right;">{{ $mb['orders_completed'] }}</td>
                <td style="text-align: right;">Rp {{ number_format($mb['total_value'], 0, ',', '.') }}</td>
                <td style="text-align: right; color: #07876B; font-weight: bold;">Rp {{ number_format($mb['confirmed_dp'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- F. Rincian Transaksi --}}
    @if(in_array($reportType, ['keseluruhan', 'pesanan', 'pembayaran']))
    <div class="section-title">Rincian Transaksi Pre-Order ({{ $orders->count() }} Transaksi)</div>
    @if($orders->count() > 0)
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 10%;">Kode</th>
                <th style="width: 9%;">Tanggal</th>
                <th style="width: 14%;">Petani</th>
                <th style="width: 14%;">Konsumen</th>
                <th style="width: 15%;">Komoditas &amp; Batch</th>
                <th style="width: 8%; text-align: right;">Jumlah</th>
                <th style="width: 10%; text-align: right;">Harga/kg</th>
                <th style="width: 10%; text-align: right;">Total Nilai</th>
                <th style="width: 10%;">Status DP</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $o)
            <tr>
                <td><strong>{{ $o->order_code }}</strong></td>
                <td>{{ $o->created_at ? $o->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y') : '-' }}</td>
                <td>{{ $o->farmer->name ?? 'Petani' }}</td>
                <td>{{ $o->buyer->name ?? 'Konsumen' }}</td>
                <td>
                    {{ $o->commodityListing->commodity_name ?? 'Komoditas' }}
                    <div style="font-size: 7.5px; color: #6b7280;">{{ $o->harvestBatch->batch_name ?? '-' }}</div>
                </td>
                <td style="text-align: right;">{{ number_format($o->quantity_kg, 0, ',', '.') }} kg</td>
                <td style="text-align: right;">Rp {{ number_format($o->price_per_kg, 0, ',', '.') }}</td>
                <td style="text-align: right; font-weight: bold;">Rp {{ number_format($o->total_amount, 0, ',', '.') }}</td>
                <td>
                    @if($o->dp_confirmed_at)
                        <span class="badge badge-success">DP Lunas</span>
                    @elseif($o->dp_uploaded_at)
                        <span class="badge badge-info">Pemeriksaan</span>
                    @elseif($o->status === 'menunggu_pembayaran_dp')
                        <span class="badge badge-warning">Menunggu DP</span>
                    @else
                        <span style="color: #9ca3af;">-</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 15px; color: #9ca3af; font-size: 9px; border: 1px dashed #e5e7eb; border-radius: 4px; margin-bottom: 10px;">
        Belum ada data transaksi pada periode ini.
    </div>
    @endif
    @endif

    {{-- Disclaimer Legalitas --}}
    <div class="disclaimer">
        <strong>Pemberitahuan Sistem:</strong> Laporan ini merupakan rekapan ringkasan operasional dan aktivitas transaksi yang tercatat secara otomatis pada server platform TaniLink berdasarkan periode yang dipilih. Pembayaran dilakukan secara transfer langsung antarrekening petani dan konsumen (bukan merupakan pendapatan platform TaniLink dan tidak dikelola melalui rekening bersama/escrow). Dokumen ini bukan kuitansi pajak atau bukti kliring perbankan resmi.
    </div>

    {{-- Footer --}}
    <div class="footer">
        Dicetak secara otomatis oleh Platform TaniLink &bull; {{ $reportTitle }} &bull; Periode: {{ $periodLabel }}
    </div>

</body>
</html>
