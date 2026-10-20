@extends('layouts.sidebar')

@section('title', 'Profil Gapoktan - TaniLink')

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
<a href="{{ route('petani.pesanan*') }}" class="nav-btn {{ request()->routeIs('petani.pesanan*') ? 'active' : '' }}">
    <span class="nav-icon">▤</span> Pesanan Masuk
</a>
<a href="{{ route('petani.profil') }}" class="nav-btn {{ request()->routeIs('petani.profil') ? 'active' : '' }}">
    <span class="nav-icon">♙</span> Profil Gapoktan
</a>
@endsection

@section('breadcrumb')
    TaniLink &rsaquo; Gapoktan &rsaquo; <strong>Profil</strong>
@endsection

@section('content')

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:26px;">
        <div>
            <div style="font-size:10px; font-weight:900; color:#049874; text-transform:uppercase; letter-spacing:1.6px; margin-bottom:6px;">TaniLink / Gapoktan</div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Profil Gapoktan</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">Kelola identitas produsen dan informasi bisnis yang ditampilkan ke pembeli Horeka.</p>
        </div>
    </div>

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
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Nama kelompok tani / Gapoktan *</label>
                        <input type="text" name="organization" value="{{ old('organization', $petani->organization ?? 'Gapoktan Ambarawa') }}" required
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Nomor kontak WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone', $petani->phone ?? '0812-3456-7890') }}"
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Email akun</label>
                        <input type="email" value="{{ $petani->email }}" readonly
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#F8FCFA; color:#85958F; font-size:12px; min-height:43px; cursor:not-allowed;">
                    </div>

                    <div style="display:flex; flex-direction:column; gap:6px; grid-column:span 2;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Alamat / Lokasi Lahan *</label>
                        <input type="text" name="location" value="{{ old('location', $petani->location ?? 'Ambarawa, Kabupaten Malang, Jawa Timur') }}" required
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    <div style="display:flex; flex-direction:column; gap:6px; grid-column:span 2;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Profil Usaha Tani</label>
                        <textarea name="bio" rows="3"
                                  style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:86px; resize:vertical;">{{ old('bio', 'Kelompok tani dengan fokus komoditas hortikultura unggulan, produksi terencana sejak pra-tanam, dan kemitraan suplai pre-order langsung ke hotel dan restoran.') }}</textarea>
                    </div>

                </div>

                <div style="height:1px; background:#E5ECEA; margin:22px 0;"></div>

                <div style="display:flex; justify-content:flex-end;">
                    <button type="submit" style="padding:11px 22px; border-radius:11px; border:none; background:#05C793; font-size:12px; font-weight:800; color:#053B2D; cursor:pointer;">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- Kanan: Kartu Profil Publik --}}
        <div style="display:flex; flex-direction:column; gap:18px;">
            <div class="card" style="padding:26px; text-align:center;">
                <div style="width:84px; height:84px; border-radius:24px; background:#DCF8EB; color:#007B55; display:grid; place-items:center; font-size:36px; margin:0 auto 16px;">
                    🌱
                </div>
                <h3 style="font-size:18px; font-weight:850; color:#172B29; margin:0 0 4px;">
                    {{ $petani->organization ?? 'Gapoktan Ambarawa' }}
                </h3>
                <div style="font-size:11px; color:#85958F; margin-bottom:12px;">
                    {{ $petani->location ?? 'Malang, Jawa Timur' }}
                </div>

                <span style="display:inline-flex; align-items:center; padding:4px 10px; border-radius:6px; background:#E9F9F2; color:#0C8D65; font-weight:800; font-size:10px; margin-bottom:18px;">
                    Produsen Terverifikasi
                </span>

                <div style="height:1px; background:#E5ECEA; margin:16px 0;"></div>

                <div style="display:flex; justify-content:space-around; font-size:11px;">
                    <div>
                        <div style="color:#85958F; font-size:10px;">Listing Aktif</div>
                        <b style="font-size:15px; color:#172B29; display:block; margin-top:2px;">{{ $listingCount }}</b>
                    </div>
                    <div>
                        <div style="color:#85958F; font-size:10px;">Kemitraan</div>
                        <b style="font-size:15px; color:#0A765A; display:block; margin-top:2px;">Pre-Order B2B</b>
                    </div>
                </div>

                <div style="margin-top:20px;">
                    <a href="{{ route('petani.komoditas') }}" style="display:block; text-align:center; padding:10px; border-radius:11px; background:#EBF9F2; color:#078362; font-size:12px; font-weight:800; text-decoration:none;">
                        Lihat Komoditas Saya →
                    </a>
                </div>
            </div>
        </div>

    </div>

@endsection

