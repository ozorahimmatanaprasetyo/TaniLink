@extends('layouts.sidebar')

@section('title', 'Dashboard Admin - TaniLink Central')

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

@push('styles')
<style>
.admin-headline {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 22px;
    flex-wrap: wrap;
    gap: 14px;
}
.admin-headline-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.admin-metrics-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 22px;
}
.admin-breakdown-strip {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 22px;
}
.admin-dashboard-layout {
    display: grid;
    grid-template-columns: minmax(0, 1.4fr) minmax(360px, 1fr);
    gap: 20px;
}
@media (max-width: 992px) {
    .admin-metrics-grid {
        grid-template-columns: 1fr 1fr;
    }
    .admin-breakdown-strip {
        grid-template-columns: 1fr 1fr;
    }
    .admin-dashboard-layout {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 640px) {
    .admin-headline {
        flex-direction: column;
        align-items: flex-start;
    }
    .admin-headline-actions {
        width: 100%;
    }
    .admin-headline-actions button,
    .admin-headline-actions a {
        flex: 1;
        justify-content: center;
        text-align: center;
    }
}
@media (max-width: 480px) {
    .admin-headline {
        margin-bottom: 16px;
        gap: 10px;
    }
    .admin-headline h1 {
        font-size: 20px !important;
        margin-bottom: 4px !important;
    }
    .admin-headline p {
        font-size: 11.5px !important;
    }
    .admin-metrics-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 10px !important;
        margin-bottom: 16px;
    }
    .admin-metrics-grid .card {
        padding: 12px 14px !important;
        border-radius: 14px !important;
    }
    .admin-metrics-grid .card .metric-val {
        font-size: 20px !important;
    }
    .admin-breakdown-strip {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 10px !important;
        margin-bottom: 16px;
    }
}
</style>
@endpush

