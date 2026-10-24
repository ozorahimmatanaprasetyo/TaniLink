@extends('layouts.sidebar')

@section('title', 'Pantau Pesanan ' . $order->order_code . ' - TaniLink')

@section('breadcrumb')
    <a href="{{ route('pembeli.pesanan') }}" style="color:#07946C; text-decoration:none; font-weight:700;">Pesanan Saya</a>
    <span style="margin:0 5px; color:#A2AEAA;">&rsaquo;</span>
    <span style="color:#50695E;">{{ $order->order_code }}</span>
    <span style="margin:0 5px; color:#A2AEAA;">&rsaquo;</span>
    <strong>Pantau Pesanan &amp; Pembayaran</strong>
@endsection

@section('content')
<div x-data="{ proofFileName: '' }">

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:22px; flex-wrap:wrap; gap:14px;">
        <div>
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px;">
                <span style="font-size:11px; font-weight:800; background:#E9FBF3; color:#0C8D65; padding:3px 9px; border-radius:6px;">{{ $order->order_code }}</span>
                <span style="font-size:12px; color:#6B8077;">Petani: <strong>{{ $order->farmer->name ?? 'Mitra Petani' }}</strong> ({{ $order->farmer->organization ?? 'Kelompok Tani' }})</span>
            </div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 4px;">
                {{ $order->commodityListing->commodity_name ?? 'Komoditas' }} &bull; {{ number_format($order->quantity_kg, 0, ',', '.') }} kg
            </h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">
                Pantau status pembayaran DP dan perkembangan tanaman di lahan kebun petani.
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
    <div style="display:grid; grid-template-columns:minmax(0, 1.5fr) minmax(320px, 1fr); gap:20px;">

        {{-- KIRI: Pembayaran DP & Perkembangan Tanaman Batch --}}
        <div style="display:flex; flex-direction:column; gap:20px;">

            {{-- 1. KOTAK INSTRUKSI & UNGGAH PEMBAYARAN DP --}}
            <div class="card" style="padding:24px;">
                <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Status Pembayaran Uang Muka (DP 30%)</h2>
                <p style="font-size:11px; color:#91A19B; margin:0 0 16px;">Pembayaran DP ditransfer langsung ke rekening bank petani mitra.</p>

                {{-- Status A: Menunggu Persetujuan Petani --}}
                @if($order->status === 'menunggu_konfirmasi')
                <div style="padding:16px; border-radius:12px; background:#FFFBEB; border:1px solid #FDE68A;">
                    <div style="font-size:13px; font-weight:800; color:#B45309; margin-bottom:4px;">
                        <i class="fa-regular fa-clock"></i> Menunggu Persetujuan Petani
                    </div>
                    <p style="font-size:11.5px; color:#92400E; margin:0; line-height:1.5;">
                        Pengajuan pesanan Anda sedang ditinjau oleh petani mitra. Setelah petani menyetujui ketersediaan panen, nomor rekening resmi petani dan instruksi transfer DP 30% akan muncul di sini.
                    </p>
                </div>

                {{-- Status B: Menunggu Pembayaran DP (Petani sudah setujui) --}}
                @elseif($order->status === 'menunggu_pembayaran_dp')
                <div>
                    {{-- Warning jika ada revisi bukti --}}
                    @if($order->dp_rejection_note)
                    <div style="padding:14px; border-radius:10px; background:#FEF2F2; border:1px solid #FECACA; color:#991B1B; font-size:12px; margin-bottom:16px;">
                        <i class="fa-solid fa-triangle-exclamation"></i> <b>Permintaan Perbaikan dari Petani:</b><br>
                        {{ $order->dp_rejection_note }}
                        <div style="font-size:11px; color:#B91C1C; margin-top:4px;">Silakan periksa mutasi atau unggah ulang bukti transfer yang jelas.</div>
                    </div>
                    @endif

                    <div style="padding:18px; border-radius:14px; background:#EFF6FF; border:1px solid #BFDBFE; margin-bottom:18px;">
                        <div style="font-size:13px; font-weight:800; color:#1D4ED8; margin-bottom:8px;">
                            <i class="fa-solid fa-building-columns"></i> Rekening Tujuan Transfer Petani
                        </div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; background:#fff; border:1px solid #DBEAFE; border-radius:10px; padding:12px; font-size:12px;">
                            <div>
                                <span style="font-size:10.5px; color:#6B7280;">NAMA BANK</span>
                                <div style="font-weight:850; color:#172B29;">{{ $order->farmer_bank_name ?: ($order->farmer->bank_name ?? 'Bank BRI') }}</div>
                            </div>
                            <div>
                                <span style="font-size:10.5px; color:#6B7280;">NOMOR REKENING</span>
                                <div style="font-weight:850; font-size:14px; color:#1E40AF;">{{ $order->farmer_bank_account ?: ($order->farmer->bank_account ?? '-') }}</div>
                            </div>
                            <div style="grid-column:span 2; border-top:1px dashed #E5ECEA; padding-top:8px;">
                                <span style="font-size:10.5px; color:#6B7280;">NAMA PEMILIK REKENING</span>
                                <div style="font-weight:800; color:#172B29;">{{ $order->farmer_bank_holder ?: ($order->farmer->bank_holder ?: ($order->farmer->name ?? 'Petani Mitra')) }}</div>
                            </div>
                        </div>

                        <div style="margin-top:14px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;">
                            <div>
                                <span style="font-size:11px; color:#1E40AF;">Nominal Transfer DP (30%):</span>
                                <div style="font-size:18px; font-weight:900; color:#0A765A;">
                                    Rp {{ number_format($order->dp_amount, 0, ',', '.') }}
                                </div>
                            </div>
                            @if($order->dp_deadline_at)
                            <div style="font-size:11px; color:#1E40AF; text-align:right;">
                                Batas Pembayaran: <b>{{ $order->dp_deadline_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} WIB</b>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Form Upload Bukti --}}
                    <form action="{{ route('pembeli.pesanan.upload_dp', $order->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div style="margin-bottom:14px;">
                            <label class="form-label">Unggah Bukti Transfer Bank <span class="req">*</span></label>
                            <label for="dp-proof-file"
                                   style="display:flex; flex-direction:column; align-items:center; justify-content:center; border:2px dashed #A9CBBB; border-radius:12px; padding:16px; background:#F8FCFA; text-align:center; color:#568977; font-size:11px; cursor:pointer;">
                                <i class="fa-solid fa-cloud-arrow-up" style="font-size:24px; color:#05C793; margin-bottom:4px;"></i>
                                <b style="color:#1C6F57;">Klik untuk Memilih File Bukti Transfer</b>
                                <span style="font-size:10px; color:#87968F; margin-top:2px;">Format JPG, PNG, WEBP, atau PDF (Maksimal 2 MB)</span>
                                <span x-show="proofFileName" style="margin-top:6px; font-weight:800; color:#05C793; font-size:11px;" x-text="'File dipilih: ' + proofFileName"></span>
                                <input id="dp-proof-file" type="file" name="dp_proof" accept="image/*,.pdf" required style="display:none;"
                                       @change="proofFileName = $event.target.files[0] ? $event.target.files[0].name : ''">
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%;">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Bukti Pembayaran DP
                        </button>
                    </form>
                </div>

                {{-- Status C: Menunggu Pemeriksaan Petani --}}
                @elseif($order->status === 'menunggu_verifikasi_dp')
                <div style="padding:16px; border-radius:12px; background:#FAF5FF; border:1px solid #E9D5FF;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:8px; flex-wrap:wrap; gap:8px;">
                        <div>
                            <div style="font-size:13px; font-weight:800; color:#7E22CE;">
                                <i class="fa-solid fa-hourglass-half"></i> Menunggu Pemeriksaan Pembayaran
                            </div>
                            <p style="font-size:11.5px; color:#6B21A8; margin:4px 0 0; line-height:1.5;">
                                Bukti pembayaran DP telah diterima sistem pada <b>{{ $order->dp_uploaded_at ? $order->dp_uploaded_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') : '-' }} WIB</b>.
                                Petani sedang memeriksa mutasi bank dan akan mengonfirmasi pesanan Anda segera.
                            </p>
                        </div>
                        <a href="{{ route('pesanan.bukti_dp', $order->id) }}" target="_blank" class="btn btn-secondary" style="font-size:11px; padding:6px 12px;">
                            <i class="fa-solid fa-eye"></i> Lihat Bukti Saya
                        </a>
                    </div>
                </div>

                {{-- Status D: Pesanan Aktif (DP Terkonfirmasi) --}}
                @elseif(in_array($order->status, ['pesanan_aktif', 'dikonfirmasi', 'dp_cair', 'tanam_berjalan', 'siap_panen', 'dalam_pengiriman', 'selesai']))
                <div style="padding:16px; border-radius:12px; background:#ECFDF5; border:1px solid #A7F3D0;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                        <div style="font-size:13px; font-weight:850; color:#065F46;">
                            <i class="fa-solid fa-circle-check"></i> Pembayaran DP 30% Terkonfirmasi
                        </div>
                        @if($order->dp_proof_path)
                        <a href="{{ route('pesanan.bukti_dp', $order->id) }}" target="_blank" style="font-size:11px; color:#047857; font-weight:750; text-decoration:none;">
                            Bukti Transfer &rsaquo;
                        </a>
                        @endif
                    </div>
                    <p style="font-size:11.5px; color:#047857; margin:0; line-height:1.5;">
                        Petani telah memverifikasi pembayaran DP 30% (<b>Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</b>).
                        Pesanan ini kini aktif. Sisa 70% (<b>Rp {{ number_format($order->escrow_locked_amount, 0, ',', '.') }}</b>) diselesaikan saat serah terima panen.
                    </p>
                </div>

                {{-- Status E: Dibatalkan --}}
                @elseif($order->status === 'dibatalkan')
                <div style="padding:16px; border-radius:12px; background:#FEF2F2; border:1px solid #FECACA;">
                    <div style="font-size:13px; font-weight:800; color:#991B1B; margin-bottom:4px;">
                        <i class="fa-solid fa-circle-xmark"></i> Pesanan Ditolak / Dibatalkan
                    </div>
                    <p style="font-size:11.5px; color:#7F1D1D; margin:0;">
                        Alasan: {{ $order->rejection_reason ?: 'Kapasitas lahan atau jadwal panen belum memungkinkan.' }}
                    </p>
                </div>
                @endif
            </div>

            {{-- 2. PERKEMBANGAN TANAMAN BERDASARKAN BATCH PANEN --}}
            @if($order->harvestBatch)
            <div class="card" style="padding:24px;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px;">
                    <div>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <span style="font-size:10px; font-weight:800; color:#0C8D65; background:#E9FBF3; padding:2px 8px; border-radius:5px;">
                                {{ $order->harvestBatch->batch_code }}
                            </span>
                            <span style="font-size:11px; color:#8A9A94;">{{ $order->harvestBatch->batch_name }}</span>
                        </div>
                        <h2 style="font-size:16px; font-weight:850; margin:6px 0 3px; color:#172B29;">
                            Perkembangan Tanaman di Lahan
                        </h2>
                        <p style="font-size:11px; color:#91A19B; margin:0;">
                            Perkiraan Panen: <b>{{ $order->harvestBatch->estimated_harvest_date ? $order->harvestBatch->estimated_harvest_date->timezone('Asia/Jakarta')->translatedFormat('d M Y') : '-' }}</b>
                        </p>
                    </div>
                    <span style="font-size:11px; font-weight:800; padding:4px 10px; border-radius:7px;
                        {{ $order->harvestBatch->status === 'siap_panen' ? 'background:#E9F9F2; color:#0C8D65;' : 'background:#E8F9FC; color:#087894;' }}">
                        {{ $order->harvestBatch->status === 'siap_panen' ? 'Siap Panen' : $order->harvestBatch->current_phase }}
                    </span>
                </div>

                {{-- 4-Phase System Timeline --}}
                <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:10px; margin-bottom:20px;">
                    @foreach($order->harvestBatch->phase_timeline as $ph)
                    <div style="padding:12px; border-radius:10px; border:1px solid {{ $ph['status'] === 'selesai' ? '#A7F3D0' : ($ph['status'] === 'berlangsung' ? '#05C793' : '#E5ECEA') }};
                                background:{{ $ph['status'] === 'selesai' ? '#F0FDF8' : ($ph['status'] === 'berlangsung' ? '#E6FFFA' : '#FAFCFB') }};">
                        <div style="font-size:11px; font-weight:850; color:#172B29;">{{ $ph['index'] }}. {{ $ph['short'] }}</div>
                        <div style="font-size:10px; margin-top:3px;
                            {{ $ph['status'] === 'selesai' ? 'color:#047857; font-weight:800;' : ($ph['status'] === 'berlangsung' ? 'color:#0E7490; font-weight:800;' : 'color:#94A3B8;') }}">
                            {{ $ph['status'] === 'selesai' ? '✓ Selesai' : ($ph['status'] === 'berlangsung' ? '● Berlangsung' : 'Menunggu') }}
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Dokumentasi Lapangan Aktual dari Petani --}}
                <h3 style="font-size:13px; font-weight:850; margin:0 0 12px; color:#172B29;">Dokumentasi Lapangan Aktual</h3>

                @php
                    $batchProgress = $order->harvestBatch->plantingProgress()->latest('reported_at')->get();
                @endphp

                @if($batchProgress->isEmpty())
                <div style="text-align:center; padding:30px 15px; color:#8A9D95; background:#F8FCFA; border-radius:12px; border:1px solid #DFEAE5;">
                    <i class="fa-solid fa-camera" style="font-size:22px; color:#94A3B8; margin-bottom:6px;"></i>
                    <p style="font-size:12px; margin:0; font-weight:600;">Belum ada pembaruan perkembangan tanaman.</p>
                    <span style="font-size:11px; color:#94A3B8;">Petani akan mengunggah foto dan catatan aktual saat melakukan perawatan tanaman di lahan.</span>
                </div>
                @else
                <div style="display:flex; flex-direction:column; gap:14px;">
                    @foreach($batchProgress as $prog)
                    <div style="display:flex; gap:14px; border:1px solid #E5ECEA; border-radius:12px; padding:12px; background:#fff;">
                        @if($prog->photo_url)
                        <div style="width:110px; height:85px; border-radius:8px; overflow:hidden; flex-shrink:0; background:#F8FCFA;">
                            <img src="{{ str_starts_with($prog->photo_url, 'http') ? $prog->photo_url : asset($prog->photo_url) }}"
                                 alt="{{ $prog->stage_name }}"
                                 style="width:100%; height:100%; object-fit:cover;">
                        </div>
                        @endif
                        <div style="flex:1;">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                                <span style="font-size:10px; font-weight:800; color:#0C8D65; background:#E9F9F2; padding:2px 7px; border-radius:4px;">
                                    {{ $prog->stage_name }}
                                </span>
                                <span style="font-size:10px; color:#8A9A94;">
                                    <i class="fa-regular fa-clock"></i> {{ $prog->reported_at ? $prog->reported_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') : '-' }} WIB
                                </span>
                            </div>
                            <p style="font-size:11.5px; color:#374151; line-height:1.5; margin:0;">
                                {{ $prog->note }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endif

            {{-- Konfirmasi Penerimaan: Hanya tampil saat panen siap atau dalam pengiriman --}}
            @if(in_array($order->status, ['siap_panen', 'dalam_pengiriman']))
            <div class="card" style="padding:22px; border:2px solid #05C793;">
                <h2 style="font-size:14px; font-weight:850; margin:0 0 10px; color:#172B29;"><i class="fa-solid fa-circle-check" style="color:#05C793;"></i> Konfirmasi Penerimaan Hasil Panen</h2>
                <p style="font-size:11px; color:#50695E; margin:0 0 14px; line-height:1.6;">
                    Petani telah menandai hasil panen siap diserahterimakan. Klik tombol di bawah setelah Anda benar-benar menerima barang dari petani mitra.
                </p>
                <form action="{{ route('pembeli.pesanan.complete', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" onclick="return confirm('Konfirmasi bahwa Anda sudah menerima hasil panen ini?')"
                            style="width:100%; padding:12px; border-radius:10px; background:#05C793; border:none; color:#053B2D; font-weight:850; font-size:13px; cursor:pointer;">
                        Konfirmasi Penerimaan Hasil Panen
                    </button>
                </form>
            </div>
            @elseif($order->status === 'selesai')
            <div class="card" style="padding:22px; background:#E9F9F2; border:1px solid #C5EBD9;">
                <div style="text-align:center; color:#0C8D65; font-size:13px; font-weight:850;"><i class="fa-solid fa-circle-check"></i> Pesanan Selesai</div>
                <p style="text-align:center; font-size:11px; color:#0A765A; margin:6px 0 0;">Anda telah mengonfirmasi penerimaan hasil panen ini.</p>
            </div>
            @endif

        </div>

        {{-- KANAN: Ringkasan Nilai & Titik Serah Terima --}}
        <div style="display:flex; flex-direction:column; gap:20px;">

            {{-- Card Ringkasan Nilai Kontrak --}}
            <div class="card" style="padding:22px;">
                <h3 style="font-size:14px; font-weight:850; margin:0 0 14px; color:#172B29;">Rincian Harga &amp; Pembayaran</h3>

                <table style="width:100%; font-size:11.5px; border-collapse:collapse;">
                    <tbody>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:8px 0; color:#8A9A94;">Jumlah Dipesan</td>
                            <td style="padding:8px 0; text-align:right; font-weight:800; color:#172B29;">
                                {{ number_format($order->quantity_kg, 0, ',', '.') }} kg
                            </td>
                        </tr>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:8px 0; color:#8A9A94;">Harga per kg</td>
                            <td style="padding:8px 0; text-align:right; font-weight:800; color:#172B29;">
                                Rp {{ number_format($order->price_per_kg, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:8px 0; color:#8A9A94;">Total Nilai Pesanan</td>
                            <td style="padding:8px 0; text-align:right; font-weight:850; font-size:13px; color:#172B29;">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:8px 0; color:#0A765A; font-weight:750;">Uang Muka (DP 30%)</td>
                            <td style="padding:8px 0; text-align:right; font-weight:850; color:#0A765A;">
                                Rp {{ number_format($order->dp_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0; color:#1E40AF; font-weight:750;">Sisa Pelunasan (70%)</td>
                            <td style="padding:8px 0; text-align:right; font-weight:850; color:#1E40AF;">
                                Rp {{ number_format($order->escrow_locked_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Card Titik Serah Terima Panen --}}
            <div class="card" style="padding:22px;">
                <h3 style="font-size:14px; font-weight:850; margin:0 0 14px; color:#172B29;">Koordinasi Serah Terima</h3>

                <div style="display:flex; flex-direction:column; gap:10px; font-size:11.5px;">
                    <div>
                        <span style="color:#8A9A94; font-size:10.5px; display:block;">PETANI MITRA</span>
                        <div style="font-weight:800; color:#172B29;">{{ $order->farmer->name ?? 'Petani Mitra' }}</div>
                        @if($order->farmer && $order->farmer->phone)
                        <div style="margin-top:2px;">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->farmer->phone) }}" target="_blank"
                               style="color:#0C8D65; font-weight:800; text-decoration:none; display:inline-flex; align-items:center; gap:4px;">
                                <i class="fa-brands fa-whatsapp"></i> Hubungi WhatsApp
                            </a>
                        </div>
                        @endif
                    </div>

                    <div style="border-top:1px solid #EEF2F0; padding-top:8px;">
                        <span style="color:#8A9A94; font-size:10.5px; display:block;">LOKASI LAHAN KEBUN</span>
                        <div style="font-weight:600; color:#172B29;">
                            {{ $order->commodityListing->location ?? ($order->farmer->location ?? 'Malang, Jawa Timur') }}
                        </div>
                    </div>

                    <div style="border-top:1px solid #EEF2F0; padding-top:8px;">
                        <span style="color:#8A9A94; font-size:10.5px; display:block;">ALAMAT TUJUAN ANDA</span>
                        <div style="font-weight:600; color:#172B29;">
                            {{ $order->buyer->location ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
