@extends('layouts.sidebar')

@section('title', 'Pusat Bantuan & Panduan - TaniLink')

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
    <span class="nav-icon">♙</span> Profil Gapoktan
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
    TaniLink &rsaquo; <strong>Pusat Bantuan</strong>
@endsection

@section('content')

    {{-- Headline --}}
    <div style="margin-bottom:26px;">
        <div style="font-size:10px; font-weight:900; color:#049874; text-transform:uppercase; letter-spacing:1.6px; margin-bottom:6px;">TaniLink Panduan</div>
        <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Pusat Bantuan & FAQ</h1>
        <p style="font-size:12px; color:#8A9A94; margin:0;">Pelajari alur tata niaga pra-tanam, skema escrow bersama, dan pemenuhan pesanan.</p>
    </div>

    <div style="display:grid; grid-template-columns:minmax(0, 1.5fr) minmax(320px, 1fr); gap:24px;">
        
        {{-- Kiri: FAQ Accordion --}}
        <div style="display:flex; flex-direction:column; gap:16px;">
            
            <div class="card" style="padding:22px;">
                <h3 style="font-size:15px; font-weight:850; color:#172B29; margin:0 0 8px;">
                    1. Bagaimana skema pembayaran DP 30% dan Escrow 70% bekerja?
                </h3>
                <p style="font-size:12px; color:#50695E; line-height:1.7; margin:0;">
                    Saat Horeka menyetujui kontrak pre-order, 30% dari total nilai kontrak langsung diteruskan ke rekening kelompok tani (Gapoktan) sebagai modal benih, pupuk, dan operasional lahan tanpa jeratan hutang. Sisa 70% disimpan aman di rekening bersama (escrow) TaniLink dan baru dirilis ke petani setelah hasil panen diterima dan diverifikasi.
                </p>
            </div>

            <div class="card" style="padding:22px;">
                <h3 style="font-size:15px; font-weight:850; color:#172B29; margin:0 0 8px;">
                    2. Apa keuntungan harga fixed-price (kontrak tetap)?
                </h3>
                <p style="font-size:12px; color:#50695E; line-height:1.7; margin:0;">
                    Harga panen disepakati sebelum masa tanam dimulai. Bagi petani, ini menjamin panen pasti dibeli dan tidak anjlok saat panen raya. Bagi hotel/restoran, anggaran bahan dapur stabil tanpa terdampak lonjakan harga pasar dadakan.
                </p>
            </div>

            <div class="card" style="padding:22px;">
                <h3 style="font-size:15px; font-weight:850; color:#172B29; margin:0 0 8px;">
                    3. Bagaimana cara memantau progres tanaman di lahan?
                </h3>
                <p style="font-size:12px; color:#50695E; line-height:1.7; margin:0;">
                    Petani mitra berkewajiban mengunggah foto lapangan dan pembaruan catatan perkembangan tanaman secara berkala di tiap fase (Benih & Semai, Vegetatif, Pembungaan, hingga Panen). Pembeli dapat melihat laporan ini langsung di menu <b>Pesanan Saya &rsaquo; Pantau</b>.
                </p>
            </div>

            <div class="card" style="padding:22px;">
                <h3 style="font-size:15px; font-weight:850; color:#172B29; margin:0 0 8px;">
                    4. Bagaimana logistik pengiriman diatur?
                </h3>
                <p style="font-size:12px; color:#50695E; line-height:1.7; margin:0;">
                    TaniLink mengoordinasikan armada pickup logistik lokal dari titik penjemputan lahan Gapoktan langsung menuju dapur hotel atau restoran penerima dalam kemasan krat standar pangan.
                </p>
            </div>

        </div>

        {{-- Kanan: Kontak Dukungan --}}
        <div style="display:flex; flex-direction:column; gap:18px;">
            <div class="card" style="padding:24px;">
                <h3 style="font-size:15px; font-weight:850; color:#172B29; margin:0 0 12px;">Butuh Bantuan Langsung?</h3>
                <p style="font-size:12px; color:#8A9A94; line-height:1.7; margin:0 0 20px;">
                    Tim operasional TaniLink siap mendampingi Anda dalam penyusunan kontrak, verifikasi mitra, atau kendala transaksi.
                </p>

                <div style="display:flex; flex-direction:column; gap:12px; font-size:12px;">
                    <div style="display:flex; align-items:center; gap:10px; color:#172B29;">
                        <span style="width:30px; height:30px; border-radius:8px; background:#E9FBF3; color:#069873; display:grid; place-items:center;">📞</span>
                        <b>WhatsApp: +62 812-3456-7890</b>
                    </div>
                    <div style="display:flex; align-items:center; gap:10px; color:#172B29;">
                        <span style="width:30px; height:30px; border-radius:8px; background:#E8F9FC; color:#0B8BAA; display:grid; place-items:center;">✉️</span>
                        <b>Email: support@tanilink.id</b>
                    </div>
                    <div style="display:flex; align-items:center; gap:10px; color:#172B29;">
                        <span style="width:30px; height:30px; border-radius:8px; background:#F1EFFB; color:#7257CA; display:grid; place-items:center;">⏰</span>
                        <span>Senin - Sabtu (08.00 - 17.00 WIB)</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

