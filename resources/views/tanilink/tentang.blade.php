@extends('layouts.app')

@section('title', 'Tentang TaniLink & SDGs - Tim Truwelu UM')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">
    
    <!-- Hero / Title -->
    <div class="text-center max-w-3xl mx-auto">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-black uppercase tracking-wider border border-emerald-300">
            <i class="fa-solid fa-graduation-cap text-emerald-600"></i> Proposal Hackathon UI/UX 2026
        </span>
        <h1 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight mt-3">
            Infrastruktur Digital Marketplace Pertanian Berbasis Pre-Order
        </h1>
        <p class="text-slate-500 text-sm sm:text-base mt-4 leading-relaxed">
            Karya inovasi sistem rantai pasok agrikultur oleh <strong>Tim Truwelu (Universitas Negeri Malang)</strong> untuk menciptakan stabilitas ekonomi petani hulu dan kepastian bahan baku industri hilir.
        </p>
    </div>

    <!-- The Core Problem: Push-Model vs Pull-Model (Halaman 1-2 Makalah) -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 sm:p-12">
        <div class="max-w-3xl mb-8">
            <span class="text-xs font-black uppercase text-emerald-700 tracking-wider">Akar Masalah (The Fundamental Gap)</span>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">Mengapa Sistem Pertanian Konvensional Merugikan Petani?</h2>
            <p class="text-slate-600 text-sm mt-2 leading-relaxed">
                Selama ini, sistem pertanian Indonesia berjalan dengan pola <strong>Push-Model</strong>: petani menanam komoditas terlebih dahulu, mengeluarkan modal benih dan pupuk (seringkali meminjam ke tengkulak dengan bunga tinggi), baru mencari pembeli saat panen tiba. Saat panen raya terjadi surplus pasokan lokal, harga anjlok drastis dan petani menanggung seluruh risiko kerugian sendirian (Sudrajat et al., 2022).
            </p>
        </div>

        <!-- Comparison Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
            
            <!-- Push Model (Lama) -->
            <div class="p-6 rounded-3xl bg-rose-50/70 border border-rose-200">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-lg">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-lg text-rose-950">Model Tradisional (Push-Model)</h3>
                        <p class="text-xs text-rose-700">Tanam Dulu, Baru Cari Pembeli</p>
                    </div>
                </div>
                <ul class="space-y-3 text-xs text-rose-900/80 leading-relaxed">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-xmark text-rose-500 mt-0.5"></i>
                        <span><strong>Harga Anjlok Bebas:</strong> Panen bersamaan menyebabkan oversupply lokal dan harga komoditas jatuh.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-xmark text-rose-500 mt-0.5"></i>
                        <span><strong>Jeratan Utang Tengkulak:</strong> Petani berutang untuk modal pupuk/benih dengan ikatan jual paksa di bawah harga wajar.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-xmark text-rose-500 mt-0.5"></i>
                        <span><strong>Ketidakpastian Industri (Horeka):</strong> Restoran & hotel kesulitan memprediksi biaya menu akibat fluktuasi harga harian.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-xmark text-rose-500 mt-0.5"></i>
                        <span><strong>Logistik Tidak Efisien:</strong> Kurir menunggu berjam-jam tanpa jadwal pasti dan rute menyebar boros BBM.</span>
                    </li>
                </ul>
            </div>

            <!-- Pull Model (TaniLink) -->
            <div class="p-6 rounded-3xl bg-emerald-50/80 border border-emerald-300 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-bold text-lg shadow-md shadow-emerald-600/30">
                        <i class="fa-solid fa-shield-check"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-lg text-emerald-950">Model Solusi TaniLink (Pull-Model)</h3>
                        <p class="text-xs text-emerald-700">Pasar & Harga Dikunci Sebelum Tanam</p>
                    </div>
                </div>
                <ul class="space-y-3 text-xs text-emerald-900/90 leading-relaxed">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5"></i>
                        <span><strong>Harga Tetap Terkunci (Fixed Price):</strong> Kontrak digital disepakati sebelum benih ditanam. Risiko pasar dieliminasi.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5"></i>
                        <span><strong>Modal Awal Tanpa Utang:</strong> Petani menerima DP 30% via escrow dari pembeli untuk membeli benih unggul & pupuk.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5"></i>
                        <span><strong>Kepastian Pasokan Horeka:</strong> Industri kuliner mendapatkan transparansi lahan, jadwal kirim pasti, dan kestabilan biaya.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5"></i>
                        <span><strong>Optimasi Rute Kurir:</strong> Algoritma shortest-path geolokasi menghemat 28% BBM penjemputan hasil tani hulu ke hilir.</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <!-- 3 SDGs Pillars -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="bg-white p-7 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl font-black mb-4">
                    9
                </div>
                <span class="text-[11px] font-black uppercase text-emerald-700 tracking-wider">Pilar Utama</span>
                <h3 class="text-lg font-black text-slate-900 mt-1">SDG 9: Industri, Inovasi & Infrastruktur</h3>
                <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                    Membangun infrastruktur digital agrikultur yang inklusif untuk menghubungkan sentra produksi hulu pedesaan dengan pusat industri hilir perkotaan.
                </p>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-100 text-[11px] font-bold text-emerald-700">
                Target 9.c: Peningkatan Akses TIK Pertanian
            </div>
        </div>

        <div class="bg-white p-7 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center text-xl font-black mb-4">
                    1
                </div>
                <span class="text-[11px] font-black uppercase text-blue-700 tracking-wider">Dampak Sosial</span>
                <h3 class="text-lg font-black text-slate-900 mt-1">SDG 1: Tanpa Kemiskinan</h3>
                <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                    Melindungi petani skala kecil dari jebakan kemiskinan akibat jeratan utang rentenir/tengkulak dan volatilitas harga panen yang tak menentu.
                </p>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-100 text-[11px] font-bold text-blue-700">
                Target 1.5: Membangun Ketahanan Ekonomi Petani
            </div>
        </div>

        <div class="bg-white p-7 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center text-xl font-black mb-4">
                    17
                </div>
                <span class="text-[11px] font-black uppercase text-amber-700 tracking-wider">Kolaborasi</span>
                <h3 class="text-lg font-black text-slate-900 mt-1">SDG 17: Kemitraan Menuju Tujuan</h3>
                <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                    Menciptakan ekosistem kemitraan strategis yang adil dan transparan antara kelompok tani (Gapoktan), sektor bisnis Horeka, dan pengusaha logistik lokal.
                </p>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-100 text-[11px] font-bold text-amber-700">
                Target 17.16: Kemitraan Multipihak Rantai Pasok
            </div>
        </div>

    </div>

    <!-- UI/UX Laws Applied in TaniLink Web -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 sm:p-10">
        <div class="max-w-2xl mb-8">
            <span class="text-xs font-black uppercase text-emerald-700 tracking-wider">Human-Centered Design</span>
            <h2 class="text-2xl font-black text-slate-900 mt-1">Penerapan Hukum Desain UI/UX dalam TaniLink</h2>
            <p class="text-slate-500 text-xs sm:text-sm mt-1">Menjamin platform dapat digunakan dengan mudah oleh pengguna dengan berbagai tingkat literasi digital.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 text-xs">
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200">
                <strong class="text-slate-900 text-sm block mb-1">Hick's Law</strong>
                <p class="text-slate-600 leading-relaxed">
                    Menyederhanakan antarmuka petani menjadi satu aksi utama per layar (kamera update foto atau form 3 langkah) agar pengguna tidak merasa overwhelmed.
                </p>
            </div>
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200">
                <strong class="text-slate-900 text-sm block mb-1">Fitts's Law</strong>
                <p class="text-slate-600 leading-relaxed">
                    Tombol kamera dan aksi booking dirancang besar dengan kontras visual tinggi agar cepat dan mudah diakses pengguna dalam segala kondisi pencahayaan.
                </p>
            </div>
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200">
                <strong class="text-slate-900 text-sm block mb-1">Visibility of System Status</strong>
                <p class="text-slate-600 leading-relaxed">
                    Setiap transaksi dan dana escrow memiliki badge status transparan: <em>Menunggu DP, DP Masuk, Fase Tanam, Siap Panen, Lunas</em>.
                </p>
            </div>
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200">
                <strong class="text-slate-900 text-sm block mb-1">Progressive Disclosure</strong>
                <p class="text-slate-600 leading-relaxed">
                    Untuk dashboard purchasing Horeka, ringkasan analitik disajikan bertahap: kartu metrik utama di atas, rincian teknis saat diklik.
                </p>
            </div>
        </div>
    </div>

    <!-- Development Team (Tim Truwelu - UM) -->
    <div class="bg-emerald-950 text-white rounded-3xl p-8 sm:p-12 shadow-xl border border-emerald-900">
        <div class="text-center max-w-xl mx-auto mb-10">
            <span class="text-xs font-black uppercase tracking-wider text-emerald-400">Penyusun Inovasi</span>
            <h2 class="text-2xl sm:text-3xl font-black mt-1">Tim Truwelu</h2>
            <p class="text-emerald-300 text-xs sm:text-sm mt-1">Universitas Negeri Malang (UM)</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-4xl mx-auto">
            
            <div class="p-6 rounded-2xl bg-emerald-900/60 border border-emerald-800 text-center flex flex-col items-center">
                <div class="w-16 h-16 rounded-full bg-emerald-700 border-2 border-emerald-400 flex items-center justify-center font-black text-xl mb-3">
                    RA
                </div>
                <h4 class="font-extrabold text-base text-white">Raden Ahmad</h4>
                <p class="text-xs text-emerald-300 mt-0.5">Universitas Negeri Malang</p>
            </div>

            <div class="p-6 rounded-2xl bg-emerald-900/60 border border-emerald-800 text-center flex flex-col items-center">
                <div class="w-16 h-16 rounded-full bg-emerald-700 border-2 border-emerald-400 flex items-center justify-center font-black text-xl mb-3">
                    WA
                </div>
                <h4 class="font-extrabold text-base text-white">Wildan Andika Putra</h4>
                <p class="text-xs text-emerald-300 mt-0.5">Universitas Negeri Malang</p>
            </div>

            <div class="p-6 rounded-2xl bg-emerald-900/60 border border-emerald-800 text-center flex flex-col items-center">
                <div class="w-16 h-16 rounded-full bg-emerald-700 border-2 border-emerald-400 flex items-center justify-center font-black text-xl mb-3">
                    TJ
                </div>
                <h4 class="font-extrabold text-base text-white">Tegar Jatu Ramadhani</h4>
                <p class="text-xs text-emerald-300 mt-0.5">Universitas Negeri Malang</p>
            </div>

        </div>
    </div>

</div>
@endsection

