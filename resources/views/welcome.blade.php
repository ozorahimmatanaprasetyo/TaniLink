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
            overflow-x: hidden;
        }
        [x-cloak] { display: none !important; }
        a { text-decoration: none; color: inherit; }

        /* ── Header Styles ── */
        .landing-header {
            border-bottom: 1px solid #E5ECEA;
            background: #ffffff;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .landing-nav-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 16px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .landing-desktop-nav {
            display: flex;
            gap: 28px;
            font-size: 13.5px;
            font-weight: 750;
            color: #587065;
        }
        .landing-desktop-nav a {
            transition: color 0.15s;
        }
        .landing-desktop-nav a:hover {
            color: var(--green);
        }
        .landing-desktop-auth {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .landing-mobile-toggle {
            display: none;
            background: none;
            border: 1px solid #DFE8E5;
            padding: 8px 12px;
            border-radius: 10px;
            color: #172B29;
            font-size: 18px;
            cursor: pointer;
        }

        /* ── Mobile Menu Dropdown ── */
        .landing-mobile-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #ffffff;
            border-bottom: 1px solid #E5ECEA;
            padding: 18px 20px 24px;
            box-shadow: 0 16px 32px rgba(12, 38, 28, 0.08);
            z-index: 49;
        }
        .landing-mobile-links {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 18px;
        }
        .landing-mobile-links a {
            font-size: 14px;
            font-weight: 750;
            color: #385248;
            padding: 10px 14px;
            border-radius: 10px;
            background: #F8FBFA;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .landing-mobile-links a:hover {
            color: var(--green-d);
            background: #EBF8F2;
        }
        .landing-mobile-auth {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* ── Hero Responsive ── */
        .hero-section {
            background: linear-gradient(110deg, #073F32 0%, #0D9976 100%);
            color: white;
            min-height: 480px;
            padding: 76px 28px;
            position: relative;
            overflow: hidden;
        }
        .hero-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 36px;
        }
        .hero-content {
            max-width: 640px;
            z-index: 2;
        }
        .hero-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 8px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(6px);
            font-size: 11px;
            font-weight: 850;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 18px;
        }
        .hero-title {
            font-size: 46px;
            letter-spacing: -1.6px;
            line-height: 1.15;
            font-weight: 850;
            margin: 0 0 20px;
        }
        .hero-desc {
            font-size: 15px;
            color: #C2E8D8;
            line-height: 1.8;
            margin: 0 0 28px;
        }
        .hero-buttons {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }
        .hero-icon {
            font-size: 120px;
            color: rgba(255,255,255,0.20);
            z-index: 2;
            user-select: none;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* ── Responsive Grids ── */
        .section-ekosistem {
            max-width: 1200px;
            margin: 0 auto;
            padding: 80px 28px;
        }
        .grid-ekosistem {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 28px;
        }

        .section-cara-kerja {
            background: #F9FBFA;
            border-top: 1px solid #E5ECEA;
            border-bottom: 1px solid #E5ECEA;
            padding: 80px 28px;
        }
        .grid-cara-kerja {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .section-escrow {
            max-width: 1200px;
            margin: 0 auto;
            padding: 80px 28px;
        }
        .grid-escrow {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .section-katalog {
            background: #F9FBFA;
            border-top: 1px solid #E5ECEA;
            border-bottom: 1px solid #E5ECEA;
            padding: 80px 28px;
        }
        .katalog-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 36px;
        }
        .grid-katalog {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        /* ── Breakpoints ── */
        @media (max-width: 992px) {
            .grid-cara-kerja {
                grid-template-columns: repeat(2, 1fr);
            }
            .grid-katalog {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .landing-nav-inner {
                padding: 12px 18px;
            }
            .landing-desktop-nav,
            .landing-desktop-auth {
                display: none !important;
            }
            .landing-mobile-toggle {
                display: block !important;
            }
            .landing-mobile-menu {
                display: block;
            }

            .hero-section {
                padding: 46px 18px;
                min-height: auto;
            }
            .hero-container {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
            }
            .hero-title {
                font-size: 30px;
                letter-spacing: -1px;
                line-height: 1.25;
            }
            .hero-desc {
                font-size: 13.5px;
                line-height: 1.65;
                margin-bottom: 22px;
            }
            .hero-buttons {
                flex-direction: column;
                width: 100%;
            }
            .hero-buttons a {
                width: 100%;
                text-align: center;
                justify-content: center;
                box-sizing: border-box;
            }
            .hero-icon {
                display: none;
            }

            .section-ekosistem,
            .section-cara-kerja,
            .section-escrow,
            .section-katalog {
                padding: 48px 18px;
            }

            .grid-ekosistem,
            .grid-cara-kerja,
            .grid-escrow,
            .grid-katalog {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .katalog-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 14px;
            }
            .katalog-header a {
                width: 100%;
                justify-content: center;
                box-sizing: border-box;
            }
        }
    </style>
</head>
<body x-data="{ mobileMenuOpen: false }">

    {{-- Top Navigation --}}
    <header class="landing-header">
        <div class="landing-nav-inner">
            {{-- Brand --}}
            <a href="/" style="display:flex; align-items:center;">
                <x-brand-logo variant="full" height="36px" alt="TaniLink" />
            </a>

            {{-- Desktop Nav links --}}
            <nav class="landing-desktop-nav">
                <a href="#ekosistem">Ekosistem</a>
                <a href="#keunggulan">Cara Kerja</a>
                <a href="#escrow">Escrow Bersama</a>
                <a href="#katalog">Komoditas Pilihan</a>
            </nav>

            {{-- Desktop Auth Buttons --}}
            <div class="landing-desktop-auth">
                @auth
                    @php $role = Auth::user()->role; @endphp
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

            {{-- Mobile Hamburger Toggle --}}
            <button type="button" class="landing-mobile-toggle" @click="mobileMenuOpen = !mobileMenuOpen" aria-label="Menu Navigasi">
                <i :class="mobileMenuOpen ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'"></i>
            </button>
        </div>

        {{-- Mobile Menu Dropdown --}}
        <div class="landing-mobile-menu" x-cloak x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="landing-mobile-links">
                <a href="#ekosistem" @click="mobileMenuOpen = false">
                    <span>Ekosistem TaniLink</span>
                    <i class="fa-solid fa-chevron-right" style="font-size:11px; color:#A2AEAA;"></i>
                </a>
                <a href="#keunggulan" @click="mobileMenuOpen = false">
                    <span>Cara Kerja Platform</span>
                    <i class="fa-solid fa-chevron-right" style="font-size:11px; color:#A2AEAA;"></i>
                </a>
                <a href="#escrow" @click="mobileMenuOpen = false">
                    <span>Skema Escrow Bersama</span>
                    <i class="fa-solid fa-chevron-right" style="font-size:11px; color:#A2AEAA;"></i>
                </a>
                <a href="#katalog" @click="mobileMenuOpen = false">
                    <span>Katalog Komoditas Siap Pre-Order</span>
                    <i class="fa-solid fa-chevron-right" style="font-size:11px; color:#A2AEAA;"></i>
                </a>
            </div>

            <div class="landing-mobile-auth">
                @auth
                    @php $role = Auth::user()->role; @endphp
                    <a href="{{ route('portal.' . $role) }}" style="display:block; text-align:center; padding:12px; border-radius:11px; font-size:13px; font-weight:850; color:#053B2D; background:#05C793;">
                        Buka Dashboard ({{ ucfirst($role) }})
                    </a>
                @else
                    <a href="{{ route('login') }}" style="display:block; text-align:center; padding:11px; border-radius:11px; font-size:13px; font-weight:800; color:#163D34; border:1px solid #DFE8E5; background:#fff;">
                        Masuk Akun
                    </a>
                    <a href="{{ route('register') }}" style="display:block; text-align:center; padding:12px; border-radius:11px; font-size:13px; font-weight:850; color:#053B2D; background:#05C793;">
                        Daftar Kemitraan Baru
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- 1. Hero Section --}}
    <section class="hero-section">
        <div class="hero-container">
            <div class="hero-content">
                <span class="hero-badge">
                    PLATFORM PRE-ORDER PERTANIAN TERENCANA
                </span>
                <h1 class="hero-title">
                    Kepastian Harga &amp; Pasokan Sebelum Musim Tanam.
                </h1>
                <p class="hero-desc">
                    Hubungkan kelompok tani produsen langsung dengan konsumen usaha (hotel, restoran, katering &amp; pasar) melalui kontrak harga pasti dan sistem escrow bersama yang transparan.
                </p>
                <div class="hero-buttons">
                    <a href="{{ route('register') }}" style="padding:14px 26px; border-radius:12px; background:#05C793; color:#053B2D; font-size:13px; font-weight:850; display:inline-flex; align-items:center; justify-content:center; gap:8px; box-shadow:0 4px 16px rgba(5,199,147,0.35);">
                        Mulai Kemitraan TaniLink
                    </a>
                    <a href="#ekosistem" style="padding:14px 26px; border-radius:12px; background:rgba(255,255,255,0.12); color:#fff; border:1px solid rgba(255,255,255,0.25); font-size:13px; font-weight:800; backdrop-filter:blur(8px); display:inline-flex; align-items:center; justify-content:center;">
                        Pelajari Ekosistem
                    </a>
                </div>
            </div>

            <div class="hero-icon">
                <i class="fa-solid fa-wheat-awn"></i>
            </div>
        </div>
    </section>

    {{-- 2. Section Ekosistem --}}
    <section id="ekosistem" class="section-ekosistem">
        <div style="text-align:center; max-width:640px; margin:0 auto 40px;">
            <div style="font-size:10px; font-weight:900; color:#049874; text-transform:uppercase; letter-spacing:1.6px; margin-bottom:6px;">EKOSISTEM TANILINK</div>
            <h2 style="font-size:clamp(22px, 5.5vw, 30px); font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 8px;">Koneksi Adil untuk Petani &amp; Konsumen</h2>
            <p style="font-size:13px; color:#80908D; margin:0; line-height:1.6;">Menghapus ketidakpastian fluktuasi harga panen dan mata rantai perantara yang merugikan kedua belah pihak.</p>
        </div>

        <div class="grid-ekosistem">
            {{-- Card Petani --}}
            <div style="background:#fff; border:1px solid #E5ECEA; border-radius:20px; padding:28px 24px; box-shadow:0 4px 20px rgba(31,61,45,0.04);">
                <div style="width:48px; height:48px; border-radius:13px; background:#E9FBF3; color:#0C8D65; display:grid; place-items:center; font-size:20px; margin-bottom:16px;">
                    <i class="fa-solid fa-person-digging"></i>
                </div>
                <h3 style="font-size:19px; font-weight:850; color:#172B29; margin:0 0 10px;">Untuk Petani (Produsen)</h3>
                <p style="font-size:13px; color:#50695E; line-height:1.75; margin:0 0 20px;">
                    Dapatkan kepastian pembeli dan patokan harga tetap sebelum menabur benih. Petani langsung menerima DP 30% di muka sebagai modal operasional dan pupuk tanpa jeratan hutang tengkulak.
                </p>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <div style="display:flex; align-items:flex-start; gap:10px; font-size:12.5px; color:#07876B; font-weight:700;">
                        <span style="width:20px; height:20px; border-radius:50%; background:#DCF8EB; color:#007B55; display:grid; place-items:center; font-size:11px; flex-shrink:0; margin-top:2px;">✓</span>
                        <span>Kepastian kontrak penjualan hasil panen dengan harga tetap</span>
                    </div>
                    <div style="display:flex; align-items:flex-start; gap:10px; font-size:12.5px; color:#07876B; font-weight:700;">
                        <span style="width:20px; height:20px; border-radius:50%; background:#DCF8EB; color:#007B55; display:grid; place-items:center; font-size:11px; flex-shrink:0; margin-top:2px;">✓</span>
                        <span>DP 30% modal tanam langsung cair sebelum masa tanam</span>
                    </div>
                    <div style="display:flex; align-items:flex-start; gap:10px; font-size:12.5px; color:#07876B; font-weight:700;">
                        <span style="width:20px; height:20px; border-radius:50%; background:#DCF8EB; color:#007B55; display:grid; place-items:center; font-size:11px; flex-shrink:0; margin-top:2px;">✓</span>
                        <span>Dokumentasi dan update foto progres berkala ke pembeli</span>
                    </div>
                </div>
            </div>

            {{-- Card Konsumen --}}
            <div style="background:#fff; border:1px solid #E5ECEA; border-radius:20px; padding:28px 24px; box-shadow:0 4px 20px rgba(31,61,45,0.04);">
                <div style="width:48px; height:48px; border-radius:13px; background:#E8F9FC; color:#0B8BAA; display:grid; place-items:center; font-size:20px; margin-bottom:16px;">
                    <i class="fa-solid fa-hotel"></i>
                </div>
                <h3 style="font-size:19px; font-weight:850; color:#172B29; margin:0 0 10px;">Untuk Konsumen (Industri &amp; Usaha)</h3>
                <p style="font-size:13px; color:#50695E; line-height:1.75; margin:0 0 20px;">
                    Jaminan suplai bahan segar berkualitas tinggi langsung dari kelompok tani terverifikasi. Anggaran biaya bahan pangan terukur kebal dari lonjakan harga pasar dadakan.
                </p>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <div style="display:flex; align-items:flex-start; gap:10px; font-size:12.5px; color:#087894; font-weight:700;">
                        <span style="width:20px; height:20px; border-radius:50%; background:#E0F7FA; color:#0B8BAA; display:grid; place-items:center; font-size:11px; flex-shrink:0; margin-top:2px;">✓</span>
                        <span>Jaminan kepastian suplai pasokan sesuai jadwal panen</span>
                    </div>
                    <div style="display:flex; align-items:flex-start; gap:10px; font-size:12.5px; color:#087894; font-weight:700;">
                        <span style="width:20px; height:20px; border-radius:50%; background:#E0F7FA; color:#0B8BAA; display:grid; place-items:center; font-size:11px; flex-shrink:0; margin-top:2px;">✓</span>
                        <span>Stabilitas harga pangan pasti dengan kontrak fixed-price</span>
                    </div>
                    <div style="display:flex; align-items:flex-start; gap:10px; font-size:12.5px; color:#087894; font-weight:700;">
                        <span style="width:20px; height:20px; border-radius:50%; background:#E0F7FA; color:#0B8BAA; display:grid; place-items:center; font-size:11px; flex-shrink:0; margin-top:2px;">✓</span>
                        <span>Monitoring pertumbuhan tanaman langsung dari kebun petani</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. Section Cara Kerja --}}
    <section id="keunggulan" class="section-cara-kerja">
        <div style="max-width:1200px; margin:0 auto;">
            <div style="text-align:center; max-width:640px; margin:0 auto 40px;">
                <div style="font-size:10px; font-weight:900; color:#05C793; text-transform:uppercase; letter-spacing:1.6px; margin-bottom:6px;">ALUR KERJA PLATFORM</div>
                <h2 style="font-size:clamp(22px, 5.5vw, 30px); font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 8px;">Cara Kerja TaniLink</h2>
                <p style="font-size:13px; color:#80908D; margin:0; line-height:1.6;">Empat langkah terstruktur menjamin transaksi pertanian yang transparan dan saling menguntungkan.</p>
            </div>

            <div class="grid-cara-kerja">
                <div style="background:#fff; border:1px solid #E5ECEA; border-radius:18px; padding:22px 20px;">
                    <div style="width:38px; height:38px; border-radius:10px; background:#E9FBF3; color:#0C8D65; display:grid; place-items:center; font-size:17px; font-weight:800; margin-bottom:14px;">
                        1
                    </div>
                    <h3 style="font-size:15px; font-weight:850; color:#172B29; margin:0 0 8px;">Penerbitan Rencana</h3>
                    <p style="font-size:12px; color:#50695E; line-height:1.7; margin:0;">
                        Petani mempublikasikan estimasi hasil panen, varietas benih, luas lahan, dan jadwal panen di platform.
                    </p>
                </div>

                <div style="background:#fff; border:1px solid #E5ECEA; border-radius:18px; padding:22px 20px;">
                    <div style="width:38px; height:38px; border-radius:10px; background:#E8F9FC; color:#0B8BAA; display:grid; place-items:center; font-size:17px; font-weight:800; margin-bottom:14px;">
                        2
                    </div>
                    <h3 style="font-size:15px; font-weight:850; color:#172B29; margin:0 0 8px;">Kontrak &amp; DP 30%</h3>
                    <p style="font-size:12px; color:#50695E; line-height:1.7; margin:0;">
                        Konsumen melakukan pre-order kuantitas. DP 30% diteruskan ke petani sebagai modal, 70% saldo diamankan di escrow.
                    </p>
                </div>

                <div style="background:#fff; border:1px solid #E5ECEA; border-radius:18px; padding:22px 20px;">
                    <div style="width:38px; height:38px; border-radius:10px; background:#FFF8EA; color:#BA7A1F; display:grid; place-items:center; font-size:17px; font-weight:800; margin-bottom:14px;">
                        3
                    </div>
                    <h3 style="font-size:15px; font-weight:850; color:#172B29; margin:0 0 8px;">Monitoring Progres</h3>
                    <p style="font-size:12px; color:#50695E; line-height:1.7; margin:0;">
                        Petani mengunggah catatan dan foto fase tanam berkala yang dapat dipantau langsung oleh konsumen secara real-time.
                    </p>
                </div>

                <div style="background:#fff; border:1px solid #E5ECEA; border-radius:18px; padding:22px 20px;">
                    <div style="width:38px; height:38px; border-radius:10px; background:#F1EFFB; color:#7257CA; display:grid; place-items:center; font-size:17px; font-weight:800; margin-bottom:14px;">
                        4
                    </div>
                    <h3 style="font-size:15px; font-weight:850; color:#172B29; margin:0 0 8px;">Panen &amp; Rilis Escrow</h3>
                    <p style="font-size:12px; color:#50695E; line-height:1.7; margin:0;">
                        Hasil panen diserahterimakan secara mandiri di lokasi kebun, dan saldo 70% escrow dicairkan tuntas ke petani.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. Section Escrow Bersama --}}
    <section id="escrow" class="section-escrow">
        <div style="text-align:center; max-width:620px; margin:0 auto 40px;">
            <div style="font-size:10px; font-weight:900; color:#7257CA; text-transform:uppercase; letter-spacing:1.6px; margin-bottom:6px;">KEAMANAN TRANSAKSI</div>
            <h2 style="font-size:clamp(22px, 5.5vw, 30px); font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 8px;">Skema Escrow Bersama TaniLink</h2>
            <p style="font-size:13px; color:#80908D; margin:0; line-height:1.6;">Transaksi terlindungi dengan pemisahan modal kerja petani dan dana pelunasan aman.</p>
        </div>

        <div class="grid-escrow">
            <div style="background:#fff; border:1px solid #E5ECEA; border-radius:18px; padding:24px 22px; box-shadow:0 4px 18px rgba(0,0,0,0.03);">
                <div style="width:42px; height:42px; border-radius:12px; background:#E9FBF3; color:#0C8D65; display:grid; place-items:center; font-size:18px; font-weight:850; margin-bottom:14px;">
                    1
                </div>
                <h3 style="font-size:16px; font-weight:850; color:#172B29; margin:0 0 8px;">30% DP Modal Tanam</h3>
                <p style="font-size:12px; color:#50695E; line-height:1.7; margin:0;">
                    Saat pre-order disepakati, 30% dari total nilai transaksi langsung disalurkan ke rekening petani mitra sebagai modal bibit dan perawatan lahan.
                </p>
            </div>

            <div style="background:#fff; border:1px solid #E5ECEA; border-radius:18px; padding:24px 22px; box-shadow:0 4px 18px rgba(0,0,0,0.03);">
                <div style="width:42px; height:42px; border-radius:12px; background:#F1EFFB; color:#7257CA; display:grid; place-items:center; font-size:18px; font-weight:850; margin-bottom:14px;">
                    2
                </div>
                <h3 style="font-size:16px; font-weight:850; color:#172B29; margin:0 0 8px;">70% Escrow Terkunci Aman</h3>
                <p style="font-size:12px; color:#50695E; line-height:1.7; margin:0;">
                    Sisa 70% saldo diamankan di rekening escrow bersama TaniLink selama masa tanam, memberi kepastian komitmen bagi kedua belah pihak.
                </p>
            </div>

            <div style="background:#fff; border:1px solid #E5ECEA; border-radius:18px; padding:24px 22px; box-shadow:0 4px 18px rgba(0,0,0,0.03);">
                <div style="width:42px; height:42px; border-radius:12px; background:#E8F9FC; color:#0B8BAA; display:grid; place-items:center; font-size:18px; font-weight:850; margin-bottom:14px;">
                    3
                </div>
                <h3 style="font-size:16px; font-weight:850; color:#172B29; margin:0 0 8px;">Verifikasi &amp; Rilis Saldo</h3>
                <p style="font-size:12px; color:#50695E; line-height:1.7; margin:0;">
                    Setelah masa panen tiba dan hasil diterima di titik serah terima, saldo 70% dirilis ke petani secara transparan dan tuntas.
                </p>
            </div>
        </div>
    </section>

    {{-- 5. Section Showcase Komoditas Pilihan --}}
    <section id="katalog" class="section-katalog">
        <div style="max-width:1200px; margin:0 auto;">
            <div class="katalog-header">
                <div>
                    <div style="font-size:10px; font-weight:900; color:#049874; text-transform:uppercase; letter-spacing:1.6px; margin-bottom:6px;">KATALOG TERSEDIA</div>
                    <h2 style="font-size:clamp(22px, 5.5vw, 28px); font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 6px;">Komoditas Siap Pre-Order</h2>
                    <p style="font-size:13px; color:#80908D; margin:0; line-height:1.6;">Pesan langsung sebelum jadwal panen untuk mengunci pasokan dengan harga terbaik.</p>
                </div>
                <a href="{{ route('login') }}" style="display:inline-flex; align-items:center; gap:6px; padding:10px 18px; border-radius:10px; background:#EBF9F2; color:#078362; font-size:12px; font-weight:800; text-decoration:none;">
                    Lihat Semua Komoditas
                </a>
            </div>

            <div class="grid-katalog">
                @php
                    $showListings = isset($listings) && $listings->isNotEmpty() ? $listings : collect();
                @endphp

                @forelse($showListings as $item)
                <div style="border:1px solid #E5ECEA; border-radius:18px; background:#fff; overflow:hidden; display:flex; flex-direction:column; justify-content:space-between; transition:transform 0.2s, box-shadow 0.2s; box-shadow:0 3px 12px rgba(0,0,0,0.03);"
                     onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 24px rgba(0,0,0,0.08)';"
                     onmouseout="this.style.transform='none'; this.style.boxShadow='0 3px 12px rgba(0,0,0,0.03)';">
                    
                    <div style="height:150px; display:grid; place-items:center; background:linear-gradient(145deg, #F0FDF4, #E2F8EE); position:relative; overflow:hidden;">
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
                <div style="grid-column:1/-1; text-align:center; padding:50px 20px; background:#fff; border-radius:16px; border:1px solid #E5ECEA;">
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
    <footer style="background:#ffffff; padding:40px 20px; text-align:center; border-top:1px solid #E5ECEA;">
        <div style="max-width:1200px; margin:0 auto;">
            <div style="display:flex; justify-content:center; align-items:center; margin-bottom:12px;">
                <x-brand-logo variant="full" height="34px" alt="TaniLink" />
            </div>
            <p style="font-size:11.5px; color:#8A9D95; margin:0 0 10px;">
                Platform Agrikultur Terencana &bull; Kepastian Suplai Panen &amp; Rekening Bersama Escrow
            </p>
            <div style="font-size:10px; color:#A2AEAA;">
                &copy; {{ date('Y') }} TaniLink Indonesia. Hak cipta dilindungi undang-undang.
            </div>
        </div>
    </footer>

</body>
</html>
