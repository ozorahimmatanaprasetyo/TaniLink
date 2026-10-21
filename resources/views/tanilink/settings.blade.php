@extends('layouts.sidebar')

@section('title', 'Pengaturan Akun - TaniLink')

@section('sidebar_nav')
@php
    $role = Auth::user()->role;
@endphp
@if($role === 'petani')
<a href="{{ route('portal.petani') }}" class="nav-btn">
    <span class="nav-icon">▦</span> Dashboard
</a>
<a href="{{ route('petani.komoditas') }}" class="nav-btn">
    <span class="nav-icon">♧</span> Komoditas Saya
</a>
<a href="{{ route('petani.komoditas.create') }}" class="nav-btn">
    <span class="nav-icon">⊕</span> Tambah Komoditas
</a>
<a href="{{ route('petani.pesanan') }}" class="nav-btn">
    <span class="nav-icon">▤</span> Pesanan Masuk
</a>
<a href="{{ route('petani.profil') }}" class="nav-btn">
    <span class="nav-icon">♙</span> Profil Petani
</a>
@elseif($role === 'pembeli')
<a href="{{ route('portal.pembeli') }}" class="nav-btn">
    <span class="nav-icon">▦</span> Dashboard
</a>
<a href="{{ route('pembeli.katalog') }}" class="nav-btn">
    <span class="nav-icon">▧</span> Marketplace
</a>
<a href="{{ route('pembeli.pesanan') }}" class="nav-btn">
    <span class="nav-icon">▤</span> Pesanan Saya
</a>
<a href="{{ route('pembeli.profil') }}" class="nav-btn">
    <span class="nav-icon">♙</span> Profil Konsumen
</a>
@else
<a href="{{ route('portal.admin') }}" class="nav-btn">
    <span class="nav-icon">▦</span> Dashboard Admin
</a>
<a href="{{ route('admin.pengguna') }}" class="nav-btn">
    <span class="nav-icon">♧</span> Kelola Pengguna
</a>
<a href="{{ route('admin.komoditas') }}" class="nav-btn">
    <span class="nav-icon">▤</span> Kelola Komoditas
</a>
<a href="{{ route('admin.pesanan') }}" class="nav-btn">
    <span class="nav-icon">▣</span> Pesanan & Escrow
</a>
<a href="{{ route('admin.laporan') }}" class="nav-btn">
    <span class="nav-icon">▥</span> Laporan & Statistik
</a>
@endif
@endsection

@section('breadcrumb')
    TaniLink &rsaquo; <strong>Pengaturan Akun</strong>
@endsection

@section('content')

    {{-- Headline --}}
    <div style="margin-bottom:24px;">
        <div style="font-size:10px; font-weight:900; color:#049874; text-transform:uppercase; letter-spacing:1.6px; margin-bottom:6px;">TaniLink Central</div>
        <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Pengaturan Akun</h1>
        <p style="font-size:12px; color:#8A9A94; margin:0;">Kelola profil akun, informasi kontak, dan kata sandi Anda.</p>
    </div>

    @if(session('success'))
    <div style="background:#ECFDF5; border:1px solid #A7F3D0; color:#065F46; padding:12px 16px; border-radius:12px; font-size:13px; font-weight:600; margin-bottom:20px;">
        ✓ {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div style="background:#FEE2E2; border:1px solid #FCA5A5; color:#991B1B; padding:12px 16px; border-radius:12px; font-size:13px; font-weight:600; margin-bottom:20px;">
        <ul style="margin:0; padding-left:20px;">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card" style="padding:28px; max-width:700px;">
        <form action="{{ route('pengaturan.update') }}" method="POST">
            @csrf

            <div style="display:flex; flex-direction:column; gap:16px;">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Nama Lengkap *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; font-size:12px;">
                    </div>

                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Organisasi / Perusahaan</label>
                        <input type="text" name="organization" value="{{ old('organization', $user->organization) }}"
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; font-size:12px;">
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Alamat Email</label>
                        <input type="email" value="{{ $user->email }}" readonly
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#F8FCFA; color:#85958F; font-size:12px; cursor:not-allowed;">
                    </div>

                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Nomor Telepon / WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; font-size:12px;">
                    </div>
                </div>

                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label style="font-size:11px; font-weight:800; color:#365448;">Alamat / Lokasi Operasional</label>
                    <input type="text" name="location" value="{{ old('location', $user->location) }}"
                           style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; font-size:12px;">
                </div>

                <div style="height:1px; background:#E5ECEA; margin:10px 0;"></div>

                <h3 style="font-size:14px; font-weight:800; color:#172B29; margin:0;">Ganti Kata Sandi (Opsional)</h3>
                <p style="font-size:11px; color:#8A9994; margin:0;">Biarkan kosong jika tidak ingin mengubah kata sandi.</p>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Password Baru</label>
                        <input type="password" name="password" placeholder="Minimal 8 karakter"
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; font-size:12px;">
                    </div>

                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; font-size:12px;">
                    </div>
                </div>

                <div style="margin-top:14px; display:flex; justify-content:flex-end;">
                    <button type="submit"
                            style="padding:12px 24px; border-radius:11px; border:none; background:#05C793; color:#053B2D; font-size:12px; font-weight:800; cursor:pointer;">
                        Simpan Pengaturan
                    </button>
                </div>
            </div>

        </form>
    </div>

@endsection