@section('content')
<div x-data="{
    stats: {{ json_encode($stats) }},
    lastSync: '{{ now('Asia/Jakarta')->translatedFormat('d M Y, H:i:s') }} WIB',
    isSyncing: false,
    pollTimer: null,
    fetchStats() {
        if (this.isSyncing) return;
        this.isSyncing = true;
        fetch('{{ route('admin.stats.api') }}', { credentials: 'same-origin' })
            .then(res => {
                if (!res.ok) throw new Error('Network error');
                return res.json();
            })
            .then(data => {
                if (data && data.data) {
                    this.stats = data.data;
                    this.lastSync = data.timestamp;
                }
            })
            .catch(err => {
                // Tangani kegagalan tanpa menampilkan error fatal
                console.warn('Auto-sync status:', err.message);
            })
            .finally(() => {
                this.isSyncing = false;
            });
    },
    startPolling() {
        this.pollTimer = setInterval(() => {
            if (!document.hidden) {
                this.fetchStats();
            }
        }, 30000);
    }
}" x-init="startPolling()" @visibilitychange.window="if (!document.hidden) fetchStats()">

    {{-- Headline & Live Indicator --}}
    <div class="admin-headline">
        <div>
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px; flex-wrap:wrap;">
                <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0;">Ringkasan Operasional Admin</h1>
                <span style="font-size:10px; font-weight:800; padding:2px 8px; border-radius:6px; background:#E9FBF3; color:#0A765A; display:inline-flex; align-items:center; gap:5px;">
                    <span style="width:6px; height:6px; border-radius:50%; background:#05C793; display:inline-block;" :class="{ 'animate-ping': isSyncing }"></span>
                    <span x-text="isSyncing ? 'Memperbarui...' : 'Data Aktual Database'">Data Aktual Database</span>
                </span>
            </div>
            <p style="font-size:12px; color:#8A9A94; margin:0;">
                Pantau ekosistem petani, pesanan aktif, pergerakan DP langsung, dan pemenuhan panen. Terakhir sinkron: <span x-text="lastSync" style="color:#50695E; font-weight:700;"></span>
            </p>
        </div>

        <div class="admin-headline-actions">
            <button type="button" @click="fetchStats()" class="btn btn-secondary" style="font-size:12px; padding:8px 14px;">
                <i class="fa-solid fa-arrows-rotate" :class="{ 'fa-spin': isSyncing }"></i> Perbarui Sekarang
            </button>
            <a href="{{ route('admin.laporan') }}" class="btn btn-primary" style="font-size:12px; padding:8px 16px;">
                Lihat Laporan Lengkap
            </a>
        </div>
    </div>

    {{-- 4 Primary Metric Cards --}}
    <div class="admin-metrics-grid">
        {{-- Total Pengguna --}}
        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Total Pengguna Mitra</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#F1EFFB; color:#7257CA; font-size:15px;"><i class="fa-solid fa-users"></i></span>
            </div>
            <div style="font-weight:850; font-size:26px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;" x-text="stats.total_users">
                {{ $stats['total_users'] }}
            </div>
            <div style="font-size:10.5px; color:#7B9187;">
                <span x-text="stats.total_farmers" style="font-weight:700; color:#0A765A;">{{ $stats['total_farmers'] }}</span> Petani &bull; 
                <span x-text="stats.total_buyers" style="font-weight:700; color:#075985;">{{ $stats['total_buyers'] }}</span> Konsumen
            </div>
        </div>

        {{-- Komoditas & Batch Panen --}}
        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Komoditas &amp; Batch Aktif</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:15px;"><i class="fa-solid fa-boxes-stacked"></i></span>
            </div>
            <div style="font-weight:850; font-size:26px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                <span x-text="stats.active_listings">{{ $stats['active_listings'] }}</span>
                <span style="font-size:14px; font-weight:700; color:#85958F;">Listing</span>
            </div>
            <div style="font-size:10.5px; color:#7B9187;">
                <span x-text="stats.active_batches" style="font-weight:700; color:#0A765A;">{{ $stats['active_batches'] }}</span> Batch panen aktif berjalan
            </div>
        </div>

        {{-- Pesanan Aktif --}}
        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Pesanan Aktif Berjalan</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E8F9FC; color:#0B8BAA; font-size:15px;"><i class="fa-solid fa-clipboard-list"></i></span>
            </div>
            <div style="font-weight:850; font-size:26px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;" x-text="stats.active_orders">
                {{ $stats['active_orders'] }}
            </div>
            <div style="font-size:10.5px; color:#7B9187;">
                <span x-text="stats.pending_confirmation" style="font-weight:700; color:#B45309;">{{ $stats['pending_confirmation'] }}</span> Butuh persetujuan
            </div>
        </div>

        {{-- Total Nilai Pesanan Sah --}}
        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Total Nilai Pesanan</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#FFF5E8; color:#BA7A1F; font-size:15px;"><i class="fa-solid fa-receipt"></i></span>
            </div>
            <div style="font-weight:850; font-size:22px; letter-spacing:-0.8px; margin:12px 0 6px; color:#172B29; white-space:nowrap;" x-text="stats.total_order_value_formatted">
                {{ $stats['total_order_value_formatted'] }}
            </div>
            <div style="font-size:10.5px; color:#7B9187;">
                DP Dikonfirmasi: <b style="color:#0C8D65;" x-text="stats.confirmed_dp_formatted">{{ $stats['confirmed_dp_formatted'] }}</b>
            </div>
        </div>
    </div>

    {{-- 4 Status Breakdown Strip --}}
    <div class="admin-breakdown-strip">
        <div style="background:#fff; border:1px solid #E5ECEA; border-radius:14px; padding:12px 16px; display:flex; justify-content:space-between; align-items:center;">
            <div>
                <span style="font-size:10.5px; color:#7B8C86; font-weight:700; text-transform:uppercase;">Menunggu DP</span>
                <div style="font-size:16px; font-weight:850; color:#92400E;" x-text="stats.pending_dp_payment">{{ $stats['pending_dp_payment'] }}</div>
            </div>
            <span style="width:28px; height:28px; border-radius:8px; background:#FEF3C7; color:#92400E; display:grid; place-items:center; font-size:12px;"><i class="fa-solid fa-clock"></i></span>
        </div>

        <div style="background:#fff; border:1px solid #E5ECEA; border-radius:14px; padding:12px 16px; display:flex; justify-content:space-between; align-items:center;">
            <div>
                <span style="font-size:10.5px; color:#7B8C86; font-weight:700; text-transform:uppercase;">Pemeriksaan DP</span>
                <div style="font-size:16px; font-weight:850; color:#1E40AF;" x-text="stats.pending_dp_verification">{{ $stats['pending_dp_verification'] }}</div>
            </div>
            <span style="width:28px; height:28px; border-radius:8px; background:#DBEAFE; color:#1E40AF; display:grid; place-items:center; font-size:12px;"><i class="fa-solid fa-magnifying-glass"></i></span>
        </div>

        <div style="background:#fff; border:1px solid #E5ECEA; border-radius:14px; padding:12px 16px; display:flex; justify-content:space-between; align-items:center;">
            <div>
                <span style="font-size:10.5px; color:#7B8C86; font-weight:700; text-transform:uppercase;">Pesanan Selesai</span>
                <div style="font-size:16px; font-weight:850; color:#065F46;" x-text="stats.completed_orders">{{ $stats['completed_orders'] }}</div>
            </div>
            <span style="width:28px; height:28px; border-radius:8px; background:#D1FAE5; color:#065F46; display:grid; place-items:center; font-size:12px;"><i class="fa-solid fa-check"></i></span>
        </div>

        <div style="background:#fff; border:1px solid #E5ECEA; border-radius:14px; padding:12px 16px; display:flex; justify-content:space-between; align-items:center;">
            <div>
                <span style="font-size:10.5px; color:#7B8C86; font-weight:700; text-transform:uppercase;">Ditolak / Batal</span>
                <div style="font-size:16px; font-weight:850; color:#991B1B;" x-text="stats.cancelled_orders">{{ $stats['cancelled_orders'] }}</div>
            </div>
            <span style="width:28px; height:28px; border-radius:8px; background:#FEE2E2; color:#991B1B; display:grid; place-items:center; font-size:12px;"><i class="fa-solid fa-xmark"></i></span>
        </div>
    </div>

    {{-- Grid 2 Kolom: Kiri Panduan Operasional & Kanan Pesanan Terkini --}}
    <div class="admin-dashboard-layout">
        
        {{-- Kiri: Transparansi Pembayaran & Alur Batch --}}
        <div class="card" style="padding:24px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                <div>
                    <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Tata Kelola Transaksi &amp; Perkembangan Tanaman</h2>
                    <p style="font-size:11px; color:#91A19B; margin:0;">Status operasional sistem pre-order langsung ke petani.</p>
                </div>
            </div>

            <div style="display:flex; flex-direction:column; gap:12px;">
                <div style="padding:14px; border-radius:12px; background:#F8FCFA; border:1px solid #DCECE5; display:flex; gap:12px;">
                    <div style="width:32px; height:32px; border-radius:10px; background:#DCF8EB; color:#007B55; display:grid; place-items:center; font-size:14px; flex-shrink:0;">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <div>
                        <b style="font-size:12.5px; color:#172B29;">Pembayaran DP Langsung ke Rekening Petani</b>
                        <p style="font-size:11px; color:#50695E; margin:3px 0 0; line-height:1.5;">
                            Konsumen melakukan transfer 30% langsung ke rekening bank atas nama petani atau kelompok tani. Status DP dikonfirmasi secara mandiri oleh petani setelah memeriksa mutasi rekening.
                        </p>
                    </div>
                </div>

                <div style="padding:14px; border-radius:12px; background:#F8FCFA; border:1px solid #DCECE5; display:flex; gap:12px;">
                    <div style="width:32px; height:32px; border-radius:10px; background:#E0F2FE; color:#0369A1; display:grid; place-items:center; font-size:14px; flex-shrink:0;">
                        <i class="fa-solid fa-seedling"></i>
                    </div>
                    <div>
                        <b style="font-size:12.5px; color:#172B29;">Perkembangan Berbasis Batch Panen</b>
                        <p style="font-size:11px; color:#50695E; margin:3px 0 0; line-height:1.5;">
                            Dokumentasi foto dan catatan lapangan dikelola per batch panen (4 fase terstruktur). Seluruh konsumen yang terikat pada batch yang sama otomatis memantau pembaruan secara transparan.
                        </p>
                    </div>
                </div>

                <div style="padding:14px; border-radius:12px; background:#F8FCFA; border:1px solid #DCECE5; display:flex; gap:12px;">
                    <div style="width:32px; height:32px; border-radius:10px; background:#FEF3C7; color:#B45309; display:grid; place-items:center; font-size:14px; flex-shrink:0;">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <b style="font-size:12.5px; color:#172B29;">Integritas &amp; Netralitas Platform Admin</b>
                        <p style="font-size:11px; color:#50695E; margin:3px 0 0; line-height:1.5;">
                            Admin bertindak sebagai pengawas ekosistem, moderator komoditas, dan fasilitator pusat bantuan. Admin tidak memegang saldo dana atau menyelesaikan transaksi secara sepihak.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kanan: Pesanan Terkini --}}
        <div class="card" style="padding:24px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                <div>
                    <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Pesanan Terkini</h2>
                    <p style="font-size:11px; color:#91A19B; margin:0;">Aktivitas kontrak teranyar di sistem.</p>
                </div>
                <a href="{{ route('admin.pesanan') }}" class="btn btn-secondary" style="font-size:11px; padding:6px 12px;">
                    Kelola Semua
                </a>
            </div>

            <div style="display:flex; flex-direction:column; gap:10px;">
                @forelse($allOrders as $o)
                <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 12px; border-radius:10px; background:#F8FCFA; border:1px solid #EEF2F0;">
                    <div>
                        <b style="font-size:12px; color:#172B29;">{{ $o->order_code }}</b>
                        <div style="font-size:10.5px; color:#6B8077; margin-top:2px;">
                            {{ $o->commodityListing->commodity_name ?? 'Komoditas' }} &bull; {{ $o->buyer->name ?? 'Konsumen' }}
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <b style="font-size:12px; color:#0A765A;">Rp {{ number_format($o->total_amount, 0, ',', '.') }}</b>
                        <div style="margin-top:2px;">
                            @if($o->status === 'selesai')
                                <span style="display:inline-flex; align-items:center; padding:2px 7px; border-radius:5px; background:#D1FAE5; color:#065F46; font-weight:800; font-size:9.5px;">Selesai</span>
                            @elseif($o->status === 'dibatalkan')
                                <span style="display:inline-flex; align-items:center; padding:2px 7px; border-radius:5px; background:#FEE2E2; color:#991B1B; font-weight:800; font-size:9.5px;">Ditolak / Batal</span>
                            @elseif($o->status === 'menunggu_verifikasi_dp')
                                <span style="display:inline-flex; align-items:center; padding:2px 7px; border-radius:5px; background:#DBEAFE; color:#1E40AF; font-weight:800; font-size:9.5px;">Pemeriksaan DP</span>
                            @else
                                <span style="display:inline-flex; align-items:center; padding:2px 7px; border-radius:5px; background:#EFF6FF; color:#1D4ED8; font-weight:800; font-size:9.5px;">{{ $o->status_label }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <p style="font-size:12px; color:#8A9D95; text-align:center; padding:20px 0;">Belum ada pesanan masuk.</p>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
