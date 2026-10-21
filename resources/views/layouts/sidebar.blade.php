<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TaniLink - Platform Pre-Order Pertanian')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --brand-primary: #05C793;
            --brand-dark:    #07876B;
            --brand-cyan:    #06B6D4;
            --admin-violet:  #7257CA;
            --text-main:     #172B29;
            --bg-body:       #F5F8F7;
            --border-color:  #E5ECEA;
            --sidebar-w:     242px;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
        }
        [x-cloak] { display: none !important; }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: #ffffff;
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        /* Brand */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 28px 15px 20px;
            text-decoration: none;
        }
        .sidebar-brand-mark {
            width: 34px;
            height: 34px;
            border-radius: 11px;
            background: linear-gradient(135deg, #05C793, #07876B);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }
        .sidebar-brand-text {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -1.2px;
            color: var(--text-main);
            line-height: 1;
        }
        .sidebar-brand-text span { color: var(--brand-primary); }

        /* Workspace pill */
        .sidebar-workspace {
            margin: 0 12px 19px;
            padding: 10px 12px;
            border: 1px solid var(--border-color);
            background: #F9FBFA;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar-workspace-avatar {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: #DCF8EB;
            color: #007B55;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 800;
            flex-shrink: 0;
        }
        .sidebar-workspace-name {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1.2;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .sidebar-workspace-role {
            font-size: 10.5px;
            color: #7B9590;
            font-weight: 500;
        }

        /* Nav label */
        .sidebar-nav-label {
            padding: 0 15px 8px;
            font-size: 10px;
            letter-spacing: 1.7px;
            color: #A2AEAA;
            font-weight: 900;
            text-transform: uppercase;
        }

        /* Nav section */
        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 2px;
            padding: 0 10px;
            margin-bottom: 6px;
        }

        /* Nav button */
        .nav-btn {
            display: flex;
            align-items: center;
            gap: 13px;
            width: 100%;
            padding: 13px 14px;
            border-radius: 12px;
            background: transparent;
            color: #71837C;
            font-size: 13px;
            font-weight: 720;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
            font-family: inherit;
        }
        .nav-btn:hover {
            background: #F2F8F5;
            color: #1C6F57;
        }
        .nav-btn.active, .nav-btn[aria-current="page"] {
            background: #E7FAF2;
            color: #008764;
        }
        .nav-btn .nav-icon {
            width: 18px;
            text-align: center;
            flex-shrink: 0;
        }

        /* Bottom spacer */
        .sidebar-bottom {
            margin-top: auto;
            padding: 10px;
            border-top: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        /* ── Header ── */
        .main-header {
            height: 78px;
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            padding: 0 38px;
            gap: 16px;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            z-index: 30;
        }
        .header-breadcrumb {
            flex: 1;
            font-size: 12px;
            color: #83938D;
            line-height: 1.4;
        }
        .header-breadcrumb strong {
            color: #1B3B32;
            font-weight: 700;
        }
        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }
        .header-date {
            font-size: 12px;
            color: #83938D;
            font-weight: 500;
        }
        .header-notif {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #71837C;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
            text-decoration: none;
        }
        .header-notif:hover { background: #F2F8F5; color: #1C6F57; }
        .header-user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: #DCF8EB;
            color: #007B55;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 800;
            flex-shrink: 0;
        }
        .header-user-name {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1.2;
        }
        .header-user-role {
            font-size: 10.5px;
            color: #7B9590;
            font-weight: 500;
        }

        /* ── Content area ── */
        .main-content {
            flex: 1;
            overflow-y: auto;
            background: var(--bg-body);
            padding: 35px 38px;
        }

        /* ── Cards ── */
        .card {
            background: #ffffff;
            border-radius: 18px;
            border: 1px solid var(--border-color);
        }
    </style>
    @stack('styles')
</head>
<body class="antialiased" x-data="{
    toast: {
        show: {{ session('success') ? 'true' : 'false' }},
        message: '{{ addslashes(session('success') ?? '') }}',
        type: 'success'
    },
    notify(msg, type = 'success') {
        this.toast.message = msg;
        this.toast.type = type;
        this.toast.show = true;
        setTimeout(() => { this.toast.show = false; }, 3500);
    }
}">

@php
    $authUser   = Auth::user();
    $currentRole = session('active_role', $authUser ? $authUser->role : 'petani');
    $roleLabels  = ['petani' => 'Petani', 'pembeli' => 'Konsumen', 'admin' => 'Administrator', 'pending' => 'Pengguna Baru'];
    $userInitial = $authUser ? strtoupper(substr($authUser->name, 0, 1)) : '?';
    $userName    = $authUser ? $authUser->name : 'Tamu';
    $roleLabel   = $roleLabels[$currentRole] ?? 'Pengguna';

    // Date in Indonesian
    $days    = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $months  = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
    $now     = now();
    $dateStr = $days[$now->dayOfWeek] . ', ' . $now->day . ' ' . $months[$now->month - 1] . ' ' . $now->year;
@endphp

{{-- ════════════════════════════════════
     Toast Notification
════════════════════════════════════ --}}
<div x-cloak x-show="toast.show"
     x-transition:enter="transition ease-out duration-300 transform"
     x-transition:enter-start="opacity-0 translate-y-3"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed bottom-6 right-5 z-50 flex items-center gap-3 px-4 py-3 rounded-2xl shadow-xl text-xs font-semibold bg-slate-900 text-white border border-slate-700/80">
    <span class="w-2 h-2 rounded-full bg-[#05C793] animate-ping"></span>
    <span x-text="toast.message"></span>
    <button @click="toast.show = false" class="ml-2 text-slate-400 hover:text-white">
        <i class="fa-solid fa-xmark"></i>
    </button>
</div>

{{-- ════════════════════════════════════
     Full-screen layout: sidebar + main
════════════════════════════════════ --}}
<div style="display:flex; min-height:100vh;">

    {{-- ── SIDEBAR ── --}}
    <aside class="sidebar">

        {{-- Brand --}}
        <a href="{{ $authUser ? route('portal.' . $currentRole) : route('login') }}" class="sidebar-brand">
            <div class="sidebar-brand-mark">🌿</div>
            <div class="sidebar-brand-text">Tani<span>Link</span></div>
        </a>

        {{-- Workspace pill --}}
        @auth
        <div class="sidebar-workspace">
            <div class="sidebar-workspace-avatar">{{ $userInitial }}</div>
            <div style="overflow:hidden;">
                <div class="sidebar-workspace-name">{{ $userName }}</div>
                <div class="sidebar-workspace-role">{{ $roleLabel }}</div>
            </div>
        </div>
        @endauth

        {{-- MENU UTAMA --}}
        <div class="sidebar-nav-label">MENU UTAMA</div>
        <nav class="sidebar-nav">
            @yield('sidebar_nav')
        </nav>

        {{-- SIDEBAR BOTTOM --}}
        <nav class="sidebar-bottom">
            @if($currentRole !== 'admin')
            <a href="{{ route('bantuan') }}" class="nav-btn {{ request()->routeIs('bantuan') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-regular fa-circle-question"></i></span>
                Pusat Bantuan
            </a>
            @endif
            @auth
            <a href="{{ route('logout') }}" class="nav-btn" style="color:#B85C5C;"
               onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
                <span class="nav-icon"><i class="fa-regular fa-right-from-bracket"></i></span>
                Keluar
            </a>
            <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            @endauth
        </nav>

    </aside>

    {{-- ── MAIN AREA (header + content) ── --}}
    <div style="flex:1; display:flex; flex-direction:column; min-width:0; min-height:100vh;">

        @php
            $notifItems = [];
            $latestNotifId = 0;
            if (Auth::check()) {
                $u = Auth::user();
                if ($u->role === 'petani') {
                    $orders = \App\Models\PreOrder::with('buyer', 'commodityListing')
                        ->where('farmer_id', $u->id)
                        ->latest()
                        ->take(5)
                        ->get();
                    foreach ($orders as $ord) {
                        $latestNotifId = max($latestNotifId, $ord->id);
                        $notifItems[] = [
                            'id' => $ord->id,
                            'title' => 'Pesanan baru: ' . $ord->order_code,
                            'desc' => ($ord->buyer->name ?? 'Konsumen') . ' memesan ' . ($ord->commodityListing->commodity_name ?? 'komoditas') . ' (' . number_format($ord->quantity_kg, 0, ',', '.') . ' kg)',
                            'time' => $ord->created_at ? $ord->created_at->diffForHumans() : 'Baru saja',
                            'icon' => '📦',
                            'url' => route('petani.pesanan'),
                        ];
                    }
                } elseif ($u->role === 'pembeli') {
                    $progs = \App\Models\PlantingProgress::whereHas('preOrder', function($q) use ($u) {
                            $q->where('buyer_id', $u->id);
                        })
                        ->with('preOrder.commodityListing')
                        ->latest()
                        ->take(5)
                        ->get();
                    foreach ($progs as $pr) {
                        $latestNotifId = max($latestNotifId, $pr->id);
                        $notifItems[] = [
                            'id' => $pr->id,
                            'title' => 'Update ' . $pr->stage_name,
                            'desc' => ($pr->preOrder->commodityListing->commodity_name ?? 'Pesanan') . ': ' . \Illuminate\Support\Str::limit($pr->note, 45),
                            'time' => $pr->created_at ? $pr->created_at->diffForHumans() : 'Baru saja',
                            'icon' => '🌱',
                            'url' => route('pembeli.pesanan'),
                        ];
                    }
                } else {
                    $latestOrders = \App\Models\PreOrder::with('commodityListing')->latest()->take(5)->get();
                    foreach ($latestOrders as $ord) {
                        $latestNotifId = max($latestNotifId, $ord->id);
                        $notifItems[] = [
                            'id' => $ord->id,
                            'title' => 'Kontrak Pre-Order ' . $ord->order_code,
                            'desc' => 'Escrow locked Rp ' . number_format($ord->escrow_locked_amount, 0, ',', '.'),
                            'time' => $ord->created_at ? $ord->created_at->diffForHumans() : 'Baru saja',
                            'icon' => '🔒',
                            'url' => route('admin.pesanan'),
                        ];
                    }
                }
            }
        @endphp

        {{-- Header --}}
        <header class="main-header" x-data="{
            notifOpen: false,
            latestId: {{ $latestNotifId }},
            unreadCount: (parseInt(localStorage.getItem('tl_seen_notif_{{ Auth::id() }}') || '0') < {{ $latestNotifId }}) ? {{ count($notifItems) }} : 0,
            toggleNotif() {
                this.notifOpen = !this.notifOpen;
                if (this.notifOpen) {
                    this.markAllRead();
                }
            },
            markAllRead() {
                this.unreadCount = 0;
                localStorage.setItem('tl_seen_notif_{{ Auth::id() }}', this.latestId);
            }
        }">
            {{-- Left: Breadcrumb --}}
            <div class="header-breadcrumb">
                @yield('breadcrumb')
            </div>

            {{-- Right: date + notif + user --}}
            <div class="header-right">
                <span class="header-date">{{ $dateStr }}</span>

                {{-- Interactive Notification Dropdown --}}
                <div style="position:relative;">
                    <button type="button" @click="toggleNotif()" class="header-notif" title="Notifikasi" style="position:relative;">
                        <i class="fa-regular fa-bell"></i>
                        <span x-show="unreadCount > 0"
                              style="position:absolute; top:-4px; right:-4px; width:18px; height:18px; border-radius:50%; background:#EF4444; color:#fff; font-size:10px; font-weight:800; display:flex; align-items:center; justify-content:center; border:2px solid #fff;"
                              x-text="unreadCount"></span>
                    </button>

                    <div x-cloak x-show="notifOpen"
                         @click.away="notifOpen = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         style="position:absolute; right:0; top:48px; width:340px; background:#ffffff; border-radius:16px; border:1px solid var(--border-color); box-shadow:0 18px 45px rgba(0,0,0,0.12); z-index:100; overflow:hidden;">
                        
                        <div style="padding:14px 18px; border-bottom:1px solid #E5ECEA; display:flex; justify-content:space-between; align-items:center; background:#FAFCFB;">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <strong style="font-size:13px; color:#172B29;">Notifikasi</strong>
                                <span x-show="unreadCount > 0" style="background:#EBF9F2; color:#0A8E68; font-size:10px; font-weight:800; padding:2px 7px; border-radius:8px;" x-text="unreadCount + ' baru'"></span>
                            </div>
                            <button type="button" @click="markAllRead()" style="background:none; border:none; color:#07946C; font-size:11px; font-weight:700; cursor:pointer;">
                                Tandai dibaca
                            </button>
                        </div>

                        <div style="max-height:300px; overflow-y:auto; display:flex; flex-direction:column;">
                            @forelse($notifItems as $item)
                            <a href="{{ $item['url'] }}" style="padding:12px 16px; border-bottom:1px solid #F0F4F2; display:flex; gap:10px; text-decoration:none; color:inherit; background:#F8FCFA; transition:background 0.15s;" onmouseover="this.style.background='#F0FAF5'" onmouseout="this.style.background='#F8FCFA'">
                                <div style="width:30px; height:30px; border-radius:8px; background:#DCF8EB; color:#007B55; display:grid; place-items:center; font-size:13px; flex-shrink:0;">{{ $item['icon'] }}</div>
                                <div>
                                    <div style="font-size:11.5px; font-weight:750; color:#172B29;">{{ $item['title'] }}</div>
                                    <div style="font-size:10.5px; color:#6B8077; margin-top:2px;">{{ $item['desc'] }}</div>
                                    <div style="font-size:9.5px; color:#A1B0AA; margin-top:4px;">{{ $item['time'] }}</div>
                                </div>
                            </a>
                            @empty
                            <div style="padding:30px 16px; text-align:center; color:#8A9D95; font-size:12px;">
                                Belum ada notifikasi baru.
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                @auth
                @php
                    $profileRoute = $currentRole === 'petani' ? route('petani.profil') : ($currentRole === 'pembeli' ? route('pembeli.profil') : '#');
                @endphp
                <a href="{{ $profileRoute }}" title="Profil {{ $roleLabel }}" style="text-decoration:none; display:flex; align-items:center;">
                    <div class="header-user-avatar">{{ $userInitial }}</div>
                </a>
                @endauth
            </div>
        </header>

        {{-- Content area --}}
        <main class="main-content">
            @yield('content')
        </main>

    </div>{{-- /main area --}}

</div>{{-- /full-screen layout --}}

@stack('scripts')
</body>
</html>
