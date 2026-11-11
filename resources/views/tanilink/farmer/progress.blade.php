@extends('layouts.sidebar')

@section('title', 'Detail Pesanan & Verifikasi DP - ' . $order->order_code . ' - TaniLink')

@section('breadcrumb')
    <a href="{{ route('petani.pesanan') }}" style="color:#07946C; text-decoration:none; font-weight:700;">Pesanan Masuk</a>
    <span style="margin:0 5px; color:#A2AEAA;">&rsaquo;</span>
    <span style="color:#50695E;">{{ $order->order_code }}</span>
    <span style="margin:0 5px; color:#A2AEAA;">&rsaquo;</span>
    <strong>Detail &amp; Verifikasi DP</strong>
@endsection

@push('styles')
<style>
    .progress-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.5fr) minmax(320px, 1fr);
        gap: 20px;
    }
    .progress-contract-grid {
        border-top: 1px solid #EEF2F0;
        padding-top: 14px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        font-size: 11.5px;
    }
    .progress-phase-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        font-size: 11px;
    }
    .progress-action-row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .progress-headline {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 22px;
        flex-wrap: wrap;
        gap: 14px;
    }
    @media (max-width: 992px) {
        .progress-layout {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 640px) {
        .progress-headline {
            flex-direction: column;
            align-items: flex-start;
        }
        .progress-contract-grid {
            grid-template-columns: 1fr;
        }
        .progress-phase-grid {
            grid-template-columns: 1fr 1fr;
        }
        .progress-action-row {
            flex-direction: column;
            width: 100%;
        }
        .progress-action-row form,
        .progress-action-row button,
        .progress-action-row a {
            width: 100%;
        }
        .progress-action-row .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div x-data="{ showRejectDpModal: false }">

    {{-- Headline --}}
    <div class="progress-headline">
        <div>
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px;">
                <span style="font-size:11px; font-weight:800; background:#E9FBF3; color:#0C8D65; padding:3px 9px; border-radius:6px;">{{ $order->order_code }}</span>
                <span style="font-size:12px; color:#6B8077;">Dipesan oleh <strong>{{ $order->buyer->name ?? 'Konsumen' }}</strong></span>
            </div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 4px;">
                {{ $order->commodityListing->commodity_name ?? 'Komoditas' }} &bull; {{ number_format($order->quantity_kg, 0, ',', '.') }} kg
            </h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">
                Tinjau permintaan kontrak, verifikasi pembayaran uang muka (DP), dan pantau batch panen terkait.
            </p>
        </div>

        <div>
            @if($order->status === 'menunggu_konfirmasi')
                <span style="display:inline-flex; align-items:center; padding:6px 14px; border-radius:8px; background:#FEF3C7; color:#D97706; font-weight:800; font-size:12px;">
                    Menunggu Persetujuan Petani
                </span>
            @elseif($order->status === 'menunggu_pembayaran_dp')
                <span style="display:inline-flex; align-items:center; padding:6px 14px; border-radius:8px; background:#EFF6FF; color:#1D4ED8; font-weight:800; font-size:12px;">
                    Menunggu Pembayaran DP
                </span>
            @elseif($order->status === 'menunggu_verifikasi_dp')
                <span style="display:inline-flex; align-items:center; padding:6px 14px; border-radius:8px; background:#FDF4FF; color:#9333EA; font-weight:800; font-size:12px;">
                    Menunggu Pemeriksaan Pembayaran
                </span>
            @elseif(in_array($order->status, ['pesanan_aktif', 'dikonfirmasi', 'dp_cair', 'tanam_berjalan']))
                <span style="display:inline-flex; align-items:center; padding:6px 14px; border-radius:8px; background:#ECFDF5; color:#047857; font-weight:800; font-size:12px;">
                    Pesanan Aktif (DP Terkonfirmasi)
                </span>
            @elseif($order->status === 'siap_panen')
                <span style="display:inline-flex; align-items:center; padding:6px 14px; border-radius:8px; background:#E0F2FE; color:#0369A1; font-weight:800; font-size:12px;">
                    Siap Panen
                </span>
            @elseif($order->status === 'selesai')
                <span style="display:inline-flex; align-items:center; padding:6px 14px; border-radius:8px; background:#E9F9F2; color:#0C8D65; font-weight:800; font-size:12px;">
                    Selesai
                </span>
            @elseif($order->status === 'dibatalkan')
                <span style="display:inline-flex; align-items:center; padding:6px 14px; border-radius:8px; background:#FEE2E2; color:#B91C1C; font-weight:800; font-size:12px;">
                    Ditolak / Batal
                </span>
            @endif
        </div>
    </div>

    {{-- Grid 2 Kolom --}}
    <div class="progress-layout">

        {{-- KIRI: Verifikasi Pembayaran DP & Batch Panen --}}
        <div style="display:flex; flex-direction:column; gap:20px;">

            {{-- SECTION 1: VERIFIKASI PEMBAYARAN DP --}}
            <div class="card" style="padding:24px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; flex-wrap:wrap; gap:8px;">
                    <div>
                        <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Alur Pembayaran Uang Muka (DP 30%)</h2>
                        <p style="font-size:11px; color:#91A19B; margin:0;">Pembayaran DP ditransfer langsung oleh konsumen ke rekening bank Anda.</p>
                    </div>
                    <span style="font-size:15px; font-weight:850; color:#0A765A;">
                        Rp {{ number_format($order->dp_amount, 0, ',', '.') }}
                    </span>
                </div>

                {{-- Status Step 1: Menunggu Persetujuan Petani --}}
                @if($order->status === 'menunggu_konfirmasi')
                <div style="padding:16px; border-radius:12px; background:#FFFBEB; border:1px solid #FDE68A; margin-bottom:16px;">
                    <div style="font-size:13px; font-weight:800; color:#B45309; margin-bottom:4px;">
                        <i class="fa-solid fa-bell"></i> Pengajuan Pre-Order Baru
                    </div>
                    <p style="font-size:11.5px; color:#92400E; margin:0 0 14px; line-height:1.5;">
                        Konsumen mengajukan pre-order sebanyak <b>{{ number_format($order->quantity_kg, 0, ',', '.') }} kg</b>.
                        Silakan setujui pesanan ini agar konsumen dapat melanjutkan pembayaran DP 30% ke rekening Anda.
                    </p>
                    <div class="progress-action-row">
                        <form action="{{ route('petani.pesanan.confirm', $order->id) }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Setujui pesanan ini dan terbitkan instruksi pembayaran DP ke konsumen?')">
                                <i class="fa-solid fa-check"></i> Setujui Pesanan
                            </button>
                        </form>
                        <form action="{{ route('petani.pesanan.reject', $order->id) }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Tolak pengajuan pre-order ini? Alokasi panen akan dikembalikan.')">
                                <i class="fa-solid fa-xmark"></i> Tolak Pesanan
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Status Step 2: Menunggu Transfer DP dari Konsumen --}}
                @elseif($order->status === 'menunggu_pembayaran_dp')
                <div style="padding:16px; border-radius:12px; background:#EFF6FF; border:1px solid #BFDBFE; margin-bottom:16px;">
                    <div style="font-size:13px; font-weight:800; color:#1D4ED8; margin-bottom:4px;">
                        <i class="fa-regular fa-clock"></i> Menunggu Konsumen Mentransfer DP
                    </div>
                    <p style="font-size:11.5px; color:#1E40AF; margin:0 0 10px; line-height:1.5;">
                        Pesanan telah Anda setujui. Konsumen sedang dalam proses transfer DP 30% (<b>Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</b>) ke rekening:
                    </p>
                    <div style="background:#fff; border:1px solid #DBEAFE; border-radius:10px; padding:12px; font-size:12px; color:#1E3A8A;">
                        <div>Bank: <b>{{ $order->farmer_bank_name ?: ($order->farmer->bank_name ?? 'Bank BRI') }}</b></div>
                        <div>No. Rekening: <b>{{ $order->farmer_bank_account ?: ($order->farmer->bank_account ?? '-') }}</b></div>
                        <div>Nama Pemilik Rekening: <b>{{ $order->farmer_bank_holder ?: ($order->farmer->bank_holder ?: ($order->farmer->name ?? 'Petani')) }}</b></div>
                    </div>
                    @if($order->dp_rejection_note)
                    <div style="margin-top:10px; font-size:11px; color:#DC2626; background:#FEF2F2; padding:8px 12px; border-radius:8px;">
                        <b>Catatan Permintaan Perbaikan Terakhir:</b> {{ $order->dp_rejection_note }}
                    </div>
                    @endif
                </div>

                {{-- Status Step 3: Konsumen Sudah Upload Bukti -> Petani Periksa Mutasi --}}
                @elseif($order->status === 'menunggu_verifikasi_dp')
                <div style="padding:18px; border-radius:14px; background:#FAF5FF; border:1.5px solid #E9D5FF; margin-bottom:16px;">
                    <div style="font-size:14px; font-weight:850; color:#7E22CE; margin-bottom:6px;">
                        <i class="fa-solid fa-file-invoice-dollar"></i> Konsumen Telah Mengunggah Bukti Pembayaran DP!
                    </div>
                    <p style="font-size:12px; color:#6B21A8; margin:0 0 14px; line-height:1.55;">
                        Silakan buka bukti transfer di bawah ini, lalu periksa mutasi pada rekening bank Anda (<b>{{ $order->farmer_bank_name }} - {{ $order->farmer_bank_account }}</b>).
                    </p>

                    <div style="display:flex; align-items:center; justify-content:space-between; background:#fff; border:1px solid #E9D5FF; border-radius:12px; padding:12px 16px; margin-bottom:16px; flex-wrap:wrap; gap:10px;">
                        <div>
                            <div style="font-size:11px; color:#6B7280;">Waktu Pengunggahan Bukti:</div>
                            <div style="font-size:12px; font-weight:800; color:#172B29;">
                                <i class="fa-regular fa-clock"></i> {{ $order->dp_uploaded_at ? $order->dp_uploaded_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') : '-' }} WIB
                            </div>
                        </div>
                        <a href="{{ route('pesanan.bukti_dp', $order->id) }}" target="_blank" class="btn btn-secondary" style="font-size:12px;">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Bukti Transfer
                        </a>
                    </div>

                    <div class="progress-action-row">
                        <form action="{{ route('petani.pesanan.confirm_dp', $order->id) }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Konfirmasi bahwa uang DP sebesar Rp {{ number_format($order->dp_amount, 0, ',', '.') }} telah benar-benar masuk ke rekening Anda?')">
                                <i class="fa-solid fa-circle-check"></i> Konfirmasi DP Diterima
                            </button>
                        </form>

                        <button type="button" @click="showRejectDpModal = true" class="btn btn-danger">
                            <i class="fa-solid fa-triangle-exclamation"></i> Minta Perbaikan Bukti
                        </button>
                    </div>
                </div>

                {{-- Status Step 4: DP Terkonfirmasi & Pesanan Aktif --}}
                @elseif(in_array($order->status, ['pesanan_aktif', 'dikonfirmasi', 'dp_cair', 'tanam_berjalan', 'siap_panen', 'dalam_pengiriman', 'selesai']))
                <div style="padding:16px; border-radius:12px; background:#ECFDF5; border:1px solid #A7F3D0; margin-bottom:16px;">
                    <div style="font-size:13px; font-weight:850; color:#065F46; margin-bottom:4px;">
                        <i class="fa-solid fa-circle-check"></i> Pembayaran DP 30% Telah Dikonfirmasi
                    </div>
                    <p style="font-size:11.5px; color:#047857; margin:0 0 10px; line-height:1.5;">
                        Uang muka sebesar <b>Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</b> telah diverifikasi diterima pada
                        <b>{{ $order->dp_confirmed_at ? $order->dp_confirmed_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') . ' WIB' : 'Terkonfirmasi' }}</b>.
                    </p>
                    <div style="font-size:11px; color:#065F46; display:flex; justify-content:space-between; align-items:center; border-top:1px dashed #A7F3D0; padding-top:8px; flex-wrap:wrap; gap:8px;">
                        <span>Sisa pelunasan 70% (<b>Rp {{ number_format($order->escrow_locked_amount, 0, ',', '.') }}</b>) diselesaikan saat serah terima hasil panen.</span>
                        @if($order->dp_proof_path)
                        <a href="{{ route('pesanan.bukti_dp', $order->id) }}" target="_blank" style="color:#047857; font-weight:800; text-decoration:none;">
                            Lihat Bukti Transfer &rsaquo;
                        </a>
                        @endif
                    </div>
                </div>

                {{-- Status Batal / Ditolak --}}
                @elseif($order->status === 'dibatalkan')
                <div style="padding:16px; border-radius:12px; background:#FEF2F2; border:1px solid #FECACA; margin-bottom:16px;">
                    <div style="font-size:13px; font-weight:800; color:#991B1B; margin-bottom:4px;">
                        <i class="fa-solid fa-circle-xmark"></i> Pesanan Ditolak / Dibatalkan
                    </div>
                    <p style="font-size:11.5px; color:#7F1D1D; margin:0; line-height:1.5;">
                        Alasan: {{ $order->rejection_reason ?: 'Kapasitas lahan atau jadwal belum memungkinkan.' }}
                    </p>
                </div>
                @endif

                {{-- Rincian Nilai Kontrak --}}
                <div class="progress-contract-grid">
                    <div>
                        <span style="color:#8A9A94; display:block;">Total Nilai Pesanan</span>
                        <b style="color:#172B29; font-size:13px;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</b>
                    </div>
                    <div>
                        <span style="color:#8A9A94; display:block;">Uang Muka (DP 30%)</span>
                        <b style="color:#0A765A; font-size:13px;">Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</b>
                    </div>
                    <div>
                        <span style="color:#8A9A94; display:block;">Sisa Pelunasan (70%)</span>
                        <b style="color:#1E40AF; font-size:13px;">Rp {{ number_format($order->escrow_locked_amount, 0, ',', '.') }}</b>
                    </div>
                </div>
            </div>

            {{-- SECTION 2: BATCH PANEN & PENGELOLAAN TERPUSAT --}}
            @if($order->harvestBatch)
            <div class="card" style="padding:24px;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px; flex-wrap:wrap; gap:12px;">
                    <div>
                        <span style="font-size:10px; font-weight:800; color:#0C8D65; background:#E9FBF3; padding:2px 8px; border-radius:5px;">
                            {{ $order->harvestBatch->batch_code }}
                        </span>
                        <h2 style="font-size:16px; font-weight:850; margin:6px 0 3px; color:#172B29;">
                            {{ $order->harvestBatch->batch_name }}
                        </h2>
                        <p style="font-size:11px; color:#91A19B; margin:0;">
                            Perkiraan Panen: <b>{{ $order->harvestBatch->estimated_harvest_date ? $order->harvestBatch->estimated_harvest_date->timezone('Asia/Jakarta')->translatedFormat('d M Y') : '-' }}</b>
                        </p>
                    </div>
                    <a href="{{ route('petani.batch.progress', $order->harvestBatch->id) }}" class="btn btn-primary" style="font-size:12px;">
                        <i class="fa-solid fa-sprout"></i> Kelola Perkembangan Batch
                    </a>
                </div>

                <div style="background:#F8FCFA; border:1px solid #DFEAE5; border-radius:12px; padding:14px; font-size:11.5px; line-height:1.6; color:#475569; margin-bottom:16px;">
                    <i class="fa-solid fa-circle-info" style="color:#0C8D65;"></i> <b>Pengelolaan Terpusat per Batch:</b>
                    Pesanan ini tergabung dalam batch tanaman bersama {{ $order->harvestBatch->preOrders->count() }} pesanan lainnya.
                    Anda cukup memperbarui catatan dan foto dokumentasi pada halaman batch panen, dan seluruh pemesan otomatis menerima kabar perkembangan yang sama.
                </div>

                {{-- Status 4 Fase Singkat --}}
                <div class="progress-phase-grid">
                    @foreach($order->harvestBatch->phase_timeline as $ph)
                    <div style="border-radius:12px; border:1px solid {{ $ph['status'] === 'selesai' ? '#A7F3D0' : ($ph['status'] === 'berlangsung' ? '#05C793' : '#E5ECEA') }};
                                background:{{ $ph['status'] === 'selesai' ? '#F0FDF8' : ($ph['status'] === 'berlangsung' ? '#E6FFFA' : '#FAFCFB') }};
                                overflow:hidden; display:flex; flex-direction:column; justify-content:space-between;">
                        <div style="height:64px; width:100%; position:relative; overflow:hidden; background:#E2E8F0;">
                            <img src="{{ $ph['image_url'] }}"
                                 alt="{{ $ph['name'] }}"
                                 onerror="this.onerror=null; this.src='{{ asset('images/phases/fase-4-vegetatif.jpg') }}';"
                                 style="width:100%; height:100%; object-fit:cover;">
                            <div style="position:absolute; inset:0; background:linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.5) 100%);"></div>
                            <span style="position:absolute; top:6px; left:6px; width:20px; height:20px; border-radius:6px; display:grid; place-items:center; font-size:10px; font-weight:850;
                                {{ $ph['status'] === 'selesai' ? 'background:#05C793; color:#fff;' : ($ph['status'] === 'berlangsung' ? 'background:#0E7490; color:#fff;' : 'background:#E2E8F0; color:#64748B;') }}">
                                {{ $ph['status'] === 'selesai' ? '✓' : $ph['index'] }}
                            </span>
                        </div>
                        <div style="padding:8px 10px;">
                            <div style="font-size:11px; font-weight:800; color:#172B29;">{{ $ph['short'] }}</div>
                            <div style="font-size:9.5px; margin-top:2px;
                                {{ $ph['status'] === 'selesai' ? 'color:#047857; font-weight:800;' : ($ph['status'] === 'berlangsung' ? 'color:#0E7490; font-weight:800;' : 'color:#94A3B8;') }}">
                                {{ $ph['status'] === 'selesai' ? '✓ Selesai' : ($ph['status'] === 'berlangsung' ? '● Berlangsung' : 'Menunggu') }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- KANAN: Data Pembeli & Informasi Pengiriman --}}
        <div style="display:flex; flex-direction:column; gap:20px;">

            {{-- Card Identitas Pembeli --}}
            <div class="card" style="padding:22px;">
                <h3 style="font-size:14px; font-weight:850; margin:0 0 14px; color:#172B29;">Identitas Pemesan</h3>

                <div style="display:flex; flex-direction:column; gap:10px; font-size:11.5px;">
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid #EEF2F0; padding-bottom:8px;">
                        <span style="color:#8A9A94;">Nama Pemesan</span>
                        <b style="color:#172B29;">{{ $order->buyer->name ?? 'Konsumen' }}</b>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid #EEF2F0; padding-bottom:8px;">
                        <span style="color:#8A9A94;">Usaha / Resto</span>
                        <b style="color:#172B29;">{{ $order->buyer->organization ?? '-' }}</b>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid #EEF2F0; padding-bottom:8px;">
                        <span style="color:#8A9A94;">WhatsApp</span>
                        @if($order->buyer && $order->buyer->phone)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->buyer->phone) }}" target="_blank"
                           style="color:#0C8D65; font-weight:800; text-decoration:none;">
                            <i class="fa-brands fa-whatsapp"></i> {{ $order->buyer->phone }}
                        </a>
                        @else
                        <span style="color:#94A3B8;">-</span>
                        @endif
                    </div>
                    <div style="border-bottom:1px solid #EEF2F0; padding-bottom:8px;">
                        <span style="color:#8A9A94; display:block; margin-bottom:2px;">Alamat Penerimaan Pasokan</span>
                        <div style="color:#172B29; font-weight:600;">
                            {{ $order->buyer->location ?? '-' }}
                        </div>
                    </div>
                    @if($order->notes)
                    <div style="padding-top:4px;">
                        <span style="color:#8A9A94; display:block; margin-bottom:2px;">Catatan Khusus Pemesan</span>
                        <div style="color:#172B29; background:#F8FCFA; border:1px solid #E5ECEA; border-radius:8px; padding:8px 10px; font-size:11px;">
                            "{{ $order->notes }}"
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Card Rekening Bank Petani --}}
            <div class="card" style="padding:22px;">
                <h3 style="font-size:14px; font-weight:850; margin:0 0 10px; color:#172B29;">Rekening Penerima DP</h3>
                <p style="font-size:11px; color:#8A9A94; margin:0 0 14px;">Rekening resmi kelompok tani Anda yang digunakan pada pesanan ini.</p>

                <div style="background:#F8FCFA; border:1px solid #DFEAE5; border-radius:10px; padding:12px; font-size:12px;">
                    <div style="color:#64748B; font-size:10.5px;">NAMA BANK</div>
                    <b style="color:#172B29;">{{ $order->farmer_bank_name ?: ($order->farmer->bank_name ?? 'Bank BRI') }}</b>

                    <div style="color:#64748B; font-size:10.5px; margin-top:8px;">NOMOR REKENING</div>
                    <b style="color:#172B29; font-size:14px;">{{ $order->farmer_bank_account ?: ($order->farmer->bank_account ?? '-') }}</b>

                    <div style="color:#64748B; font-size:10.5px; margin-top:8px;">NAMA PEMILIK REKENING</div>
                    <b style="color:#172B29;">{{ $order->farmer_bank_holder ?: ($order->farmer->bank_holder ?: ($order->farmer->name ?? 'Petani')) }}</b>
                </div>
            </div>

        </div>

    </div>

    {{-- MODAL MINTA PERBAIKAN BUKTI TRANSFER --}}
    <div x-cloak x-show="showRejectDpModal"
         style="position:fixed; inset:0; z-index:100; display:flex; align-items:center; justify-content:center; background:rgba(21,40,31,0.52); padding:16px;">
        <div style="background:#fff; border-radius:20px; width:480px; max-width:95%; max-height:90vh; overflow-y:auto; padding:26px; box-shadow:0 35px 90px rgba(16,35,29,0.4);"
             @click.away="showRejectDpModal = false">

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
                <h2 style="font-size:16px; font-weight:850; margin:0; color:#172B29;">Minta Perbaikan Bukti Transfer</h2>
                <button type="button" @click="showRejectDpModal = false" style="background:none; border:none; font-size:16px; cursor:pointer; color:#8A9D95;">✕</button>
            </div>

            <p style="font-size:11.5px; color:#6B7280; margin:0 0 14px;">
                Tuliskan alasan mengapa bukti transfer tidak dapat diverifikasi (misalnya: nominal tidak sesuai, nama pengirim buram, atau dana belum masuk mutasi bank).
            </p>

            <form action="{{ route('petani.pesanan.reject_dp', $order->id) }}" method="POST">
                @csrf
                <div style="margin-bottom:16px;">
                    <label class="form-label">Alasan Perbaikan <span class="req">*</span></label>
                    <textarea name="rejection_note" rows="3" required placeholder="Contoh: Bukti transfer terpotong / belum terlihat pada mutasi bank per tanggal hari ini..."
                              class="form-input" style="height:auto; resize:vertical;"></textarea>
                </div>

                <div style="display:flex; gap:10px; justify-content:flex-end;">
                    <button type="button" @click="showRejectDpModal = false" class="btn btn-secondary">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        Kirim Permintaan Perbaikan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
