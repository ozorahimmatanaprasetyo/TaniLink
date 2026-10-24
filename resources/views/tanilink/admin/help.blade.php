@extends('layouts.sidebar')

@section('title', 'Kelola Pusat Bantuan - Admin TaniLink')

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
    <span class="nav-icon"><i class="fa-solid fa-receipt"></i></span> Pesanan & Transaksi
</a>
<a href="{{ route('admin.laporan') }}" class="nav-btn {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-chart-line"></i></span> Laporan & Statistik
</a>
<a href="{{ route('admin.bantuan') }}" class="nav-btn {{ request()->routeIs('admin.bantuan*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fa-solid fa-circle-question"></i></span> Kelola Pusat Bantuan
</a>
@endsection

@section('content')
<div x-data="{
    activeTab: 'petani',
    showAddModal: false,
    showEditModal: false,
    editId: null,
    editRole: 'petani',
    editQuestion: '',
    editAnswer: '',
    editOrder: 0,
    openEdit(id, role, question, answer, order) {
        this.editId = id;
        this.editRole = role;
        this.editQuestion = question;
        this.editAnswer = answer;
        this.editOrder = order;
        this.showEditModal = true;
    }
}">

    {{-- Headline --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:24px;">
        <div>
            <h1 style="font-size:26px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Kelola Pusat Bantuan &amp; Kontak Admin</h1>
            <p style="font-size:12px; color:#8A9A94; margin:0;">Kelola kontak operasional admin dan konten FAQ terpisah untuk Petani dan Konsumen.</p>
        </div>
        <button type="button" @click="showAddModal = true"
                style="display:inline-flex; align-items:center; gap:8px; padding:10px 18px; border-radius:11px; border:none; background:#7257CA; color:#fff; font-size:12px; font-weight:800; cursor:pointer; box-shadow:0 3px 10px rgba(114,87,202,0.25);">
            + Tambah Topik FAQ
        </button>
    </div>

    @if(session('success'))
    <div style="background:#ECFDF8; border:1px solid #6EE7B7; color:#065F46; padding:12px 16px; border-radius:12px; font-size:13px; font-weight:600; display:flex; align-items:center; gap:10px; margin-bottom:20px;">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
    @endif

    {{-- 1. Card Form Khusus Kontak Operasional Admin (Dinamis tampil di Petani & Konsumen) --}}
    <div class="card" style="padding:22px; margin-bottom:24px;">
        <div style="margin-bottom:14px;">
            <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Pengaturan Kontak Bantuan Admin (Tampil di Petani &amp; Konsumen)</h2>
            <p style="font-size:11.5px; color:#91A19B; margin:0;">Informasi ini disetel langsung oleh admin dan otomatis tampil secara dinamis pada halaman Pusat Bantuan Petani maupun Konsumen.</p>
        </div>

        <form action="{{ route('admin.bantuan.contact') }}" method="POST">
            @csrf
            <div style="display:grid; grid-template-columns:1fr 1fr 1.3fr auto; gap:12px; align-items:flex-end;">
                <div>
                    <label style="display:block; font-size:11px; font-weight:800; color:#365448; margin-bottom:5px;">Nomor HP / WhatsApp Admin <span style="color:#E11D48;">*</span></label>
                    <input type="text" name="admin_phone" value="{{ old('admin_phone', $adminPhone ?? '+62 812-3456-7890') }}" required
                           style="width:100%; border:1.5px solid #DFEAE5; padding:10px 14px; border-radius:10px; font-size:12.5px; font-weight:700; color:#172B29;">
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:800; color:#365448; margin-bottom:5px;">Email Resmi Admin <span style="color:#E11D48;">*</span></label>
                    <input type="email" name="admin_email" value="{{ old('admin_email', $adminEmail ?? 'support@tanilink.id') }}" required
                           style="width:100%; border:1.5px solid #DFEAE5; padding:10px 14px; border-radius:10px; font-size:12.5px; font-weight:700; color:#172B29;">
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:800; color:#365448; margin-bottom:5px;">Waktu Operasional Admin <span style="color:#E11D48;">*</span></label>
                    <input type="text" name="admin_hours" value="{{ old('admin_hours', $adminHours ?? 'Senin - Sabtu (08.00 - 17.00 WIB)') }}" required
                           style="width:100%; border:1.5px solid #DFEAE5; padding:10px 14px; border-radius:10px; font-size:12.5px; font-weight:700; color:#172B29;">
                </div>
                <div>
                    <button type="submit"
                            style="padding:11px 20px; border-radius:10px; border:none; background:#05C793; color:#053B2D; font-size:12.5px; font-weight:850; cursor:pointer; box-shadow:0 3px 10px rgba(5,199,147,0.25); white-space:nowrap;">
                        Simpan Kontak Operasional
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Segmented Navigation Tabs FAQ --}}
    <div class="segmented-tabs-wrapper" style="margin-bottom:22px;">
        <button type="button" @click="activeTab = 'petani'"
                class="segmented-tab-btn" :class="{ 'active': activeTab === 'petani' }">
            <i class="fa-solid fa-seedling segmented-tab-icon"></i>
            <span>FAQ Khusus Petani</span>
            <span class="segmented-tab-badge">
                {{ $petaniTopics->count() }}
            </span>
        </button>

        <button type="button" @click="activeTab = 'konsumen'"
                class="segmented-tab-btn" :class="{ 'active': activeTab === 'konsumen' }">
            <i class="fa-solid fa-cart-shopping segmented-tab-icon"></i>
            <span>FAQ Khusus Konsumen</span>
            <span class="segmented-tab-badge">
                {{ $konsumenTopics->count() }}
            </span>
        </button>

        <button type="button" @click="activeTab = 'semua'"
                class="segmented-tab-btn" :class="{ 'active': activeTab === 'semua' }">
            <i class="fa-solid fa-users segmented-tab-icon"></i>
            <span>FAQ Umum (Semua)</span>
            <span class="segmented-tab-badge">
                {{ $semuaTopics->count() }}
            </span>
        </button>
    </div>

    {{-- TAB 1: FAQ PETANI --}}
    <div class="card" style="padding:24px;" x-show="activeTab === 'petani'">
        <div style="margin-bottom:18px;">
            <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Daftar Topik Bantuan Khusus Petani</h2>
            <p style="font-size:11px; color:#91A19B; margin:0;">Hanya tampil pada portal Petani. Urutan mengikuti pertanyaan yang pertama dimasukkan di posisi paling bawah.</p>
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:left;">
                <thead>
                    <tr>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px; width:70px;">ID / URUTAN</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px; width:130px;">TARGET SASARAN</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">PERTANYAAN &amp; JAWABAN</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px; text-align:right; width:140px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($petaniTopics as $t)
                    <tr style="border-bottom:1px solid #EEF2F0;">
                        <td style="font-size:12px; font-weight:800; color:#183B30; padding:15px 10px; vertical-align:top;">
                            #{{ $t->id }}
                        </td>
                        <td style="font-size:11px; padding:15px 10px; vertical-align:top;">
                            <span style="display:inline-flex; align-items:center; gap:4px; padding:4px 9px; border-radius:6px; background:#E9F9F2; color:#0C8D65; font-weight:800; font-size:10.5px;">
                                🌱 Khusus Petani
                            </span>
                        </td>
                        <td style="font-size:12px; padding:15px 10px; vertical-align:top;">
                            <b style="color:#172B29; font-size:13px; font-weight:850; display:block; margin-bottom:4px;">{{ $t->question }}</b>
                            <p style="color:#50695E; font-size:11.5px; line-height:1.6; margin:0;">{{ $t->answer }}</p>
                        </td>
                        <td style="font-size:11px; padding:15px 10px; text-align:right; vertical-align:top;">
                            <div style="display:inline-flex; align-items:center; gap:6px;">
                                <button type="button"
                                        @click="openEdit({{ $t->id }}, '{{ $t->target_role }}', '{{ addslashes($t->question) }}', '{{ addslashes($t->answer) }}', {{ $t->order_num }})"
                                        style="display:inline-flex; align-items:center; gap:4px; padding:5px 10px; border-radius:7px; background:#F1EFFB; border:1px solid #D8D1F5; color:#7257CA; font-weight:800; font-size:11px; cursor:pointer;">
                                    <i class="fa-regular fa-pen-to-square"></i> Edit
                                </button>
                                <form action="{{ route('admin.bantuan.destroy', $t->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus pertanyaan FAQ ini?');"
                                      style="display:inline; margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            style="display:inline-flex; align-items:center; gap:4px; padding:5px 10px; border-radius:7px; background:#FEE2E2; border:1px solid #FECACA; color:#B91C1C; font-weight:800; font-size:11px; cursor:pointer;">
                                        <i class="fa-regular fa-trash-can"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding:40px; text-align:center; color:#8A9D95;">Belum ada topik khusus Petani.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- TAB 2: FAQ KONSUMEN --}}
    <div class="card" style="padding:24px;" x-show="activeTab === 'konsumen'" x-cloak>
        <div style="margin-bottom:18px;">
            <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Daftar Topik Bantuan Khusus Konsumen</h2>
            <p style="font-size:11px; color:#91A19B; margin:0;">Hanya tampil pada portal Konsumen. Urutan mengikuti pertanyaan yang pertama dimasukkan di posisi paling bawah.</p>
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:left;">
                <thead>
                    <tr>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px; width:70px;">ID / URUTAN</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px; width:130px;">TARGET SASARAN</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">PERTANYAAN &amp; JAWABAN</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px; text-align:right; width:140px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($konsumenTopics as $t)
                    <tr style="border-bottom:1px solid #EEF2F0;">
                        <td style="font-size:12px; font-weight:800; color:#183B30; padding:15px 10px; vertical-align:top;">
                            #{{ $t->id }}
                        </td>
                        <td style="font-size:11px; padding:15px 10px; vertical-align:top;">
                            <span style="display:inline-flex; align-items:center; gap:4px; padding:4px 9px; border-radius:6px; background:#E8F9FC; color:#0B8BAA; font-weight:800; font-size:10.5px;">
                                🛒 Khusus Konsumen
                            </span>
                        </td>
                        <td style="font-size:12px; padding:15px 10px; vertical-align:top;">
                            <b style="color:#172B29; font-size:13px; font-weight:850; display:block; margin-bottom:4px;">{{ $t->question }}</b>
                            <p style="color:#50695E; font-size:11.5px; line-height:1.6; margin:0;">{{ $t->answer }}</p>
                        </td>
                        <td style="font-size:11px; padding:15px 10px; text-align:right; vertical-align:top;">
                            <div style="display:inline-flex; align-items:center; gap:6px;">
                                <button type="button"
                                        @click="openEdit({{ $t->id }}, '{{ $t->target_role }}', '{{ addslashes($t->question) }}', '{{ addslashes($t->answer) }}', {{ $t->order_num }})"
                                        style="display:inline-flex; align-items:center; gap:4px; padding:5px 10px; border-radius:7px; background:#F1EFFB; border:1px solid #D8D1F5; color:#7257CA; font-weight:800; font-size:11px; cursor:pointer;">
                                    <i class="fa-regular fa-pen-to-square"></i> Edit
                                </button>
                                <form action="{{ route('admin.bantuan.destroy', $t->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus pertanyaan FAQ ini?');"
                                      style="display:inline; margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            style="display:inline-flex; align-items:center; gap:4px; padding:5px 10px; border-radius:7px; background:#FEE2E2; border:1px solid #FECACA; color:#B91C1C; font-weight:800; font-size:11px; cursor:pointer;">
                                        <i class="fa-regular fa-trash-can"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding:40px; text-align:center; color:#8A9D95;">Belum ada topik khusus Konsumen.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- TAB 3: FAQ SEMUA PENGGUNA --}}
    <div class="card" style="padding:24px;" x-show="activeTab === 'semua'" x-cloak>
        <div style="margin-bottom:18px;">
            <h2 style="font-size:15px; font-weight:850; margin:0 0 4px; color:#172B29;">Daftar Topik Bantuan Umum (Semua Pengguna)</h2>
            <p style="font-size:11px; color:#91A19B; margin:0;">Tampil di kedua portal Petani dan Konsumen.</p>
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:left;">
                <thead>
                    <tr>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px; width:70px;">ID / URUTAN</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px; width:130px;">TARGET SASARAN</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px;">PERTANYAAN &amp; JAWABAN</th>
                        <th style="font-size:10px; letter-spacing:0.3px; color:#8D9E98; font-weight:800; border-bottom:1px solid #E5ECEA; padding:12px 10px; text-align:right; width:140px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($semuaTopics as $t)
                    <tr style="border-bottom:1px solid #EEF2F0;">
                        <td style="font-size:12px; font-weight:800; color:#183B30; padding:15px 10px; vertical-align:top;">
                            #{{ $t->id }}
                        </td>
                        <td style="font-size:11px; padding:15px 10px; vertical-align:top;">
                            <span style="display:inline-flex; align-items:center; gap:4px; padding:4px 9px; border-radius:6px; background:#F1EFFB; color:#7257CA; font-weight:800; font-size:10.5px;">
                                👥 Semua Pengguna
                            </span>
                        </td>
                        <td style="font-size:12px; padding:15px 10px; vertical-align:top;">
                            <b style="color:#172B29; font-size:13px; font-weight:850; display:block; margin-bottom:4px;">{{ $t->question }}</b>
                            <p style="color:#50695E; font-size:11.5px; line-height:1.6; margin:0;">{{ $t->answer }}</p>
                        </td>
                        <td style="font-size:11px; padding:15px 10px; text-align:right; vertical-align:top;">
                            <div style="display:inline-flex; align-items:center; gap:6px;">
                                <button type="button"
                                        @click="openEdit({{ $t->id }}, '{{ $t->target_role }}', '{{ addslashes($t->question) }}', '{{ addslashes($t->answer) }}', {{ $t->order_num }})"
                                        style="display:inline-flex; align-items:center; gap:4px; padding:5px 10px; border-radius:7px; background:#F1EFFB; border:1px solid #D8D1F5; color:#7257CA; font-weight:800; font-size:11px; cursor:pointer;">
                                    <i class="fa-regular fa-pen-to-square"></i> Edit
                                </button>
                                <form action="{{ route('admin.bantuan.destroy', $t->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus pertanyaan FAQ ini?');"
                                      style="display:inline; margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            style="display:inline-flex; align-items:center; gap:4px; padding:5px 10px; border-radius:7px; background:#FEE2E2; border:1px solid #FECACA; color:#B91C1C; font-weight:800; font-size:11px; cursor:pointer;">
                                        <i class="fa-regular fa-trash-can"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding:40px; text-align:center; color:#8A9D95;">Belum ada topik umum.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH TOPIK --}}
    <div x-cloak x-show="showAddModal"
         style="position:fixed; inset:0; z-index:100; display:flex; align-items:center; justify-content:center; background:rgba(21,40,31,0.52); padding:16px;">
        <div style="background:#fff; border-radius:20px; width:520px; max-width:95%; padding:26px; box-shadow:0 35px 90px rgba(16,35,29,0.4);"
             @click.away="showAddModal = false">
            
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
                <h2 style="font-size:17px; font-weight:850; margin:0; color:#172B29;">Tambah Topik FAQ Baru</h2>
                <button type="button" @click="showAddModal = false" style="background:none; border:none; font-size:16px; cursor:pointer; color:#8A9D95;">✕</button>
            </div>

            <form action="{{ route('admin.bantuan.store') }}" method="POST">
                @csrf
                <div style="display:flex; flex-direction:column; gap:12px;">
                    <div>
                        <label style="display:block; font-size:11px; font-weight:800; color:#365448; margin-bottom:5px;">Target Peran Sasaran FAQ <span style="color:#E11D48;">*</span></label>
                        <select name="target_role" required
                                style="width:100%; border:1px solid #DFEAE5; padding:10px 14px; border-radius:10px; font-size:12px;">
                            <option value="petani">🌱 Khusus Petani</option>
                            <option value="konsumen">🛒 Khusus Konsumen</option>
                            <option value="semua">👥 Semua Pengguna (Petani & Konsumen)</option>
                        </select>
                    </div>

                    <div>
                        <label style="display:block; font-size:11px; font-weight:800; color:#365448; margin-bottom:5px;">Pertanyaan FAQ <span style="color:#E11D48;">*</span></label>
                        <input type="text" name="question" required placeholder="Contoh: Bagaimana mekanisme pengambilan hasil panen?"
                               style="width:100%; border:1px solid #DFEAE5; padding:10px 14px; border-radius:10px; font-size:12px;">
                    </div>

                    <div>
                        <label style="display:block; font-size:11px; font-weight:800; color:#365448; margin-bottom:5px;">Jawaban Lengkap <span style="color:#E11D48;">*</span></label>
                        <textarea name="answer" rows="4" required placeholder="Tuliskan penjelasan detail yang jelas dan informatif..."
                                  style="width:100%; border:1px solid #DFEAE5; padding:10px 14px; border-radius:10px; font-size:12px; resize:vertical;"></textarea>
                    </div>
                </div>

                <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:20px;">
                    <button type="button" @click="showAddModal = false"
                            style="padding:10px 16px; border-radius:11px; border:1px solid #DFEAE5; background:#fff; font-size:12px; font-weight:800; color:#365249; cursor:pointer;">
                        Batal
                    </button>
                    <button type="submit"
                            style="padding:10px 20px; border-radius:11px; border:none; background:#7257CA; font-size:12px; font-weight:800; color:#fff; cursor:pointer; box-shadow:0 3px 10px rgba(114,87,202,0.25);">
                        Simpan Topik FAQ
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- MODAL EDIT TOPIK --}}
    <div x-cloak x-show="showEditModal"
         style="position:fixed; inset:0; z-index:100; display:flex; align-items:center; justify-content:center; background:rgba(21,40,31,0.52); padding:16px;">
        <div style="background:#fff; border-radius:20px; width:520px; max-width:95%; padding:26px; box-shadow:0 35px 90px rgba(16,35,29,0.4);"
             @click.away="showEditModal = false">
            
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
                <h2 style="font-size:17px; font-weight:850; margin:0; color:#172B29;">Edit Topik FAQ</h2>
                <button type="button" @click="showEditModal = false" style="background:none; border:none; font-size:16px; cursor:pointer; color:#8A9D95;">✕</button>
            </div>

            <form :action="'{{ url('/admin/bantuan') }}/' + editId" method="POST">
                @csrf
                @method('PUT')
                <div style="display:flex; flex-direction:column; gap:12px;">
                    <div>
                        <label style="display:block; font-size:11px; font-weight:800; color:#365448; margin-bottom:5px;">Target Peran Sasaran FAQ <span style="color:#E11D48;">*</span></label>
                        <select name="target_role" x-model="editRole" required
                                style="width:100%; border:1px solid #DFEAE5; padding:10px 14px; border-radius:10px; font-size:12px;">
                            <option value="petani">🌱 Khusus Petani</option>
                            <option value="konsumen">🛒 Khusus Konsumen</option>
                            <option value="semua">👥 Semua Pengguna (Petani & Konsumen)</option>
                        </select>
                    </div>

                    <div>
                        <label style="display:block; font-size:11px; font-weight:800; color:#365448; margin-bottom:5px;">Pertanyaan FAQ <span style="color:#E11D48;">*</span></label>
                        <input type="text" name="question" x-model="editQuestion" required
                                style="width:100%; border:1px solid #DFEAE5; padding:10px 14px; border-radius:10px; font-size:12px;">
                    </div>

                    <div>
                        <label style="display:block; font-size:11px; font-weight:800; color:#365448; margin-bottom:5px;">Jawaban Lengkap <span style="color:#E11D48;">*</span></label>
                        <textarea name="answer" rows="4" x-model="editAnswer" required
                                  style="width:100%; border:1px solid #DFEAE5; padding:10px 14px; border-radius:10px; font-size:12px; resize:vertical;"></textarea>
                    </div>
                </div>

                <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:20px;">
                    <button type="button" @click="showEditModal = false"
                            style="padding:10px 16px; border-radius:11px; border:1px solid #DFEAE5; background:#fff; font-size:12px; font-weight:800; color:#365249; cursor:pointer;">
                        Batal
                    </button>
                    <button type="submit"
                            style="padding:10px 20px; border-radius:11px; border:none; background:#7257CA; font-size:12px; font-weight:800; color:#fff; cursor:pointer; box-shadow:0 3px 10px rgba(114,87,202,0.25);">
                        Perbarui Topik FAQ
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
