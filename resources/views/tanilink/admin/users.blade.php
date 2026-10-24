@extends('layouts.sidebar')

@section('title', 'Kelola Pengguna - Admin TaniLink')

@section('sidebar_nav')
<a href="{{ route('portal.admin') }}" class="nav-btn {{ request()->routeIs('portal.admin') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-chart-pie"></i></span> Beranda Admin
</a>
<a href="{{ route('admin.pengguna') }}" class="nav-btn {{ request()->routeIs('admin.pengguna*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-users"></i></span> Kelola Pengguna
</a>
<a href="{{ route('admin.komoditas') }}" class="nav-btn {{ request()->routeIs('admin.komoditas*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-boxes-stacked"></i></span> Kelola Komoditas
</a>
<a href="{{ route('admin.pesanan') }}" class="nav-btn {{ request()->routeIs('admin.pesanan*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-receipt"></i></span> Pesanan &amp; Transaksi
</a>
<a href="{{ route('admin.laporan') }}" class="nav-btn {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-chart-line"></i></span> Laporan &amp; Statistik
</a>
<a href="{{ route('admin.bantuan') }}" class="nav-btn {{ request()->routeIs('admin.bantuan*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-circle-question"></i></span> Kelola Pusat Bantuan
</a>
@endsection

@section('content')
<div x-data="{ search: '', filterRole: '' }">

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:26px;">
        <div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Kelola Pengguna</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">Manajemen akun produsen petani, pembeli konsumen, dan status akun.</p>
        </div>
    </div>

    @if(session('success'))
    <div style="background:#ECFDF8; border:1px solid #6EE7B7; color:#065F46; padding:12px 16px; border-radius:12px; font-size:13px; font-weight:600; display:flex; align-items:center; gap:10px; margin-bottom:20px;">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
    @endif

    {{-- 3 Metric Cards --}}
    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; margin-bottom:24px;">
        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Total Pengguna Aktif</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#F1EFFB; color:#7257CA; font-size:15px;"><i class="fa-solid fa-users"></i></span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ $allUsers->count() }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Semua akun mitra terdaftar</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Petani Produsen</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:15px;"><i class="fa-solid fa-sprout"></i></span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ $allUsers->where('role', 'petani')->count() }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Produsen komoditas terdaftar</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Pembeli (Konsumen)</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E8F9FC; color:#0B8BAA; font-size:15px;"><i class="fa-solid fa-building"></i></span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ $allUsers->where('role', 'pembeli')->count() }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Konsumen horeka &amp; perorangan</div>
        </div>
    </div>

    {{-- Users Table Card --}}
    <div class="card" style="padding:24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:12px;">
            <div>
                <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Daftar Akun Pengguna</h2>
                <p style="font-size:11px; color:#91A19B; margin:0;">Otorisasi dan data profil pengguna di dalam platform.</p>
            </div>

            <div style="display:flex; gap:10px;">
                <input type="text" x-model="search" placeholder="⌕ Cari nama, email, organisasi..."
                       style="padding:9px 13px; border:1px solid #E5ECEA; border-radius:10px; font-size:12px; color:#50695E; background:#fff; min-width:220px; outline:0;">
                <select x-model="filterRole"
                        style="padding:9px 13px; border:1px solid #E5ECEA; border-radius:10px; font-size:12px; color:#50695E; background:#fff; outline:0;">
                    <option value="">Semua Peran</option>
                    <option value="petani">Petani</option>
                    <option value="pembeli">Konsumen</option>
                </select>
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:left;">
                <thead>
                    <tr>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">PENGGUNA</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">EMAIL</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">PERAN</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">ORGANISASI / USAHA</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">NO. TELEPON / WA</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">BERGABUNG</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px; text-align:right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allUsers as $u)
                    @php
                        $target = strtolower($u->name . ' ' . $u->email . ' ' . ($u->organization ?? ''));
                    @endphp
                    <tr x-show="(search === '' || '{{ $target }}'.includes(search.toLowerCase())) && (filterRole === '' || '{{ $u->role }}' === filterRole)"
                        style="border-bottom:1px solid #EEF2F0;">
                        <td style="font-size:11px; font-weight:800; color:#183B30; padding:15px 10px;">
                            <div style="display:flex; align-items:center; gap:9px;">
                                <div style="width:30px; height:30px; border-radius:8px; background:#F5F8F7; display:grid; place-items:center; font-size:12px; font-weight:800; color:#0A765A;">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    {{ $u->name }}
                                </div>
                            </div>
                        </td>
                        <td style="font-size:11px; color:#50695E; padding:15px 10px;">
                            {{ $u->email }}
                        </td>
                        <td style="font-size:11px; padding:15px 10px;">
                            @if($u->role === 'petani')
                                <span style="display:inline-flex; align-items:center; padding:4px 8px; border-radius:6px; background:#E9F9F2; color:#0C8D65; font-weight:800; font-size:10px;">Petani</span>
                            @elseif($u->role === 'pembeli')
                                <span style="display:inline-flex; align-items:center; padding:4px 8px; border-radius:6px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:10px;">Konsumen</span>
                            @else
                                <span style="display:inline-flex; align-items:center; padding:4px 8px; border-radius:6px; background:#F0F3F2; color:#75847F; font-weight:800; font-size:10px;">Pengguna</span>
                            @endif
                        </td>
                        <td style="font-size:11px; font-weight:650; color:#172B29; padding:15px 10px;">
                            {{ $u->organization ?? '-' }}
                        </td>
                        <td style="font-size:11px; font-weight:700; color:#0A765A; padding:15px 10px;">
                            {{ $u->phone ?? '-' }}
                        </td>
                        <td style="font-size:11px; color:#85958F; padding:15px 10px;">
                            {{ $u->created_at ? $u->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y') : '-' }}
                        </td>
                        <td style="font-size:11px; padding:15px 10px; text-align:right;">
                            <form action="{{ route('admin.pengguna.destroy', $u->id) }}" method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $u->name }}?');"
                                  style="display:inline; margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        style="display:inline-flex; align-items:center; gap:4px; padding:5px 11px; border-radius:7px; background:#FEE2E2; border:1px solid #FECACA; color:#B91C1C; font-weight:800; font-size:11px; cursor:pointer;">
                                    <i class="fa-regular fa-trash-can"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
