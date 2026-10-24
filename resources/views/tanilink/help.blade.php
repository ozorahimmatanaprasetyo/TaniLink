@extends('layouts.sidebar')

@section('title', 'Pusat Bantuan & Panduan - TaniLink')

@section('sidebar_nav')
@php
    $role = Auth::user() ? Auth::user()->role : 'pembeli';
@endphp
@if($role === 'petani')
<a href="{{ route('portal.petani') }}" class="nav-btn">
    <span class="nav-icon"><i class="fa-solid fa-chart-pie"></i></span> Beranda
</a>
<a href="{{ route('petani.komoditas') }}" class="nav-btn">
    <span class="nav-icon"><i class="fa-solid fa-seedling"></i></span> Komoditas Saya
</a>
<a href="{{ route('petani.pesanan') }}" class="nav-btn">
    <span class="nav-icon"><i class="fa-solid fa-clipboard-list"></i></span> Pesanan Masuk
</a>
<a href="{{ route('petani.profil') }}" class="nav-btn">
    <span class="nav-icon"><i class="fa-solid fa-user"></i></span> Profil Petani
</a>
@elseif($role === 'pembeli')
<a href="{{ route('portal.pembeli') }}" class="nav-btn">
    <span class="nav-icon"><i class="fa-solid fa-chart-pie"></i></span> Beranda
</a>
<a href="{{ route('pembeli.katalog') }}" class="nav-btn">
    <span class="nav-icon"><i class="fa-solid fa-store"></i></span> Marketplace
</a>
<a href="{{ route('pembeli.pesanan') }}" class="nav-btn">
    <span class="nav-icon"><i class="fa-solid fa-bag-shopping"></i></span> Pesanan Saya
</a>
<a href="{{ route('pembeli.profil') }}" class="nav-btn">
    <span class="nav-icon"><i class="fa-solid fa-user"></i></span> Profil Konsumen
</a>
@else
<a href="{{ route('portal.admin') }}" class="nav-btn">
    <span class="nav-icon"><i class="fa-solid fa-chart-pie"></i></span> Beranda Admin
</a>
<a href="{{ route('admin.pengguna') }}" class="nav-btn">
    <span class="nav-icon"><i class="fa-solid fa-users"></i></span> Kelola Pengguna
</a>
<a href="{{ route('admin.komoditas') }}" class="nav-btn">
    <span class="nav-icon"><i class="fa-solid fa-boxes-stacked"></i></span> Kelola Komoditas
</a>
<a href="{{ route('admin.pesanan') }}" class="nav-btn">
    <span class="nav-icon"><i class="fa-solid fa-receipt"></i></span> Pesanan & Transaksi
</a>
<a href="{{ route('admin.laporan') }}" class="nav-btn">
    <span class="nav-icon"><i class="fa-solid fa-chart-line"></i></span> Laporan & Statistik
</a>
<a href="{{ route('admin.bantuan') }}" class="nav-btn">
    <span class="nav-icon"><i class="fa-solid fa-circle-question"></i></span> Kelola Pusat Bantuan
</a>
@endif
@endsection

@section('content')

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:26px;">
        <div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Pusat Bantuan & FAQ</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">Pelajari alur tata niaga pra-tanam, skema pembayaran bersama, dan pemenuhan pesanan mandiri.</p>
        </div>
        @if(Auth::user() && Auth::user()->role === 'admin')
        <a href="{{ route('admin.bantuan') }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 16px; border-radius:11px; background:#7257CA; color:#fff; font-size:12px; font-weight:800; text-decoration:none;">
            <i class="fa-solid fa-gear"></i> Kelola FAQ (Admin)
        </a>
        @endif
    </div>

    <div style="display:grid; grid-template-columns:minmax(0, 1.5fr) minmax(320px, 1fr); gap:24px;">
        
        {{-- Kiri: Dynamic FAQ Topics from Database --}}
        <div style="display:flex; flex-direction:column; gap:16px;">
            
            @forelse($topics as $index => $topic)
            <div class="card" style="padding:22px;">
                <div style="margin-bottom:8px;">
                    <h3 style="font-size:15px; font-weight:850; color:#172B29; margin:0;">
                        {{ $index + 1 }}. {{ $topic->question }}
                    </h3>
                </div>
                <p style="font-size:12.5px; color:#50695E; line-height:1.75; margin:0;">
                    {{ $topic->answer }}
                </p>
            </div>
            @empty
            <div class="card" style="padding:40px; text-align:center; color:#8A9D95;">
                <p style="font-size:13px; font-weight:700;">Belum ada topik bantuan yang dimuat.</p>
            </div>
            @endforelse

        </div>

        {{-- Kanan: Kontak Dukungan --}}
        <div style="display:flex; flex-direction:column; gap:18px;">
            <div class="card" style="padding:24px;">
                <h3 style="font-size:15px; font-weight:850; color:#172B29; margin:0 0 12px;">Butuh Bantuan Langsung?</h3>
                <p style="font-size:12px; color:#8A9A94; line-height:1.7; margin:0 0 20px;">
                    Tim operasional TaniLink siap mendampingi Anda dalam koordinasi kontrak, verifikasi mitra, atau kendala transaksi.
                </p>

                <div style="display:flex; flex-direction:column; gap:12px; font-size:12px;">
                    <div style="display:flex; align-items:center; gap:10px; color:#172B29;">
                        <span style="width:30px; height:30px; border-radius:8px; background:#E9FBF3; color:#069873; display:grid; place-items:center;"><i class="fa-solid fa-phone"></i></span>
                        <b>WhatsApp: {{ $adminPhone ?? '+62 812-3456-7890' }}</b>
                    </div>
                    <div style="display:flex; align-items:center; gap:10px; color:#172B29;">
                        <span style="width:30px; height:30px; border-radius:8px; background:#E8F9FC; color:#0B8BAA; display:grid; place-items:center;"><i class="fa-solid fa-envelope"></i></span>
                        <b>Email: {{ $adminEmail ?? 'support@tanilink.id' }}</b>
                    </div>
                    <div style="display:flex; align-items:center; gap:10px; color:#172B29;">
                        <span style="width:30px; height:30px; border-radius:8px; background:#F1EFFB; color:#7257CA; display:grid; place-items:center;"><i class="fa-regular fa-clock"></i></span>
                        <span>{{ $adminHours ?? 'Senin - Sabtu (08.00 - 17.00 WIB)' }}</span>
                    </div>
                </div>

                <div style="margin-top:20px; padding:12px; border-radius:10px; background:#F8FCFA; border:1px solid #DFEAE5; font-size:11px; color:#56786A; line-height:1.6;">
                    <i class="fa-solid fa-circle-info" style="color:#0B8BAA; margin-right:4px;"></i> <b>Catatan Serah Terima:</b> TaniLink memfasilitasi transaksi harga pasti dan pembayaran bersama yang aman. Penyerahan dan pengambilan barang dilakukan secara mandiri atau dikoordinasikan langsung antara pembeli dan petani di lokasi lahan panen.
                </div>
            </div>
        </div>

    </div>

@endsection
