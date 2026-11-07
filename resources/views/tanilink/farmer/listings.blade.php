@extends('layouts.sidebar')

@section('title', 'Komoditas Saya - TaniLink')

@push('styles')
<style>
    .listings-headline {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 24px;
        gap: 16px;
    }
    .listings-metrics-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    .desktop-listings-table {
        display: block;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .mobile-listings-list {
        display: none;
    }

    @media (max-width: 768px) {
        .listings-metrics-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .listings-metrics-grid > div:last-child {
            grid-column: span 2;
        }
    }

    @media (max-width: 640px) {
        .listings-headline {
            flex-direction: column;
            align-items: stretch;
            gap: 14px;
            margin-bottom: 18px;
        }
        .listings-headline h1 {
            font-size: 21px !important;
            letter-spacing: -0.6px !important;
            margin-bottom: 4px !important;
        }
        .listings-headline a {
            width: 100%;
            justify-content: center;
            box-sizing: border-box;
            padding: 11px 16px !important;
        }
        .desktop-listings-table {
            display: none !important;
        }
        .mobile-listings-list {
            display: flex !important;
            flex-direction: column;
            gap: 12px;
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

    {{-- Headline --}}
    <div class="listings-headline">
        <div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Komoditas Saya</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">Kelola seluruh penawaran komoditas, stok alokasi, dan estimasi panen.</p>
        </div>
        <a href="{{ route('petani.komoditas.create') }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 16px; border-radius:11px; background:#05C793; color:#053B2D; font-size:12px; font-weight:800; text-decoration:none; box-shadow:0 3px 10px rgba(5,199,147,0.2); flex-shrink:0;">
            <i class="fa-solid fa-plus"></i> Tambah Komoditas
        </a>
    </div>

    {{-- 3 Metric Cards --}}
    <div class="listings-metrics-grid">
        <div class="card" style="padding:18px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Jumlah Komoditas</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:15px;"><i class="fa-solid fa-seedling"></i></span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">{{ $myListings->count() }}</div>
            <div style="font-size:10px; color:#7B9187;">Komoditas aktif terdaftar</div>
        </div>

        <div class="card" style="padding:18px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Kapasitas Estimasi</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:15px;"><i class="fa-solid fa-weight-hanging"></i></span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">{{ number_format($totalCropKg, 0, ',', '.') }} kg</div>
            <div style="font-size:10px; color:#7B9187;">Total estimasi panen</div>
        </div>

        <div class="card" style="padding:18px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Total Nilai Pesanan</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:15px;"><i class="fa-solid fa-receipt"></i></span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">Rp {{ number_format($dpReceived, 0, ',', '.') }}</div>
            <div style="font-size:10px; color:#7B9187;">Akumulasi nilai pesanan aktif</div>
        </div>
    </div>

    {{-- Usulan Kategori Saya (Jika ada) --}}
    @if(isset($myProposals) && $myProposals->isNotEmpty())
    <div class="card" style="padding:20px 24px; margin-bottom:24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; flex-wrap:wrap; gap:8px;">
            <h2 style="font-size:14px; font-weight:850; margin:0; color:#172B29;">Status Pengajuan Kategori Baru</h2>
            <span style="font-size:11px; color:#8A9A94;">Ditinjau oleh Admin TaniLink</span>
        </div>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(220px, 1fr)); gap:10px;">
            @foreach($myProposals as $prop)
            <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 14px; background:#F8FCFA; border:1px solid #E5ECEA; border-radius:10px;">
                <div>
                    <div style="font-size:12px; font-weight:800; color:#172B29;">{{ $prop->name }}</div>
                    <div style="font-size:10px; color:#8A9A94; margin-top:2px;">{{ $prop->created_at ? $prop->created_at->format('d M Y') : '-' }}</div>
                </div>
                <div>
                    @if($prop->status === 'disetujui')
                        <span style="font-size:10px; font-weight:800; color:#0C8D65; background:#E9F9F2; padding:3px 9px; border-radius:6px;">Disetujui</span>
                    @elseif($prop->status === 'ditolak')
                        <span style="font-size:10px; font-weight:800; color:#DC2626; background:#FEE2E2; padding:3px 9px; border-radius:6px;" title="{{ $prop->admin_note }}">Ditolak</span>
                    @else
                        <span style="font-size:10px; font-weight:800; color:#D97706; background:#FEF3C7; padding:3px 9px; border-radius:6px;">Menunggu Tinjauan</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Listings Card --}}
    <div class="card" style="padding:22px;">
        <div style="margin-bottom:18px;">
            <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Daftar Komoditas</h2>
            <p style="font-size:11px; color:#91A19B; margin:0;">Setiap penawaran dapat diedit atau dihapus secara mandiri.</p>
        </div>

        @if($myListings->isEmpty())
        <div style="text-align:center; padding:50px 20px; color:#8A9D95;">
            <i class="fa-solid fa-wheat-awn" style="font-size:38px; color:#A9CBBB; margin-bottom:12px;"></i>
            <h3 style="font-size:15px; font-weight:850; color:#172B29; margin-bottom:4px;">Belum Ada Komoditas</h3>
            <p style="font-size:12px; margin-bottom:16px;">Publikasikan penawaran tanaman Anda agar pembeli konsumen dapat mengajukan pemesanan.</p>
            <a href="{{ route('petani.komoditas.create') }}" style="display:inline-flex; align-items:center; gap:6px; padding:10px 18px; border-radius:11px; background:#05C793; color:#053B2D; font-size:12px; font-weight:800; text-decoration:none;">
                <i class="fa-solid fa-plus"></i> Tambah Komoditas Baru
            </a>
        </div>
        @else

        {{-- Desktop Table View (>= 640px) --}}
        <div class="desktop-listings-table">
            <table style="width:100%; border-collapse:collapse; text-align:left; min-width:600px;">
                <thead>
                    <tr>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">KOMODITAS</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">HARGA / KG</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">TERSEDIA</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">EST. PANEN</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">STATUS</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px; text-align:right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($myListings as $listing)
                    <tr style="border-bottom:1px solid #EEF2F0;">
                        <td style="font-size:11px; font-weight:650; padding:15px 10px;">
                            <div style="display:flex; align-items:center; gap:10px;">
                                @if($listing->image_url)
                                <div style="width:38px; height:38px; border-radius:8px; overflow:hidden; border:1px solid #E5ECEA; flex-shrink:0;">
                                    <img src="{{ str_starts_with($listing->image_url, 'http') ? $listing->image_url : asset($listing->image_url) }}" alt="" style="width:100%; height:100%; object-fit:cover;">
                                </div>
                                @endif
                                <div>
                                    <b style="font-weight:850; color:#183B30;">{{ $listing->commodity_name }}</b>
                                    <div style="font-size:10px; color:#85958F; margin-top:2px;">{{ $listing->category }} &bull; {{ $listing->variety ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:11px; font-weight:800; color:#0A765A; padding:15px 10px;">
                            Rp {{ number_format($listing->price_per_kg, 0, ',', '.') }}
                        </td>
                        <td style="font-size:11px; font-weight:650; padding:15px 10px;">
                            {{ number_format($listing->available_kg, 0, ',', '.') }} kg
                        </td>
                        <td style="font-size:11px; font-weight:650; padding:15px 10px;">
                            {{ $listing->harvest_estimated_date ? \Carbon\Carbon::parse($listing->harvest_estimated_date)->format('d M Y') : '-' }}
                        </td>
                        <td style="font-size:11px; padding:15px 10px;">
                            @if($listing->status === 'booked' || $listing->available_kg <= 0)
                                <span style="display:inline-flex; align-items:center; padding:5px 9px; border-radius:7px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:10px;">Terkontrak</span>
                            @else
                                <span style="display:inline-flex; align-items:center; padding:5px 9px; border-radius:7px; background:#E9F9F2; color:#0C8D65; font-weight:800; font-size:10px;">Aktif</span>
                            @endif
                        </td>
                        <td style="font-size:11px; padding:15px 10px; text-align:right;">
                            <div style="display:inline-flex; align-items:center; gap:6px; justify-content:flex-end;">
                                <a href="{{ route('petani.komoditas.edit', $listing->id) }}"
                                   style="display:inline-flex; align-items:center; gap:4px; padding:6px 12px; border-radius:8px; background:#EBF9F2; border:1px solid #C6EEDC; color:#078362; font-weight:800; font-size:11px; text-decoration:none;">
                                    <i class="fa-regular fa-pen-to-square"></i> Edit
                                </a>
                                @php $firstBatch = $listing->harvestBatches->first(); @endphp
                                @if($firstBatch)
                                <a href="{{ route('petani.batch.progress', $firstBatch->id) }}"
                                   style="display:inline-flex; align-items:center; gap:4px; padding:6px 12px; border-radius:8px; background:#EFF6FF; border:1px solid #BFDBFE; color:#1D4ED8; font-weight:800; font-size:11px; text-decoration:none;">
                                    <i class="fa-solid fa-seedling"></i> Batch
                                </a>
                                @endif
                                <form action="{{ route('petani.komoditas.destroy', $listing->id) }}" method="POST"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus komoditas {{ $listing->commodity_name }}?');"
                                      style="display:inline; margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            style="display:inline-flex; align-items:center; gap:4px; padding:6px 12px; border-radius:8px; background:#FEE2E2; border:1px solid #FECACA; color:#B91C1C; font-weight:800; font-size:11px; cursor:pointer;">
                                        <i class="fa-regular fa-trash-can"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile Commodity Cards (< 640px) --}}
        <div class="mobile-listings-list">
            @foreach($myListings as $listing)
            <div style="border:1px solid #E5ECEA; border-radius:14px; padding:14px; background:#F8FCFA; display:flex; flex-direction:column; gap:12px;">
                <div style="display:flex; gap:12px; align-items:flex-start;">
                    @if($listing->image_url)
                    <div style="width:56px; height:56px; border-radius:10px; overflow:hidden; border:1px solid #E5ECEA; flex-shrink:0;">
                        <img src="{{ str_starts_with($listing->image_url, 'http') ? $listing->image_url : asset($listing->image_url) }}" alt="" style="width:100%; height:100%; object-fit:cover;">
                    </div>
                    @endif
                    <div style="flex:1; min-width:0;">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:6px;">
                            <b style="font-size:14px; font-weight:850; color:#183B30; line-height:1.3;">{{ $listing->commodity_name }}</b>
                            @if($listing->status === 'booked' || $listing->available_kg <= 0)
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:10px; flex-shrink:0;">Terkontrak</span>
                            @else
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#E9F9F2; color:#0C8D65; font-weight:800; font-size:10px; flex-shrink:0;">Aktif</span>
                            @endif
                        </div>
                        <div style="font-size:11px; color:#6B8077; margin-top:2px;">{{ $listing->category }} &bull; {{ $listing->variety ?? '-' }}</div>
                        <div style="font-size:14px; font-weight:900; color:#0A765A; margin-top:4px;">
                            Rp {{ number_format($listing->price_per_kg, 0, ',', '.') }} <span style="font-size:10px; font-weight:500; color:#85958F;">/kg</span>
                        </div>
                    </div>
                </div>

                <div style="display:flex; justify-content:space-between; align-items:center; background:#fff; border:1px solid #EEF2F0; border-radius:10px; padding:10px 12px; font-size:11px;">
                    <div>
                        <span style="color:#8A9A94; font-size:10px; display:block;">Sisa Tersedia:</span>
                        <b style="color:#172B29;">{{ number_format($listing->available_kg, 0, ',', '.') }} kg</b>
                    </div>
                    <div style="text-align:right;">
                        <span style="color:#8A9A94; font-size:10px; display:block;">Est. Panen:</span>
                        <b style="color:#172B29;">{{ $listing->harvest_estimated_date ? \Carbon\Carbon::parse($listing->harvest_estimated_date)->format('d M Y') : '-' }}</b>
                    </div>
                </div>

                <div style="display:flex; gap:8px; align-items:center; justify-content:flex-end; border-top:1px dashed #E5ECEA; padding-top:10px;">
                    <a href="{{ route('petani.komoditas.edit', $listing->id) }}"
                       style="flex:1; display:inline-flex; align-items:center; justify-content:center; gap:4px; padding:8px 10px; border-radius:8px; background:#EBF9F2; border:1px solid #C6EEDC; color:#078362; font-weight:800; font-size:11.5px; text-decoration:none;">
                        <i class="fa-regular fa-pen-to-square"></i> Edit
                    </a>
                    @php $firstBatch = $listing->harvestBatches->first(); @endphp
                    @if($firstBatch)
                    <a href="{{ route('petani.batch.progress', $firstBatch->id) }}"
                       style="flex:1; display:inline-flex; align-items:center; justify-content:center; gap:4px; padding:8px 10px; border-radius:8px; background:#EFF6FF; border:1px solid #BFDBFE; color:#1D4ED8; font-weight:800; font-size:11.5px; text-decoration:none;">
                        <i class="fa-solid fa-seedling"></i> Batch
                    </a>
                    @endif
                    <form action="{{ route('petani.komoditas.destroy', $listing->id) }}" method="POST"
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus komoditas {{ $listing->commodity_name }}?');"
                          style="margin:0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                style="display:inline-flex; align-items:center; justify-content:center; gap:4px; padding:8px 12px; border-radius:8px; background:#FEE2E2; border:1px solid #FECACA; color:#B91C1C; font-weight:800; font-size:11.5px; cursor:pointer;">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

@endsection
