<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Password - TaniLink</title>
    <link rel="icon" type="image/png" href="{{ asset('images/tanilink-icon.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;850&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #F4F7F6;
            color: #172B29;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }
        .card-container {
            width: 100%;
            max-width: 440px;
            background: #FFFFFF;
            border: 1px solid #E5ECEA;
            border-radius: 20px;
            padding: 36px 32px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        }
        .form-label {
            display: block;
            font-size: 11.5px;
            font-weight: 800;
            color: #365448;
            margin-bottom: 7px;
            letter-spacing: 0.1px;
        }
        .form-input {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #E5ECEA;
            border-radius: 11px;
            font-size: 13px;
            color: #172B29;
            font-family: inherit;
            outline: none;
            transition: all 0.15s ease;
            background: #FAFCFB;
        }
        .form-input:focus {
            border-color: #05C793;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(5, 199, 147, 0.12);
        }
        .btn-submit {
            width: 100%;
            padding: 12px;
            background: #05C793;
            color: #053B2D;
            border: none;
            border-radius: 11px;
            font-size: 13px;
            font-weight: 850;
            font-family: inherit;
            cursor: pointer;
            box-shadow: 0 3px 12px rgba(5, 199, 147, 0.3);
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-submit:hover {
            background: #04b384;
            transform: translateY(-1px);
        }
        .alert-error {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 12px;
            color: #991B1B;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            line-height: 1.5;
        }
    </style>
</head>
<body>

    <div class="card-container" x-data="{ showPass1: false, showPass2: false }">
        {{-- Logo Brand --}}
        <div style="text-align:center; margin-bottom:24px;">
            <a href="/" style="display:inline-block; text-decoration:none;">
                <x-brand-logo variant="full" height="40px" alt="TaniLink" />
            </a>
        </div>

        <h2 style="font-size:20px; font-weight:850; letter-spacing:-0.5px; color:#172B29; margin-bottom:6px; text-align:center;">
            Atur Ulang Password
        </h2>
        <p style="font-size:12px; color:#6B8077; line-height:1.5; margin-bottom:22px; text-align:center;">
            Buat password baru minimal 8 karakter untuk akun TaniLink Anda.
        </p>

        {{-- Error Alert --}}
        @if($errors->any())
        <div class="alert-error">
            <i class="fa-solid fa-circle-exclamation" style="margin-top:2px; color:#DC2626;"></i>
            <div>{{ $errors->first() }}</div>
        </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div style="margin-bottom:16px;">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" name="email" id="email"
                       value="{{ $email ?? old('email') }}"
                       required autofocus
                       class="form-input"
                       placeholder="nama@email.com">
            </div>

            <div style="margin-bottom:16px;">
                <label for="password" class="form-label">Password Baru (min. 8 karakter)</label>
                <div style="position:relative;">
                    <input :type="showPass1 ? 'text' : 'password'" name="password" id="password"
                           required
                           class="form-input"
                           style="padding-right:42px;"
                           placeholder="••••••••">
                    <button type="button" @click="showPass1 = !showPass1"
                            tabindex="-1"
                            style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:#8A9D96; cursor:pointer; font-size:14px; padding:4px;">
                        <i :class="showPass1 ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"></i>
                    </button>
                </div>
            </div>

            <div style="margin-bottom:22px;">
                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                <div style="position:relative;">
                    <input :type="showPass2 ? 'text' : 'password'" name="password_confirmation" id="password_confirmation"
                           required
                           class="form-input"
                           style="padding-right:42px;"
                           placeholder="••••••••">
                    <button type="button" @click="showPass2 = !showPass2"
                            tabindex="-1"
                            style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:#8A9D96; cursor:pointer; font-size:14px; padding:4px;">
                        <i :class="showPass2 ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                Simpan Password Baru <i class="fa-solid fa-check"></i>
            </button>
        </form>

        <div style="text-align:center; margin-top:22px;">
            <a href="{{ route('login') }}" style="font-size:12.5px; color:#50695E; font-weight:700; text-decoration:none;">
                Kembali ke Halaman Masuk
            </a>
        </div>
    </div>

</body>
</html>

