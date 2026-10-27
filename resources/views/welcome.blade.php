<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaniLink — Ekosistem Agrikultur Terencana & Escrow Bersama</title>
    <link rel="icon" type="image/png" href="{{ asset('images/tanilink-icon.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --green: #05C793;
            --green-d: #07876B;
            --ink: #172B29;
            --line: #E5ECEA;
            --bg: #F5F8F7;
        }
        * { box-sizing: border-box; scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #ffffff;
            color: var(--ink);
            margin: 0;
            padding: 0;
        }
        a { text-decoration: none; color: inherit; }
    </style>
</head>
<body>

    {{-- Top Navigation: Urutan dari kiri ke kanan berurutan dari atas ke bawah halaman --}}
    <header style="border-bottom:1px solid #E5ECEA; background:#ffffff; position:sticky; top:0; z-index:40;">
        <div style="max-width:1200px; margin:0 auto; padding:18px 30px; display:flex; align-items:center; justify-content:space-between;">
            {{-- Brand --}}
            <a href="/" style="display:flex; align-items:center;">
                <x-brand-logo variant="full" height="38px" alt="TaniLink" />
            </a>

            {{-- Nav links urut dari atas halaman ke bawah --}}
            <nav style="display:flex; gap:28px; font-size:13.5px; font-weight:750; color:#587065;">
                <a href="#ekosistem" style="transition:color 0.15s;" onmouseover="this.style.color='#05C793'" onmouseout="this.style.color='#587065'">Ekosistem</a>
                <a href="#keunggulan" style="transition:color 0.15s;" onmouseover="this.style.color='#05C793'" onmouseout="this.style.color='#587065'">Cara Kerja</a>
                <a href="#escrow" style="transition:color 0.15s;" onmouseover="this.style.color='#05C793'" onmouseout="this.style.color='#587065'">Escrow Bersama</a>
                <a href="#katalog" style="transition:color 0.15s;" onmouseover="this.style.color='#05C793'" onmouseout="this.style.color='#587065'">Komoditas Pilihan</a>
            </nav>

            {{-- Auth Buttons --}}
            <div style="display:flex; align-items:center; gap:12px;">
                @auth
                    @php
                        $role = Auth::user()->role;
                    @endphp
                    <a href="{{ route('portal.' . $role) }}" style="padding:10px 20px; border-radius:11px; font-size:12px; font-weight:850; color:#053B2D; background:#05C793; box-shadow:0 3px 10px rgba(5,199,147,0.25);">
                        Buka Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" style="padding:10px 18px; border-radius:11px; font-size:12px; font-weight:800; color:#163D34; border:1px solid #DFE8E5; background:#fff; transition:all 0.15s;">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" style="padding:10px 20px; border-radius:11px; font-size:12px; font-weight:850; color:#053B2D; background:#05C793; border:none; transition:all 0.15s; box-shadow:0 3px 10px rgba(5,199,147,0.25);">
                        Daftar Kemitraan
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- 1. Hero Section --}}
    <section style="background:linear-gradient(110deg, #073F32 0%, #0D9976 100%); color:white; min-height:500px; padding:80px 30px; position:relative; overflow:hidden;">
        <div style="max-width:1200px; margin:0 auto; display:flex; justify-content:space-between; align-items:center; gap:40px;">
            <div style="max-width:640px; z-index:2;">
                <span style="display:inline-block; padding:6px 12px; border-radius:8px; background:rgba(255,255,255,0.15); backdrop-filter:blur(6px); font-size:11px; font-weight:850; letter-spacing:1.5px; text-transform:uppercase; margin-bottom:18px;">
                    PLATFORM PRE-ORDER PERTANIAN TERENCANA
                </span>
                <h1 style="font-size:48px; letter-spacing:-1.8px; line-height:1.15; font-weight:850; margin:0 0 20px;">
                    Kepastian Harga & Pasokan Sebelum Musim Tanam.
                </h1>
                <p style="font-size:15px; color:#C2E8D8; line-height:1.8; margin:0 0 28px;">
                    Hubungkan kelompok tani produsen langsung dengan konsumen usaha (hotel, restoran, katering & pasar) melalui kontrak harga pasti dan sistem escrow bersama yang transparan.
                </p>
                <div style="display:flex; gap:14px; flex-wrap:wrap;">
                    <a href="{{ route('register') }}" style="padding:14px 26px; border-radius:12px; background:#05C793; color:#053B2D; font-size:13px; font-weight:850; display:inline-flex; align-items:center; gap:8px; box-shadow:0 4px 16px rgba(5,199,147,0.35);">
                        Mulai Kemitraan TaniLink
                    </a>
                    <a href="#ekosistem" style="padding:14px 26px; border-radius:12px; background:rgba(255,255,255,0.12); color:#fff; border:1px solid rgba(255,255,255,0.25); font-size:13px; font-weight:800; backdrop-filter:blur(8px);">
                        Pelajari Ekosistem
                    </a>
                </div>
            </div>

            <div style="font-size:130px; color:rgba(255,255,255,0.22); z-index:2; user-select:none; display:flex; align-items:center; justify-content:center;">
                <i class="fa-solid fa-wheat-awn"></i>
            </div>
        </div>
    </section>

    {{-- 2. Section Ekosistem --}}
    <section id="ekosistem" style="max-width:1200px; margin:0 auto; padding:80px 30px;">
        <div style="text-align:center; max-width:640px; margin:0 auto 48px;">
            <div style="font-size:10px; font-weight:900; color:#049874; text-transform:uppercase; letter-spacing:1.6px; margin-bottom:6px;">EKOSISTEM TANILINK</div>
            <h2 style="font-size:30px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 8px;">Koneksi Adil untuk Petani & Konsumen</h2>
            <p style="font-size:13px; color:#80908D; margin:0;">Menghapus ketidakpastian fluktuasi harga panen dan mata rantai perantara yang merugikan kedua belah pihak.</p>
        </div>

        <div style="display:grid; grid-template-columns:repeat(2, 1fr); gap:28px;">
            {{-- Card Petani --}}
            <div style="background:#fff; border:1px solid #E5ECEA; border-radius:20px; padding:32px; box-shadow:0 4px 20px rgba(31,61,45,0.04);">
                <div style="width:52px; height:52px; border-radius:14px; background:#E9FBF3; color:#0C8D65; display:grid; place-items:center; font-size:22px; margin-bottom:18px;">
                    <i class="fa-solid fa-person-digging"></i>
                </div>
                <h3 style="font-size:20px; font-weight:850; color:#172B29; margin:0 0 10px;">Untuk Petani (Produsen)</h3>
                <p style="font-size:13px; color:#50695E; line-height:1.75; margin:0 0 20px;">
                    Dapatkan kepastian pembeli dan patokan harga tetap sebelum menabur benih. Petani langsung menerima DP 30% di muka sebagai modal operasional dan pupuk tanpa jeratan hutang tengkulak.
                </p>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <div style="display:flex; align-items:center; gap:10px; font-size:12.5px; color:#07876B; font-weight:700;">
                        <span style="width:20px; height:20px; border-radius:50%; background:#DCF8EB; color:#007B55; display:grid; place-items:center; font-size:11px; flex-shrink:0;">✓</span>
                        <span>Kepastian kontrak penjualan hasil panen dengan harga tetap</span>
                    </div>
                    <div style="display:flex; align-items:center; gap:10px; font-size:12.5px; color:#07876B; font-weight:700;">
                        <span style="width:20px; height:20px; border-radius:50%; background:#DCF8EB; color:#007B55; display:grid; place-items:center; font-size:11px; flex-shrink:0;">✓</span>
                        <span>DP 30% modal tanam langsung cair sebelum masa tanam</span>
                    </div>
                    <div style="display:flex; align-items:center; gap:10px; font-size:12.5px; color:#07876B; font-weight:700;">
                        <span style="width:20px; height:20px; border-radius:50%; background:#DCF8EB; color:#007B55; display:grid; place-items:center; font-size:11px; flex-shrink:0;">✓</span>
                        <span>Dokumentasi dan update foto progres berkala ke pembeli</span>
                    </div>
                </div>
            </div>

            {{-- Card Konsumen --}}
            <div style="background:#fff; border:1px solid #E5ECEA; border-radius:20px; padding:32px; box-shadow:0 4px 20px rgba(31,61,45,0.04);">
                <div style="width:52px; height:52px; border-radius:14px; background:#E8F9FC; color:#0B8BAA; display:grid; place-items:center; font-size:22px; margin-bottom:18px;">
                    <i class="fa-solid fa-hotel"></i>
                </div>
                <h3 style="font-size:20px; font-weight:850; color:#172B29; margin:0 0 10px;">Untuk Konsumen (Industri & Usaha)</h3>
                <p style="font-size:13px; color:#50695E; line-height:1.75; margin:0 0 20px;">
                    Jaminan suplai bahan segar berkualitas tinggi langsung dari kelompok tani terverifikasi. Anggaran biaya bahan pangan terukur kebal dari lonjakan harga pasar dadakan.
                </p>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <div style="display:flex; align-items:center; gap:10px; font-size:12.5px; color:#087894; font-weight:700;">
                        <span style="width:20px; height:20px; border-radius:50%; background:#E0F7FA; color:#0B8BAA; display:grid; place-items:center; font-size:11px; flex-shrink:0;">✓</span>
                        <span>Jaminan kepastian suplai pasokan sesuai jadwal panen</span>
                    </div>
                    <div style="display:flex; align-items:center; gap:10px; font-size:12.5px; color:#087894; font-weight:700;">
                        <span style="width:20px; height:20px; border-radius:50%; background:#E0F7FA; color:#0B8BAA; display:grid; place-items:center; font-size:11px; flex-shrink:0;">✓</span>
                        <span>Stabilitas harga pangan pasti dengan kontrak fixed-price</span>
                    </div>
                    <div style="display:flex; align-items:center; gap:10px; font-size:12.5px; color:#087894; font-weight:700;">
                        <span style="width:20px; height:20px; border-radius:50%; background:#E0F7FA; color:#0B8BAA; display:grid; place-items:center; font-size:11px; flex-shrink:0;">✓</span>
                        <span>Monitoring pertumbuhan tanaman langsung dari kebun petani</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. Section Cara Kerja --}}
    <section id="keunggulan" style="background:#F9FBFA; border-top:1px solid #E5ECEA; border-bottom:1px solid #E5ECEA; padding:80px 30px;">
        <div style="max-width:1200px; margin:0 auto;">
            <div style="text-align:center; max-width:640px; margin:0 auto 48px;">
                <div style="font-size:10px; font-weight:900; color:#05C793; text-transform:uppercase; letter-spacing:1.6px; margin-bottom:6px;">ALUR KERJA PLATFORM</div>
                <h2 style="font-size:30px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 8px;">Cara Kerja TaniLink</h2>
                <p style="font-size:13px; color:#80908D; margin:0;">Empat langkah terstruktur menjamin transaksi pertanian yang transparan dan saling menguntungkan.</p>
            </div>

            <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:20px;">
                <div style="background:#fff; border:1px solid #E5ECEA; border-radius:18px; padding:24px;">
                    <div style="width:40px; height:40px; border-radius:11px; background:#E9FBF3; color:#0C8D65; display:grid; place-items:center; font-size:18px; font-weight:800; margin-bottom:14px;">
                        1
                    </div>
                    <h3 style="font-size:15px; font-weight:850; color:#172B29; margin:0 0 8px;">Penerbitan Rencana</h3>
                    <p style="font-size:12px; color:#50695E; line-height:1.7; margin:0;">
                        Petani mempublikasikan estimasi hasil panen, varietas benih, luas lahan, dan jadwal panen di platform.
                    </p>
                </div>

                <div style="background:#fff; border:1px solid #E5ECEA; border-radius:18px; padding:24px;">
                    <div style="width:40px; height:40px; border-radius:11px; background:#E8F9FC; color:#0B8BAA; display:grid; place-items:center; font-size:18px; font-weight:800; margin-bottom:14px;">
                        2
                    </div>
                    <h3 style="font-size:15px; font-weight:850; color:#172B29; margin:0 0 8px;">Kontrak & DP 30%</h3>
                    <p style="font-size:12px; color:#50695E; line-height:1.7; margin:0;">
                        Konsumen melakukan pre-order kuantitas. DP 30% diteruskan ke petani sebagai modal, 70% saldo diamankan di escrow.
                    </p>
                </div>

                <div style="background:#fff; border:1px solid #E5ECEA; border-radius:18px; padding:24px;">
                    <div style="width:40px; height:40px; border-radius:11px; background:#FFF8EA; color:#BA7A1F; display:grid; place-items:center; font-size:18px; font-weight:800; margin-bottom:14px;">
                        3
                    </div>
                    <h3 style="font-size:15px; font-weight:850; color:#172B29; margin:0 0 8px;">Monitoring Progres</h3>
                    <p style="font-size:12px; color:#50695E; line-height:1.7; margin:0;">
                        Petani mengunggah catatan dan foto fase tanam berkala yang dapat dipantau langsung oleh konsumen secara real-time.
                    </p>
                </div>

                <div style="background:#fff; border:1px solid #E5ECEA; border-radius:18px; padding:24px;">
                    <div style="width:40px; height:40px; border-radius:11px; background:#F1EFFB; color:#7257CA; display:grid; place-items:center; font-size:18px; font-weight:800; margin-bottom:14px;">
                        4
                    </div>
                    <h3 style="font-size:15px; font-weight:850; color:#172B29; margin:0 0 8px;">Panen & Rilis Escrow</h3>
                    <p style="font-size:12px; color:#50695E; line-height:1.7; margin:0;">
                        Hasil panen diserahterimakan secara mandiri di lokasi kebun, dan saldo 70% escrow dicairkan tuntas ke petani.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. Section Escrow Bersama --}}
    <section id="escrow" style="max-width:1200px; margin:0 auto; padding:80px 30px;">
        <div style="text-align:center; max-width:620px; margin:0 auto 48px;">
            <div style="font-size:10px; font-weight:900; color:#7257CA; text-transform:uppercase; letter-spacing:1.6px; margin-bottom:6px;">KEAMANAN TRANSAKSI</div>
            <h2 style="font-size:30px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 8px;">Skema Escrow Bersama TaniLink</h2>
            <p style="font-size:13px; color:#80908D; margin:0;">Transaksi terlindungi dengan pemisahan modal kerja petani dan dana pelunasan aman.</p>
        </div>

        <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:24px;">
            <div style="background:#fff; border:1px solid #E5ECEA; border-radius:18px; padding:28px; box-shadow:0 4px 18px rgba(0,0,0,0.03);">
                <div style="width:44px; height:44px; border-radius:12px; background:#E9FBF3; color:#0C8D65; display:grid; place-items:center; font-size:20px; font-weight:850; margin-bottom:16px;">
                    1
                </div>
                <h3 style="font-size:16px; font-weight:850; color:#172B29; margin:0 0 8px;">30% DP Modal Tanam</h3>
                <p style="font-size:12px; color:#50695E; line-height:1.7; margin:0;">
                    Saat pre-order disepakati, 30% dari total nilai transaksi langsung disalurkan ke rekening petani mitra sebagai modal bibit dan perawatan lahan.
                </p>
            </div>

            <div style="background:#fff; border:1px solid #E5ECEA; border-radius:18px; padding:28px; box-shadow:0 4px 18px rgba(0,0,0,0.03);">
                <div style="width:44px; height:44px; border-radius:12px; background:#F1EFFB; color:#7257CA; display:grid; place-items:center; font-size:20px; font-weight:850; margin-bottom:16px;">
                    2
                </div>
                <h3 style="font-size:16px; font-weight:850; color:#172B29; margin:0 0 8px;">70% Escrow Terkunci Aman</h3>
                <p style="font-size:12px; color:#50695E; line-height:1.7; margin:0;">
                    Sisa 70% saldo diamankan di rekening escrow bersama TaniLink selama masa tanam, memberi kepastian komitmen bagi kedua belah pihak.
                </p>
            </div>

            <div style="background:#fff; border:1px solid #E5ECEA; border-radius:18px; padding:28px; box-shadow:0 4px 18px rgba(0,0,0,0.03);">
                <div style="width:44px; height:44px; border-radius:12px; background:#E8F9FC; color:#0B8BAA; display:grid; place-items:center; font-size:20px; font-weight:850; margin-bottom:16px;">
                    3
                </div>
                <h3 style="font-size:16px; font-weight:850; color:#172B29; margin:0 0 8px;">Verifikasi & Rilis Saldo</h3>
                <p style="font-size:12px; color:#50695E; line-height:1.7; margin:0;">
                    Setelah masa panen tiba dan hasil diterima di titik serah terima, saldo 70% dirilis ke petani secara transparan dan tuntas.
                </p>
            </div>
        </div>
    </section>

    {{-- 5. Section Showcase Komoditas Pilihan --}}
    <section id="katalog" style="background:#F9FBFA; border-top:1px solid #E5ECEA; border-bottom:1px solid #E5ECEA; padding:80px 30px;">
        <div style="max-width:1200px; margin:0 auto;">
            <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:36px;">
                <div>
                    <div style="font-size:10px; font-weight:900; color:#049874; text-transform:uppercase; letter-spacing:1.6px; margin-bottom:6px;">KATALOG TERSEDIA</div>
                    <h2 style="font-size:28px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Komoditas Siap Pre-Order</h2>
                    <p style="font-size:13px; color:#80908D; margin:0;">Pesan langsung sebelum jadwal panen untuk mengunci pasokan dengan harga terbaik.</p>
                </div>
                <a href="{{ route('login') }}" style="display:inline-flex; align-items:center; gap:6px; padding:10px 18px; border-radius:10px; background:#EBF9F2; color:#078362; font-size:12px; font-weight:800; text-decoration:none;">
                    Lihat Semua Komoditas
                </a>
            </div>

            <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:20px;">
                @php
                    $showListings = isset($listings) && $listings->isNotEmpty() ? $listings : collect();
                @endphp

                @forelse($showListings as $item)
                <div style="border:1px solid #E5ECEA; border-radius:18px; background:#fff; overflow:hidden; display:flex; flex-direction:column; justify-content:space-between; transition:transform 0.2s, box-shadow 0.2s; box-shadow:0 3px 12px rgba(0,0,0,0.03);"
                     onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 24px rgba(0,0,0,0.08)';"
                     onmouseout="this.style.transform='none'; this.style.boxShadow='0 3px 12px rgba(0,0,0,0.03)';">
                    
                    <div style="height:140px; display:grid; place-items:center; background:linear-gradient(145deg, #F0FDF4, #E2F8EE); position:relative; overflow:hidden;">
                        @if($item->image_url)
                            <img src="{{ str_starts_with($item->image_url, 'http') ? $item->image_url : asset($item->image_url) }}"
                                 alt="{{ $item->commodity_name }}" style="width:100%; height:100%; object-fit:cover; position:absolute; inset:0;">
                        @else
                            <i class="fa-solid fa-seedling" style="font-size:38px; color:#05C793;"></i>
                        @endif
                        <span style="position:absolute; top:10px; left:10px; font-size:9.5px; font-weight:850; background:#fff; color:#0C8D65; border-radius:7px; padding:3px 8px; box-shadow:0 2px 5px rgba(0,0,0,0.08);">
                            Stok {{ number_format($item->available_kg, 0, ',', '.') }} kg
                        </span>
                    </div>

                    <div style="padding:16px;">
                        <div style="font-size:9.5px; color:#80908D; text-transform:uppercase; font-weight:800;">{{ $item->category }}</div>
                        <h3 style="font-size:14px; font-weight:850; margin:4px 0 2px; color:#172B29; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $item->commodity_name }}
                        </h3>
                        <div style="font-size:10.5px; color:#80908D;">{{ $item->variety ?? 'Unggul Lokal' }} &bull; {{ $item->location ?? 'Malang' }}</div>
                        
                        <div style="font-size:16px; font-weight:900; color:#0A765A; margin:10px 0 6px;">
                            Rp {{ number_format($item->price_per_kg, 0, ',', '.') }}
                            <span style="font-size:10px; font-weight:500; color:#80908D;">/kg</span>
                        </div>

                        <div style="font-size:10.5px; color:#80908D;">
                            Est. Panen: <b style="color:#172B29;">{{ \Carbon\Carbon::parse($item->harvest_estimated_date)->format('d M Y') }}</b>
                        </div>
                    </div>
                </div>
                @empty
                <div style="grid-column:span 4; text-align:center; padding:50px; background:#fff; border-radius:16px; border:1px solid #E5ECEA;">
                    <div style="font-size:36px; margin-bottom:8px; color:#05C793;"><i class="fa-solid fa-seedling"></i></div>
                    <h3 style="font-size:15px; font-weight:800; color:#172B29;">Katalog Komoditas Sedang Dimuat</h3>
                    <p style="font-size:12px; color:#8A9A94; margin:4px 0 16px;">Silakan login atau daftar untuk melihat penawaran aktif kelompok tani mitra.</p>
                    <a href="{{ route('login') }}" style="display:inline-block; padding:10px 18px; border-radius:10px; background:#05C793; color:#053B2D; font-size:12px; font-weight:800;">
                        Masuk ke Akun
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer style="background:#ffffff; padding:40px 30px; text-align:center;">
        <div style="max-width:1200px; margin:0 auto;">
            <div style="display:flex; justify-content:center; align-items:center; margin-bottom:12px;">
                <x-brand-logo variant="full" height="34px" alt="TaniLink" />
            </div>
            <p style="font-size:11.5px; color:#8A9D95; margin:0 0 10px;">
                Platform Agrikultur Terencana &bull; Kepastian Suplai Panen & Rekening Bersama Escrow
            </p>
            <div style="font-size:10px; color:#A2AEAA;">
                &copy; {{ date('Y') }} TaniLink Indonesia. Hak cipta dilindungi undang-undang.
            </div>
        </div>
    </footer>

</body>
</html>