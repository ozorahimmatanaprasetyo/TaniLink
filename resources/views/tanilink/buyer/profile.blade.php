@extends('layouts.sidebar')

@section('title', 'Profil Konsumen - TaniLink')

@section('sidebar_nav')
<a href="{{ route('portal.pembeli') }}" class="nav-btn {{ request()->routeIs('portal.pembeli') ? 'active' : '' }}">
    <span class="nav-icon">▦</span> Dashboard
</a>
<a href="{{ route('pembeli.katalog') }}" class="nav-btn {{ request()->routeIs('pembeli.katalog*') ? 'active' : '' }}">
    <span class="nav-icon">▧</span> Marketplace
</a>
<a href="{{ route('pembeli.pesanan') }}" class="nav-btn {{ request()->routeIs('pembeli.pesanan*') ? 'active' : '' }}">
    <span class="nav-icon">▤</span> Pesanan Saya
</a>
<a href="{{ route('pembeli.profil') }}" class="nav-btn {{ request()->routeIs('pembeli.profil*') ? 'active' : '' }}">
    <span class="nav-icon">♙</span> Profil Konsumen
</a>
@endsection

@section('content')

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:26px;">
        <div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Profil Konsumen</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">Kelola data entitas bisnis, kontak narahubung, dan alamat tujuan pasokan komoditas.</p>
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
                <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Informasi Usaha & Kontak</h2>
                <p style="font-size:11px; color:#91A19B; margin:0;">Data ini digunakan pada surat perjanjian kontrak pre-order bersama petani.</p>
            </div>

            <form action="{{ route('pembeli.profil.update') }}" method="POST">
                @csrf
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Nama penanggung jawab *</label>
                        <input type="text" name="name" value="{{ old('name', $pembeli->name) }}" required
                               placeholder="Nama penanggung jawab"
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Nama Usaha / Perusahaan</label>
                        <input type="text" name="organization" value="{{ old('organization', $pembeli->organization) }}"
                               placeholder="Belum diisi - Contoh: CV Dapur Rasa / Resto Malang"
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Email akun</label>
                        <input type="email" value="{{ $pembeli->email }}" disabled
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#F5F8F7; color:#8A9A94; font-size:12px; min-height:43px;">
                    </div>

                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Nomor WhatsApp / Kontak</label>
                        <input type="text" name="phone" value="{{ old('phone', $pembeli->phone) }}"
                               placeholder="Belum diisi - Contoh: 082198765432"
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                    <div style="display:flex; flex-direction:column; gap:6px; grid-column:span 2;">
                        <label style="font-size:11px; font-weight:800; color:#365448;">Alamat Kantor / Dapur Penerima Pasokan</label>
                        <input type="text" name="location" value="{{ old('location', $pembeli->location) }}"
                               placeholder="Belum diisi - Contoh: Jl. Raya Ijen No. 45, Kota Malang, Jawa Timur"
                               style="outline:0; width:100%; border:1px solid #DFEAE5; padding:12px 14px; border-radius:10px; background:#fff; color:#254D3F; font-size:12px; min-height:43px;">
                    </div>

                </div>

                <div style="display:flex; justify-content:flex-end; margin-top:24px;">
                    <button type="submit" style="padding:11px 24px; border-radius:11px; border:none; background:#05C793; font-size:12px; font-weight:800; color:#053B2D; cursor:pointer; box-shadow:0 3px 10px rgba(5,199,147,0.25);">
                        Simpan Profil Konsumen ↗
                    </button>
                </div>
            </form>
        </div>

        {{-- Kanan: Status Akun --}}
        <div style="display:flex; flex-direction:column; gap:18px;">
            <div class="card" style="padding:22px;">
                <h3 style="font-size:15px; font-weight:850; margin:0 0 14px; color:#172B29;">Status Pembeli</h3>
                
                <div style="display:flex; align-items:center; gap:12px; padding:14px; border-radius:12px; background:#E8F9FC; border:1px solid #BFEBF3; margin-bottom:16px;">
                    <span style="font-size:24px;">🏢</span>
                    <div>
                        <b style="font-size:13px; color:#087894;">Konsumen Terverifikasi</b>
                        <p style="font-size:10px; color:#085568; margin:2px 0 0;">Akun berhak memesan pre-order komoditas dengan rekening escrow bersama.</p>
                    </div>
                </div>

                <div style="display:flex; flex-direction:column; gap:10px; font-size:11px; color:#7B8C86;">
                    <div style="display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px solid #EEF2F0;">
                        <span>Bergabung Sejak</span>
                        <b style="color:#172B29;">{{ $pembeli->created_at ? $pembeli->created_at->format('d M Y') : 'Okt 2026' }}</b>
                    </div>
                    <div style="display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px solid #EEF2F0;">
                        <span>Peran Akun</span>
                        <b style="color:#172B29;">Konsumen</b>
                    </div>
                    <div style="display:flex; justify-content:space-between; padding:6px 0;">
                        <span>Status Rekening Escrow</span>
                        <b style="color:#0C8D65;">Aktif & Siap Transaksi</b>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

