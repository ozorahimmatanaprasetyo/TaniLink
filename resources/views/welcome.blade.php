<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaniLink — Koneksi Panen & Pasar B2B</title>
    
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
        * { box-sizing: border-box; }
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

    {{-- Top Navigation --}}
    <header style="border-bottom:1px solid #E5ECEA; background:#ffffff; position:sticky; top:0; z-index:40;">
        <div style="max-width:1200px; margin:0 auto; padding:20px 30px; display:flex; align-items:center; justify-content:space-between;">
            {{-- Brand --}}
            <a href="/" style="display:flex; align-items:center; gap:10px;">
                <div style="width:34px; height:34px; border-radius:11px; background:linear-gradient(135deg, #05C793, #07876B); display:grid; place-items:center; font-size:16px; color:#fff;">
                    🌿
                </div>
                <strong style="font-size:22px; font-weight:850; letter-spacing:-1.2px; color:#172B29;">
                    Tani<span style="color:#05C793;">Link</span>
                </strong>
            </a>

            {{-- Nav links --}}
            <nav style="display:flex; gap:32px; font-size:13px; font-weight:750; color:#587065;">
                <a href="#fitur" style="transition:color 0.15s;" onmouseover="this.style.color='#05C793'" onmouseout="this.style.color='#587065'">Keunggulan</a>
                <a href="#peran" style="transition:color 0.15s;" onmouseover="this.style.color='#05C793'" onmouseout="this.style.color='#587065'">Ekosistem</a>
                <a href="#escrow" style="transition:color 0.15s;" onmouseover="this.style.color='#05C793'" onmouseout="this.style.color='#587065'">Escrow Bersama</a>
            </nav>

            {{-- Auth Buttons --}}
            <div style="display:flex; align-items:center; gap:12px;">
                <a href="{{ route('login') }}" style="padding:10px 18px; border-radius:11px; font-size:12px; font-weight:800; color:#163D34; border:1px solid #DFE8E5; background:#fff; transition:all 0.15s;">
                    Masuk
                </a>
                <a href="{{ route('register') }}" style="padding:10px 18px; border-radius:11px; font-size:12px; font-weight:800; color:#053B2D; background:#05C793; border:none; transition:all 0.15s; box-shadow:0 3px 10px rgba(5,199,147,0.25);">
                    Daftar Sekarang ↗
                </a>
            </div>
        </div>
    </header>

    {{-- Hero Section --}}
    <section style="background:linear-gradient(110deg, #073F32 0%, #0D9976 100%); color:white; min-height:500px; padding:80px 30px; position:relative; overflow:hidden;">
        <div style="max-width:1200px; margin:0 auto; display:flex; justify-content:space-between; align-items:center; gap:40px;">
            <div style="max-width:640px; z-index:2;">
                <span style="display:inline-block; padding:6px 12px; border-radius:8px; background:rgba(255,255,255,0.15); backdrop-filter:blur(6px); font-size:11px; font-weight:850; letter-spacing:1.5px; text-transform:uppercase; margin-bottom:18px;">
                    PLATFORM PRE-ORDER PERTANIAN B2B
                </span>
                <h1 style="font-size:48px; letter-spacing:-1.8px; line-height:1.15; font-weight:850; margin:0 0 20px;">
                    Kepastian Harga & Pasokan Sebelum Musim Tanam.
                </h1>
                <p style="font-size:15px; color:#C2E8D8; line-height:1.8; margin:0 0 28px;">
                    Hubungkan kelompok tani produsen langsung dengan industri hotel, restoran, dan katering melalui kontrak harga pasti dan sistem escrow bersama yang aman.
                </p>
                <div style="display:flex; gap:14px; flex-wrap:wrap;">
                    <a href="{{ route('register') }}" style="padding:14px 26px; border-radius:12px; background:#05C793; color:#053B2D; font-size:13px; font-weight:850; display:inline-flex; align-items:center; gap:8px; box-shadow:0 4px 16px rgba(5,199,147,0.35);">
                        Mulai Kemitraan TaniLink ↗
                    </a>
                    <a href="{{ route('login') }}" style="padding:14px 26px; border-radius:12px; background:rgba(255,255,255,0.12); color:#fff; border:1px solid rgba(255,255,255,0.25); font-size:13px; font-weight:800; backdrop-filter:blur(8px);">
                        Login Simulasi Portal
                    </a>
                </div>
            </div>

            <div style="font-size:180px; filter:drop-shadow(0 25px 20px rgba(1,45,28,0.5)); z-index:2; user-select:none;">
                🌾
            </div>
        </div>
    </section>

    {{-- 3 Feature Cards --}}
    <section id="fitur" style="max-width:1200px; margin:0 auto; padding:60px 30px;">
        <div style="text-align:center; max-width:600px; margin:0 auto 40px;">
            <div style="font-size:10px; font-weight:900; color:#049874; text-transform:uppercase; letter-spacing:1.6px; margin-bottom:6px;">TRANSFORMASI TATA NIAGA</div>
            <h2 style="font-size:28px; font-weight:850; letter-spacing:-1px; color:#172B29; margin:0 0 8px;">Dibuat untuk Menjawab Masalah Nyata</h2>
            <p style="font-size:13px; color:#80908D; margin:0;">Menghapus ketidakpastian fluktuasi harga panen dan rantai tengkulak yang merugikan kedua belah pihak.</p>
        </div>

        <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:24px;">
            {{-- Card 1 --}}
            <div style="background:#fff; border:1px solid #E5ECEA; border-radius:18px; padding:28px; box-shadow:0 4px 16px rgba(31,61,45,0.03);">
                <div style="width:48px; height:48px; border-radius:14px; background:#E9FBF3; color:#0C8D65; display:grid; place-items:center; font-size:24px; margin-bottom:18px;">
                    🌱
                </div>
                <h3 style="font-size:17px; font-weight:850; color:#172B29; margin:0 0 8px;">Untuk Gapoktan (Petani)</h3>
                <p style="font-size:12px; color:#50695E; line-height:1.7; margin:0;">
                    Kepastian harga jual dan pembeli sebelum bibit ditanam. Menerima DP 30% sebagai modal benih dan pupuk tanpa harus meminjam ke tengkulak.
                </p>
            </div>

            {{-- Card 2 --}}
            <div style="background:#fff; border:1px solid #E5ECEA; border-radius:18px; padding:28px; box-shadow:0 4px 16px rgba(31,61,45,0.03);">
                <div style="width:48px; height:48px; border-radius:14px; background:#E8F9FC; color:#0B8BAA; display:grid; place-items:center; font-size:24px; margin-bottom:18px;">
                    🏨
                </div>
                <h3 style="font-size:17px; font-weight:850; color:#172B29; margin:0 0 8px;">Untuk Industri Horeka</h3>
                <p style="font-size:12px; color:#50695E; line-height:1.7; margin:0;">
                    Jaminan suplai bahan segar berkualitas tinggi dengan harga kontrak tetap yang kebal terhadap lonjakan harga pasar dadakan.
                </p>
            </div>

            {{-- Card 3 --}}
            <div id="escrow" style="background:#fff; border:1px solid #E5ECEA; border-radius:18px; padding:28px; box-shadow:0 4px 16px rgba(31,61,45,0.03);">
                <div style="width:48px; height:48px; border-radius:14px; background:#F1EFFB; color:#6953B5; display:grid; place-items:center; font-size:24px; margin-bottom:18px;">
                    🔒
                </div>
                <h3 style="font-size:17px; font-weight:850; color:#172B29; margin:0 0 8px;">Rekening Bersama Escrow</h3>
                <p style="font-size:12px; color:#50695E; line-height:1.7; margin:0;">
                    70% pelunasan diamankan di escrow TaniLink dan hanya dirilis setelah komoditas fisik sampai di lokasi dan lolos verifikasi timbangan Horeka.
                </p>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer style="border-top:1px solid #E5ECEA; background:#F8FCFA; padding:40px 30px; text-align:center;">
        <div style="max-width:1200px; margin:0 auto;">
            <div style="display:flex; justify-content:center; align-items:center; gap:8px; margin-bottom:12px;">
                <div style="width:26px; height:26px; border-radius:8px; background:linear-gradient(135deg, #05C793, #07876B); display:grid; place-items:center; font-size:12px; color:#fff;">
                    🌿
                </div>
                <strong style="font-size:18px; font-weight:850; color:#172B29;">Tani<span style="color:#05C793;">Link</span></strong>
            </div>
            <p style="font-size:11px; color:#8A9D95; margin:0 0 10px;">
                Ekosistem Agrikultur Terencana &bull; Kepastian Suplai Panen & Rekening Bersama Escrow
            </p>
            <div style="font-size:10px; color:#A2AEAA;">
                &copy; {{ date('Y') }} TaniLink Indonesia. Hak cipta dilindungi undang-undang.
            </div>
        </div>
    </footer>

</body>
</html>
