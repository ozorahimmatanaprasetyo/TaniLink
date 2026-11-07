@extends('layouts.sidebar')

@section('title', 'Edit Komoditas - TaniLink')

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

@section('breadcrumb')
    <a href="{{ route('petani.komoditas') }}" style="color:inherit; text-decoration:none;">Komoditas Saya</a> &rsaquo; <strong>Edit Komoditas</strong>
@endsection

@push('styles')
<style>
    .edit-headline {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 14px;
    }
    .edit-form-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.5fr) minmax(320px, 1fr);
        gap: 20px;
    }
    .edit-fields-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    .edit-col-span-2 {
        grid-column: span 2;
    }
    .edit-actions-group {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }
    @media (max-width: 992px) {
        .edit-form-layout {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 640px) {
        .edit-headline {
            flex-direction: column;
            align-items: flex-start;
        }
        .edit-headline .back-btn {
            width: 100%;
            justify-content: center;
        }
        .edit-fields-grid {
            grid-template-columns: 1fr;
            gap: 14px;
        }
        .edit-col-span-2 {
            grid-column: span 1;
        }
        .edit-actions-group {
            flex-direction: column-reverse;
        }
        .edit-actions-group a,
        .edit-actions-group button {
            width: 100%;
            text-align: center;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')

    {{-- Headline --}}
    <div class="edit-headline">
        <div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Edit Komoditas</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">Perbarui detail harga, estimasi panen, atau kuantitas penawaran.</p>
        </div>
        <a href="{{ route('petani.komoditas') }}" class="back-btn" style="display:inline-flex; align-items:center; gap:8px; padding:10px 16px; border-radius:11px; border:1px solid #DFE8E5; background:#fff; color:#365249; font-size:12px; font-weight:800; text-decoration:none;">
            <i class="fa-solid fa-arrow-left"></i> Kembali
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
    <form action="{{ route('petani.komoditas.update', $listing->id) }}" method="POST" enctype="multipart/form-data"
          x-data="{
              fileName: '',
              selectedCategory: '{{ old('category', $listing->category) }}',
              get isOtherCategory() {
                  return this.selectedCategory === 'Lainnya' || this.selectedCategory === 'Lainnya / Ajukan Kategori Baru';
              }
          }">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" value="{{ $listing->status }}">
        
        <div class="edit-form-layout">
            
            {{-- Kiri: Detail Komoditas --}}
            <div class="card" style="padding:24px;">
                <div style="margin-bottom:20px;">
                    <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Informasi Komoditas</h2>
                    <p style="font-size:11px; color:#91A19B; margin:0;">Perubahan akan langsung terbarui di marketplace pembeli konsumen.</p>
                </div>

                <div class="edit-fields-grid">
                    
                    {{-- Nama --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Nama Komoditas <span style="color:#E11D48;">*</span></label>
                        <input type="text" name="commodity_name" value="{{ old('commodity_name', $listing->commodity_name) }}" required
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    {{-- Kategori --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Kategori <span style="color:#E11D48;">*</span></label>
                        <select name="category" x-model="selectedCategory" required
                                style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                            @foreach($availableCategories ?? ['Cabai & Bumbu', 'Sayuran', 'Palawija', 'Buah', 'Biji-bijian'] as $cat)
                            <option value="{{ $cat }}" {{ old('category', $listing->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                            <option value="Lainnya" {{ old('category', $listing->category) == 'Lainnya' ? 'selected' : '' }}>Lainnya / Ajukan Kategori Baru</option>
                        </select>
                    </div>

                    {{-- Usulan Kategori Baru --}}
                    <div x-show="isOtherCategory" x-cloak class="edit-col-span-2" style="display:flex; flex-direction:column; gap:6px; background:#F8FCFA; border:1px dashed #A9CBBB; border-radius:10px; padding:14px;">
                        <label style="font-size:11px; font-weight:800; color:#1C6F57;">Nama Kategori yang Diusulkan</label>
                        <input type="text" name="proposed_category" value="{{ old('proposed_category') }}"
                               placeholder="Contoh: Tanaman Obat / Herbal, Jamur, Bunga Hias..."
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:11px 13px; border-radius:8px; background:#fff; color:#254D3F; font-size:12px;">
                        <small style="font-size:10px; color:#5D7D72;">Usulan kategori akan disimpan untuk ditinjau oleh Admin.</small>
                    </div>

                    {{-- Varietas atau Jenis Bibit --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Varietas atau Jenis Bibit</label>
                        <input type="text" name="variety" value="{{ old('variety', $listing->variety) }}"
                               placeholder="Contoh: Ori 212, IR 64, Bibit Hibrida F1..."
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                        <small style="font-size:10px; color:#8A9A94;">Kosongkan jika tidak mengetahui varietasnya.</small>
                    </div>

                    {{-- Grade & Kualitas Mutu --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Grade &amp; Kualitas Mutu <span style="color:#E11D48;">*</span></label>
                        @php
                            $currentGrade = old('grade', $listing->grade ?: 'Grade A');
                            // Normalize to short grade keys
                            $gradeMap = [
                                'Grade A (Ekspor & Supermarket)' => 'Grade A',
                                'Grade B (Pasar Swalayan & Resto)' => 'Grade B',
                                'Grade C (Lokal Pasar)' => 'Grade C',
                                'Kualitas Pilihan' => 'Grade A',
                                'Kualitas Standar' => 'Grade B',
                                'Kualitas Campuran' => 'Grade C',
                                'Belum Ditentukan' => 'Standar',
                            ];
                            $normalizedGrade = $gradeMap[$currentGrade] ?? $currentGrade;
                        @endphp
                        <select name="grade"
                                style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                            <option value="Grade A" {{ $normalizedGrade == 'Grade A' ? 'selected' : '' }}>Grade A (Kualitas Pilihan / Supermarket)</option>
                            <option value="Grade B" {{ $normalizedGrade == 'Grade B' ? 'selected' : '' }}>Grade B (Kualitas Standar / Pasar Swalayan &amp; Resto)</option>
                            <option value="Grade C" {{ $normalizedGrade == 'Grade C' ? 'selected' : '' }}>Grade C (Kualitas Campuran / Pasar Tradisional)</option>
                            <option value="Standar" {{ $normalizedGrade == 'Standar' ? 'selected' : '' }}>Standar Mutu Panen Umum</option>
                        </select>
                        <small style="font-size:10px; color:#8A9A94;">Tingkat mutu panen untuk spesifikasi pesanan konsumen.</small>
                    </div>

                    {{-- Luas Lahan --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Luas Lahan (m²) <span style="color:#E11D48;">*</span></label>
                        <input type="number" name="land_area_m2" value="{{ old('land_area_m2', $listing->land_area_m2) }}" required
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    {{-- Perkiraan Total Hasil --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Estimasi Total Panen (kg) <span style="color:#E11D48;">*</span></label>
                        <input type="number" name="estimated_harvest_kg" value="{{ old('estimated_harvest_kg', $listing->estimated_harvest_kg) }}" required
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    {{-- Alokasi Dipesan --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Sisa Alokasi Dipesan (kg) <span style="color:#E11D48;">*</span></label>
                        <input type="number" name="available_kg" value="{{ old('available_kg', $listing->available_kg) }}" required
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    {{-- Harga Penawaran --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Harga Penawaran (Rp/kg) <span style="color:#E11D48;">*</span></label>
                        <input type="number" name="price_per_kg" value="{{ old('price_per_kg', $listing->price_per_kg) }}" required
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    {{-- Perkiraan Waktu Panen --}}
                    <div class="edit-col-span-2" style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Perkiraan Waktu Panen <span style="color:#E11D48;">*</span></label>
                        <input type="date" name="harvest_estimated_date" value="{{ old('harvest_estimated_date', $listing->harvest_estimated_date ? \Carbon\Carbon::parse($listing->harvest_estimated_date)->format('Y-m-d') : '') }}" required
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    {{-- Deskripsi --}}
                    <div class="edit-col-span-2" style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Deskripsi &amp; Kondisi Lahan</label>
                        <textarea name="description" rows="3"
                                  style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:86px; resize:vertical;">{{ old('description', $listing->description) }}</textarea>
                    </div>

                </div>

                <div style="height:1px; background:#E5ECEA; margin:22px 0;"></div>

                <div class="edit-actions-group">
                    <a href="{{ route('petani.komoditas') }}" style="display:inline-flex; align-items:center; justify-content:center; padding:11px 18px; border-radius:11px; border:1px solid #DFEAE5; background:#fff; font-size:12px; font-weight:800; color:#365249; text-decoration:none;">
                        Batal
                    </a>
                    <button type="submit" style="display:inline-flex; align-items:center; justify-content:center; padding:11px 22px; border-radius:11px; border:none; background:#05C793; font-size:12px; font-weight:800; color:#053B2D; cursor:pointer;">
                        Simpan Perubahan
                    </button>
                </div>
            </div>

            {{-- Kanan: Foto Komoditas --}}
            <div style="display:flex; flex-direction:column; gap:18px;">
                <div class="card" style="padding:22px;">
                    <div style="margin-bottom:14px;">
                        <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Foto Komoditas</h2>
                        <p style="font-size:11px; color:#91A19B; margin:0;">
                            {{ $listing->image_url ? 'Kosongkan jika tidak ingin mengubah foto komoditas.' : 'Silakan unggah foto komoditas.' }}
                        </p>
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
                        <i class="fa-solid fa-camera" style="font-size:28px; margin-bottom:6px; color:#1C6F57;"></i>
                        <b style="color:#1C6F57;">{{ $listing->image_url ? 'Klik untuk memilih foto baru' : 'Pilih foto komoditas *' }}</b>
                        <span style="font-size:10px; color:#87968F; margin-top:4px;">JPG, JPEG, PNG, WebP &bull; Maks 2 MB</span>
                        <span x-show="fileName" style="margin-top:8px; font-weight:700; color:#05C793; font-size:11px;" x-text="'File terpilih: ' + fileName"></span>
                        <input id="edit-photo-input" type="file" name="image" accept="image/jpeg,image/png,image/jpg,image/webp" {{ $listing->image_url ? '' : 'required' }} style="display:none;"
                               @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
                    </label>
                </div>
            </div>

        </div>
    </form>

@endsection
