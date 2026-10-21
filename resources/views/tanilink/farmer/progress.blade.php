@extends('layouts.sidebar')

@section('title', 'Monitoring Tanam - ' . $order->order_code . ' - TaniLink')

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
<div x-data="{ showModal: false }">

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:20px;">
        <div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Monitoring Tanam & Kontrak</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">
                {{ $order->order_code }} &bull; {{ $order->commodityListing->commodity_name ?? 'Komoditas' }} &bull; Dipesan oleh <strong>{{ $order->buyer->name ?? 'Pembeli' }}</strong> ({{ $order->buyer->organization ?? '-' }})
            </p>
        </div>
        <button @click="showModal = true"
                style="display:inline-flex; align-items:center; gap:8px; padding:10px 18px; border-radius:11px; border:none; background:#05C793; color:#053B2D; font-size:12px; font-weight:800; cursor:pointer; box-shadow:0 3px 10px rgba(5,199,147,0.2);">
            ＋ Update Progres
        </button>
    </div>

    {{-- Notice --}}
    <div style="padding:14px 18px; display:flex; align-items:flex-start; gap:11px; border-radius:12px; background:#FFF8EA; border:1px solid #F6E3B9; color:#7F631E; font-size:11px; line-height:1.6; margin-bottom:20px;">
        <span>ⓘ</span>
        <span><b>Sistem Escrow Bersama:</b> DP 30% telah diteruskan untuk pembiayaan tanam dan 70% saldo diamankan di rekening escrow bersama TaniLink hingga barang diterima.</span>
    </div>

    {{-- Grid 2 Kolom --}}
    <div style="display:grid; grid-template-columns:minmax(0, 1.5fr) minmax(320px, 1fr); gap:20px;">
        
        {{-- Kiri Stack --}}
        <div style="display:flex; flex-direction:column; gap:18px;">
            
            {{-- Card Progres --}}
            <div class="card" style="padding:24px;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
                    <div>
                        <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Perjalanan dari semai menuju panen</h2>
                        <p style="font-size:11px; color:#91A19B; margin:0;">
                            {{ $order->commodityListing->commodity_name ?? 'Komoditas' }} &bull; Varietas {{ $order->commodityListing->variety ?? '-' }}
                        </p>
                    </div>
                    <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:10px;">
                        {{ $order->current_stage ?? 'Fase Pembungaan' }}
                    </span>
                </div>

                <div style="display:flex; justify-content:space-between; margin:18px 0 8px; font-size:12px; font-weight:750;">
                    <span style="color:#172B29;">Progres pertumbuhan</span>
                    <span style="color:#0A9370; font-weight:850;">{{ $order->stage_percentage ?? 65 }}%</span>
                </div>

                <div style="height:8px; border-radius:12px; background:#E8F2ED; overflow:hidden; margin-bottom:20px;">
                    <div style="height:100%; border-radius:12px; background:linear-gradient(90deg, #05C793, #14AAB1); width:{{ $order->stage_percentage ?? 65 }}%;"></div>
                </div>

                <div style="height:1px; background:#E5ECEA; margin:16px 0;"></div>

                {{-- Dynamic Timeline Item list --}}
                <div style="display:flex; flex-direction:column; gap:16px;">
                    @foreach($order->phase_timeline as $ph)
                    <div style="display:flex; gap:14px; align-items:flex-start;">
                        <div style="height:30px; width:30px; min-width:30px; border-radius:50%; display:grid; place-items:center; font-size:14px;
                            {{ $ph['status'] === 'selesai' ? 'background:#E5F9EF; color:#069873;' : ($ph['status'] === 'berlangsung' ? 'background:#E8F9FC; color:#0B8BAA; border:2px solid #05C793;' : 'background:#F5F8F7; color:#91A19B;') }}">
                            {{ $ph['icon'] }}
                        </div>
                        <div style="flex:1;">
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <b style="font-size:12.5px; color:#172B29;">{{ $ph['name'] }}</b>
                                @if($ph['status'] === 'selesai')
                                    <span style="font-size:10px; font-weight:800; color:#0C8D65; background:#E9F9F2; padding:2px 8px; border-radius:5px;">Selesai</span>
                                @elseif($ph['status'] === 'berlangsung')
                                    <span style="font-size:10px; font-weight:800; color:#0B8BAA; background:#E8F9FC; padding:2px 8px; border-radius:5px;">Sedang Berlangsung</span>
                                @else
                                    <span style="font-size:10px; font-weight:750; color:#91A19B; background:#F5F8F7; padding:2px 8px; border-radius:5px;">Menunggu</span>
                                @endif
                            </div>
                            <p style="font-size:11px; color:#6B8077; line-height:1.5; margin:4px 0 0;">{{ $ph['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Card Riwayat Dokumentasi --}}
            <div class="card" style="padding:24px;">
                <h2 style="font-size:15px; font-weight:850; margin:0 0 14px; color:#172B29;">Catatan Lapangan Terakhir</h2>
                
                @if($order->plantingProgress->isEmpty())
                <p style="font-size:12px; color:#8A9994;">Belum ada catatan progres yang diunggah.</p>
                @else
                <div style="display:flex; flex-direction:column; gap:12px;">
                    @foreach($order->plantingProgress as $prog)
                    <div style="padding:14px; border-radius:12px; background:#F8FCFA; border:1px solid #E5ECEA;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                            <b style="font-size:12px; color:#172B29;">{{ $prog->stage_name }}</b>
                            <span style="font-size:10px; color:#8A9994;">{{ $prog->reported_at ? $prog->reported_at->diffForHumans() : 'Baru saja' }}</span>
                        </div>
                        <p style="font-size:11px; color:#50695E; line-height:1.6; margin:0;">
                            {{ $prog->note }}
                        </p>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>

        {{-- Kanan Stack --}}
        <div style="display:flex; flex-direction:column; gap:18px;">
            
            {{-- Card Ringkasan Kontrak --}}
            <div class="card" style="padding:22px;">
                <h2 style="font-size:15px; font-weight:850; margin:0 0 16px; color:#172B29;">Ringkasan Kontrak</h2>
                
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:14px;">
                    <div style="border-radius:13px; background:#F7FAF9; padding:15px;">
                        <span style="font-size:10px; color:#889991;">Kuantitas kontrak</span>
                        <b style="display:block; font-size:19px; margin-top:8px; color:#172B29;">{{ number_format($order->quantity_kg, 0, ',', '.') }} kg</b>
                    </div>
                    <div style="border-radius:13px; background:#F7FAF9; padding:15px;">
                        <span style="font-size:10px; color:#889991;">Nilai kesepakatan</span>
                        <b style="display:block; font-size:19px; margin-top:8px; color:#0A765A;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</b>
                    </div>
                    <div style="border-radius:13px; background:#F7FAF9; padding:15px;">
                        <span style="font-size:10px; color:#889991;">DP 30% (modal benih)</span>
                        <b style="display:block; font-size:19px; margin-top:8px; color:#0C8D65;">Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</b>
                    </div>
                    <div style="border-radius:13px; background:#F7FAF9; padding:15px;">
                        <span style="font-size:10px; color:#889991;">Escrow 70% (terkunci)</span>
                        <b style="display:block; font-size:19px; margin-top:8px; color:#6953B5;">Rp {{ number_format($order->escrow_locked_amount, 0, ',', '.') }}</b>
                    </div>
                </div>

                <div style="font-size:10px; color:#93A69B; border-top:1px dashed #DCE7E1; padding-top:12px;">
                    Angka keuangan adalah hasil perhitungan kontrak pre-order, bukan bukti fisik transfer perbankan.
                </div>
            </div>

            {{-- Card Titik Serah Terima --}}
            <div class="card" style="padding:22px;">
                <h2 style="font-size:15px; font-weight:850; margin:0 0 14px; color:#172B29;">Titik Serah Terima & Lokasi</h2>
                
                <table style="width:100%; font-size:11px; border-collapse:collapse;">
                    <tbody>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:10px 0; color:#8A9994;">Kontak Konsumen</td>
                            <td style="padding:10px 0; text-align:right; font-weight:750; color:#172B29;">
                                {{ $order->buyer->name }} &bull; {{ $order->buyer->organization ?? '-' }}
                                @if($order->buyer->phone)
                                <div style="color:#0C8D65; font-size:10px; margin-top:2px;">
                                    <i class="fa-brands fa-whatsapp"></i> {{ $order->buyer->phone }}
                                </div>
                                @endif
                            </td>
                        </tr>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:10px 0; color:#8A9994;">Lokasi Lahan Petani</td>
                            <td style="padding:10px 0; text-align:right; font-weight:750; color:#172B29;">
                                {{ $order->deliveryTracking->pickup_address ?? 'Lahan Petani, Malang' }}
                            </td>
                        </tr>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:10px 0; color:#8A9994;">Tujuan Konsumen</td>
                            <td style="padding:10px 0; text-align:right; font-weight:750; color:#172B29;">
                                {{ $order->deliveryTracking->destination_address ?? ($order->buyer->organization ?? 'Konsumen Malang') }}
                            </td>
                        </tr>
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="padding:10px 0; color:#8A9994;">Metode Penyerahan</td>
                            <td style="padding:10px 0; text-align:right; font-weight:750; color:#078362;">
                                Pengambilan Mandiri di Lahan Panen
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:10px 0; color:#8A9994;">Status Panen</td>
                            <td style="padding:10px 0; text-align:right;">
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; background:#FFF5E8; color:#BA7A1F; font-weight:800; font-size:10px;">
                                    {{ $order->current_stage_status === 'selesai' && $order->current_stage === 'Fase Pematangan & Siap Panen' ? 'Siap Diambil' : 'Dalam Budidaya' }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    {{-- MODAL UPDATE PROGRES --}}
    <div x-cloak x-show="showModal"
         style="position:fixed; inset:0; z-index:100; display:flex; align-items:center; justify-content:center; background:rgba(21,40,31,0.52); padding:16px;">
        <div style="background:#fff; border-radius:20px; width:500px; max-width:95%; padding:28px; box-shadow:0 35px 90px rgba(16,35,29,0.4);"
             @click.away="showModal = false">
            
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
                <h2 style="font-size:18px; font-weight:850; margin:0; color:#172B29;">Update Progres Tanaman</h2>
                <button @click="showModal = false" style="background:none; border:none; font-size:16px; cursor:pointer; color:#8A9D95;">✕</button>
            </div>
            
            <p style="font-size:12px; color:#7C9086; margin:0 0 18px;">
                Informasi ini akan terkirim langsung ke dasbor pembeli {{ $order->buyer->name }}.
            </p>

            <form action="{{ route('petani.progress.store') }}" method="POST" enctype="multipart/form-data" x-data="{ photoName: '' }">
                @csrf
                <input type="hidden" name="pre_order_id" value="{{ $order->id }}">

                <div style="display:flex; flex-direction:column; gap:14px;">
                    <div style="display:grid; grid-template-columns:1.3fr 1fr; gap:12px;">
                        <div style="display:flex; flex-direction:column; gap:6px;">
                            <label style="font-size:11px; font-weight:800; color:#365448;">Pilih Tahap Tanaman *</label>
                            <select name="stage_name" required
                                    style="outline:0; width:100%; border:1px solid #DFEAE5; padding:11px 14px; border-radius:10px; background:#fff; font-size:12px;">
                                <option value="Fase Benih & Penyemaian" {{ $order->current_stage === 'Fase Benih & Penyemaian' ? 'selected' : '' }}>🌱 Fase Benih & Penyemaian</option>
                                <option value="Fase Vegetatif & Pertunasan" {{ $order->current_stage === 'Fase Vegetatif & Pertunasan' ? 'selected' : '' }}>🌿 Fase Vegetatif & Pertunasan</option>
                                <option value="Fase Pembungaan & Bakal Buah" {{ $order->current_stage === 'Fase Pembungaan & Bakal Buah' ? 'selected' : '' }}>🌼 Fase Pembungaan & Bakal Buah</option>
                                <option value="Fase Pematangan & Siap Panen" {{ $order->current_stage === 'Fase Pematangan & Siap Panen' ? 'selected' : '' }}>🌶️ Fase Pematangan & Siap Panen</option>
                            </select>
                        </div>

                        <div style="display:flex; flex-direction:column; gap:6px;">
                            <label style="font-size:11px; font-weight:800; color:#365448;">Status Fase Ini *</label>
                            <select name="stage_status" required
                                    style="outline:0; width:100%; border:1px solid #DFEAE5; padding:11px 14px; border-radius:10px; background:#fff; font-size:12px;">
                                <option value="berlangsung" {{ ($order->current_stage_status ?? '') === 'berlangsung' ? 'selected' : '' }}>Sedang Berlangsung</option>
                                <option value="selesai" {{ ($order->current_stage_status ?? '') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Catatan Kondisi Lapangan *</label>
                        <textarea name="note" rows="3" required placeholder="Contoh: Kondisi tanaman sehat, pemupukan kalium lanjutan lancar, tidak ada hama..."
                                  style="outline:0; width:100%; border:1px solid #DFEAE5; padding:11px 14px; border-radius:10px; background:#fff; font-size:12px; resize:vertical;"></textarea>
                    </div>

                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Foto Dokumentasi Lapangan</label>
                        <label for="progress-order-photo"
                               style="display:flex; flex-direction:column; align-items:center; justify-content:center; border:2px dashed #A9CBBB; border-radius:12px; padding:14px; background:#F8FCFA; text-align:center; color:#568977; font-size:11px; cursor:pointer; transition:all 0.2s;"
                               onmouseover="this.style.background='#EEFBF4'; this.style.borderColor='#05C793';"
                               onmouseout="this.style.background='#F8FCFA'; this.style.borderColor='#A9CBBB';">
                            <div style="font-size:22px; margin-bottom:4px;">📷</div>
                            <b style="color:#1C6F57;">Pilih Foto Dokumentasi Tanaman</b>
                            <span style="font-size:10px; color:#87968F; margin-top:2px;">Format JPG, PNG (Maks 2 MB)</span>
                            <span x-show="photoName" style="margin-top:6px; font-weight:800; color:#05C793; font-size:11px;" x-text="'File terpilih: ' + photoName"></span>
                            <input id="progress-order-photo" type="file" name="photo" accept="image/*" style="display:none;"
                                   @change="photoName = $event.target.files[0] ? $event.target.files[0].name : ''">
                        </label>
                    </div>
                </div>

                <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:22px;">
                    <button type="button" @click="showModal = false"
                            style="padding:10px 16px; border-radius:11px; border:1px solid #DFEAE5; background:#fff; font-size:12px; font-weight:800; color:#365249; cursor:pointer;">
                        Batal
                    </button>
                    <button type="submit"
                            style="padding:10px 20px; border-radius:11px; border:none; background:#05C793; font-size:12px; font-weight:800; color:#053B2D; cursor:pointer;">
                        Kirim Update Progres ↗
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection

