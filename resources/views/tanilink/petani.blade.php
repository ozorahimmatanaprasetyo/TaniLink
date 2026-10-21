@extends('layouts.sidebar')

@section('title', 'Dashboard Petani - TaniLink')

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
<div x-data="{ showUpdateModal: false, selectedOrderId: '{{ $activeOrders->first()->id ?? '' }}' }">

    {{-- Headline Section --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:26px;">
        <div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">
                Selamat datang kembali, {{ $petani->name }} 👋
            </h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">
                Pantau hasil panen, kontrak, dan progres tanaman dalam satu tempat.
            </p>
        </div>
        <a href="{{ route('petani.komoditas.create') }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 16px; border-radius:11px; background:#05C793; color:#053B2D; font-size:12px; font-weight:800; text-decoration:none; box-shadow:0 3px 10px rgba(5,199,147,0.2);">
            + Tambah Komoditas
        </a>
    </div>

    {{-- 4 Metric Cards --}}
    <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:16px; margin-bottom:20px;">
        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Listing Aktif</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">♧</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ str_pad($myListings->count(), 2, '0', STR_PAD_LEFT) }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Jumlah komoditas ditawarkan</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Total Kapasitas</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">▥</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ number_format($totalCropKg, 0, ',', '.') }} kg
            </div>
            <div style="font-size:10px; color:#7B9187;">Estimasi hasil panen total</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Pesanan Masuk</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">▤</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ str_pad($activeOrders->count(), 2, '0', STR_PAD_LEFT) }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Kontrak berjalan</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>DP Diterima</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">◉</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                Rp {{ number_format($dpReceived, 0, ',', '.') }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Modal awal 30% kontrak</div>
        </div>
    </div>

    {{-- Demo Banner --}}
    <div style="padding:14px 18px; display:flex; align-items:flex-start; gap:11px; border-radius:12px; background:#FFF8EA; border:1px solid #F6E3B9; color:#7F631E; font-size:11px; line-height:1.6; margin-bottom:20px;">
        <span>ⓘ</span>
        <span><b>Sistem Keuangan Terencana:</b> DP 30% diteruskan sebagai modal biaya tanam dan 70% saldo diamankan di rekening escrow bersama TaniLink hingga panen diverifikasi.</span>
    </div>

    {{-- Grid 2 Kolom --}}
    <div style="display:grid; grid-template-columns:minmax(0, 1.6fr) minmax(300px, 1fr); gap:18px;">
        
        {{-- Kiri: Pesanan Terbaru --}}
        <div class="card" style="padding:22px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
                <div>
                    <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Pesanan Terbaru</h2>
                    <p style="font-size:11px; color:#91A19B; margin:0;">Lihat permintaan dan tindak lanjut kontrak.</p>
                </div>
                <a href="{{ route('petani.pesanan') }}" style="display:inline-flex; align-items:center; gap:6px; padding:6px 12px; border-radius:8px; background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D; font-size:11px; font-weight:800; text-decoration:none;">
                    Lihat semua →
                </a>
            </div>

            @if($activeOrders->isEmpty())
            <div style="text-align:center; padding:40px 10px; color:#8A9D95;">
                <div style="font-size:32px; margin-bottom:8px;">📦</div>
                <div style="font-size:13px; font-weight:700;">Belum Ada Pesanan</div>
                <p style="font-size:11px; margin-top:4px;">Pesanan dari konsumen akan muncul di sini setelah komoditas dibooking.</p>
            </div>
            @else
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; text-align:left;">
                    <thead>
                        <tr>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">PESANAN</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">PEMBELI</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">NILAI</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">STATUS</th>
                            <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px; text-align:right;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeOrders->take(4) as $order)
                        <tr style="border-bottom:1px solid #EEF2F0;">
                            <td style="font-size:11px; font-weight:650; padding:14px 10px;">
                                <b style="font-weight:850; color:#183B30;">{{ $order->commodityListing->commodity_name ?? 'Komoditas' }}</b>
                                <div style="font-size:10px; color:#85958F; margin-top:2px;">{{ $order->order_code }} • {{ number_format($order->quantity_kg, 0, ',', '.') }} kg</div>
                            </td>
                            <td style="font-size:11px; font-weight:650; color:#172B29; padding:14px 10px;">
                                {{ $order->buyer->name ?? 'Pembeli' }}
                            </td>
                            <td style="font-size:11px; font-weight:800; color:#0A765A; padding:14px 10px;">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td style="font-size:11px; padding:14px 10px;">
                                <span style="display:inline-flex; align-items:center; padding:4px 8px; border-radius:6px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:10px;">
                                    {{ $order->status === 'dp_cair' ? 'DP Cair' : ucfirst($order->status) }}
                                </span>
                            </td>
                            <td style="font-size:11px; padding:14px 10px; text-align:right;">
                                <a href="{{ route('petani.pesanan.show', $order->id) }}"
                                   style="display:inline-block; padding:5px 12px; border-radius:7px; background:#EBF9F2; border:1px solid #C6EEDC; color:#078362; font-weight:800; font-size:11px; text-decoration:none;">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- Kanan: Progres Tanam (Monitoring) --}}
        @php
            $featuredOrder = $activeOrders->first();
        @endphp
        <div class="card" style="padding:22px;">
            <div style="margin-bottom:16px;">
                <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Progres Tanam</h2>
                <p style="font-size:11px; color:#91A19B; margin:0;">
                    {{ $featuredOrder ? 'Pesanan ' . $featuredOrder->order_code : 'Monitoring tanaman' }}
                </p>
            </div>

            @if($featuredOrder)
            <div>
                <span style="display:inline-flex; align-items:center; padding:4px 9px; border-radius:6px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:10px;">
                    {{ $featuredOrder->current_stage ?? 'Fase Pertumbuhan' }}
                </span>

                <div style="display:flex; justify-content:space-between; margin:20px 0 8px; font-size:12px; font-weight:750;">
                    <span style="color:#172B29;">Menuju panen</span>
                    <span style="color:#0A9370; font-weight:850;">{{ $featuredOrder->stage_percentage ?? 65 }}%</span>
                </div>

                {{-- Progress track --}}
                <div style="height:8px; border-radius:12px; background:#E8F2ED; overflow:hidden; margin-bottom:18px;">
                    <div style="height:100%; border-radius:12px; background:linear-gradient(90deg, #05C793, #14AAB1); width:{{ $featuredOrder->stage_percentage ?? 65 }}%;"></div>
                </div>

                <div style="height:1px; background:#E5ECEA; margin:16px 0;"></div>

                {{-- Dynamic Timeline Stages --}}
                <div style="display:flex; flex-direction:column; gap:10px;">
                    @foreach($featuredOrder->phase_timeline as $ph)
                    <div style="display:flex; align-items:center; justify-content:space-between; padding:8px 0; border-bottom:1px solid #EEF2F0; font-size:11px;">
                        <span>{{ $ph['icon'] }} &nbsp; {{ $ph['short'] }}</span>
                        @if($ph['status'] === 'selesai')
                            <span style="color:#0C8D65; font-weight:800; font-size:10px; background:#E9F9F2; padding:2px 7px; border-radius:5px;">Selesai</span>
                        @elseif($ph['status'] === 'berlangsung')
                            <span style="color:#0B8BAA; font-weight:800; font-size:10px; background:#E8F9FC; padding:2px 7px; border-radius:5px;">Berlangsung</span>
                        @else
                            <span style="color:#91A19B; font-weight:750; font-size:10px; background:#F5F7F6; padding:2px 7px; border-radius:5px;">Menunggu</span>
                        @endif
                    </div>
                    @endforeach
                </div>

                <div style="margin-top:20px; display:flex; flex-direction:column; gap:8px;">
                    <button type="button" @click="showUpdateModal = true"
                            style="display:flex; align-items:center; justify-content:center; gap:6px; width:100%; padding:10px; border-radius:11px; background:#05C793; border:none; color:#053B2D; font-size:12px; font-weight:800; cursor:pointer;">
                        <i class="fa-solid fa-camera"></i> Update & Foto Progres
                    </button>
                    <a href="{{ route('petani.pesanan.show', $featuredOrder->id) }}"
                       style="display:block; text-align:center; padding:9px; border-radius:11px; background:#fff; border:1px solid #DFE8E5; color:#365249; font-size:11px; font-weight:800; text-decoration:none;">
                        Detail Monitoring Tanam ↗
                    </a>
                </div>
            </div>
            @else
            <div style="text-align:center; padding:40px 10px; color:#8A9D95;">
                <p style="font-size:12px;">Belum ada pesanan aktif untuk dimonitor.</p>
            </div>
            @endif
        </div>

    </div>

    {{-- MODAL QUICK UPDATE PROGRES --}}
    @if($featuredOrder)
    <div x-cloak x-show="showUpdateModal"
         style="position:fixed; inset:0; z-index:100; display:flex; align-items:center; justify-content:center; background:rgba(21,40,31,0.52); padding:16px;">
        <div style="background:#fff; border-radius:20px; width:480px; max-width:95%; padding:26px; box-shadow:0 35px 90px rgba(16,35,29,0.4);"
             @click.away="showUpdateModal = false">
            
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                <h2 style="font-size:17px; font-weight:850; margin:0; color:#172B29;">Update Progres Tanam</h2>
                <button @click="showUpdateModal = false" style="background:none; border:none; font-size:16px; cursor:pointer; color:#8A9D95;">✕</button>
            </div>
            
            <p style="font-size:11.5px; color:#7C9086; margin:0 0 16px;">
                Pesanan <strong>{{ $featuredOrder->order_code }}</strong> ({{ $featuredOrder->commodityListing->commodity_name ?? 'Komoditas' }}). Laporan ini akan langsung tampil di dasbor konsumen.
            </p>

            @php
                $dashStageIdxMap = [
                    'Fase Benih & Penyemaian' => 1,
                    'Fase Vegetatif & Pertunasan' => 2,
                    'Fase Pembungaan & Bakal Buah' => 3,
                    'Fase Pematangan & Siap Panen' => 4,
                ];
                $dCurIdx = $dashStageIdxMap[$featuredOrder->current_stage ?? ''] ?? 1;
                $dCurStatus = $featuredOrder->current_stage_status ?? 'berlangsung';
                $dMinAvailableIdx = ($dCurStatus === 'selesai') ? min(4, $dCurIdx + 1) : $dCurIdx;
                $dIsAllCompleted = ($dCurIdx === 4 && $dCurStatus === 'selesai');
            @endphp

            @if($dIsAllCompleted)
            <div style="padding:14px; border-radius:12px; background:#EBFBF3; border:1px solid #C4EED9; color:#0A7B59; font-size:12px; margin-bottom:16px;">
                🎉 <b>Semua fase budidaya telah selesai tuntas (100%).</b> Pesanan ini telah siap panen dan masuk tahap serah terima logistik.
            </div>
            @endif

            <form action="{{ route('petani.progress.store') }}" method="POST" enctype="multipart/form-data" x-data="{ photoName: '' }">
                @csrf
                <input type="hidden" name="pre_order_id" value="{{ $featuredOrder->id }}">

                <div style="display:flex; flex-direction:column; gap:14px;">
                    <div style="display:grid; grid-template-columns:1.3fr 1fr; gap:12px;">
                        <div style="display:flex; flex-direction:column; gap:6px;">
                            <label style="font-size:11px; font-weight:800; color:#365448;">Fase Perkembangan *</label>
                            <select name="stage_name" required {{ $dIsAllCompleted ? 'disabled' : '' }}
                                    style="outline:0; width:100%; border:1px solid #DFEAE5; padding:11px 14px; border-radius:10px; background:#fff; font-size:12px;">
                                <option value="Fase Benih & Penyemaian" {{ 1 < $dMinAvailableIdx ? 'disabled' : '' }} {{ $dMinAvailableIdx === 1 ? 'selected' : '' }}>
                                    🌱 Fase Benih & Penyemaian {{ 1 < $dMinAvailableIdx ? '🔒 (Sudah Selesai)' : '' }}
                                </option>
                                <option value="Fase Vegetatif & Pertunasan" {{ 2 < $dMinAvailableIdx ? 'disabled' : '' }} {{ $dMinAvailableIdx === 2 ? 'selected' : '' }}>
                                    🌿 Fase Vegetatif & Pertunasan {{ 2 < $dMinAvailableIdx ? '🔒 (Sudah Selesai)' : '' }}
                                </option>
                                <option value="Fase Pembungaan & Bakal Buah" {{ 3 < $dMinAvailableIdx ? 'disabled' : '' }} {{ $dMinAvailableIdx === 3 ? 'selected' : '' }}>
                                    🌼 Fase Pembungaan & Bakal Buah {{ 3 < $dMinAvailableIdx ? '🔒 (Sudah Selesai)' : '' }}
                                </option>
                                <option value="Fase Pematangan & Siap Panen" {{ 4 < $dMinAvailableIdx ? 'disabled' : '' }} {{ $dMinAvailableIdx === 4 ? 'selected' : '' }}>
                                    🌶️ Fase Pematangan & Siap Panen {{ 4 < $dMinAvailableIdx ? '🔒 (Sudah Selesai)' : '' }}
                                </option>
                            </select>
                            <span style="font-size:10px; color:#859990;">* Fase yang telah selesai terkunci secara otomatis.</span>
                        </div>

                        <div style="display:flex; flex-direction:column; gap:6px;">
                            <label style="font-size:11px; font-weight:800; color:#365448;">Status Fase Ini *</label>
                            <select name="stage_status" required {{ $dIsAllCompleted ? 'disabled' : '' }}
                                    style="outline:0; width:100%; border:1px solid #DFEAE5; padding:11px 14px; border-radius:10px; background:#fff; font-size:12px;">
                                <option value="berlangsung" selected>Sedang Berlangsung</option>
                                <option value="selesai">Tandai Selesai (Otomatis Lanjut Fase) ↗</option>
                            </select>
                        </div>
                    </div>

                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Catatan Perkembangan Tanaman & Lapangan *</label>
                        <textarea name="note" rows="3" required placeholder="Contoh: Kondisi tanaman sangat sehat, pemupukan organik berjalan lancar, cuaca mendukung..."
                                  style="outline:0; width:100%; border:1px solid #DFEAE5; padding:11px 14px; border-radius:10px; background:#fff; font-size:12px; resize:vertical;"></textarea>
                    </div>

                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Foto Kondisi Saat Itu (Opsional)</label>
                        <label for="dashboard-progress-photo"
                               style="display:flex; flex-direction:column; align-items:center; justify-content:center; border:2px dashed #A9CBBB; border-radius:12px; padding:14px; background:#F8FCFA; text-align:center; color:#568977; font-size:11px; cursor:pointer; transition:all 0.2s;"
                               onmouseover="this.style.background='#EEFBF4'; this.style.borderColor='#05C793';"
                               onmouseout="this.style.background='#F8FCFA'; this.style.borderColor='#A9CBBB';">
                            <div style="font-size:22px; margin-bottom:4px;">📷</div>
                            <b style="color:#1C6F57;">Pilih Foto Dokumentasi Tanaman</b>
                            <span style="font-size:10px; color:#87968F; margin-top:2px;">Format JPG, PNG (Maks 2 MB)</span>
                            <span x-show="photoName" style="margin-top:6px; font-weight:800; color:#05C793; font-size:11px;" x-text="'File terpilih: ' + photoName"></span>
                            <input id="dashboard-progress-photo" type="file" name="photo" accept="image/*" style="display:none;"
                                   @change="photoName = $event.target.files[0] ? $event.target.files[0].name : ''">
                        </label>
                    </div>
                </div>

                <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:20px;">
                    <button type="button" @click="showUpdateModal = false"
                            style="padding:10px 16px; border-radius:11px; border:1px solid #DFEAE5; background:#fff; font-size:12px; font-weight:800; color:#365249; cursor:pointer;">
                        Batal
                    </button>
                    <button type="submit"
                            style="padding:10px 20px; border-radius:11px; border:none; background:#05C793; font-size:12px; font-weight:800; color:#053B2D; cursor:pointer;">
                        Simpan & Kirim Update ↗
                    </button>
                </div>
            </form>

        </div>
    </div>
    @endif

</div>
@endsection
