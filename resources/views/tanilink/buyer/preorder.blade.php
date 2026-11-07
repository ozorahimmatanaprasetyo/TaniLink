@extends('layouts.sidebar')

@section('title', 'Ajukan Pre-Order ' . $listing->commodity_name . ' - TaniLink')

@section('sidebar_nav')
<a href="{{ route('portal.pembeli') }}" class="nav-btn {{ request()->routeIs('portal.pembeli') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-chart-pie"></i></span> Beranda
</a>
<a href="{{ route('pembeli.katalog') }}" class="nav-btn {{ request()->routeIs('pembeli.katalog*') || request()->routeIs('pembeli.komoditas*') || request()->routeIs('pembeli.preorder*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-store"></i></span> Marketplace
</a>
<a href="{{ route('pembeli.pesanan') }}" class="nav-btn {{ request()->routeIs('pembeli.pesanan*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-bag-shopping"></i></span> Pesanan Saya
</a>
<a href="{{ route('pembeli.profil') }}" class="nav-btn {{ request()->routeIs('pembeli.profil*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-user"></i></span> Profil Konsumen
</a>
@endsection

@push('styles')
<style>
.preorder-headline {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 12px;
}
.preorder-layout {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(320px, 1fr);
    gap: 24px;
}
.preorder-input-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 16px;
}
.preorder-finance-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 14px;
}
@media (max-width: 992px) {
    .preorder-layout {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 640px) {
    .preorder-headline {
        flex-direction: column;
        align-items: flex-start;
    }
    .preorder-headline a {
        width: 100%;
        justify-content: center;
    }
    .preorder-input-grid {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 420px) {
    .preorder-finance-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div x-data="{
    qty: '',
    price: {{ (int)$listing->price_per_kg }},
    maxQty: {{ $listing->available_kg }},
    agree: true,
    get total() { return (this.qty ? this.qty : 0) * this.price; },
    get dp() { return Math.round(this.total * 0.3); },
    get escrow() { return Math.round(this.total * 0.7); }
}">

    {{-- Headline --}}
    <div class="preorder-headline">
        <div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Ajukan Pre-Order</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">
                {{ $listing->commodity_name }} &bull; {{ $listing->farmer->organization ?? ($listing->farmer->name ?? 'Gapoktan') }}
            </p>
        </div>
        <a href="{{ route('pembeli.komoditas.show', $listing->id) }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 16px; border-radius:11px; border:1px solid #DFE8E5; background:#fff; color:#365249; font-size:12px; font-weight:800; text-decoration:none;">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke detail
        </a>
    </div>

    @if($errors->any())
    <div style="background:#FEE2E2; border:1px solid #FCA5A5; color:#991B1B; padding:12px 16px; border-radius:12px; font-size:13px; font-weight:600; margin-bottom:20px;">
        <ul style="margin:0; padding-left:20px;">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Form Grid --}}
    <form action="{{ route('pembeli.booking.store') }}" method="POST">
        @csrf
        <input type="hidden" name="listing_id" value="{{ $listing->id }}">

        <div class="preorder-layout">
            
            {{-- Kiri: Detail Kebutuhan Bisnis --}}
            <div class="card" style="padding:26px;">
                <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Detail Kebutuhan Pesanan</h2>
                <p style="font-size:11px; color:#91A19B; margin:0 0 20px;">Pastikan jumlah dan jadwal pemenuhan sesuai rencana kebutuhan usaha Anda.</p>

                {{-- Field commodity --}}
                <div style="display:flex; flex-direction:column; gap:6px; margin-bottom:16px;">
                    <label style="font-size:11px; font-weight:800; color:#365448;">Komoditas Terpilih</label>
                    <div style="border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#F8FCFA; color:#172B29; font-size:12px; font-weight:750; display:flex; align-items:center; gap:8px;">
                        <i class="fa-solid fa-seedling" style="color:#0C8D65;"></i>
                        <span>{{ $listing->commodity_name }} &bull; Varietas {{ $listing->variety ?? '-' }} &bull; Lokasi {{ $listing->location ?? 'Malang' }}</span>
                    </div>
                </div>

                <div class="preorder-input-grid">
                    {{-- Jumlah kg --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Jumlah Pemesanan (kg) <span style="color:#E11D48;">*</span></label>
                        <input type="number" name="quantity_kg" x-model.number="qty" min="1" :max="maxQty" required
                               placeholder="Contoh: 100"
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                        <small style="color:#87968F; font-size:10px;">Tersedia maksimal: <b x-text="maxQty"></b> kg</small>
                    </div>

                    {{-- Tanggal dibutuhkan --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Tanggal Dibutuhkan <span style="color:#E11D48;">*</span></label>
                        <input type="date" name="need_date" value="{{ $listing->harvest_estimated_date ? \Carbon\Carbon::parse($listing->harvest_estimated_date)->format('Y-m-d') : now()->addDays(20)->format('Y-m-d') }}" required
                               min="{{ $listing->harvest_estimated_date ? \Carbon\Carbon::parse($listing->harvest_estimated_date)->format('Y-m-d') : '' }}"
                               style="outline:0; width:100%; border:1.5px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; font-size:12px;">
                        <small style="color:#87968F; font-size:10px;">Estimasi panen: {{ \Carbon\Carbon::parse($listing->harvest_estimated_date)->format('d M Y') }}</small>
                    </div>
                </div>
                    {{-- Jumlah kg --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Jumlah Pemesanan (kg) <span style="color:#E11D48;">*</span></label>
                        <input type="number" name="quantity_kg" x-model.number="qty" min="1" :max="maxQty" required
                               placeholder="Contoh: 100"
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                        <small style="color:#87968F; font-size:10px;">Tersedia maksimal: <b x-text="maxQty"></b> kg</small>
                    </div>

                    {{-- Tanggal dibutuhkan --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Tanggal Dibutuhkan <span style="color:#E11D48;">*</span></label>
                        <input type="date" name="need_date" value="{{ $listing->harvest_estimated_date ? \Carbon\Carbon::parse($listing->harvest_estimated_date)->format('Y-m-d') : now()->addDays(20)->format('Y-m-d') }}" required
                               min="{{ $listing->harvest_estimated_date ? \Carbon\Carbon::parse($listing->harvest_estimated_date)->format('Y-m-d') : '' }}"
                               style="outline:0; width:100%; border:1.5px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; font-size:12px;">
                        <small style="color:#87968F; font-size:10px;">Estimasi panen: {{ \Carbon\Carbon::parse($listing->harvest_estimated_date)->format('d M Y') }}</small>
                    </div>
                </div>

                {{-- Catatan untuk petani --}}
                <div style="display:flex; flex-direction:column; gap:6px; margin-bottom:18px;">
                    <label style="font-size:11px; font-weight:800; color:#365448;">Catatan Khusus untuk Kelompok Tani</label>
                    <textarea name="notes" rows="3" placeholder="Contoh: Kualitas segar, sortir ukuran seragam, dan pengemasan krat standar..."
                              style="outline:0; width:100%; border:1.5px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; font-size:12px; resize:vertical;"></textarea>
                </div>

                {{-- Notice cyan --}}
                <div style="padding:14px 16px; border-radius:12px; background:#EEF9FB; border:1px solid #CCEBF2; color:#287B8A; font-size:11px; line-height:1.6; margin-bottom:18px;">
                    <i class="fa-solid fa-circle-info"></i> <b>Skema Kesepakatan Pemesanan:</b> Pengajuan ini merupakan komitmen kuantitas dan harga tetap sebelum panen. Petani akan mengonfirmasi ketersediaan lahan sebelum pesanan diproses.
                </div>

                {{-- Checkbox --}}
                <label style="display:flex; align-items:flex-start; gap:10px; cursor:pointer; margin-bottom:24px;">
                    <input type="checkbox" x-model="" required style="width:18px; height:18px; margin-top:2px; accent-color:#05C793;">
                    <span style="font-size:11.5px; color:#50695E; line-height:1.6;">
                        Saya menyetujui bahwa jadwal panen, harga penawaran, dan spesifikasi pesanan terikat dalam pemesanan sebelum panen TaniLink.
                    </span>
                </label>

                {{-- Tombol Pengajuan --}}
                <div>
                    <button type="submit"
                            style="width:100%; padding:15px 24px; border-radius:11px; border:none; background:#05C793; color:#033527; font-size:14px; font-weight:850; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:10px; box-shadow:0 4px 14px rgba(5,199,147,0.3); transition:all 0.15s;">
                        <i class="fa-solid fa-file-signature"></i>
                        <span>Kirim Pengajuan Pesanan</span>
                    </button>
                    <p style="font-size:11px; color:#859990; text-align:center; margin:8px 0 0;">
                        Klik tombol di atas setelah menentukan kuantitas panen yang diinginkan.
                    </p>
                </div>

            </div>

            {{-- Kanan: Ringkasan Harga & Panen --}}
            <div style="display:flex; flex-direction:column; gap:18px;">
                
                <div class="card" style="padding:22px;">
                    <h2 style="font-size:15px; font-weight:850; margin:0 0 16px; color:#172B29;">Ringkasan Harga & Kontrak</h2>
                    
                    <div style="display:flex; justify-content:space-between; font-size:12px; margin-bottom:12px;">
                        <span style="color:#8A9994;">Harga per kg</span>
                        <b style="color:#172B29;">Rp {{ number_format($listing->price_per_kg, 0, ',', '.') }}</b>
                    </div>

                    <div style="display:flex; justify-content:space-between; font-size:12px; margin-bottom:12px;">
                        <span style="color:#8A9994;">Kuantitas yang diajukan</span>
                        <b style="color:#172B29;"><span x-text="qty"></span> kg</b>
                    </div>

                    <div style="height:1px; background:#E5ECEA; margin:14px 0;"></div>

                    <div style="display:flex; justify-content:space-between; align-items:baseline; margin-bottom:18px;">
                        <b style="font-size:13px; color:#172B29;">Estimasi Total</b>
                        <b style="font-size:22px; font-weight:900; color:#09815E;">
                            Rp <span x-text="total.toLocaleString('id-ID')"></span>
                        </b>
                    </div>

                    {{-- Finance 2x2 cells --}}
                    <div class="preorder-finance-grid">
                        <div style="border-radius:11px; background:#F7FAF9; padding:12px;">
                            <span style="font-size:10px; color:#889991;">DP 30% (Modal Benih)</span>
                            <b style="display:block; font-size:16px; color:#0C8D65; margin-top:4px;">
                                Rp <span x-text="dp.toLocaleString('id-ID')"></span>
                            </b>
                        </div>
                        <div style="border-radius:11px; background:#F7FAF9; padding:12px;">
                            <span style="font-size:10px; color:#889991;">Escrow 70% (Terkunci)</span>
                            <b style="display:block; font-size:16px; color:#6953B5; margin-top:4px;">
                                Rp <span x-text="escrow.toLocaleString('id-ID')"></span>
                            </b>
                        </div>
                    </div>

                    <div style="font-size:10px; color:#93A69B; border-top:1px dashed #DCE7E1; padding-top:10px; line-height:1.6;">
                        Sesuai konsep tata niaga TaniLink: 30% diteruskan ke petani sebagai modal benih awal, 70% diamankan hingga panen sampai.
                    </div>
                </div>

                {{-- Estimasi Panen Card --}}
                <div class="card" style="padding:22px;">
                    <h2 style="font-size:15px; font-weight:850; margin:0 0 12px; color:#172B29;">Estimasi Siap Panen</h2>
                    
                    <div style="display:flex; gap:12px; align-items:flex-start;">
                        <div style="width:34px; height:34px; border-radius:10px; background:#E9FBF3; color:#139B78; display:grid; place-items:center; font-size:15px; flex-shrink:0;">
                            <i class="fa-regular fa-calendar-days"></i>
                        </div>
                        <div>
                            <b style="font-size:13px; color:#172B29;">
                                {{ $listing->harvest_estimated_date ? \Carbon\Carbon::parse($listing->harvest_estimated_date)->format('d F Y') : '-' }}
                            </b>
                            <p style="font-size:11px; color:#8A9994; margin:4px 0 0; line-height:1.5;">
                                Estimasi jadwal panen pertama dari kelompok tani {{ $listing->farmer->organization ?? 'Mitra' }}.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </form>

</div>
@endsection

