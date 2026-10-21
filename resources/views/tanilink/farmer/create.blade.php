@extends('layouts.sidebar')

@section('title', 'Tambah Komoditas - TaniLink')

@section('sidebar_nav')
<a href="{{ route('portal.petani') }}" class="nav-btn {{ request()->routeIs('portal.petani') ? 'active' : '' }}">
    <span class="nav-icon">▦</span> Dashboard
</a>
<a href="{{ route('petani.komoditas') }}" class="nav-btn {{ request()->routeIs('petani.komoditas') ? 'active' : '' }}">
    <span class="nav-icon">♧</span> Komoditas Saya
</a>
<a href="{{ route('petani.komoditas.create') }}" class="nav-btn {{ request()->routeIs('petani.komoditas.create') ? 'active' : '' }}">
    <span class="nav-icon">⊕</span> Tambah Komoditas
</a>
<a href="{{ route('petani.pesanan') }}" class="nav-btn {{ request()->routeIs('petani.pesanan*') ? 'active' : '' }}">
    <span class="nav-icon">▤</span> Pesanan Masuk
</a>
<a href="{{ route('petani.profil') }}" class="nav-btn {{ request()->routeIs('petani.profil') ? 'active' : '' }}">
    <span class="nav-icon">♙</span> Profil Petani
</a>
@endsection

@section('breadcrumb')
    <a href="{{ route('petani.komoditas') }}" style="color:inherit; text-decoration:none;">Komoditas Saya</a> &rsaquo; <strong>Tambah Komoditas</strong>
@endsection

