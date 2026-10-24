@extends('layouts.sidebar')

@section('title', 'Manajemen Pesanan - Admin TaniLink')

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
    search: '', 
    filterStatus: '',
    showModal: false,
    selectedOrder: null,
    openDetail(order) {
        this.selectedOrder = order;
        this.showModal = true;
    }
}">

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:26px;">
        <div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Manajemen Pesanan &amp; Transaksi</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">Monitoring status transaksi pre-order, konfirmasi DP antar pihak, dan progres pemenuhan batch panen.</p>
        </div>
    </div>

    {{-- 4 Metric Cards --}}
    <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:16px; margin-bottom:24px;">
        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Total Nilai Pesanan Sah</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#FFF5E8; color:#BA7A1F; font-size:15px;"><i class="fa-solid fa-receipt"></i></span>
            </div>
            <div style="font-weight:850; font-size:22px; letter-spacing:-0.8px; margin:12px 0 6px; color:#172B29;">
                {{ $stats['total_order_value_formatted'] }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Akumulasi nilai pesanan aktif &amp; selesai</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>DP Dikonfirmasi Petani</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:15px;"><i class="fa-solid fa-circle-check"></i></span>
            </div>
            <div style="font-weight:850; font-size:22px; letter-spacing:-0.8px; margin:12px 0 6px; color:#0A765A;">
                {{ $stats['confirmed_dp_formatted'] }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Transfer DP 30% terverifikasi</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Pesanan Aktif Berjalan</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E8F9FC; color:#0B8BAA; font-size:15px;"><i class="fa-solid fa-seedling"></i></span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ $stats['active_orders'] }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Dalam proses budidaya di lahan</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Pesanan Selesai</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#D1FAE5; color:#065F46; font-size:15px;"><i class="fa-solid fa-check-double"></i></span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ $stats['completed_orders'] }} / {{ $stats['total_orders'] }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Diterima konsumen &amp; tuntas</div>
        </div>
    </div>

    {{-- Orders Table Card --}}
    <div class="card" style="padding:24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:12px;">
            <div>
                <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Daftar Transaksi Pre-Order</h2>
                <p style="font-size:11px; color:#91A19B; margin:0;">Klik baris pesanan untuk melihat detail transaksi. Laporan PDF tersedia di halaman Laporan &amp; Statistik.</p>
            </div>

            <div style="display:flex; gap:10px;">
                <input type="text" x-model="search" placeholder="⌕ Cari kode, komoditas, pihak..."
                       style="padding:9px 13px; border:1px solid #E5ECEA; border-radius:10px; font-size:12px; color:#50695E; background:#fff; min-width:220px; outline:0;">
                <select x-model="filterStatus"
                        style="padding:9px 13px; border:1px solid #E5ECEA; border-radius:10px; font-size:12px; color:#50695E; background:#fff; outline:0;">
                    <option value="">Semua Status</option>
                    <option value="menunggu_konfirmasi">Menunggu Konfirmasi</option>
                    <option value="menunggu_pembayaran_dp">Menunggu Pembayaran DP</option>
                    <option value="menunggu_verifikasi_dp">Pemeriksaan DP</option>
                    <option value="pesanan_aktif">Pesanan Aktif</option>
                    <option value="siap_panen">Siap Panen</option>
                    <option value="dalam_pengiriman">Dalam Pengiriman</option>
                    <option value="selesai">Selesai</option>
                    <option value="dibatalkan">Ditolak / Batal</option>
                </select>
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
                    @foreach($allOrders as $order)
                    @php
                        $target = strtolower($order->order_code . ' ' . ($order->commodityListing->commodity_name ?? '') . ' ' . ($order->farmer->name ?? '') . ' ' . ($order->buyer->name ?? ''));
                    @endphp
                    <tr x-show="(search === '' || '{{ $target }}'.includes(search.toLowerCase())) && (filterStatus === '' || '{{ $order->status }}' === filterStatus)"
                        style="border-bottom:1px solid #EEF2F0; cursor:pointer; transition: background 0.15s;"
                        onmouseover="this.style.background='#F8FCFA'" onmouseout="this.style.background='transparent'"
                        @click="openDetail({
                            code: '{{ $order->order_code }}',
                            created: '{{ $order->created_at ? $order->created_at->timezone("Asia/Jakarta")->translatedFormat("d M Y, H:i") : "-" }}',
                            commodity: '{{ addslashes($order->commodityListing->commodity_name ?? "Komoditas") }}',
                            category: '{{ addslashes($order->commodityListing->category ?? "-") }}',
                            batch: '{{ addslashes($order->harvestBatch->batch_name ?? "Batch") }}',
                            quantity: '{{ number_format($order->quantity_kg, 0, ",", ".") }}',
                            price_per_kg: 'Rp {{ number_format($order->price_per_kg, 0, ",", ".") }}',
                            total: 'Rp {{ number_format($order->total_amount, 0, ",", ".") }}',
                            dp_amount: 'Rp {{ number_format($order->dp_amount, 0, ",", ".") }}',
                            sisa: 'Rp {{ number_format($order->escrow_locked_amount, 0, ",", ".") }}',
                            farmer: '{{ addslashes($order->farmer->name ?? "Petani") }}',
                            farmer_org: '{{ addslashes($order->farmer->organization ?? "-") }}',
                            buyer: '{{ addslashes($order->buyer->name ?? "Konsumen") }}',
                            buyer_org: '{{ addslashes($order->buyer->organization ?? "-") }}',
                            status: '{{ $order->status }}',
                            status_label: '{{ $order->status_label }}',
                            dp_status: '{{ $order->dp_confirmed_at ? "confirmed" : ($order->dp_uploaded_at ? "uploaded" : ($order->status === "menunggu_pembayaran_dp" ? "waiting" : "none")) }}',
                            confirmed_at: '{{ $order->confirmed_at ? $order->confirmed_at->timezone("Asia/Jakarta")->translatedFormat("d M Y, H:i") . " WIB" : "-" }}',
                            dp_confirmed_at: '{{ $order->dp_confirmed_at ? $order->dp_confirmed_at->timezone("Asia/Jakarta")->translatedFormat("d M Y, H:i") . " WIB" : "-" }}',
                            completed_at: '{{ $order->completed_at ? $order->completed_at->timezone("Asia/Jakarta")->translatedFormat("d M Y, H:i") . " WIB" : "-" }}',
                            rejected_at: '{{ $order->rejected_at ? $order->rejected_at->timezone("Asia/Jakarta")->translatedFormat("d M Y, H:i") . " WIB" : "-" }}',
                            rejection_reason: '{{ addslashes($order->rejection_reason ?? "") }}'
                        })">
                        <td style="font-size:11px; font-weight:800; color:#183B30; padding:15px 10px;">
                            {{ $order->order_code }}
                        </td>
                        <td style="font-size:10.5px; color:#50695E; padding:15px 10px;">
                            {{ $order->created_at ? $order->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y') : '-' }}
                        </td>
                        <td style="font-size:11px; padding:15px 10px;">
                            <b style="font-weight:850; color:#183B30;">{{ $order->commodityListing->commodity_name ?? 'Komoditas' }}</b>
                            <div style="font-size:10px; color:#0A765A; margin-top:2px;">{{ $order->harvestBatch->batch_name ?? 'Batch' }} &bull; {{ number_format($order->quantity_kg, 0, ',', '.') }} kg</div>
                        </td>
                        <td style="font-size:11px; color:#172B29; padding:15px 10px;">
                            <div style="font-weight:700;">{{ $order->farmer->name ?? 'Petani' }}</div>
                            <div style="font-size:10px; color:#85958F;">{{ $order->farmer->organization ?? '-' }}</div>
                        </td>
                        <td style="font-size:11px; color:#172B29; padding:15px 10px;">
                            <div style="font-weight:700;">{{ $order->buyer->name ?? 'Pembeli' }}</div>
                            <div style="font-size:10px; color:#85958F;">{{ $order->buyer->organization ?? '-' }}</div>
                        </td>
                        <td style="font-size:11px; font-weight:800; color:#0A765A; padding:15px 10px;">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                        <td style="font-size:11px; padding:15px 10px;">
                            @if($order->dp_confirmed_at)
                                <span style="display:inline-flex; align-items:center; gap:4px; padding:3px 8px; border-radius:6px; background:#D1FAE5; color:#065F46; font-weight:800; font-size:10px;">
                                    <i class="fa-solid fa-check"></i> DP Lunas (30%)
                                </span>
                            @elseif($order->dp_uploaded_at)
                                <span style="display:inline-flex; align-items:center; gap:4px; padding:3px 8px; border-radius:6px; background:#DBEAFE; color:#1E40AF; font-weight:800; font-size:10px;">
                                    <i class="fa-solid fa-clock"></i> Bukti Diunggah
                                </span>
                            @elseif($order->status === 'menunggu_pembayaran_dp')
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#FEF3C7; color:#92400E; font-weight:800; font-size:10px;">
                                    Menunggu Bayar
                                </span>
                            @else
                                <span style="font-size:10.5px; color:#85958F;">-</span>
                            @endif
                        </td>
                        <td style="font-size:11px; padding:15px 10px;">
                            @if($order->status === 'selesai')
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#D1FAE5; color:#065F46; font-weight:800; font-size:10px;">Selesai</span>
                            @elseif(in_array($order->status, ['pesanan_aktif', 'dikonfirmasi']))
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#EFF6FF; color:#1D4ED8; font-weight:800; font-size:10px;">Pesanan Aktif</span>
                            @elseif($order->status === 'menunggu_konfirmasi')
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#FFF8EA; color:#B45309; font-weight:800; font-size:10px;">Menunggu Konfirmasi</span>
                            @elseif($order->status === 'menunggu_pembayaran_dp')
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#FEF3C7; color:#92400E; font-weight:800; font-size:10px;">Menunggu DP</span>
                            @elseif($order->status === 'menunggu_verifikasi_dp')
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#DBEAFE; color:#1E40AF; font-weight:800; font-size:10px;">Pemeriksaan DP</span>
                            @elseif($order->status === 'siap_panen')
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#E9FBF3; color:#0A765A; font-weight:800; font-size:10px;">Siap Panen</span>
                            @elseif($order->status === 'dalam_pengiriman')
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#F0FDF4; color:#15803D; font-weight:800; font-size:10px;">Dalam Pengiriman</span>
                            @elseif($order->status === 'dibatalkan')
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#FEE2E2; color:#991B1B; font-weight:800; font-size:10px;">Ditolak / Batal</span>
                            @else
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:10px;">{{ $order->status_label }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($allOrders->isEmpty())
        <div style="text-align:center; padding:40px 20px; color:#85958F; font-size:12px;">
            <i class="fa-solid fa-inbox" style="font-size:28px; margin-bottom:10px; display:block; color:#C5D5CD;"></i>
            Belum ada pesanan pre-order yang tercatat.
        </div>
        @endif
    </div>

    {{-- Detail Modal --}}
    <div x-show="showModal" x-cloak
         style="position:fixed; inset:0; z-index:1000; display:flex; align-items:center; justify-content:center; padding:20px;"
         @keydown.escape.window="showModal = false">
        <div style="position:fixed; inset:0; background:rgba(0,0,0,0.4);" @click="showModal = false"></div>
        <div style="position:relative; background:#fff; border-radius:16px; max-width:640px; width:100%; max-height:85vh; overflow-y:auto; padding:28px; box-shadow:0 20px 60px rgba(0,0,0,0.15);">
            {{-- Modal Header --}}
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <div>
                    <h3 style="font-size:17px; font-weight:850; color:#172B29; margin:0 0 4px;">Detail Transaksi</h3>
                    <p style="font-size:11px; color:#85958F; margin:0;">Kode: <b x-text="selectedOrder?.code" style="color:#0A765A;"></b></p>
                </div>
                <button @click="showModal = false" style="width:32px; height:32px; border-radius:8px; border:1px solid #E5ECEA; background:#fff; cursor:pointer; display:grid; place-items:center; color:#85958F; font-size:14px;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- Status Badge --}}
            <div style="margin-bottom:18px;">
                <template x-if="selectedOrder?.status === 'selesai'">
                    <span style="display:inline-flex; align-items:center; padding:5px 12px; border-radius:8px; background:#D1FAE5; color:#065F46; font-weight:800; font-size:11px;">Selesai</span>
                </template>
                <template x-if="selectedOrder?.status === 'dibatalkan'">
                    <span style="display:inline-flex; align-items:center; padding:5px 12px; border-radius:8px; background:#FEE2E2; color:#991B1B; font-weight:800; font-size:11px;">Ditolak / Batal</span>
                </template>
                <template x-if="selectedOrder?.status !== 'selesai' && selectedOrder?.status !== 'dibatalkan'">
                    <span style="display:inline-flex; align-items:center; padding:5px 12px; border-radius:8px; background:#EFF6FF; color:#1D4ED8; font-weight:800; font-size:11px;" x-text="selectedOrder?.status_label"></span>
                </template>
            </div>

            {{-- Info Grid --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:18px;">
                <div style="padding:14px; border-radius:10px; background:#F8FCFA; border:1px solid #E5ECEA;">
                    <div style="font-size:10px; color:#7B8C86; font-weight:700; margin-bottom:6px; text-transform:uppercase;">Petani Produsen</div>
                    <div style="font-size:12px; font-weight:800; color:#172B29;" x-text="selectedOrder?.farmer"></div>
                    <div style="font-size:10.5px; color:#85958F;" x-text="selectedOrder?.farmer_org"></div>
                </div>
                <div style="padding:14px; border-radius:10px; background:#F8FCFA; border:1px solid #E5ECEA;">
                    <div style="font-size:10px; color:#7B8C86; font-weight:700; margin-bottom:6px; text-transform:uppercase;">Konsumen Pemesan</div>
                    <div style="font-size:12px; font-weight:800; color:#172B29;" x-text="selectedOrder?.buyer"></div>
                    <div style="font-size:10.5px; color:#85958F;" x-text="selectedOrder?.buyer_org"></div>
                </div>
            </div>

            {{-- Commodity --}}
            <div style="padding:14px; border-radius:10px; background:#F8FCFA; border:1px solid #E5ECEA; margin-bottom:18px;">
                <div style="font-size:10px; color:#7B8C86; font-weight:700; margin-bottom:6px; text-transform:uppercase;">Komoditas &amp; Batch</div>
                <div style="font-size:13px; font-weight:800; color:#172B29;" x-text="selectedOrder?.commodity"></div>
                <div style="font-size:11px; color:#0A765A; margin-top:2px;">
                    <span x-text="selectedOrder?.batch"></span> &bull; <span x-text="selectedOrder?.quantity"></span> kg &bull; Kategori: <span x-text="selectedOrder?.category"></span>
                </div>
            </div>

            {{-- Financial --}}
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; margin-bottom:18px;">
                <div style="padding:12px; border-radius:10px; border:1px solid #E5ECEA; text-align:center;">
                    <div style="font-size:9.5px; color:#7B8C86; font-weight:700; margin-bottom:4px;">HARGA SATUAN</div>
                    <div style="font-size:13px; font-weight:850; color:#172B29;" x-text="selectedOrder?.price_per_kg"></div>
                    <div style="font-size:9px; color:#85958F;">per kg</div>
                </div>
                <div style="padding:12px; border-radius:10px; border:1px solid #E5ECEA; text-align:center;">
                    <div style="font-size:9.5px; color:#7B8C86; font-weight:700; margin-bottom:4px;">TOTAL TRANSAKSI</div>
                    <div style="font-size:13px; font-weight:850; color:#0A765A;" x-text="selectedOrder?.total"></div>
                </div>
                <div style="padding:12px; border-radius:10px; border:1px solid #E5ECEA; text-align:center;">
                    <div style="font-size:9.5px; color:#7B8C86; font-weight:700; margin-bottom:4px;">DP 30%</div>
                    <div style="font-size:13px; font-weight:850; color:#172B29;" x-text="selectedOrder?.dp_amount"></div>
                    <template x-if="selectedOrder?.dp_status === 'confirmed'">
                        <div style="font-size:9px; color:#065F46; font-weight:700;">✓ Dikonfirmasi</div>
                    </template>
                    <template x-if="selectedOrder?.dp_status === 'uploaded'">
                        <div style="font-size:9px; color:#1E40AF; font-weight:700;">Menunggu Periksa</div>
                    </template>
                    <template x-if="selectedOrder?.dp_status === 'waiting'">
                        <div style="font-size:9px; color:#92400E; font-weight:700;">Belum Dibayar</div>
                    </template>
                    <template x-if="selectedOrder?.dp_status === 'none'">
                        <div style="font-size:9px; color:#85958F;">-</div>
                    </template>
                </div>
            </div>

            {{-- Timestamps --}}
            <div style="padding:14px; border-radius:10px; background:#FAFBFC; border:1px solid #E5ECEA; margin-bottom:18px;">
                <div style="font-size:10px; color:#7B8C86; font-weight:700; margin-bottom:8px; text-transform:uppercase;">Waktu Penting</div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:6px; font-size:11px;">
                    <div><span style="color:#85958F;">Diajukan:</span> <b style="color:#172B29;" x-text="selectedOrder?.created"></b></div>
                    <div><span style="color:#85958F;">Dikonfirmasi Petani:</span> <b style="color:#172B29;" x-text="selectedOrder?.confirmed_at"></b></div>
                    <div><span style="color:#85958F;">DP Dikonfirmasi:</span> <b style="color:#172B29;" x-text="selectedOrder?.dp_confirmed_at"></b></div>
                    <div><span style="color:#85958F;">Selesai:</span> <b style="color:#172B29;" x-text="selectedOrder?.completed_at"></b></div>
                </div>
            </div>

            {{-- Rejection Reason --}}
            <template x-if="selectedOrder?.rejection_reason">
                <div style="padding:12px; border-radius:10px; background:#FEF2F2; border:1px solid #FECACA; margin-bottom:14px;">
                    <div style="font-size:10px; font-weight:800; color:#991B1B; margin-bottom:4px;">Catatan Penolakan</div>
                    <div style="font-size:11px; color:#7F1D1D;" x-text="selectedOrder?.rejection_reason"></div>
                </div>
            </template>

            {{-- Footer Note --}}
            <div style="font-size:10px; color:#91A19B; text-align:center; padding-top:10px; border-top:1px solid #EEF2F0;">
                <i class="fa-solid fa-circle-info" style="margin-right:3px;"></i>
                Data bersifat baca-saja. Konfirmasi DP dan pengelolaan pesanan dilakukan oleh masing-masing Petani dan Konsumen.
            </div>
        </div>
    </div>

</div>
@endsection
