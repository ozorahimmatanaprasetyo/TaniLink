@extends('layouts.sidebar')

@section('title', 'Pesanan Masuk - TaniLink')

@section('sidebar_nav')
<a href="{{ route('portal.petani') }}" class="nav-btn {{ request()->routeIs('portal.petani') ? 'active' : '' }}">
    <span class="nav-icon">▦</span> Dashboard
</a>
<a href="{{ route('petani.komoditas') }}" class="nav-btn {{ request()->routeIs('petani.komoditas') ? 'active' : '' }}">
    <span class="nav-icon">♧</span> Komoditas Saya
</a>
<a href="{{ route('petani.pesanan') }}" class="nav-btn {{ request()->routeIs('petani.pesanan*') ? 'active' : '' }}">
    <span class="nav-icon">▤</span> Pesanan Masuk
</a>
<a href="{{ route('petani.profil') }}" class="nav-btn {{ request()->routeIs('petani.profil') ? 'active' : '' }}">
    <span class="nav-icon">♙</span> Profil Petani
</a>
@endsection

@section('content')
<div x-data="{ search: '', filterStatus: '' }">

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:26px;">
        <div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Pesanan Masuk</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">Kelola permintaan pre-order, kontrak, dan rencana pemenuhan panen.</p>
        </div>
        @if($activeOrders->isNotEmpty())
        <a href="{{ route('petani.pesanan.show', $activeOrders->first()->id) }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 16px; border-radius:11px; background:#05C793; color:#053B2D; font-size:12px; font-weight:800; text-decoration:none; box-shadow:0 3px 10px rgba(5,199,147,0.2);">
            Lihat Progres Tanam ↗
        </a>
        @endif
    </div>

    {{-- 3 Metric Cards --}}
    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; margin-bottom:24px;">
        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Pesanan Aktif</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">▤</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ str_pad($activeOrders->count(), 2, '0', STR_PAD_LEFT) }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Dari pembeli konsumen</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Volume Terkontrak</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">▥</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ number_format($activeOrders->sum('quantity_kg'), 0, ',', '.') }} kg
            </div>
            <div style="font-size:10px; color:#7B9187;">Komitmen panen terjual</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Nilai Kontrak Total</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">◉</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                Rp {{ number_format($activeOrders->sum('total_amount'), 0, ',', '.') }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Termasuk 30% DP diterima</div>
        </div>
    </div>

    {{-- Orders Table Card --}}
    <div class="card" style="padding:24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:12px;">
            <div>
                <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Daftar Pesanan</h2>
                <p style="font-size:11px; color:#91A19B; margin:0;">Status kontrak dan jadwal pemenuhan panen.</p>
            </div>

            {{-- Toolbar --}}
            <div style="display:flex; gap:10px;">
                <input type="text" x-model="search" placeholder="⌕ Cari ID, produk, pembeli..."
                       style="padding:9px 13px; border:1px solid #E5ECEA; border-radius:10px; font-size:12px; color:#50695E; background:#fff; min-width:200px; outline:0;">
                <select x-model="filterStatus"
                        style="padding:9px 13px; border:1px solid #E5ECEA; border-radius:10px; font-size:12px; color:#50695E; background:#fff; outline:0;">
                    <option value="">Semua status</option>
                    <option value="menunggu_konfirmasi">Menunggu Konfirmasi</option>
                    <option value="dikonfirmasi">Dikonfirmasi</option>
                    <option value="dp_cair">DP Cair (Proses)</option>
                    <option value="siap_panen">Siap Panen</option>
                    <option value="dalam_pengiriman">Dalam Pengiriman</option>
                    <option value="selesai">Selesai</option>
                    <option value="dibatalkan">Ditolak/Batal</option>
                </select>
            </div>
        </div>

        @if($activeOrders->isEmpty())
        <div style="text-align:center; padding:50px 20px; color:#8A9D95;">
            <div style="font-size:42px; margin-bottom:10px;">📋</div>
            <h3 style="font-size:15px; font-weight:800; color:#172B29; margin-bottom:4px;">Belum Ada Pesanan Masuk</h3>
            <p style="font-size:12px; margin-top:4px;">Saat konsumen memesan komoditas Anda, pesanan akan tercatat di sini.</p>
        </div>
        @else
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:left;">
                <thead>
                    <tr>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">KODE PESANAN</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">KOMODITAS</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">PEMBELI</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">QTY</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">TOTAL</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">STATUS</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px; text-align:right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activeOrders as $order)
                    @php
                        $searchTarget = strtolower($order->order_code . ' ' . ($order->commodityListing->commodity_name ?? '') . ' ' . ($order->buyer->name ?? ''));
                    @endphp
                    <tr x-show="(search === '' || '{{ $searchTarget }}'.includes(search.toLowerCase())) && (filterStatus === '' || '{{ $order->status }}' === filterStatus)"
                        style="border-bottom:1px solid #EEF2F0;">
                        <td style="font-size:11px; font-weight:800; color:#183B30; padding:15px 10px;">
                            {{ $order->order_code }}
                        </td>
                        <td style="font-size:11px; padding:15px 10px;">
                            <b style="font-weight:850; color:#183B30;">{{ $order->commodityListing->commodity_name ?? 'Komoditas' }}</b>
                            <div style="font-size:10px; color:#85958F;">{{ $order->commodityListing->variety ?? '-' }}</div>
                        </td>
                        <td style="font-size:11px; font-weight:650; color:#172B29; padding:15px 10px;">
                            <div>{{ $order->buyer->name ?? 'Pembeli' }}</div>
                            <div style="font-size:10px; color:#85958F;">{{ $order->buyer->organization ?? '-' }}</div>
                            @if($order->buyer && $order->buyer->phone)
                            <div style="font-size:10px; color:#0A765A; font-weight:700; margin-top:2px;">
                                <i class="fa-brands fa-whatsapp"></i> {{ $order->buyer->phone }}
                            </div>
                            @endif
                        </td>
                        <td style="font-size:11px; font-weight:650; padding:15px 10px;">
                            {{ number_format($order->quantity_kg, 0, ',', '.') }} kg
                        </td>
                        <td style="font-size:11px; font-weight:800; color:#0A765A; padding:15px 10px;">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                        <td style="font-size:11px; padding:15px 10px;">
                            @if($order->status === 'menunggu_konfirmasi')
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#FFF8EA; color:#B45309; font-weight:800; font-size:10px;">Menunggu Konfirmasi</span>
                            @elseif($order->status === 'dikonfirmasi')
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#EFF6FF; color:#1D4ED8; font-weight:800; font-size:10px;">Dikonfirmasi (Budidaya)</span>
                            @elseif(in_array($order->status, ['dp_cair', 'tanam_berjalan']))
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:10px;">Sedang Berjalan</span>
                            @elseif($order->status === 'siap_panen')
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#E9FBF3; color:#0A765A; font-weight:800; font-size:10px;">Siap Panen</span>
                            @elseif($order->status === 'dalam_pengiriman')
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#F0FDF4; color:#15803D; font-weight:800; font-size:10px;">Dalam Pengiriman</span>
                            @elseif($order->status === 'selesai')
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#E9F9F2; color:#0C8D65; font-weight:800; font-size:10px;">Selesai</span>
                            @elseif($order->status === 'dibatalkan')
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#FEE2E2; color:#991B1B; font-weight:800; font-size:10px;">Ditolak / Batal</span>
                            @else
                                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#F3F4F6; color:#6B7280; font-weight:800; font-size:10px;">{{ $order->status }}</span>
                            @endif
                        </td>
                        <td style="font-size:11px; padding:15px 10px; text-align:right;">
                            <div style="display:inline-flex; gap:6px; align-items:center;">
                                @if($order->status === 'menunggu_konfirmasi')
                                <form action="{{ route('petani.pesanan.confirm', $order->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Terima pengajuan pre-order ini?')"
                                            style="padding:5px 10px; border-radius:7px; background:#05C793; border:none; color:#053B2D; font-weight:850; font-size:11px; cursor:pointer;">
                                        Terima
                                    </button>
                                </form>
                                <form action="{{ route('petani.pesanan.reject', $order->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Tolak pengajuan pre-order ini? Stok akan dikembalikan.')"
                                            style="padding:5px 10px; border-radius:7px; background:#FEE2E2; border:1px solid #FCA5A5; color:#991B1B; font-weight:800; font-size:11px; cursor:pointer;">
                                        Tolak
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('petani.pesanan.show', $order->id) }}"
                                   style="display:inline-block; padding:5px 12px; border-radius:7px; background:#EBF9F2; border:1px solid #C6EEDC; color:#078362; font-weight:800; font-size:11px; text-decoration:none;">
                                    Detail ↗
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>
@endsection
