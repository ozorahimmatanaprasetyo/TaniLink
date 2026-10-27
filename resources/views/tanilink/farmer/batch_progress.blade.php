@extends('layouts.sidebar')

@section('title', 'Perkembangan Tanaman - ' . $batch->batch_name . ' - TaniLink')

@php
    $batchLabel = 'Panen ' . \Carbon\Carbon::parse($batch->estimated_harvest_date)->translatedFormat('F Y');
@endphp

@section('breadcrumb')
    <a href="{{ route('petani.komoditas') }}" style="color:#07946C; text-decoration:none; font-weight:700;">Komoditas Saya</a>
    <span style="margin:0 5px; color:#A2AEAA;">&rsaquo;</span>
    <a href="{{ route('petani.komoditas') }}" style="color:#07946C; text-decoration:none; font-weight:700;">{{ $batch->commodityListing->commodity_name }}</a>
    <span style="margin:0 5px; color:#A2AEAA;">&rsaquo;</span>
    <span style="color:#50695E;">{{ $batchLabel }}</span>
    <span style="margin:0 5px; color:#A2AEAA;">&rsaquo;</span>
    <strong>Perkembangan Tanaman</strong>
@endsection

@section('content')
<div x-data="{ showUploadModal: false, photoName: '' }">

    {{-- Headline & Action Buttons --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:22px; flex-wrap:wrap; gap:16px;">
        <div>
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                <span style="font-size:10px; font-weight:800; background:#E9FBF3; color:#0C8D65; padding:3px 9px; border-radius:6px; letter-spacing:0.5px;">{{ $batch->batch_code }}</span>
                <span style="font-size:11px; color:#8A9A94;">{{ $batch->commodityListing->commodity_name }} ({{ $batch->commodityListing->variety ?: 'Varietas Umum' }})</span>
            </div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">
                {{ $batchLabel }}
            </h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">
                Kelola 4 fase budidaya tanaman batch panen ini. Pembaruan otomatis dapat dipantau oleh seluruh pemesan terkait.
            </p>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            @if($batch->status !== 'selesai')
            <button type="button" @click="showUploadModal = true" class="btn btn-primary">
                <i class="fa-solid fa-camera"></i> Tambah Dokumentasi & Catatan
            </button>
            @endif

            @if($batch->current_phase_status === 'berlangsung')
            <form action="{{ route('petani.batch.phase.complete', $batch->id) }}" method="POST" style="margin:0;"
                  onsubmit="return confirm('Tandai fase saat ini ({{ $batch->current_phase }}) sebagai selesai? Pastikan Anda sudah mengunggah foto dan catatan aktual untuk fase ini.');">
                @csrf
                <button type="submit" class="btn" style="background:#0F766E; color:#ffffff; box-shadow:0 3px 10px rgba(15,118,110,0.25);">
                    <i class="fa-solid fa-circle-check"></i> Tandai Fase Selesai
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- 4 Metric Cards --}}
    <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:16px; margin-bottom:22px;">
        <div class="card" style="padding:18px 20px;">
            <div style="font-size:11px; color:#7B8C86; font-weight:750; margin-bottom:8px;">Perkiraan Waktu Panen</div>
            <div style="font-size:18px; font-weight:850; color:#172B29;">
                {{ $batch->estimated_harvest_date ? $batch->estimated_harvest_date->timezone('Asia/Jakarta')->translatedFormat('d M Y') : '-' }}
            </div>
            <div style="font-size:10px; color:#7B9187; margin-top:4px;">Perkiraan panen</div>
        </div>

        <div class="card" style="padding:18px 20px;">
            <div style="font-size:11px; color:#7B8C86; font-weight:750; margin-bottom:8px;">Total Perkiraan Panen</div>
            <div style="font-size:18px; font-weight:850; color:#172B29;">
                {{ number_format($batch->estimated_harvest_kg, 0, ',', '.') }} kg
            </div>
            <div style="font-size:10px; color:#7B9187; margin-top:4px;">Kapasitas panen batch ini</div>
        </div>

        <div class="card" style="padding:18px 20px;">
            <div style="font-size:11px; color:#7B8C86; font-weight:750; margin-bottom:8px;">Jumlah yang Sudah Dipesan</div>
            <div style="font-size:18px; font-weight:850; color:#0A765A;">
                {{ number_format($batch->ordered_kg, 0, ',', '.') }} kg
            </div>
            <div style="font-size:10px; color:#7B9187; margin-top:4px;">{{ $batch->preOrders->count() }} pesanan terikat</div>
        </div>

        <div class="card" style="padding:18px 20px;">
            <div style="font-size:11px; color:#7B8C86; font-weight:750; margin-bottom:8px;">Sisa Kuota Tersedia</div>
            <div style="font-size:18px; font-weight:850; color:{{ $batch->available_kg > 0 ? '#0C8D65' : '#B91C1C' }};">
                {{ number_format($batch->available_kg, 0, ',', '.') }} kg
            </div>
            <div style="font-size:10px; color:#7B9187; margin-top:4px;">{{ $batch->available_kg > 0 ? 'Dapat dipesan konsumen' : 'Kuota panen terpenuhi' }}</div>
        </div>
    </div>

    {{-- 4-Phase System Timeline Card --}}
    <div class="card" style="padding:24px; margin-bottom:22px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
            <div>
                <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Sistem 4 Fase Perkembangan Tanaman</h2>
                <p style="font-size:11px; color:#91A19B; margin:0;">Fase bergerak berurutan otomatis dari persiapan hingga siap panen.</p>
            </div>
            <span style="font-size:11px; font-weight:800; padding:5px 12px; border-radius:8px;
                {{ $batch->status === 'siap_panen' ? 'background:#E9F9F2; color:#0C8D65;' : 'background:#E8F9FC; color:#087894;' }}">
                {{ $batch->status === 'siap_panen' ? 'Siap Panen' : 'Fase Aktif: ' . $batch->current_phase }}
            </span>
        </div>

        {{-- 4 Phase Steps Grid --}}
        <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:14px;">
            @foreach($batch->phase_timeline as $ph)
            <div style="border:1.5px solid {{ $ph['status'] === 'berlangsung' ? '#05C793' : ($ph['status'] === 'selesai' ? '#A7F3D0' : '#E5ECEA') }};
                        background:{{ $ph['status'] === 'berlangsung' ? '#F0FDF8' : ($ph['status'] === 'selesai' ? '#F8FCFA' : '#FAFCFB') }};
                        border-radius:14px; padding:16px; position:relative;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:10px;">
                    <span style="width:26px; height:26px; border-radius:8px; display:grid; place-items:center; font-size:12px; font-weight:850;
                        {{ $ph['status'] === 'selesai' ? 'background:#05C793; color:#fff;' : ($ph['status'] === 'berlangsung' ? 'background:#0E7490; color:#fff;' : 'background:#E2E8F0; color:#64748B;') }}">
                        {{ $ph['status'] === 'selesai' ? '✓' : $ph['index'] }}
                    </span>
                    @if($ph['status'] === 'selesai')
                        <span style="font-size:10px; font-weight:800; color:#065F46; background:#D1FAE5; padding:2px 7px; border-radius:5px;">Selesai</span>
                    @elseif($ph['status'] === 'berlangsung')
                        <span style="font-size:10px; font-weight:800; color:#0E7490; background:#CFFAFE; padding:2px 7px; border-radius:5px;">Sedang Berlangsung</span>
                    @else
                        <span style="font-size:10px; font-weight:750; color:#94A3B8; background:#F1F5F9; padding:2px 7px; border-radius:5px;">Menunggu</span>
                    @endif
                </div>

                <div style="font-size:13px; font-weight:850; color:#172B29; margin-bottom:4px;">
                    {{ $ph['name'] }}
                </div>
                <p style="font-size:11px; color:#64748B; margin:0 0 10px; line-height:1.45;">
                    {{ $ph['desc'] }}
                </p>

                @if($ph['completed_at'])
                <div style="font-size:10px; color:#065F46; font-weight:700; border-top:1px dashed #A7F3D0; padding-top:8px;">
                    <i class="fa-regular fa-clock"></i> Selesai: {{ $ph['completed_at']->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} WIB
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- Grid 2 Kolom: Kiri Riwayat Dokumentasi, Kanan Pesanan Terikat --}}
    <div style="display:grid; grid-template-columns:minmax(0, 1.6fr) minmax(320px, 1fr); gap:20px;">

        {{-- Kiri: Riwayat Dokumentasi Lapangan --}}
        <div class="card" style="padding:24px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                <div>
                    <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Riwayat Dokumentasi Perkembangan</h2>
                    <p style="font-size:11px; color:#91A19B; margin:0;">Foto dan catatan lapangan aktual yang tersimpan di server.</p>
                </div>
                <span style="font-size:11px; color:#6B8077; font-weight:750;">{{ $batch->plantingProgress->count() }} pembaruan</span>
            </div>

            @if($batch->plantingProgress->isEmpty())
            <div style="text-align:center; padding:45px 20px; color:#8A9D95;">
                <div style="width:52px; height:52px; border-radius:14px; background:#F2F8F5; color:#05C793; display:inline-flex; align-items:center; justify-content:center; font-size:22px; margin-bottom:10px;">
                    <i class="fa-solid fa-camera"></i>
                </div>
                <h3 style="font-size:14px; font-weight:800; color:#172B29; margin:0 0 4px;">Belum Ada Pembaruan Perkembangan</h3>
                <p style="font-size:12px; margin:0 0 16px;">Unggah catatan dan foto kondisi tanaman terkini untuk memberikan transparansi kepada pemesan.</p>
                <button type="button" @click="showUploadModal = true" class="btn btn-primary" style="font-size:12px;">
                    <i class="fa-solid fa-camera"></i> Tambah Dokumentasi Pertama
                </button>
            </div>
            @else
            <div style="display:flex; flex-direction:column; gap:16px;">
                @foreach($batch->plantingProgress()->latest('reported_at')->get() as $prog)
                <div style="display:flex; gap:16px; border:1px solid #E5ECEA; border-radius:14px; padding:14px; background:#fff;">
                    @if($prog->photo_url)
                    <div style="width:120px; height:90px; border-radius:10px; overflow:hidden; flex-shrink:0; background:#F8FCFA;">
                        <img src="{{ str_starts_with($prog->photo_url, 'http') ? $prog->photo_url : asset($prog->photo_url) }}"
                             alt="{{ $prog->stage_name }}"
                             style="width:100%; height:100%; object-fit:cover;">
                    </div>
                    @endif
                    <div style="flex:1;">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:6px;">
                            <div>
                                <span style="font-size:10px; font-weight:800; color:#0C8D65; background:#E9F9F2; padding:2px 8px; border-radius:5px;">
                                    {{ $prog->stage_name }}
                                </span>
                            </div>
                            <span style="font-size:10.5px; color:#8A9A94; font-weight:600;">
                                <i class="fa-regular fa-clock"></i> {{ $prog->reported_at ? $prog->reported_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') : '-' }} WIB
                            </span>
                        </div>
                        <p style="font-size:12px; color:#374151; line-height:1.55; margin:0;">
                            {{ $prog->note }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Kanan: Daftar Pesanan Konsumen di Batch Ini --}}
        <div class="card" style="padding:24px;">
            <div style="margin-bottom:16px;">
                <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Pesanan Konsumen Terikat</h2>
                <p style="font-size:11px; color:#91A19B; margin:0;">Semua konsumen berikut otomatis melihat perkembangan batch ini.</p>
            </div>

            @if($batch->preOrders->isEmpty())
            <div style="text-align:center; padding:30px 10px; color:#8A9D95;">
                <p style="font-size:12px; margin:0;">Belum ada konsumen yang memesan batch panen ini.</p>
            </div>
            @else
            <div style="display:flex; flex-direction:column; gap:10px;">
                @foreach($batch->preOrders as $ord)
                <div style="border:1px solid #EEF2F0; border-radius:12px; padding:12px; background:#F8FCFA; display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <div style="font-size:12px; font-weight:850; color:#172B29;">{{ $ord->order_code }}</div>
                        <div style="font-size:11px; color:#50695E; margin-top:2px;">
                            {{ $ord->buyer->name ?? 'Konsumen' }} &bull; <b>{{ number_format($ord->quantity_kg, 0, ',', '.') }} kg</b>
                        </div>
                        <div style="font-size:10px; color:#8A9A94; margin-top:2px;">
                            DP: Rp {{ number_format($ord->dp_amount, 0, ',', '.') }}
                            @if($ord->dp_confirmed_at)
                                <span style="color:#0C8D65; font-weight:800;">(Lunas DP)</span>
                            @else
                                <span style="color:#D97706; font-weight:800;">(Menunggu DP)</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('petani.pesanan.show', $ord->id) }}" class="btn btn-secondary" style="font-size:11px; padding:6px 12px;">
                            Detail
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>

    {{-- MODAL UPLOAD DOKUMENTASI AKTUAL --}}
    <div x-cloak x-show="showUploadModal"
         style="position:fixed; inset:0; z-index:100; display:flex; align-items:center; justify-content:center; background:rgba(21,40,31,0.52); padding:16px;">
        <div style="background:#fff; border-radius:20px; width:520px; max-width:95%; padding:26px; box-shadow:0 35px 90px rgba(16,35,29,0.4);"
             @click.away="showUploadModal = false">

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                <div>
                    <h2 style="font-size:17px; font-weight:850; margin:0 0 3px; color:#172B29;">Dokumentasi Perkembangan Tanaman</h2>
                    <p style="font-size:11px; color:#8A9A94; margin:0;">Fase saat ini: <b>{{ $batch->current_phase }}</b></p>
                </div>
                <button type="button" @click="showUploadModal = false" style="background:none; border:none; font-size:16px; cursor:pointer; color:#8A9D95;">✕</button>
            </div>

            <form action="{{ route('petani.batch.progress.store', $batch->id) }}" method="POST" enctype="multipart/form-data"
                  x-data="{ isSubmitting: false }" @submit="if(isSubmitting) { $event.preventDefault(); return false; } isSubmitting = true;">
                @csrf
                <div style="display:flex; flex-direction:column; gap:14px;">
                    <div>
                        <label class="form-label">Catatan Aktual Perkembangan &amp; Kondisi Lapangan <span class="req">*</span></label>
                        <textarea name="note" rows="3" required placeholder="Contoh: Tanaman dalam kondisi sangat subur, pengairan berjalan lancar, cuaca sangat mendukung..."
                                  class="form-input" style="height:auto; resize:vertical;"></textarea>
                    </div>

                    <div>
                        <label class="form-label">Foto Dokumentasi Aktual di Lahan <span class="req">*</span></label>
                        <label for="batch-photo-input"
                               style="display:flex; flex-direction:column; align-items:center; justify-content:center; border:2px dashed #A9CBBB; border-radius:12px; padding:18px; background:#F8FCFA; text-align:center; color:#568977; font-size:11px; cursor:pointer;">
                            <i class="fa-solid fa-camera" style="font-size:24px; color:#568977; margin-bottom:6px;"></i>
                            <b style="color:#1C6F57;">Pilih Berkas Foto Lapangan</b>
                            <span style="font-size:10px; color:#87968F; margin-top:2px;">Format JPG, PNG, WEBP (Maksimal 2 MB)</span>
                            <span x-show="photoName" style="margin-top:6px; font-weight:800; color:#05C793; font-size:11px;" x-text="'Berkas terpilih: ' + photoName"></span>
                            <input id="batch-photo-input" type="file" name="photo" accept="image/*" required style="display:none;"
                                   @change="photoName = $event.target.files[0] ? $event.target.files[0].name : ''">
                        </label>
                    </div>

                    <div style="font-size:11px; color:#6B8077; background:#F8FCFA; border:1px solid #DFEAE5; border-radius:10px; padding:10px 12px;">
                        <i class="fa-regular fa-clock"></i> Tanggal dan waktu update dicatat otomatis oleh server (WIB).
                    </div>
                </div>

                <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:20px;">
                    <button type="button" @click="showUploadModal = false" class="btn btn-secondary">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary" :disabled="isSubmitting" :style="isSubmitting ? 'opacity:0.6; cursor:not-allowed;' : ''">
                        <span x-show="!isSubmitting">Simpan Dokumentasi</span>
                        <span x-show="isSubmitting">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

