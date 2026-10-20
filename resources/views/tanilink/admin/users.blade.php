@extends('layouts.sidebar')

@section('title', 'Kelola Pengguna - Admin TaniLink')

@section('sidebar_nav')
<a href="{{ route('portal.admin') }}" class="nav-btn {{ request()->routeIs('portal.admin') ? 'active' : '' }}">
    <span class="nav-icon">▦</span> Dashboard Admin
</a>
<a href="{{ route('admin.pengguna') }}" class="nav-btn {{ request()->routeIs('admin.pengguna*') ? 'active' : '' }}">
    <span class="nav-icon">♧</span> Kelola Pengguna
</a>
<a href="{{ route('admin.komoditas') }}" class="nav-btn {{ request()->routeIs('admin.komoditas*') ? 'active' : '' }}">
    <span class="nav-icon">▤</span> Kelola Komoditas
</a>
<a href="{{ route('admin.pesanan') }}" class="nav-btn {{ request()->routeIs('admin.pesanan*') ? 'active' : '' }}">
    <span class="nav-icon">▣</span> Pesanan & Escrow
</a>
<a href="{{ route('admin.laporan') }}" class="nav-btn {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
    <span class="nav-icon">▥</span> Laporan & Statistik
</a>
@endsection

@section('breadcrumb')
    TaniLink &rsaquo; Admin &rsaquo; <strong>Kelola Pengguna</strong>
@endsection

@section('content')
<div x-data="{ search: '', filterRole: '' }">

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:26px;">
        <div>
            <div style="font-size:10px; font-weight:900; color:#7257CA; text-transform:uppercase; letter-spacing:1.6px; margin-bottom:6px;">TaniLink / Admin Central</div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Kelola Pengguna</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">Manajemen akun produsen petani, pembeli Horeka, dan status verifikasi.</p>
        </div>
    </div>

    {{-- 3 Metric Cards --}}
    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; margin-bottom:24px;">
        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Total Pengguna</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#F1EFFB; color:#7257CA; font-size:16px;">👥</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ $allUsers->count() }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Semua akun terdaftar</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Petani / Gapoktan</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E9FBF3; color:#139B78; font-size:16px;">🌱</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ $allUsers->where('role', 'petani')->count() }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Produsen terverifikasi</div>
        </div>

        <div class="card" style="padding:19px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#7B8C86; font-weight:750;">
                <span>Pembeli (Horeka)</span>
                <span style="width:34px; height:34px; display:grid; place-items:center; border-radius:11px; background:#E8F9FC; color:#0B8BAA; font-size:16px;">🏨</span>
            </div>
            <div style="font-weight:850; font-size:25px; letter-spacing:-1px; margin:12px 0 6px; color:#172B29;">
                {{ $allUsers->where('role', 'pembeli')->count() }}
            </div>
            <div style="font-size:10px; color:#7B9187;">Hotel, restoran, katering</div>
        </div>
    </div>

    {{-- Users Table Card --}}
    <div class="card" style="padding:24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:12px;">
            <div>
                <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Daftar Akun Pengguna</h2>
                <p style="font-size:11px; color:#91A19B; margin:0;">Otorisasi dan peran akun di dalam platform.</p>
            </div>

            <div style="display:flex; gap:10px;">
                <input type="text" x-model="search" placeholder="⌕ Cari nama, email, organisasi..."
                       style="padding:9px 13px; border:1px solid #E5ECEA; border-radius:10px; font-size:12px; color:#50695E; background:#fff; min-width:220px; outline:0;">
                <select x-model="filterRole"
                        style="padding:9px 13px; border:1px solid #E5ECEA; border-radius:10px; font-size:12px; color:#50695E; background:#fff; outline:0;">
                    <option value="">Semua Peran</option>
                    <option value="petani">Petani / Gapoktan</option>
                    <option value="pembeli">Pembeli Horeka</option>
                    <option value="admin">Administrator</option>
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
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">ORGANISASI</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">BERGABUNG</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">STATUS</th>
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
                                <div style="width:30px; height:30px; border-radius:8px; background:#F5F8F7; display:grid; place-items:center; font-size:12px;">
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
                                <span style="display:inline-flex; align-items:center; padding:4px 8px; border-radius:6px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:10px;">Horeka</span>
                            @elseif($u->role === 'admin')
                                <span style="display:inline-flex; align-items:center; padding:4px 8px; border-radius:6px; background:#F1EFFB; color:#6953B5; font-weight:800; font-size:10px;">Admin</span>
                            @else
                                <span style="display:inline-flex; align-items:center; padding:4px 8px; border-radius:6px; background:#F0F3F2; color:#75847F; font-weight:800; font-size:10px;">Pending</span>
                            @endif
                        </td>
                        <td style="font-size:11px; font-weight:650; color:#172B29; padding:15px 10px;">
                            {{ $u->organization ?? '-' }}
                        </td>
                        <td style="font-size:11px; color:#85958F; padding:15px 10px;">
                            {{ $u->created_at ? $u->created_at->format('d M Y') : '-' }}
                        </td>
                        <td style="font-size:11px; padding:15px 10px;">
                            <span style="display:inline-flex; align-items:center; padding:3px 7px; border-radius:5px; background:#E9F9F2; color:#0C8D65; font-weight:800; font-size:9px;">
                                Aktif
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

