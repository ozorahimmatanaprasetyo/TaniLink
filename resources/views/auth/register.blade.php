<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru - TaniLink</title>
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
        .register-left {
            width: 50%;
            background: linear-gradient(140deg, #064737, #08a881);
            position: relative;
            display: flex;
            flex-direction: column;
            padding: 48px 56px;
            overflow: hidden;
        }
        .register-left-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .register-left-mark {
            width: 38px;
            height: 38px;
            border-radius: 11px;
            background: rgba(255,255,255,0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
        }
        .register-left-brand-text {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.8px;
            color: #ffffff;
        }
        .register-left-brand-text span { color: #A7F3D0; }
        .register-left-heading {
            margin-top: 80px;
            font-size: 44px;
            font-weight: 900;
            letter-spacing: -1.6px;
            color: #ffffff;
            line-height: 1.15;
        }
        .register-left-para {
            margin-top: 20px;
            color: #C9F5E4;
            font-size: 15px;
            line-height: 1.8;
            max-width: 440px;
        }
        .register-left-features {
            margin-top: 36px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .register-left-feat-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #ffffff;
            font-size: 13.5px;
            font-weight: 700;
        }
        .register-left-feat-icon {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: rgba(255,255,255,0.15);
            display: grid;
            place-items: center;
            font-size: 13px;
            color: #A7F3D0;
        }
        .register-left-deco {
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
        .register-right {
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 60px;
            background: #ffffff;
            overflow-y: auto;
        }
        .register-right-inner {
            width: 100%;
            max-width: 420px;
        }
        .register-heading {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.8px;
            color: #172B29;
            margin-bottom: 6px;
        }
        .register-subheading {
            font-size: 14px;
            color: #8A9D96;
            font-weight: 500;
            margin-bottom: 24px;
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
        .register-btn {
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
            margin-top: 10px;
        }
        .register-btn:hover { background: #0f2b24; }

        /* Alert */
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
    </style>
</head>
<body>

    {{-- ── LEFT PANEL ── --}}
    <div class="register-left">
        {{-- Brand --}}
        <div class="register-left-brand">
            <div class="register-left-mark">🌿</div>
            <div class="register-left-brand-text">Tani<span>Link</span></div>
        </div>

        {{-- Headline --}}
        <h1 class="register-left-heading">
            Mulai Perjalanan<br>Agrikultur Anda.
        </h1>
        <p class="register-left-para">
            Bergabunglah bersama petani produsen dan konsumen usaha dalam ekosistem pemesanan pertanian dengan kepastian harga dan pembayaran terjamin.
        </p>

        {{-- Features list --}}
        <div class="register-left-features">
            <div class="register-left-feat-item">
                <div class="register-left-feat-icon"><i class="fa-solid fa-handshake"></i></div>
                <span>Kontrak Langsung Produsen &amp; Pembeli</span>
            </div>
            <div class="register-left-feat-item">
                <div class="register-left-feat-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <span>Rekening Bersama Terjamin</span>
            </div>
            <div class="register-left-feat-item">
                <div class="register-left-feat-icon"><i class="fa-solid fa-chart-line"></i></div>
                <span>Stabilitas Harga Sebelum Musim Tanam</span>
            </div>
        </div>

        {{-- Decoration --}}
        <div class="register-left-deco" aria-hidden="true">🌿</div>
    </div>

    {{-- ── RIGHT PANEL ── --}}
    <div class="register-right">
        <div class="register-right-inner">

            <h2 class="register-heading">Buat Akun TaniLink</h2>
            <p class="register-subheading">Lengkapi formulir pendaftaran di bawah ini.</p>

            {{-- Error alert --}}
            @if($errors->any())
            <div class="alert-error">
                <i class="fa-solid fa-circle-exclamation" style="margin-top:1px; color:#DC2626;"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Form registrasi simetris --}}
            <form action="{{ route('register.post') }}" method="POST" x-data="{ showPass: false, showConfirmPass: false }">
                @csrf

                {{-- Nama Lengkap --}}
                <div style="margin-bottom:14px;">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" id="name"
                           value="{{ old('name') }}"
                           required autofocus
                           class="form-input"
                           placeholder="Contoh: Budi Santoso">
                </div>

                {{-- Email --}}
                <div style="margin-bottom:14px;">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email') }}"
                           required
                           class="form-input"
                           placeholder="nama@email.com">
                </div>

                {{-- Nomor WhatsApp / HP --}}
                <div style="margin-bottom:14px;">
                    <label for="phone" class="form-label">Nomor WhatsApp / HP Aktif</label>
                    <input type="text" name="phone" id="phone"
                           value="{{ old('phone') }}"
                           required
                           class="form-input"
                           placeholder="0812xxxxxxxx">
                    <span style="font-size:10.5px; color:#8A9D95; display:block; margin-top:3px;">Digunakan untuk verifikasi akun dan koordinasi transaksi.</span>
                </div>

                {{-- Password --}}
                <div style="margin-bottom:14px;">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <div style="position:relative;">
                        <input :type="showPass ? 'text' : 'password'" name="password" id="password"
                               required minlength="8"
                               class="form-input"
                               style="padding-right:42px;"
                               placeholder="Minimal 8 karakter">
                        <button type="button" @click="showPass = !showPass"
                                tabindex="-1"
                                style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:#8A9D96; cursor:pointer; font-size:14px; padding:4px;"
                                title="Lihat kata sandi">
                            <i :class="showPass ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"></i>
                        </button>
                    </div>
                </div>

                {{-- Konfirmasi Kata Sandi --}}
                <div style="margin-bottom:20px;">
                    <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                    <div style="position:relative;">
                        <input :type="showConfirmPass ? 'text' : 'password'" name="password_confirmation" id="password_confirmation"
                               required minlength="8"
                               class="form-input"
                               style="padding-right:42px;"
                               placeholder="Ulangi kata sandi">
                        <button type="button" @click="showConfirmPass = !showConfirmPass"
                                tabindex="-1"
                                style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:#8A9D96; cursor:pointer; font-size:14px; padding:4px;"
                                title="Lihat konfirmasi kata sandi">
                            <i :class="showConfirmPass ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"></i>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="register-btn">
                    Daftar Akun Sekarang <i class="fa-solid fa-user-plus"></i>
                </button>
            </form>

            <div style="margin-top:24px; text-align:center; font-size:13.5px; color:#71837C;">
                Sudah punya akun?
                <a href="{{ route('login') }}" style="font-weight:700; color:#172B29; text-decoration:none;">Masuk di sini</a>
            </div>

        </div>
    </div>

</body>
</html>