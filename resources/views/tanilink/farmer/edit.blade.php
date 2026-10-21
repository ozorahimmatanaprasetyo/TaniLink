@extends('layouts.sidebar')

@section('title', 'Edit Komoditas - TaniLink')

@section('sidebar_nav')
<a href="{{ route('portal.petani') }}" class="nav-btn {{ request()->routeIs('portal.petani') ? 'active' : '' }}">
    <span class="nav-icon">▦</span> Dashboard
</a>
<a href="{{ route('petani.komoditas') }}" class="nav-btn {{ request()->routeIs('petani.komoditas*') ? 'active' : '' }}">
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

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:26px;">
        <div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Edit Komoditas</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">Perbarui detail harga, estimasi panen, atau kuantitas penawaran.</p>
        </div>
        <a href="{{ route('petani.komoditas') }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 16px; border-radius:11px; border:1px solid #DFE8E5; background:#fff; color:#365249; font-size:12px; font-weight:800; text-decoration:none;">
            ← Kembali
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
    <form action="{{ route('petani.komoditas.update', $listing->id) }}" method="POST" enctype="multipart/form-data" x-data="{
        fileName: ''
    }">
        @csrf
        @method('PUT')
        <input type="hidden" name="available_kg" value="{{ $listing->available_kg }}">
        <input type="hidden" name="status" value="{{ $listing->status }}">
        
        <div style="display:grid; grid-template-columns:minmax(0, 1.5fr) minmax(320px, 1fr); gap:20px;">
            
            {{-- Kiri: Detail Komoditas --}}
            <div class="card" style="padding:24px;">
                <div style="margin-bottom:20px;">
                    <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Informasi Komoditas</h2>
                    <p style="font-size:11px; color:#91A19B; margin:0;">Perubahan akan langsung terbarui di marketplace pembeli konsumen.</p>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    
                    {{-- Nama --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Nama komoditas *</label>
                        <input type="text" name="commodity_name" value="{{ old('commodity_name', $listing->commodity_name) }}" required
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    {{-- Kategori --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Kategori *</label>
                        <select name="category" required
                                style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                            @foreach(['Cabai & Bumbu', 'Sayuran', 'Palawija', 'Buah', 'Biji-bijian'] as $cat)
                            <option value="{{ $cat }}" {{ old('category', $listing->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Varietas --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Varietas / Benih</label>
                        <input type="text" name="variety" value="{{ old('variety', $listing->variety) }}"
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    {{-- Luas Lahan --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Luas lahan (m²) *</label>
                        <input type="number" name="land_area_m2" value="{{ old('land_area_m2', $listing->land_area_m2) }}" required
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    {{-- Perkiraan Hasil --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Perkiraan hasil / Kapasitas (kg) *</label>
                        <input type="number" name="estimated_harvest_kg" value="{{ old('estimated_harvest_kg', $listing->estimated_harvest_kg) }}" required
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    {{-- Harga Penawaran --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Harga penawaran (Rp/kg) *</label>
                        <input type="number" name="price_per_kg" value="{{ old('price_per_kg', $listing->price_per_kg) }}" required
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    {{-- Estimasi Panen --}}
                    <div style="display:flex; flex-direction:column; gap:6px; grid-column:span 2;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Estimasi panen *</label>
                        <input type="date" name="harvest_estimated_date" value="{{ old('harvest_estimated_date', $listing->harvest_estimated_date ? \Carbon\Carbon::parse($listing->harvest_estimated_date)->format('Y-m-d') : '') }}" required
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    {{-- Deskripsi --}}
                    <div style="display:flex; flex-direction:column; gap:6px; grid-column:span 2;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Deskripsi & Kondisi Lahan</label>
                        <textarea name="description" rows="3"
                                  style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:86px; resize:vertical;">{{ old('description', $listing->description) }}</textarea>
                    </div>

                </div>

                <div style="height:1px; background:#E5ECEA; margin:22px 0;"></div>

                <div style="display:flex; gap:12px; justify-content:flex-end;">
                    <a href="{{ route('petani.komoditas') }}" style="padding:11px 18px; border-radius:11px; border:1px solid #DFEAE5; background:#fff; font-size:12px; font-weight:800; color:#365249; text-decoration:none;">
                        Batal
                    </a>
                    <button type="submit" style="padding:11px 22px; border-radius:11px; border:none; background:#05C793; font-size:12px; font-weight:800; color:#053B2D; cursor:pointer;">
                        Simpan Perubahan ↗
                    </button>
                </div>
            </div>

            {{-- Kanan: Foto Komoditas --}}
            <div style="display:flex; flex-direction:column; gap:18px;">
                <div class="card" style="padding:22px;">
                    <div style="margin-bottom:14px;">
                        <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Foto Komoditas</h2>
                        <p style="font-size:11px; color:#91A19B; margin:0;">Klik kotak di bawah untuk mengganti foto.</p>
                    </div>

                    @if($listing->image_url)
                    <div style="margin-bottom:12px; border-radius:10px; overflow:hidden; border:1px solid #E5ECEA; height:140px;">
                        <img src="{{ str_starts_with($listing->image_url, '/storage') ? asset($listing->image_url) : $listing->image_url }}"
                             alt="{{ $listing->commodity_name }}" style="width:100%; height:100%; object-fit:cover;">
                    </div>
                    @endif

                    <label for="edit-photo-input"
                           style="display:flex; flex-direction:column; align-items:center; justify-content:center; border:2px dashed #A9CBBB; border-radius:14px; padding:22px 16px; background:#F8FCFA; min-height:120px; text-align:center; color:#568977; font-size:11px; cursor:pointer; transition:all 0.2s;"
                           onmouseover="this.style.background='#EEFBF4'; this.style.borderColor='#05C793';"
                           onmouseout="this.style.background='#F8FCFA'; this.style.borderColor='#A9CBBB';">
                        <div style="font-size:28px; margin-bottom:6px;">📷</div>
                        <b style="color:#1C6F57;">Klik untuk memilih foto baru</b>
                        <span style="font-size:10px; color:#87968F; margin-top:4px;">JPG, PNG, JPEG &bull; Maks 2 MB</span>
                        <span x-show="fileName" style="margin-top:8px; font-weight:700; color:#05C793; font-size:11px;" x-text="'File terpilih: ' + fileName"></span>
                        <input id="edit-photo-input" type="file" name="image" accept="image/*" style="display:none;"
                               @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
                    </label>
                </div>
            </div>

        </div>
    </form>

@endsection

