@extends('layouts.sidebar')

@section('title', 'Profil Petani - TaniLink')

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

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:26px;">
        <div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Profil Petani</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">Kelola identitas produsen, rekening pencairan DP, dan informasi bisnis Anda.</p>
        </div>
    </div>

    @if(session('success'))
    <div style="background:#ECFDF8; border:1px solid #6EE7B7; color:#065F46; padding:12px 16px; border-radius:12px; font-size:13px; font-weight:600; display:flex; align-items:center; gap:10px; margin-bottom:20px;">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Grid 2 Kolom --}}
    <div style="display:grid; grid-template-columns:minmax(0, 1.5fr) minmax(320px, 1fr); gap:20px;">
        
        {{-- Kiri: Form Profil --}}
        <div class="card" style="padding:24px;">
            <div style="margin-bottom:20px;">
                <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Informasi Produsen</h2>
                <p style="font-size:11px; color:#91A19B; margin:0;">Informasi legalitas dan kontak kelompok tani Anda.</p>
            </div>

            <form action="{{ route('petani.profil.update') }}" method="POST">
                @csrf
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Nama penanggung jawab *</label>
                        <input type="text" name="name" value="{{ old('name', $petani->name) }}" required
                               placeholder="Nama lengkap penanggung jawab"
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Nama kelompok tani / Usaha</label>
                        <input type="text" name="organization" value="{{ old('organization', $petani->organization) }}"
                               placeholder="Belum diisi - Contoh: Kelompok Tani Makmur"
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Email akun</label>
                        <input type="email" value="{{ $petani->email }}" disabled
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#F5F8F7; color:#8A9A94; font-size:12px; min-height:43px;">
                    </div>

                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Nomor WhatsApp / Kontak</label>
                        <input type="text" name="phone" value="{{ old('phone', $petani->phone) }}"
                               placeholder="Belum diisi - Contoh: 081234567890"
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    <div style="display:flex; flex-direction:column; gap:6px; grid-column:span 2;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Alamat Kebun / Lahan Pertanian</label>
                        <input type="text" name="location" id="farmer-location-input" value="{{ old('location', $petani->location) }}"
                               placeholder="Belum diisi - Contoh: Desa Banyubiru, Kec. Ambarawa, Kab. Semarang, Jawa Tengah"
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                        <span style="font-size:10px; color:#859990;">Masukkan nama desa, kecamatan, atau patokan yang mudah dideteksi di Google Maps.</span>
                    </div>

                    {{-- Koordinat GPS Presisi untuk Google Maps --}}
                    <div style="grid-column:span 2; background:#F8FCFA; border:1px solid #DFEAE5; border-radius:12px; padding:16px;"
                         x-data="{
                             lat: '{{ old('latitude', $petani->latitude) }}',
                             lng: '{{ old('longitude', $petani->longitude) }}',
                             detecting: false,
                             geoMsg: '',
                             detectLocation() {
                                 if (!navigator.geolocation) {
                                     alert('Browser Anda tidak mendukung geolokasi GPS.');
                                     return;
                                 }
                                 this.detecting = true;
                                 this.geoMsg = 'Mendeteksi titik koordinat GPS dari perangkat Anda...';
                                 navigator.geolocation.getCurrentPosition(
                                     (pos) => {
                                         this.lat = pos.coords.latitude.toFixed(6);
                                         this.lng = pos.coords.longitude.toFixed(6);
                                         this.detecting = false;
                                         this.geoMsg = '✓ Titik GPS kebun berhasil didapatkan!';
                                     },
                                     (err) => {
                                         this.detecting = false;
                                         this.geoMsg = 'Gagal mendeteksi otomatis: ' + err.message + '. Anda bisa mengisi manual atau mencari di Google Maps.';
                                     },
                                     { enableHighAccuracy: true, timeout: 10000 }
                                 );
                             }
                         }">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                            <div>
                                <b style="font-size:12px; color:#172B29;">Titik Koordinat Google Maps Lahan</b>
                                <p style="font-size:10.5px; color:#788E84; margin:2px 0 0;">Koordinat ini digunakan konsumen untuk mengukur jarak dan panduan rute saat pengambilan panen.</p>
                            </div>
                            <button type="button" @click="detectLocation()" :disabled="detecting"
                                    style="padding:7px 14px; border-radius:8px; border:1px solid #05C793; background:#E9FBF3; color:#0A7E5E; font-size:11px; font-weight:800; cursor:pointer; display:flex; align-items:center; gap:6px;">
                                <span x-show="!detecting">📍 Gunakan GPS Saya</span>
                                <span x-show="detecting">⏳ Mendeteksi...</span>
                            </button>
                        </div>

                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                            <div>
                                <label style="font-size:10.5px; font-weight:750; color:#557064;">Latitude</label>
                                <input type="number" step="any" name="latitude" x-model="lat" placeholder="Contoh: -7.261944"
                                       style="width:100%; border:1px solid #DFEAE5; padding:8px 12px; border-radius:8px; font-size:11.5px; background:#fff; margin-top:3px;">
                            </div>
                            <div>
                                <label style="font-size:10.5px; font-weight:750; color:#557064;">Longitude</label>
                                <input type="number" step="any" name="longitude" x-model="lng" placeholder="Contoh: 110.404722"
                                       style="width:100%; border:1px solid #DFEAE5; padding:8px 12px; border-radius:8px; font-size:11.5px; background:#fff; margin-top:3px;">
                            </div>
                        </div>

                        <div x-show="geoMsg" style="font-size:11px; margin-top:8px;" :style="geoMsg.startsWith('✓') ? 'color:#0A7E5E; font-weight:750;' : 'color:#B45309;'" x-text="geoMsg"></div>

                        <template x-if="lat && lng">
                            <div style="margin-top:10px; font-size:11px;">
                                <a :href="'https://www.google.com/maps?q=' + lat + ',' + lng" target="_blank"
                                   style="color:#0C8D65; font-weight:750; text-decoration:none; display:inline-flex; align-items:center; gap:5px;">
                                    ↗ Cek lokasi titik lahan di Google Maps
                                </a>
                            </div>
                        </template>
                    </div>

                </div>

                <div style="height:1px; background:#E5ECEA; margin:22px 0;"></div>

                {{-- Rekening Bank --}}
                <div style="margin-bottom:16px;">
                    <h3 style="font-size:13px; font-weight:850; color:#172B29; margin:0 0 4px;">Rekening Pencairan DP & Hasil Panen</h3>
                    <p style="font-size:11px; color:#8A9A94; margin:0;">Rekening resmi kelompok tani untuk penerimaan DP 30% dan pencairan escrow 70%.</p>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Nama Bank</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', $petani->bank_name) }}"
                               placeholder="Belum diisi - Contoh: Bank BRI / Mandiri / BCA"
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Nomor Rekening</label>
                        <input type="text" name="bank_account" value="{{ old('bank_account', $petani->bank_account) }}"
                               placeholder="Belum diisi - Contoh: 0341-01-002341-50-8"
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-end; margin-top:24px;">
                    <button type="submit" style="padding:11px 24px; border-radius:11px; border:none; background:#05C793; font-size:12px; font-weight:800; color:#053B2D; cursor:pointer; box-shadow:0 3px 10px rgba(5,199,147,0.25);">
                        Simpan Profil Petani ↗
                    </button>
                </div>
            </form>
        </div>

        {{-- Kanan: Status Kemitraan --}}
        <div style="display:flex; flex-direction:column; gap:18px;">
            <div class="card" style="padding:22px;">
                <h3 style="font-size:15px; font-weight:850; margin:0 0 14px; color:#172B29;">Status Kemitraan</h3>
                
                <div style="display:flex; align-items:center; gap:12px; padding:14px; border-radius:12px; background:#E9FBF3; border:1px solid #C6EEDC; margin-bottom:16px;">
                    <span style="font-size:24px;">🌾</span>
                    <div>
                        <b style="font-size:13px; color:#0C8D65;">Petani Mitra Terverifikasi</b>
                        <p style="font-size:10px; color:#1C6F57; margin:2px 0 0;">Akun berhak mempublikasikan penawaran dan menerima kontrak pre-order.</p>
                    </div>
                </div>

                <div style="display:flex; flex-direction:column; gap:10px; font-size:11px; color:#7B8C86;">
                    <div style="display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px solid #EEF2F0;">
                        <span>Bergabung Sejak</span>
                        <b style="color:#172B29;">{{ $petani->created_at ? $petani->created_at->format('d M Y') : 'Okt 2026' }}</b>
                    </div>
                    <div style="display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px solid #EEF2F0;">
                        <span>Peran Akun</span>
                        <b style="color:#172B29;">Produsen Petani</b>
                    </div>
                    <div style="display:flex; justify-content:space-between; padding:6px 0;">
                        <span>Verifikasi Escrow</span>
                        <b style="color:#0C8D65;">Aktif</b>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