@section('content')

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:26px;">
        <div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Tambah Komoditas</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">Publikasikan komoditas yang siap tersedia atau masih dalam rencana panen.</p>
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
    <form action="{{ route('petani.jualan.store') }}" method="POST" enctype="multipart/form-data" x-data="{
        fileName: ''
    }">
        @csrf
        <div style="display:grid; grid-template-columns:minmax(0, 1.5fr) minmax(320px, 1fr); gap:20px;">
            
            {{-- Kiri: Detail Komoditas --}}
            <div class="card" style="padding:24px;">
                <div style="margin-bottom:20px;">
                    <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Informasi Komoditas</h2>
                    <p style="font-size:11px; color:#91A19B; margin:0;">Data akan tampil pada katalog marketplace pembeli konsumen.</p>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    
                    {{-- Nama --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Nama komoditas *</label>
                        <input type="text" name="commodity_name" value="{{ old('commodity_name') }}" required
                               placeholder="Contoh: Cabai Rawit Merah, Beras Pandan Wangi..."
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    {{-- Kategori --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Kategori *</label>
                        <select name="category" required
                                style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                            <option value="" disabled {{ old('category') ? '' : 'selected' }}>Pilih Kategori Komoditas</option>
                            <option value="Cabai & Bumbu" {{ old('category') == 'Cabai & Bumbu' ? 'selected' : '' }}>Cabai & Bumbu</option>
                            <option value="Sayuran" {{ old('category') == 'Sayuran' ? 'selected' : '' }}>Sayuran</option>
                            <option value="Palawija" {{ old('category') == 'Palawija' ? 'selected' : '' }}>Palawija</option>
                            <option value="Buah" {{ old('category') == 'Buah' ? 'selected' : '' }}>Buah</option>
                            <option value="Biji-bijian" {{ old('category') == 'Biji-bijian' ? 'selected' : '' }}>Biji-bijian</option>
                        </select>
                    </div>

                    {{-- Varietas --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Varietas / Benih</label>
                        <input type="text" name="variety" value="{{ old('variety') }}"
                               placeholder="Contoh: Ori 212, IR 64, Hibrida F1..."
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    {{-- Luas Lahan --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Luas lahan (m²) *</label>
                        <input type="number" name="land_area_m2" value="{{ old('land_area_m2') }}" required
                               placeholder="Contoh: 2500"
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    {{-- Perkiraan Hasil --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Perkiraan hasil panen (kg) *</label>
                        <input type="number" name="estimated_harvest_kg" value="{{ old('estimated_harvest_kg') }}" required
                               placeholder="Contoh: 1000"
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    {{-- Harga Penawaran --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Harga penawaran (Rp/kg) *</label>
                        <input type="number" name="price_per_kg" value="{{ old('price_per_kg') }}" required
                               placeholder="Contoh: 28000"
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    {{-- Estimasi Panen --}}
                    <div style="display:flex; flex-direction:column; gap:6px; grid-column:span 2;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Estimasi panen *</label>
                        <input type="date" name="harvest_estimated_date" value="{{ old('harvest_estimated_date') }}" required
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    {{-- Deskripsi --}}
                    <div style="display:flex; flex-direction:column; gap:6px; grid-column:span 2;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Deskripsi & Kondisi Lahan</label>
                        <textarea name="description" rows="3"
                                  placeholder="Jelaskan kualitas bibit, metode budidaya, atau sistem irigasi lahan Anda..."
                                  style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:86px; resize:vertical;">{{ old('description') }}</textarea>
                    </div>

                </div>

                <div style="height:1px; background:#E5ECEA; margin:22px 0;"></div>

                <div style="display:flex; gap:12px; justify-content:flex-end;">
                    <a href="{{ route('petani.komoditas') }}" style="padding:11px 18px; border-radius:11px; border:1px solid #DFEAE5; background:#fff; font-size:12px; font-weight:800; color:#365249; text-decoration:none;">
                        Batal
                    </a>
                    <button type="submit" style="padding:11px 22px; border-radius:11px; border:none; background:#05C793; font-size:12px; font-weight:800; color:#053B2D; cursor:pointer; box-shadow:0 3px 10px rgba(5,199,147,0.25);">
                        Publikasikan Komoditas ↗
                    </button>
                </div>
            </div>

            {{-- Kanan: Upload Foto & Panduan --}}
            <div style="display:flex; flex-direction:column; gap:18px;">
                
                {{-- Foto Card with Whole Area Clickable --}}
                <div class="card" style="padding:22px;">
                    <div style="margin-bottom:14px;">
                        <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Foto Komoditas</h2>
                        <p style="font-size:11px; color:#91A19B; margin:0;">Upload foto komoditas dengan pencahayaan jelas.</p>
                    </div>

                    <label for="create-commodity-photo"
                           style="display:flex; flex-direction:column; align-items:center; justify-content:center; border:2px dashed #A9CBBB; border-radius:14px; padding:28px 16px; background:#F8FCFA; min-height:140px; text-align:center; color:#568977; font-size:11px; cursor:pointer; transition:all 0.2s;"
                           onmouseover="this.style.background='#EEFBF4'; this.style.borderColor='#05C793';"
                           onmouseout="this.style.background='#F8FCFA'; this.style.borderColor='#A9CBBB';">
                        <div style="font-size:32px; margin-bottom:8px;">📷</div>
                        <b style="color:#1C6F57; font-size:12px;">Pilih foto komoditas</b>
                        <span style="font-size:10px; color:#87968F; margin-top:4px;">Klik area ini untuk memilih file (JPG, PNG, JPEG &bull; Maks 2 MB)</span>
                        <span x-show="fileName" style="margin-top:10px; font-weight:700; color:#05C793; font-size:11px; background:#EBF9F2; padding:3px 10px; border-radius:8px;" x-text="'File terpilih: ' + fileName"></span>
                        <input id="create-commodity-photo" type="file" name="image" accept="image/*" style="display:none;"
                               @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
                    </label>
                </div>

                {{-- Panduan Listing --}}
                <div class="card" style="padding:22px;">
                    <h2 style="font-size:15px; font-weight:850; margin:0 0 16px; color:#172B29;">Panduan Listing</h2>
                    
                    <div style="display:flex; flex-direction:column; gap:14px;">
                        <div style="display:flex; gap:12px;">
                            <div style="height:25px; width:25px; min-width:25px; border-radius:50%; display:grid; place-items:center; background:#E5F9EF; color:#069873; font-size:11px; font-weight:800;">1</div>
                            <div>
                                <b style="font-size:12px; color:#172B29;">Isi spesifikasi</b>
                                <p style="font-size:10px; color:#8A9994; line-height:1.5; margin:3px 0 0;">Komoditas, varietas benih, dan patokan harga tetap per kg.</p>
                            </div>
                        </div>

                        <div style="display:flex; gap:12px;">
                            <div style="height:25px; width:25px; min-width:25px; border-radius:50%; display:grid; place-items:center; background:#E5F9EF; color:#069873; font-size:11px; font-weight:800;">2</div>
                            <div>
                                <b style="font-size:12px; color:#172B29;">Tuliskan perkiraan panen</b>
                                <p style="font-size:10px; color:#8A9994; line-height:1.5; margin:3px 0 0;">Transparan mengenai tanggal perkiraan panen agar pembeli bisa menjadwalkan suplai.</p>
                            </div>
                        </div>

                        <div style="display:flex; gap:12px;">
                            <div style="height:25px; width:25px; min-width:25px; border-radius:50%; display:grid; place-items:center; background:#E5F9EF; color:#069873; font-size:11px; font-weight:800;">3</div>
                            <div>
                                <b style="font-size:12px; color:#172B29;">Publikasikan penawaran</b>
                                <p style="font-size:10px; color:#8A9994; line-height:1.5; margin:3px 0 0;">Pembeli konsumen melihat informasi pada marketplace dan dapat melakukan booking.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </form>

@endsection
