<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - TaniLink</title>
    <link rel="icon" type="image/png" href="{{ asset('images/tanilink-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            background-color: #ffffff;
        }

        /* ── Left panel ── */
        .login-left {
            width: 50%;
            min-height: 100vh;
            min-height: 100dvh;
            background-color: #073F32;
            background-image: 
                linear-gradient(135deg, rgba(4, 97, 74, 0.92) 0%, rgba(6, 62, 50, 0.88) 50%, rgba(7, 48, 38, 0.94) 100%),
                url("{{ asset('images/landing/hero-farm.jpg') }}");
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 56px;
            overflow: hidden;
        }
        .login-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 20% 30%, rgba(22, 202, 157, 0.22) 0%, transparent 60%);
            pointer-events: none;
        }
        .login-left-inner {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 2;
        }
        .login-left-brand {
            margin-bottom: 28px;
        }
        .login-left-heading {
            font-size: 38px;
            font-weight: 900;
            letter-spacing: -1.4px;
            color: #ffffff;
            line-height: 1.15;
            margin-bottom: 16px;
        }
        .login-left-para {
            color: #C9F5E4;
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 28px;
        }
        .login-left-features {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .login-left-feat-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
        }
        .login-left-feat-icon {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: rgba(255,255,255,0.18);
            display: grid;
            place-items: center;
            font-size: 12px;
            color: #A7F3D0;
            flex-shrink: 0;
        }

        /* ── Right panel ── */
        .login-right {
            width: 50%;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 56px;
            background: #ffffff;
            overflow-y: auto;
        }
        .login-right-inner {
            width: 100%;
            max-width: 400px;
        }
        .login-heading {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.6px;
            color: #172B29;
            margin-bottom: 6px;
        }
        .login-subheading {
            font-size: 13.5px;
            color: #8A9D96;
            font-weight: 500;
            margin-bottom: 24px;
        }

        /* Form fields */
        .form-label {
            display: block;
            font-size: 12.5px;
            font-weight: 700;
            color: #172B29;
            margin-bottom: 6px;
        }
        .form-input {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #DFEAE5;
            border-radius: 10px;
            font-size: 13.5px;
            font-family: inherit;
            color: #172B29;
            background: #fff;
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
        }
        .form-input::placeholder { color: #A2AEAA; font-weight: 400; }
        .form-input:focus {
            border-color: #05C793;
            box-shadow: 0 0 0 3px #05C79320;
        }

        /* Submit button */
        .login-btn {
            width: 100%;
            padding: 12px;
            background: #163D34;
            color: #ffffff;
            border: none;
            border-radius: 11px;
            font-size: 14px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.18s;
            margin-top: 6px;
        }
        .login-btn:hover { background: #0f2b24; }

        /* Alert */
        .alert-success {
            background: #EDFFF7;
            border: 1px solid #A7F3D0;
            color: #065F46;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 12.5px;
            margin-bottom: 16px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .alert-error {
            background: #FFF1F0;
            border: 1px solid #FECACA;
            color: #9B1C1C;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 12.5px;
            margin-bottom: 16px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        /* ── Responsive ── */
        @media (max-width: 900px) {
            body { flex-direction: column; }
            .login-left { display: none; }
            .login-right { width: 100%; min-height: 100dvh; padding: 48px 24px; }
            .login-mobile-brand { display: flex !important; justify-content: center; margin-bottom: 24px; }
        }
        @media (max-width: 480px) {
            .login-right { padding: 36px 18px; }
        }
    </style>
</head>
<body>

    {{-- ── LEFT PANEL ── --}}
    <div class="login-left">
        <div class="login-left-inner">
            {{-- Brand: logo original langsung tanpa background box --}}
            <div class="login-left-brand">
                <x-brand-logo variant="full" height="62px" alt="TaniLink" />
            </div>

            {{-- Headline --}}
            <h1 class="login-left-heading">
                Hubungkan<br>Panen &amp; Peluang.
            </h1>
            <p class="login-left-para">
                TaniLink adalah platform pemesanan komoditas pertanian yang mempertemukan petani dan konsumen secara langsung — transparan, efisien, dan tanpa perantara.
            </p>

            {{-- Features list --}}
            <div class="login-left-features">
                <div class="login-left-feat-item">
                    <div class="login-left-feat-icon"><i class="fa-solid fa-handshake"></i></div>
                    <span>Kontrak Langsung Produsen &amp; Pembeli</span>
                </div>
                <div class="login-left-feat-item">
                    <div class="login-left-feat-icon"><i class="fa-solid fa-shield-halved"></i></div>
                    <span>Rekening Bersama Terjamin</span>
                </div>
                <div class="login-left-feat-item">
                    <div class="login-left-feat-icon"><i class="fa-solid fa-chart-line"></i></div>
                    <span>Stabilitas Harga Sebelum Musim Tanam</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── RIGHT PANEL ── --}}
    <div class="login-right">
        <div class="login-right-inner">

            {{-- Mobile Brand Logo --}}
            <div class="login-mobile-brand" style="display:none;">
                <x-brand-logo variant="full" height="40px" alt="TaniLink" />
            </div>

            <h2 class="login-heading">Masuk ke TaniLink</h2>
            <p class="login-subheading">Masukkan data Anda untuk melanjutkan.</p>

            {{-- Success alert --}}
            @if(session('success'))
            <div class="alert-success">
                <i class="fa-solid fa-circle-check" style="margin-top:1px; color:#059669;"></i>
                <div>{{ session('success') }}</div>
            </div>
            @endif

            {{-- Warning alert --}}
            @if(session('warning'))
            <div class="alert-warning" style="background:#FFFBEB; border:1px solid #FCD34D; color:#92400E; padding:12px 14px; border-radius:10px; font-size:12.5px; display:flex; gap:10px; margin-bottom:18px;">
                <i class="fa-solid fa-triangle-exclamation" style="margin-top:1px; color:#D97706;"></i>
                <div>{{ session('warning') }}</div>
            </div>
            @endif

            {{-- Error alert --}}
            @if($errors->any())
            <div class="alert-error">
                <i class="fa-solid fa-circle-exclamation" style="margin-top:1px; color:#DC2626;"></i>
                <div>{{ $errors->first() }}</div>
            </div>
            @endif

            {{-- Login form --}}
            <form action="{{ route('login.post') }}" method="POST" x-data="{ showPass: false }">
                @csrf

                {{-- Email / Phone --}}
                <div style="margin-bottom:14px;">
                    <label for="login_input" class="form-label">Alamat Email atau Nomor HP</label>
                    <input type="text" name="login" id="login_input"
                           value="{{ old('login', old('email')) }}"
                           required autofocus
                           class="form-input"
                           placeholder="Masukkan email atau nomor HP">
                </div>

                {{-- Password with Eye Toggle --}}
                <div style="margin-bottom:18px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                        <label for="password" class="form-label" style="margin-bottom:0;">Kata Sandi</label>
                        <a href="{{ route('password.request') }}"
                           style="font-size:12px; font-weight:600; color:#05C793; text-decoration:none;">
                            Lupa password?
                        </a>
                    </div>
                    <div style="position:relative;">
                        <input :type="showPass ? 'text' : 'password'"
                               name="password" id="password"
                               required
                               class="form-input"
                               style="padding-right:42px;"
                               placeholder="Masukkan kata sandi (minimal 8 karakter)">
                        <button type="button" @click="showPass = !showPass"
                                tabindex="-1"
                                style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:#8A9D96; cursor:pointer; font-size:14px; padding:4px;"
                                title="Lihat kata sandi">
                            <i :class="showPass ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"></i>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="login-btn">
                    Masuk ke Akun
                </button>
            </form>

            <div style="margin-top:20px; text-align:center; font-size:13px; color:#71837C;">
                Belum punya akun?
                <a href="{{ route('register') }}" style="font-weight:700; color:#05C793; text-decoration:none;">Daftar sekarang</a>
            </div>

        </div>
    </div>

</body>
</html>
