<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - TaniLink</title>
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
            display: flex;
        }

        /* ── Left panel ── */
        .login-left {
            width: 50%;
            background: linear-gradient(140deg, #064737, #08a881);
            position: relative;
            display: flex;
            flex-direction: column;
            padding: 48px 56px;
            overflow: hidden;
        }
        .login-left-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .login-left-mark {
            width: 38px;
            height: 38px;
            border-radius: 11px;
            background: rgba(255,255,255,0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
        }
        .login-left-brand-text {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.8px;
            color: #ffffff;
        }
        .login-left-brand-text span { color: #A7F3D0; }
        .login-left-heading {
            margin-top: 100px;
            font-size: 46px;
            font-weight: 900;
            letter-spacing: -1.8px;
            color: #ffffff;
            line-height: 1.1;
        }
        .login-left-para {
            margin-top: 22px;
            color: #C9F5E4;
            font-size: 15px;
            line-height: 1.8;
            max-width: 400px;
        }
        .login-left-deco {
            position: absolute;
            bottom: -30px;
            left: -20px;
            font-size: 255px;
            opacity: 0.65;
            pointer-events: none;
            user-select: none;
            line-height: 1;
        }

        /* ── Right panel ── */
        .login-right {
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 70px 60px;
            background: #ffffff;
            overflow-y: auto;
        }
        .login-right-inner {
            width: 100%;
            max-width: 390px;
        }
        .login-heading {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.8px;
            color: #172B29;
            margin-bottom: 8px;
        }
        .login-subheading {
            font-size: 14px;
            color: #8A9D96;
            font-weight: 500;
            margin-bottom: 30px;
        }

        /* Form fields */
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #172B29;
            margin-bottom: 7px;
        }
        .form-input {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid #DFEAE5;
            border-radius: 10px;
            font-size: 14px;
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
            padding: 13px;
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
            margin-top: 4px;
        }
        .login-btn:hover { background: #0f2b24; }

        /* Alert */
        .alert-success {
            background: #EDFFF7;
            border: 1px solid #A7F3D0;
            color: #065F46;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 18px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .alert-error {
            background: #FFF1F0;
            border: 1px solid #FECACA;
            color: #9B1C1C;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 18px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        /* Demo panel */
        .demo-panel {
            margin-top: 28px;
            padding: 16px;
            background: #F9FBFA;
            border: 1.5px dashed #D0E5DC;
            border-radius: 12px;
        }
        .demo-panel-title {
            font-size: 11px;
            font-weight: 800;
            color: #172B29;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .demo-credentials {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .demo-item {
            background: #fff;
            border: 1px solid #E5ECEA;
            border-radius: 8px;
            padding: 8px 10px;
        }
        .demo-item-role {
            font-size: 10.5px;
            font-weight: 800;
            color: #172B29;
            margin-bottom: 2px;
        }
        .demo-item-email {
            font-size: 10.5px;
            color: #71837C;
            font-family: monospace;
            word-break: break-all;
        }
        .demo-item-full {
            grid-column: 1 / -1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .demo-password {
            font-size: 10.5px;
            font-weight: 700;
            color: #172B29;
        }
        .demo-password-val {
            font-family: monospace;
            font-size: 10.5px;
            color: #71837C;
        }
    </style>
</head>
<body>

    {{-- ── LEFT PANEL ── --}}
    <div class="login-left">
        {{-- Brand --}}
        <div class="login-left-brand">
            <div class="login-left-mark">🌿</div>
            <div class="login-left-brand-text">Tani<span>Link</span></div>
        </div>

        {{-- Headline --}}
        <h1 class="login-left-heading">
            Hubungkan<br>Panen &amp; Peluang.
        </h1>
        <p class="login-left-para">
            TaniLink adalah platform pre-order agrikultur yang mempertemukan petani dan konsumen secara langsung — transparan, efisien, dan tanpa perantara.
        </p>

        {{-- Decoration --}}
        <div class="login-left-deco" aria-hidden="true">🌿</div>
    </div>

    {{-- ── RIGHT PANEL ── --}}
    <div class="login-right">
        <div class="login-right-inner">

            <h2 class="login-heading">Masuk ke TaniLink</h2>
            <p class="login-subheading">Masukkan kredensial Anda untuk melanjutkan.</p>

            {{-- Success alert --}}
            @if(session('success'))
            <div class="alert-success">
                <i class="fa-solid fa-circle-check" style="margin-top:1px; color:#059669;"></i>
                <div>{{ session('success') }}</div>
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
                <div style="margin-bottom:16px;">
                    <label for="login_input" class="form-label">Alamat Email atau Nomor HP</label>
                    <input type="text" name="login" id="login_input"
                           value="{{ old('login', old('email')) }}"
                           required autofocus
                           class="form-input"
                           placeholder="nama@email.com atau 08123456789">
                </div>

                {{-- Password with Eye Toggle --}}
                <div style="margin-bottom:20px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:7px;">
                        <label for="password" class="form-label" style="margin-bottom:0;">Password</label>
                        <a href="#" style="font-size:12px; font-weight:700; color:#05C793; text-decoration:none;">Lupa Password?</a>
                    </div>
                    <div style="position:relative;">
                        <input :type="showPass ? 'text' : 'password'" name="password" id="password"
                               required
                               class="form-input"
                               style="padding-right:42px;"
                               placeholder="••••••••">
                        <button type="button" @click="showPass = !showPass"
                                tabindex="-1"
                                style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:#8A9D96; cursor:pointer; font-size:14px; padding:4px;"
                                title="Lihat password">
                            <i :class="showPass ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"></i>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="login-btn">
                    Masuk <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </button>
            </form>

            {{-- Register link --}}
            <div style="text-align:center; margin-top:20px;">
                <p style="font-size:13px; color:#8A9D96; font-weight:500;">
                    Belum punya akun?
                    <a href="{{ route('register') }}" style="color:#05C793; font-weight:700; text-decoration:none;">Daftar sekarang</a>
                </p>
            </div>

            {{-- Demo credentials panel --}}
            <div class="demo-panel">
                <div class="demo-panel-title">
                    <i class="fa-solid fa-flask" style="color:#05C793;"></i>
                    Kredensial Demo
                </div>
                <div class="demo-credentials">
                    <div class="demo-item">
                        <div class="demo-item-role">Petani</div>
                        <div class="demo-item-email">raden@tanilink.id</div>
                    </div>
                    <div class="demo-item">
                        <div class="demo-item-role">Konsumen</div>
                        <div class="demo-item-email">maya@grandhotel.com</div>
                    </div>
                    <div class="demo-item">
                        <div class="demo-item-role">Admin</div>
                        <div class="demo-item-email">admin@tanilink.id</div>
                    </div>
                    <div class="demo-item demo-item-full">
                        <div>
                            <div class="demo-password">Password semua akun</div>
                            <div class="demo-password-val">password123</div>
                        </div>
                        <i class="fa-solid fa-key" style="color:#C8D8D3;"></i>
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
