<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Peran - TaniLink</title>
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
            background: #F5F8F7;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }

        /* Card */
        .onboard-card {
            width: 100%;
            max-width: 560px;
        }

        /* Brand */
        .onboard-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 28px;
        }
        .onboard-brand-mark {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: linear-gradient(135deg, #05C793, #07876B);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        .onboard-brand-text {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -1px;
            color: #172B29;
        }
        .onboard-brand-text span { color: #05C793; }

        /* Heading */
        .onboard-heading {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.8px;
            color: #172B29;
            text-align: center;
            margin-bottom: 8px;
        }
        .onboard-sub {
            font-size: 14px;
            color: #8A9D96;
            text-align: center;
            margin-bottom: 32px;
            font-weight: 500;
        }

        /* Role grid */
        .role-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 20px;
        }

        /* Role card */
        .role-card {
            padding: 25px 20px;
            min-height: 170px;
            border: 1.5px solid #E5ECEA;
            border-radius: 15px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: #ffffff;
            transition: border-color 0.18s, background 0.18s, box-shadow 0.18s;
            user-select: none;
        }
        .role-card:hover {
            border-color: #A7D9C6;
            box-shadow: 0 4px 16px rgba(5,199,147,0.08);
        }
        .role-card.selected {
            background: #E7F9F0;
            border: 2px solid #05C793;
        }
        .role-card-icon {
            font-size: 36px;
            line-height: 1;
        }
        .role-card-title {
            font-size: 15px;
            font-weight: 800;
            color: #172B29;
        }
        .role-card-desc {
            font-size: 12.5px;
            color: #7B9590;
            line-height: 1.6;
        }

        /* Error */
        .alert-error {
            background: #FFF1F0;
            border: 1px solid #FECACA;
            color: #9B1C1C;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Submit button */
        .submit-btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 11px;
            font-size: 15px;
            font-weight: 800;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.18s, color 0.18s, opacity 0.18s;
            margin-top: 8px;
        }
        .submit-btn.active {
            background: #163D34;
            color: #ffffff;
        }
        .submit-btn.active:hover { background: #0f2b24; }
        .submit-btn.disabled {
            background: #D1DDD9;
            color: #8A9D96;
            cursor: not-allowed;
        }

        /* Logout link */
        .logout-link {
            display: block;
            text-align: center;
            margin-top: 18px;
            font-size: 12.5px;
            color: #A2AEAA;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.15s;
        }
        .logout-link:hover { color: #71837C; }
    </style>
</head>
<body x-data="{ selectedRole: '' }">

    <div class="onboard-card">

        {{-- Brand --}}
        <div class="onboard-brand">
            <div class="onboard-brand-mark">🌿</div>
            <div class="onboard-brand-text">Tani<span>Link</span></div>
        </div>

        {{-- Heading --}}
        <h1 class="onboard-heading">Pilih peran Anda</h1>
        <p class="onboard-sub">
            Halo, {{ explode(' ', Auth::user()->name)[0] }}! Sesuaikan pengalaman Anda di TaniLink.
        </p>

        {{-- Error --}}
        @if($errors->any())
        <div class="alert-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            Silakan pilih salah satu peran untuk melanjutkan.
        </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('onboarding.post') }}" method="POST">
            @csrf
            <input type="hidden" name="purpose" :value="selectedRole">

            {{-- Role cards --}}
            <div class="role-grid">

                {{-- Petani --}}
                <div class="role-card" :class="{ selected: selectedRole === 'petani' }" @click="selectedRole = 'petani'">
                    <div class="role-card-icon">🌱</div>
                    <div>
                        <div class="role-card-title">Saya Petani</div>
                        <div class="role-card-desc">Tawarkan hasil panen, terima pesanan dari konsumen, dan kelola proses tanam hingga panen.</div>
                    </div>
                </div>

                {{-- Konsumen / Pembeli --}}
                <div class="role-card" :class="{ selected: selectedRole === 'pembeli' }" @click="selectedRole = 'pembeli'">
                    <div class="role-card-icon">🏢</div>
                    <div>
                        <div class="role-card-title">Saya Konsumen</div>
                        <div class="role-card-desc">Cari komoditas terbaik langsung dari petani, pesan sebelum panen, dan pantau progres tanam.</div>
                    </div>
                </div>

            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="submit-btn"
                    :class="selectedRole ? 'active' : 'disabled'"
                    :disabled="!selectedRole">
                <span x-text="selectedRole ? 'Konfirmasi Pilihan' : 'Pilih Peran Dahulu'"></span>
                <i class="fa-solid fa-arrow-right" x-show="selectedRole"></i>
            </button>
        </form>

        {{-- Logout --}}
        <a href="{{ route('logout') }}" class="logout-link"
           onclick="event.preventDefault(); document.getElementById('onboard-logout-form').submit();">
            Bukan {{ explode(' ', Auth::user()->name)[0] }}? Keluar akun
        </a>
        <form id="onboard-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>

    </div>

</body>
</html>
