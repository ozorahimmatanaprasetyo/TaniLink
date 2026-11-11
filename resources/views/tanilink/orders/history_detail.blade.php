@extends('layouts.sidebar')

@section('title', 'Detail Riwayat Pesanan - ' . $order->order_code . ' - TaniLink')

@section('breadcrumb')
    @if($role === 'petani')
        <a href="{{ route('petani.pesanan') }}" style="color:#07946C; text-decoration:none; font-weight:700;">Pesanan Masuk</a>
    @else
        <a href="{{ route('pembeli.pesanan') }}" style="color:#07946C; text-decoration:none; font-weight:700;">Pesanan Saya</a>
    @endif
    <span style="margin:0 5px; color:#A2AEAA;">&rsaquo;</span>
    <span style="color:#50695E;">{{ $order->order_code }}</span>
    <span style="margin:0 5px; color:#A2AEAA;">&rsaquo;</span>
    <strong>Detail Riwayat Pesanan</strong>
@endsection

@push('styles')
<style>
.hd-layout {
    display: grid;
    grid-template-columns: minmax(0, 1.4fr) minmax(340px, 1fr);
    gap: 24px;
}
.hd-commodity-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-bottom: 18px;
}
.hd-payment-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}
.hd-headline-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
@media (max-width: 992px) {
    .hd-layout {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 640px) {
    .hd-commodity-grid,
    .hd-payment-grid {
        grid-template-columns: 1fr;
    }
    .hd-headline-actions {
        flex-direction: column;
        width: 100%;
    }
    .hd-headline-actions a {
        width: 100%;
        justify-content: center;
        text-align: center;
    }
}
</style>
@endpush

@section('content')
<div>

    {{-- Headline & Action Buttons --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:24px; flex-wrap:wrap; gap:14px;">
        <div>
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                <span style="font-size:11px; font-weight:800; background:#E9FBF3; color:#0C8D65; padding:3px 9px; border-radius:6px;">{{ $order->order_code }}</span>
                @if($order->status === 'selesai')
                    <span style="font-size:11px; font-weight:800; background:#D1FAE5; color:#065F46; padding:3px 9px; border-radius:6px;">Pesanan Selesai</span>
                @elseif($order->status === 'dibatalkan')
                    <span style="font-size:11px; font-weight:800; background:#FEE2E2; color:#991B1B; padding:3px 9px; border-radius:6px;">Ditolak / Batal</span>
                @else
                    <span style="font-size:11px; font-weight:800; background:#E0F2FE; color:#075985; padding:3px 9px; border-radius:6px;">{{ $order->status_label }}</span>
                @endif
            </div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">
                Riwayat Transaksi: {{ $order->commodityListing->commodity_name ?? 'Komoditas' }}
            </h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">
                Catatan kronologis aktivitas dan laporan resmi transaksi pre-order.
            </p>
        </div>

        <div class="hd-headline-actions">
            @php
                $backUrl = $role === 'petani' ? route('petani.pesanan') : route('pembeli.pesanan');
                $pdfUrl = $role === 'petani' ? route('petani.pesanan.pdf', $order->id) : route('pembeli.pesanan.pdf', $order->id);
            @endphp
            <a href="{{ $backUrl }}" class="btn btn-secondary" style="font-size:12px; padding:8px 14px;">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
            </a>
            <a href="{{ $pdfUrl }}" class="btn btn-primary" style="font-size:12px; padding:8px 16px;">
                <i class="fa-solid fa-file-pdf"></i> Unduh Laporan PDF
            </a>
        </div>
    </div>

    {{-- Grid 2 Kolom --}}
    <div class="hd-layout">

        {{-- Kolom Kiri: Rincian Pesanan, Finansial & Timeline --}}
        <div style="display:flex; flex-direction:column; gap:20px;">

            {{-- Card Rincian Komoditas --}}
            <div class="card" style="padding:24px;">
                <h3 style="font-size:15px; font-weight:850; color:#172B29; margin:0 0 16px;">Informasi Komoditas &amp; Nilai Kontrak</h3>

                <div class="hd-commodity-grid">
                    <div style="background:#F8FCFA; border:1px solid #E5ECEA; border-radius:12px; padding:14px;">
                        <span style="font-size:10.5px; color:#7B9187; font-weight:700; text-transform:uppercase; display:block; margin-bottom:4px;">Komoditas &amp; Varietas</span>
                        <div style="font-size:14px; font-weight:800; color:#172B29;">{{ $order->commodityListing->commodity_name ?? '-' }}</div>
                        <span style="font-size:11px; color:#6B8077;">{{ $order->commodityListing->variety ?: 'Varietas Umum' }} ({{ $order->commodityListing->category ?? 'Sayuran' }})</span>
                    </div>

                    <div style="background:#F8FCFA; border:1px solid #E5ECEA; border-radius:12px; padding:14px;">
                        <span style="font-size:10.5px; color:#7B9187; font-weight:700; text-transform:uppercase; display:block; margin-bottom:4px;">Batch Panen Terkait</span>
                        <div style="font-size:14px; font-weight:800; color:#07876B;">{{ $order->harvestBatch->batch_name ?? 'Batch Panen' }}</div>
                        <span style="font-size:11px; color:#6B8077;">Kode: {{ $order->harvestBatch->batch_code ?? '-' }}</span>
                    </div>
                </div>

                <div style="border-top:1px solid #EEF2F0; padding-top:16px; display:flex; flex-direction:column; gap:10px;">
                    <div style="display:flex; justify-content:space-between; font-size:12.5px;">
                        <span style="color:#6B8077;">Jumlah Pesanan:</span>
                        <strong style="color:#172B29;">{{ number_format($order->quantity_kg, 0, ',', '.') }} kg</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:12.5px;">
                        <span style="color:#6B8077;">Harga Kesepakatan per kg:</span>
                        <strong style="color:#172B29;">Rp {{ number_format($order->price_per_kg, 0, ',', '.') }}</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:14px; border-top:1px dashed #DFEAE5; padding-top:10px; margin-top:4px;">
                        <span style="font-weight:800; color:#172B29;">Total Nilai Pesanan:</span>
                        <strong style="font-size:17px; font-weight:900; color:#0C8D65;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>

            {{-- Card Rincian Pembayaran --}}
            <div class="card" style="padding:24px;">
                <h3 style="font-size:15px; font-weight:850; color:#172B29; margin:0 0 16px;">Status &amp; Rincian Pembayaran</h3>

                <div class="hd-payment-grid">
                    <div style="border:1px solid #E5ECEA; border-radius:12px; padding:16px; background:#fff;">
                        <span style="font-size:11px; font-weight:800; color:#0C8D65; background:#E9FBF3; padding:2px 8px; border-radius:5px;">DP 30% (Awal)</span>
                        <div style="font-size:18px; font-weight:850; color:#172B29; margin:10px 0 4px;">Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</div>
                        <div style="font-size:11.5px; color:#50695E;">
                            @if($order->dp_confirmed_at)
                                <span style="color:#0C8D65; font-weight:800;"><i class="fa-solid fa-circle-check"></i> Dikonfirmasi Lunas</span>
                                <div style="font-size:10.5px; color:#8A9A94; margin-top:2px;">{{ $order->dp_confirmed_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} WIB</div>
                            @elseif($order->dp_uploaded_at)
                                <span style="color:#D97706; font-weight:800;"><i class="fa-solid fa-clock"></i> Menunggu Verifikasi</span>
                            @else
                                <span style="color:#991B1B; font-weight:800;"><i class="fa-solid fa-circle-xmark"></i> Belum Dibayar</span>
                            @endif
                        </div>
                    </div>

                    <div style="border:1px solid #E5ECEA; border-radius:12px; padding:16px; background:#fff;">
                        <span style="font-size:11px; font-weight:800; color:#075985; background:#E0F2FE; padding:2px 8px; border-radius:5px;">Sisa 70% (Pelunasan)</span>
                        <div style="font-size:18px; font-weight:850; color:#172B29; margin:10px 0 4px;">Rp {{ number_format($order->escrow_locked_amount, 0, ',', '.') }}</div>
                        <div style="font-size:11.5px; color:#50695E;">
                            @if($order->status === 'selesai')
                                <span style="color:#0C8D65; font-weight:800;"><i class="fa-solid fa-circle-check"></i> Selesai saat serah terima</span>
                                <div style="font-size:10.5px; color:#8A9A94; margin-top:2px;">{{ $order->completed_at ? $order->completed_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}</div>
                            @else
                                <span style="color:#6B8077;"><i class="fa-solid fa-lock"></i> Terkunci (Dibayar saat panen tiba)</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($order->rejection_reason)
                <div style="margin-top:16px; background:#FEF2F2; border:1px solid #FECACA; border-radius:12px; padding:14px;">
                    <div style="font-size:12px; font-weight:800; color:#991B1B; margin-bottom:4px;">Catatan Penolakan:</div>
                    <div style="font-size:12px; color:#7F1D1D; line-height:1.5;">{{ $order->rejection_reason }}</div>
                </div>
                @endif
            </div>

            {{-- Card Timeline Kronologis Server --}}
            <div class="card" style="padding:24px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
                    <div>
                        <h3 style="font-size:15px; font-weight:850; color:#172B29; margin:0 0 4px;">Kronologi &amp; Riwayat Aktivitas Server</h3>
                        <p style="font-size:11px; color:#8A9A94; margin:0;">Seluruh peristiwa tercatat secara permanen dengan stempel waktu resmi (WIB).</p>
                    </div>
                    <span style="font-size:11px; color:#6B8077; font-weight:750;">{{ $order->activities->count() }} Peristiwa</span>
                </div>

                <div style="display:flex; flex-direction:column; gap:16px;">
                    @forelse($order->activities as $act)
                    <div style="display:flex; gap:14px; position:relative;">
                        <div style="width:32px; height:32px; border-radius:10px; background:#E9FBF3; color:#0C8D65; display:grid; place-items:center; font-size:13px; flex-shrink:0;">
                            <i class="fa-regular fa-clock"></i>
                        </div>
                        <div style="flex:1; border-bottom:1px solid #F0F4F2; padding-bottom:14px;">
                            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:4px;">
                                <div style="font-size:13px; font-weight:800; color:#172B29;">{{ $act->title }}</div>
                                <span style="font-size:11px; color:#8A9A94; font-weight:600;">
                                    {{ $act->occurred_at ? $act->occurred_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') : '-' }} WIB
                                </span>
                            </div>
                            @if($act->description)
                            <p style="font-size:12px; color:#50695E; margin:0; line-height:1.5;">
                                {{ $act->description }}
                            </p>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div style="text-align:center; padding:30px 10px; color:#8A9D95; font-size:12px;">
                        Belum ada aktivitas tercatat untuk pesanan ini.
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Kolom Kanan: Pihak Terlibat & Dokumentasi Batch --}}
        <div style="display:flex; flex-direction:column; gap:20px;">

            {{-- Card Pihak Terlibat --}}
            <div class="card" style="padding:22px;">
                <h3 style="font-size:14px; font-weight:850; color:#172B29; margin:0 0 16px;">Pihak yang Bertransaksi</h3>

                <div style="display:flex; flex-direction:column; gap:14px;">
                    {{-- Petani --}}
                    <div style="padding:12px; border:1px solid #EEF2F0; border-radius:12px; background:#F8FCFA;">
                        <span style="font-size:10px; font-weight:800; color:#0C8D65; text-transform:uppercase;">Pihak Petani</span>
                        <div style="font-size:13px; font-weight:850; color:#172B29; margin-top:4px;">{{ $order->farmer->name ?? 'Mitra Petani' }}</div>
                        <div style="font-size:11px; color:#6B8077; margin-top:2px;">{{ $order->farmer->organization ?? 'Kelompok Tani' }}</div>
                        <div style="font-size:10.5px; color:#8A9A94; margin-top:4px;"><i class="fa-solid fa-location-dot"></i> {{ $order->farmer->location ?? 'Malang, Jawa Timur' }}</div>
                    </div>

                    {{-- Konsumen --}}
                    <div style="padding:12px; border:1px solid #EEF2F0; border-radius:12px; background:#F8FCFA;">
                        <span style="font-size:10px; font-weight:800; color:#075985; text-transform:uppercase;">Pihak Konsumen</span>
                        <div style="font-size:13px; font-weight:850; color:#172B29; margin-top:4px;">{{ $order->buyer->name ?? 'Konsumen' }}</div>
                        <div style="font-size:11px; color:#6B8077; margin-top:2px;">{{ $order->buyer->organization ?? 'Pemesan' }}</div>
                        <div style="font-size:10.5px; color:#8A9A94; margin-top:4px;"><i class="fa-solid fa-location-dot"></i> {{ $order->deliveryTracking->destination_address ?? ($order->buyer->location ?? '-') }}</div>
                    </div>
                </div>
            </div>

            {{-- Card Dokumentasi Panen Terkait --}}
            @if($order->harvestBatch)
            <div class="card" style="padding:22px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
                    <h3 style="font-size:14px; font-weight:850; color:#172B29; margin:0;">Dokumentasi Batch Panen</h3>
                    <span style="font-size:10.5px; color:#0C8D65; font-weight:800;">{{ $order->harvestBatch->plantingProgress->count() }} Update</span>
                </div>
                <p style="font-size:11.5px; color:#6B8077; margin:0 0 14px;">
                    Dokumentasi riil yang diunggah petani selama masa tanam batch ini.
                </p>

                <div style="display:flex; flex-direction:column; gap:12px;">
                    @forelse($order->harvestBatch->plantingProgress->take(4) as $prog)
                    <div style="display:flex; gap:12px; border:1px solid #EEF2F0; border-radius:10px; padding:10px; background:#fff;">
                        <div style="width:60px; height:60px; border-radius:8px; overflow:hidden; flex-shrink:0; background:#F8FCFA;">
                            <img src="{{ $prog->resolved_photo_url }}"
                                 alt="{{ $prog->stage_name }}"
                                 onerror="this.onerror=null; this.src='{{ asset('images/phases/fase-4-vegetatif.jpg') }}';"
                                 style="width:100%; height:100%; object-fit:cover;">
                        </div>
                        <div style="flex:1;">
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <span style="font-size:10px; font-weight:800; color:#0C8D65; background:#E9FBF3; padding:2px 6px; border-radius:4px;">{{ $prog->stage_name }}</span>
                                <span style="font-size:9.5px; color:#8A9A94;">{{ $prog->reported_at ? $prog->reported_at->timezone('Asia/Jakarta')->translatedFormat('d M Y') : '-' }}</span>
                            </div>
                            <p style="font-size:11px; color:#374151; margin:4px 0 0; line-height:1.4;">
                                {{ \Illuminate\Support\Str::limit($prog->note, 65) }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <div style="font-size:11.5px; color:#8A9D95; text-align:center; padding:20px 0;">
                        Belum ada foto dokumentasi lapangan untuk batch ini.
                    </div>
                    @endforelse
                </div>
            </div>
            @endif

        </div>

    </div>

</div>
@endsection

