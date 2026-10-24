<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Riwayat Pesanan - {{ $order->order_code }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm 15mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #1f2937;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #05C793;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }
        .header table {
            width: 100%;
        }
        .brand-title {
            font-size: 20px;
            font-weight: bold;
            color: #07876B;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .brand-subtitle {
            font-size: 9px;
            color: #6b7280;
            margin: 2px 0 0 0;
        }
        .doc-title {
            text-align: right;
        }
        .doc-title h2 {
            font-size: 14px;
            font-weight: bold;
            color: #111827;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .doc-title p {
            font-size: 9.5px;
            color: #6b7280;
            margin: 2px 0 0 0;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-success { background-color: #d1fae5; color: #065f46; }
        .badge-danger { background-color: #fee2e2; color: #991b1b; }
        .badge-info { background-color: #e0f2fe; color: #075985; }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #07876B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 14px 0 6px 0;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 3px;
        }

        table.info-grid {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
        }
        table.info-grid td {
            vertical-align: top;
            padding: 4px 6px;
            font-size: 10.5px;
        }
        .label {
            color: #6b7280;
            width: 32%;
        }
        .value {
            color: #111827;
            font-weight: 500;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            margin-bottom: 14px;
        }
        table.data-table th {
            background-color: #f3f4f6;
            color: #374151;
            font-size: 10px;
            font-weight: bold;
            text-align: left;
            padding: 6px 8px;
            border: 1px solid #e5e7eb;
            text-transform: uppercase;
        }
        table.data-table td {
            padding: 6px 8px;
            border: 1px solid #e5e7eb;
            font-size: 10px;
        }
        table.data-table tr:nth-child(even) td {
            background-color: #fafafa;
        }

        .timeline {
            margin-top: 6px;
            margin-bottom: 14px;
        }
        .timeline-item {
            padding-left: 14px;
            border-left: 2px solid #05C793;
            margin-bottom: 10px;
            position: relative;
        }
        .timeline-date {
            font-size: 9px;
            font-weight: bold;
            color: #07876B;
        }
        .timeline-title {
            font-size: 10.5px;
            font-weight: bold;
            color: #111827;
            margin: 1px 0;
        }
        .timeline-desc {
            font-size: 9.5px;
            color: #4b5563;
        }

        .disclaimer {
            margin-top: 22px;
            padding: 8px 10px;
            background-color: #f9fafb;
            border: 1px dashed #d1d5db;
            border-radius: 4px;
            font-size: 8.5px;
            color: #6b7280;
            line-height: 1.4;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8.5px;
            color: #9ca3af;
            border-top: 1px solid #f3f4f6;
            padding-top: 8px;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <table>
            <tr>
                <td style="width: 55%; vertical-align: middle;">
                    <div class="brand-title">TaniLink</div>
                    <div class="brand-subtitle">Platform Pre-Order &amp; Kolaborasi Komoditas Pertanian</div>
                </td>
                <td style="width: 45%; vertical-align: middle;" class="doc-title">
                    <h2>Laporan Riwayat Pesanan</h2>
                    <p>Kode: <strong>{{ $order->order_code }}</strong></p>
                    <p>Dicetak: {{ now('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB</p>
                </td>
            </tr>
        </table>
    </div>

    {{-- Ringkasan Status --}}
    <table style="width: 100%; margin-bottom: 14px;">
        <tr>
            <td style="width: 50%;">
                <span class="label">Status Akhir Pesanan:</span>
                @if($order->status === 'selesai')
                    <span class="badge badge-success">Selesai (Pesanan Terpenuhi)</span>
                @elseif($order->status === 'dibatalkan')
                    <span class="badge badge-danger">Ditolak / Dibatalkan</span>
                @else
                    <span class="badge badge-info">{{ $order->status_label }}</span>
                @endif
            </td>
            <td style="width: 50%; text-align: right;">
                @if($order->completed_at)
                    <span class="label">Waktu Selesai:</span>
                    <strong>{{ $order->completed_at->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB</strong>
                @elseif($order->rejected_at)
                    <span class="label">Waktu Ditolak:</span>
                    <strong>{{ $order->rejected_at->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB</strong>
                @endif
            </td>
        </tr>
    </table>

    {{-- Identitas Para Pihak --}}
    <table style="width: 100%; margin-bottom: 10px;">
        <tr>
            <td style="width: 48%; vertical-align: top; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px;">
                <div style="font-weight: bold; color: #07876B; font-size: 10px; margin-bottom: 4px; text-transform: uppercase;">Informasi Petani (Produsen)</div>
                <table class="info-grid" style="margin: 0;">
                    <tr>
                        <td class="label">Nama Petani</td>
                        <td class="value">: {{ $order->farmer->name ?? 'Mitra Petani' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Kelompok/Usaha</td>
                        <td class="value">: {{ $order->farmer->organization ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Lokasi Kebun</td>
                        <td class="value">: {{ $order->farmer->location ?? ($order->commodityListing->location ?? '-') }}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 4%;"></td>
            <td style="width: 48%; vertical-align: top; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px;">
                <div style="font-weight: bold; color: #07876B; font-size: 10px; margin-bottom: 4px; text-transform: uppercase;">Informasi Konsumen (Pembeli)</div>
                <table class="info-grid" style="margin: 0;">
                    <tr>
                        <td class="label">Nama Pemesan</td>
                        <td class="value">: {{ $order->buyer->name ?? 'Konsumen' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Entitas/Usaha</td>
                        <td class="value">: {{ $order->buyer->organization ?? 'Pribadi / Horeka' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Alamat Pengiriman</td>
                        <td class="value">: {{ $order->deliveryTracking->destination_address ?? ($order->buyer->location ?? '-') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Detail Komoditas & Finansial --}}
    <div class="section-title">Detail Komoditas &amp; Nilai Transaksi</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Komoditas &amp; Batch</th>
                <th>Kebutuhan Panen</th>
                <th style="text-align: right;">Jumlah (kg)</th>
                <th style="text-align: right;">Harga Satuan (Rp)</th>
                <th style="text-align: right;">Total Nilai (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>{{ $order->commodityListing->commodity_name ?? 'Komoditas' }}</strong>
                    <div style="font-size: 8.5px; color: #6b7280;">
                        Kategori: {{ $order->commodityListing->category ?? '-' }} | Varietas: {{ $order->commodityListing->variety ?? 'Umum' }}
                    </div>
                    <div style="font-size: 8.5px; color: #07876B;">
                        Batch: {{ $order->harvestBatch->batch_name ?? 'Batch Standar' }}
                    </div>
                </td>
                <td>
                    {{ $order->need_date ? $order->need_date->translatedFormat('d F Y') : ($order->harvestBatch->estimated_harvest_date ? $order->harvestBatch->estimated_harvest_date->translatedFormat('d F Y') : '-') }}
                </td>
                <td style="text-align: right; font-weight: bold;">
                    {{ number_format($order->quantity_kg, 0, ',', '.') }} kg
                </td>
                <td style="text-align: right;">
                    Rp {{ number_format($order->price_per_kg, 0, ',', '.') }}
                </td>
                <td style="text-align: right; font-weight: bold; color: #07876B;">
                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    {{-- Rincian Pembayaran --}}
    <table style="width: 100%; margin-bottom: 12px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <div class="section-title" style="margin-top: 0;">Status Pembayaran DP (30%)</div>
                <table class="info-grid">
                    <tr>
                        <td class="label">Nominal DP 30%</td>
                        <td class="value">: Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Status DP</td>
                        <td class="value">: 
                            @if($order->dp_confirmed_at)
                                <strong style="color: #065f46;">Telah Dikonfirmasi Petani</strong>
                            @elseif($order->dp_uploaded_at)
                                <span style="color: #075985;">Menunggu Pemeriksaan</span>
                            @else
                                <span style="color: #991b1b;">Belum Terkonfirmasi</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Waktu Konfirmasi DP</td>
                        <td class="value">: {{ $order->dp_confirmed_at ? $order->dp_confirmed_at->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') . ' WIB' : '-' }}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%; vertical-align: top;">
                <div class="section-title" style="margin-top: 0;">Sisa Pembayaran (70%)</div>
                <table class="info-grid">
                    <tr>
                        <td class="label">Nominal Sisa 70%</td>
                        <td class="value">: Rp {{ number_format($order->escrow_locked_amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Status Pelunasan</td>
                        <td class="value">: 
                            @if($order->status === 'selesai')
                                <strong style="color: #065f46;">Lunas (Penyerahan Selesai)</strong>
                            @else
                                <span style="color: #6b7280;">Pelunasan saat serah terima panen</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    @if($order->rejection_reason)
    <div style="background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 4px; padding: 8px 10px; margin-bottom: 12px;">
        <strong style="color: #991b1b; font-size: 10px;">Catatan Penolakan/Pembatalan:</strong>
        <p style="margin: 2px 0 0 0; font-size: 9.5px; color: #7f1d1d;">{{ $order->rejection_reason }}</p>
    </div>
    @endif

    {{-- Timeline Kronologis Aktivitas --}}
    <div class="section-title">Kronologi &amp; Riwayat Aktivitas Transaksi</div>
    <div class="timeline">
        @forelse($order->activities as $act)
            <div class="timeline-item">
                <div class="timeline-date">
                    {{ $act->occurred_at ? $act->occurred_at->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') : '-' }} WIB
                </div>
                <div class="timeline-title">{{ $act->title }}</div>
                @if($act->description)
                    <div class="timeline-desc">{{ $act->description }}</div>
                @endif
            </div>
        @empty
            <div style="font-size: 9.5px; color: #6b7280; font-style: italic;">
                Belum ada rekaman aktivitas terperinci untuk pesanan ini.
            </div>
        @endforelse
    </div>

    {{-- Disclaimer Legalitas --}}
    <div class="disclaimer">
        <strong>Pemberitahuan Sistem:</strong> Laporan ini merupakan catatan riwayat aktivitas dan status operasional transaksi yang tercatat secara otomatis pada server platform TaniLink. Dokumen ini bukan merupakan bukti transfer perbankan, kuitansi pajak resmi, atau bukti kliring keuangan jika pembayaran dilakukan secara transfer langsung antarrekening di luar escrow platform.
    </div>

    {{-- Footer --}}
    <div class="footer">
        Dicetak secara otomatis oleh Platform TaniLink &bull; Halaman 1 dari 1 &bull; {{ $order->order_code }}
    </div>

</body>
</html>

