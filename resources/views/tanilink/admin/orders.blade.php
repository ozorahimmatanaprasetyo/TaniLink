@extends('layouts.sidebar')

@section('title', 'Pesanan & Escrow - Admin TaniLink')

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
<div x-data="{ search: '', filterStatus: '' }">

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:26px;">
        <div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Pesanan & Rekening Escrow</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">Monitoring kepatuhan kontrak, dana DP awal petani, dan keamanan dana escrow 70%.</p>
        </div>
    </div>

    {{-- Notice --}}
    <div style="padding:14px 18px; display:flex; align-items:flex-start; gap:11px; border-radius:12px; background:#FFF8EA; border:1px solid #F6E3B9; color:#7F631E; font-size:11px; line-height:1.6; margin-bottom:20px;">
        <span>ⓘ</span>
        <span><b>Mekanisme Escrow:</b> Dana 70% pelunasan hanya dirilis ke rekening kelompok tani setelah komoditas fisik diterima, ditimbang, dan diverifikasi kualitasnya oleh pihak Horeka.</span>
    </div>

    {{-- 4 Metric Cards --}}
    <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:16px; margin-bottom:24px;">
        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Total GMV</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#FFF5E8; color:#BA7A1F; font-size:16px;">▥</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                Rp {{ number_format($totalGmv, 0, ',', '.') }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Nilai seluruh kontrak</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>DP Tersalurkan</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">🌱</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#0A765A;">
                Rp {{ number_format($totalDpDisbursed, 0, ',', '.') }}
            </div>
            <div style="font-size:10px; color:#7B9187;">30% modal benih petani</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Escrow Terkunci</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#F1EFFB; color:#7257CA; font-size:16px;">🔒</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#6953B5;">
                Rp {{ number_format($totalEscrowLocked, 0, ',', '.') }}
            </div>
            <div style="font-size:10px; color:#7B9187;">70% saldo aman platform</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Pesanan Selesai</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E8F9FC; color:#0B8BAA; font-size:16px;">✓</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ $allOrders->where('status', 'selesai')->count() }} / {{ $allOrders->count() }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Serah terima tuntas</div>
        </div>
    </div>

    {{-- Orders Table Card --}}
    <div class="card" style="padding:24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:12px;">
            <div>
                <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Daftar Kontrak & Rekening Bersama</h2>
                <p style="font-size:11px; color:#91A19B; margin:0;">Kelola pelepasan escrow setelah verifikasi penerimaan barang.</p>
            </div>

            <div style="display:flex; gap:10px;">
                <input type="text" x-model="search" placeholder="⌕ Cari kode, komoditas, pihak..."
                       style="padding:9px 13px; border:1px solid #E5ECEA; border-radius:10px; font-size:12px; color:#50695E; background:#fff; min-width:220px; outline:0;">
                <select x-model="filterStatus"
                        style="padding:9px 13px; border:1px solid #E5ECEA; border-radius:10px; font-size:12px; color:#50695E; background:#fff; outline:0;">
                    <option value="">Semua Status</option>
                    <option value="dp_cair">DP Cair (Dalam Proses)</option>
                    <option value="selesai">Selesai (Escrow Dirilis)</option>
                </select>
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:left;">
                <thead>
                    <tr>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">KODE</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">KOMODITAS</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">PETANI</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">PEMBELI</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">NILAI KONTRAK</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">ESCROW (70%)</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">STATUS</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allOrders as $order)
                    @php
                        $target = strtolower($order->order_code . ' ' . ($order->commodityListing->commodity_name ?? '') . ' ' . ($order->farmer->name ?? '') . ' ' . ($order->buyer->name ?? ''));
                    @endphp
                    <tr x-show="(search === '' || '{{ $target }}'.includes(search.toLowerCase())) && (filterStatus === '' || '{{ $order->status }}' === filterStatus)"
                        style="border-bottom:1px solid #EEF2F0;">
                        <td style="font-size:11px; font-weight:800; color:#183B30; padding:15px 10px;">
                            {{ $order->order_code }}
                        </td>
                        <td style="font-size:11px; padding:15px 10px;">
                            <b style="font-weight:850; color:#183B30;">{{ $order->commodityListing->commodity_name ?? 'Komoditas' }}</b>
                            <div style="font-size:10px; color:#85958F;">{{ number_format($order->quantity_kg, 0, ',', '.') }} kg</div>
                        </td>
                        <td style="font-size:11px; color:#172B29; padding:15px 10px;">
                            {{ $order->farmer->name ?? 'Petani' }}
                        </td>
                        <td style="font-size:11px; color:#172B29; padding:15px 10px;">
                            {{ $order->buyer->name ?? 'Pembeli' }}
                            <div style="font-size:10px; color:#85958F;">{{ $order->buyer->organization ?? '-' }}</div>
                        </td>
                        <td style="font-size:11px; font-weight:800; color:#0A765A; padding:15px 10px;">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                        <td style="font-size:11px; font-weight:800; color:#6953B5; padding:15px 10px;">
                            Rp {{ number_format($order->escrow_locked_amount, 0, ',', '.') }}
                        </td>
                        <td style="font-size:11px; padding:15px 10px;">
                            @if($order->status === 'selesai')
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#E9F9F2; color:#0C8D65; font-weight:800; font-size:10px;">
                                    Selesai
                                </span>
                            @elseif($order->status === 'dikonfirmasi')
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#EFF6FF; color:#1D4ED8; font-weight:800; font-size:10px;">
                                    Dikonfirmasi
                                </span>
                            @elseif($order->status === 'menunggu_konfirmasi')
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#FFF8EA; color:#B45309; font-weight:800; font-size:10px;">
                                    Menunggu Konfirmasi
                                </span>
                            @elseif($order->status === 'siap_panen')
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#E9FBF3; color:#0A765A; font-weight:800; font-size:10px;">
                                    Siap Panen
                                </span>
                            @elseif($order->status === 'dibatalkan')
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#FEE2E2; color:#991B1B; font-weight:800; font-size:10px;">
                                    Dibatalkan
                                </span>
                            @else
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:10px;">
                                    Terkunci (Budidaya)
                                </span>
                            @endif
                        </td>
                        <td style="font-size:11px; padding:15px 10px;">
                            {{-- Escrow hanya bisa dirilis setelah pembeli konfirmasi penerimaan (selesai) --}}
                            @if($order->status === 'selesai' && $order->escrow_locked_amount > 0)
                            <form action="{{ route('admin.escrow.release', $order->id) }}" method="POST" onsubmit="return confirm('[SIMULASI] Rilis dana escrow 70% ke petani?')">
                                @csrf
                                <button type="submit" style="padding:6px 12px; border-radius:8px; border:none; background:#7257CA; color:#fff; font-size:11px; font-weight:800; cursor:pointer;">
                                    Rilis Escrow 🔓
                                </button>
                            </form>
                            @elseif($order->status === 'selesai')
                            <span style="font-size:11px; color:#85958F; font-weight:700;">Dana Dirilis ✓</span>
                            @else
                            <span style="font-size:10px; color:#AABBB5; font-style:italic;">Tunggu konfirmasi pembeli</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

