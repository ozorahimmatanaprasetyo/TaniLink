@extends('layouts.sidebar')

@section('title', 'Kelola Komoditas - Admin TaniLink')

@push('styles')
<style>
    .listings-headline {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .listings-metrics-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    .listings-filter-bar {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .listings-table-desktop {
        display: table;
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    .listings-card-mobile {
        display: none;
        flex-direction: column;
        gap: 12px;
    }
    .proposal-table-desktop {
        display: table;
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    .proposal-card-mobile {
        display: none;
        flex-direction: column;
        gap: 12px;
    }

    @media (max-width: 992px) {
        .listings-metrics-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .listings-metrics-grid > div:last-child {
            grid-column: span 2;
        }
    }
    @media (max-width: 480px) {
        .listings-metrics-grid {
            gap: 10px !important;
            margin-bottom: 16px !important;
        }
        .listings-metrics-grid .card {
            padding: 12px 14px !important;
            border-radius: 14px !important;
        }
        .listings-headline h1 {
            font-size: 20px !important;
        }
    }
    @media (max-width: 768px) {
        .listings-table-desktop,
        .proposal-table-desktop {
            display: none !important;
        }
        .listings-card-mobile,
        .proposal-card-mobile {
            display: flex !important;
        }
        .listings-filter-bar {
            width: 100%;
            flex-direction: column;
        }
        .listings-filter-bar input,
        .listings-filter-bar select {
            width: 100% !important;
            min-width: 0 !important;
        }
    }
</style>
@endpush

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
<div x-data="{ search: '', filterCategory: '' }">

    {{-- Headline --}}
    <div class="listings-headline">
        <div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Kelola Komoditas</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">Moderasi penawaran komoditas pertanian dan tinjau usulan kategori baru dari petani.</p>
        </div>
    </div>

    {{-- 3 Metric Cards --}}
    <div class="listings-metrics-grid">
        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Total Komoditas Unik</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:15px;"><i class="fa-solid fa-boxes-stacked"></i></span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ $allListings->count() }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Listing komoditas terdaftar</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Kapasitas Pasokan Total</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E8F9FC; color:#0B8BAA; font-size:15px;"><i class="fa-solid fa-weight-hanging"></i></span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ number_format($allListings->sum('estimated_harvest_kg'), 0, ',', '.') }} kg
            </div>
            <div style="font-size:10px; color:#7B9187;">Total estimasi panen kumulatif</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Luas Lahan Produktif</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#F1EFFB; color:#7257CA; font-size:15px;"><i class="fa-solid fa-map-location-dot"></i></span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ number_format($allListings->sum('land_area_m2'), 0, ',', '.') }} m²
            </div>
            <div style="font-size:10px; color:#7B9187;">Lahan kelompok tani mitra</div>
        </div>
    </div>

    {{-- Usulan Kategori Baru dari Petani --}}
    @if(isset($categoryProposals) && $categoryProposals->isNotEmpty())
    <div class="card" style="padding:24px; margin-bottom:24px;">
        <div style="margin-bottom:16px;">
            <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Usulan Kategori Baru dari Petani</h2>
            <p style="font-size:11px; color:#91A19B; margin:0;">Tinjau pengajuan nama kategori baru sebelum diaktifkan untuk publik.</p>
        </div>

        <div>
            <table class="proposal-table-desktop">
                <thead>
                    <tr>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:10px;">NAMA KATEGORI</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:10px;">PETANI PENGUSUL</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:10px;">ALASAN / CATATAN</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:10px;">STATUS</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:10px; text-align:right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categoryProposals as $prop)
                    <tr style="border-bottom:1px solid #EEF2F0;">
                        <td style="font-size:11.5px; font-weight:800; color:#183B30; padding:12px 10px;">
                            {{ $prop->name }}
                        </td>
                        <td style="font-size:11px; color:#172B29; padding:12px 10px;">
                            <div>{{ $prop->user->name ?? 'Petani' }}</div>
                            <div style="font-size:10px; color:#85958F;">{{ $prop->user->organization ?? '-' }}</div>
                        </td>
                        <td style="font-size:11px; color:#50695E; padding:12px 10px;">
                            {{ $prop->description ?: 'Tidak ada catatan tambahan.' }}
                        </td>
                        <td style="font-size:11px; padding:12px 10px;">
                            @if($prop->status === 'menunggu')
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#FEF3C7; color:#92400E; font-weight:800; font-size:10px;">Menunggu Tinjauan</span>
                            @elseif($prop->status === 'disetujui')
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#D1FAE5; color:#065F46; font-weight:800; font-size:10px;">Disetujui</span>
                            @else
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#FEE2E2; color:#991B1B; font-weight:800; font-size:10px;">Ditolak</span>
                            @endif
                        </td>
                        <td style="font-size:11px; padding:12px 10px; text-align:right;">
                            @if($prop->status === 'menunggu')
                            <div style="display:inline-flex; gap:6px;">
                                <form action="{{ route('admin.category.approve', $prop->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary" style="padding:5px 12px; font-size:11px; min-height:auto;">
                                        Setujui
                                    </button>
                                </form>
                                <form action="{{ route('admin.category.reject', $prop->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Tolak usulan kategori ini?');">
                                    @csrf
                                    <button type="submit" class="btn btn-danger" style="padding:5px 12px; font-size:11px; min-height:auto;">
                                        Tolak
                                    </button>
                                </form>
                            </div>
                            @else
                                <span style="font-size:11px; color:#A1B0AA;">Selesai ditinjau</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Mobile Cards for Category Proposals --}}
            <div class="proposal-card-mobile">
                @foreach($categoryProposals as $prop)
                <div style="border:1px solid #EEF2F0; border-radius:12px; padding:14px; background:#FAFCFB; display:flex; flex-direction:column; gap:10px;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:8px;">
                        <div>
                            <div style="font-size:13px; font-weight:800; color:#183B30;">{{ $prop->name }}</div>
                            <div style="font-size:11px; color:#6B8077; margin-top:2px;">{{ $prop->user->name ?? 'Petani' }} &bull; {{ $prop->user->organization ?? '-' }}</div>
                        </div>
                        <div>
                            @if($prop->status === 'menunggu')
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#FEF3C7; color:#92400E; font-weight:800; font-size:10px;">Menunggu</span>
                            @elseif($prop->status === 'disetujui')
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#D1FAE5; color:#065F46; font-weight:800; font-size:10px;">Disetujui</span>
                            @else
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#FEE2E2; color:#991B1B; font-weight:800; font-size:10px;">Ditolak</span>
                            @endif
                        </div>
                    </div>
                    @if($prop->description)
                    <div style="font-size:11px; color:#50695E; background:#F3F6F5; padding:8px 10px; border-radius:8px;">
                        {{ $prop->description }}
                    </div>
                    @endif
                    @if($prop->status === 'menunggu')
                    <div style="display:flex; gap:8px; margin-top:4px;">
                        <form action="{{ route('admin.category.approve', $prop->id) }}" method="POST" style="margin:0; flex:1;">
                            @csrf
                            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:8px; font-size:11px; min-height:auto;">
                                Setujui
                            </button>
                        </form>
                        <form action="{{ route('admin.category.reject', $prop->id) }}" method="POST" style="margin:0; flex:1;" onsubmit="return confirm('Tolak usulan kategori ini?');">
                            @csrf
                            <button type="submit" class="btn btn-danger" style="width:100%; justify-content:center; padding:8px; font-size:11px; min-height:auto;">
                                Tolak
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Listings Table Card --}}
    <div class="card" style="padding:24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:12px;">
            <div>
                <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Daftar Komoditas Marketplace</h2>
                <p style="font-size:11px; color:#91A19B; margin:0;">Status alokasi kuota, harga satuan, dan perkiraan panen petani.</p>
            </div>

            <div class="listings-filter-bar">
                <input type="text" x-model="search" placeholder="Cari komoditas, varietas, petani..."
                       style="padding:9px 13px; border:1px solid #E5ECEA; border-radius:10px; font-size:12px; color:#50695E; background:#fff; min-width:220px; outline:0;">
                <select x-model="filterCategory"
                        style="padding:9px 13px; border:1px solid #E5ECEA; border-radius:10px; font-size:12px; color:#50695E; background:#fff; outline:0;">
                    <option value="">Semua Kategori</option>
                    @foreach($allListings->pluck('category')->unique()->filter() as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <table class="listings-table-desktop">
                <thead>
                    <tr>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">KOMODITAS</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">PRODUSEN / PETANI</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">HARGA / KG</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">PERKIRAAN PANEN</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">KUOTA TERSEDIA / TOTAL</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">STATUS</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px; text-align:right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allListings as $item)
                    @php
                        $target = strtolower($item->commodity_name . ' ' . ($item->variety ?? '') . ' ' . ($item->farmer->name ?? ''));
                    @endphp
                    <tr x-show="(search === '' || '{{ $target }}'.includes(search.toLowerCase())) && (filterCategory === '' || '{{ $item->category }}' === filterCategory)"
                        style="border-bottom:1px solid #EEF2F0;">
                        <td style="font-size:11px; font-weight:800; color:#183B30; padding:15px 10px;">
                            <b style="font-weight:850; color:#183B30;">{{ $item->commodity_name }}</b>
                            <div style="font-size:10px; color:#85958F; margin-top:2px;">{{ $item->variety ?: 'Varietas Umum' }} &bull; {{ $item->category }}</div>
                        </td>
                        <td style="font-size:11px; color:#172B29; padding:15px 10px;">
                            <div style="font-weight:700;">{{ $item->farmer->name ?? 'Petani Mitra' }}</div>
                            <div style="font-size:10px; color:#85958F;">{{ $item->location ?? 'Malang' }}</div>
                        </td>
                        <td style="font-size:11px; font-weight:800; color:#0A765A; padding:15px 10px;">
                            Rp {{ number_format($item->price_per_kg, 0, ',', '.') }}
                        </td>
                        <td style="font-size:11px; font-weight:650; padding:15px 10px;">
                            {{ $item->harvest_estimated_date ? $item->harvest_estimated_date->timezone('Asia/Jakarta')->translatedFormat('d M Y') : '-' }}
                        </td>
                        <td style="font-size:11px; padding:15px 10px;">
                            <strong style="color:{{ $item->available_kg > 0 ? '#0C8D65' : '#B91C1C' }};">{{ number_format($item->available_kg, 0, ',', '.') }}</strong>
                            <span style="color:#85958F;">/ {{ number_format($item->estimated_harvest_kg, 0, ',', '.') }} kg</span>
                        </td>
                        <td style="font-size:11px; padding:15px 10px;">
                            @if($item->status === 'available')
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#E9F9F2; color:#0C8D65; font-weight:800; font-size:10px;">Tersedia</span>
                            @else
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:10px;">Terkontrak Penuh</span>
                            @endif
                        </td>
                        <td style="font-size:11px; padding:15px 10px; text-align:right;">
                            <form action="{{ route('admin.komoditas.destroy', $item->id) }}" method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus komoditas {{ $item->commodity_name }} ini?');"
                                  style="display:inline; margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding:5px 11px; font-size:11px; min-height:auto;">
                                    <i class="fa-regular fa-trash-can"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Mobile Cards for Listings --}}
            <div class="listings-card-mobile">
                @foreach($allListings as $item)
                @php
                    $target = strtolower($item->commodity_name . ' ' . ($item->variety ?? '') . ' ' . ($item->farmer->name ?? ''));
                @endphp
                <div x-show="(search === '' || '{{ $target }}'.includes(search.toLowerCase())) && (filterCategory === '' || '{{ $item->category }}' === filterCategory)"
                     style="border:1px solid #EEF2F0; border-radius:12px; padding:14px; background:#FAFCFB; display:flex; flex-direction:column; gap:10px;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:8px;">
                        <div>
                            <div style="font-size:13px; font-weight:850; color:#183B30;">{{ $item->commodity_name }}</div>
                            <div style="font-size:11px; color:#85958F; margin-top:2px;">{{ $item->variety ?: 'Varietas Umum' }} &bull; {{ $item->category }}</div>
                        </div>
                        <div>
                            @if($item->status === 'available')
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#E9F9F2; color:#0C8D65; font-weight:800; font-size:10px;">Tersedia</span>
                            @else
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:10px;">Terkontrak</span>
                            @endif
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; font-size:11px; background:#F3F6F5; padding:10px; border-radius:8px;">
                        <div>
                            <div style="font-size:10px; color:#85958F;">Petani</div>
                            <div style="font-weight:700; color:#172B29;">{{ $item->farmer->name ?? 'Petani Mitra' }}</div>
                            <div style="font-size:10px; color:#85958F;">{{ $item->location ?? 'Malang' }}</div>
                        </div>
                        <div>
                            <div style="font-size:10px; color:#85958F;">Harga / kg</div>
                            <div style="font-weight:800; color:#0A765A;">Rp {{ number_format($item->price_per_kg, 0, ',', '.') }}</div>
                        </div>
                        <div>
                            <div style="font-size:10px; color:#85958F;">Perkiraan Panen</div>
                            <div style="font-weight:600; color:#172B29;">{{ $item->harvest_estimated_date ? $item->harvest_estimated_date->timezone('Asia/Jakarta')->translatedFormat('d M Y') : '-' }}</div>
                        </div>
                        <div>
                            <div style="font-size:10px; color:#85958F;">Kuota Tersedia</div>
                            <div>
                                <strong style="color:{{ $item->available_kg > 0 ? '#0C8D65' : '#B91C1C' }};">{{ number_format($item->available_kg, 0, ',', '.') }}</strong>
                                <span style="color:#85958F; font-size:10px;">/ {{ number_format($item->estimated_harvest_kg, 0, ',', '.') }} kg</span>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex; justify-content:flex-end;">
                        <form action="{{ route('admin.komoditas.destroy', $item->id) }}" method="POST"
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus komoditas {{ $item->commodity_name }} ini?');"
                              style="width:100%; margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="width:100%; justify-content:center; padding:8px; font-size:11px; min-height:auto;">
                                <i class="fa-regular fa-trash-can"></i> Hapus Komoditas
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection
