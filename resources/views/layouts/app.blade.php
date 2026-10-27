<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TaniLink - Platform Pre-Order Pertanian')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/tanilink-icon.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --brand-primary: #05C793;
            --brand-primary-hover: #04b383;
            --brand-cyan: #06B6D4;
        }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #F8FAFB;
        }
        [x-cloak] { display: none !important; }
        .bg-brand { background-color: #05C793; }
        .text-brand { color: #05C793; }
        .border-brand { border-color: #05C793; }
        .bg-brand-gradient { background: linear-gradient(135deg, #05C793 0%, #06B6D4 100%); }
    </style>
    @stack('styles')
</head>
<body class="text-slate-800 antialiased min-h-screen flex flex-col bg-[#F8FAFB]" x-data="{ 
    toast: { 
        show: {{ session('success') ? 'true' : 'false' }}, 
        message: '{{ session('success') ?? '' }}', 
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
        $currentRole = session('active_role', Auth::user() ? Auth::user()->role : 'petani');
        if ($currentRole === 'admin') session(['active_role' => 'admin']);
        $roleLabels = ['petani' => 'Petani', 'pembeli' => 'Pembeli', 'admin' => 'Admin', 'pending' => 'Pengguna Baru'];
        $roleIcons = ['petani' => 'fa-person-digging', 'pembeli' => 'fa-hotel', 'admin' => 'fa-shield-halved', 'pending' => 'fa-user'];
        $roleColors = ['petani' => 'emerald', 'pembeli' => 'cyan', 'admin' => 'purple', 'pending' => 'slate'];
    @endphp

    {{-- Toast Notification --}}
    <div x-cloak x-show="toast.show" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-3"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed bottom-6 right-4 sm:right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-2xl shadow-xl text-xs sm:text-sm font-semibold bg-slate-900 text-white border border-slate-700/80">
        <span class="w-2 h-2 rounded-full bg-[#05C793] animate-ping"></span>
        <span x-text="toast.message"></span>
        <button @click="toast.show = false" class="ml-2 text-slate-400 hover:text-white">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    {{-- Clean Solid Header --}}
    <header class="bg-white border-b border-slate-200/60 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14 sm:h-16">
                
                {{-- Left: Logo --}}
                <a href="{{ $currentRole === 'pending' ? route('onboarding') : route('portal.' . $currentRole) }}" class="flex items-center group">
                    <x-brand-logo variant="full" height="36px" alt="TaniLink" />
                </a>

                {{-- Right: Role Badge + Logout --}}
                <div class="flex items-center gap-3">
                    @auth
                    <div class="hidden sm:flex items-center gap-2 pr-3 border-r border-slate-200">
                        <div class="text-right leading-none">
                            <div class="text-xs font-extrabold text-slate-900">{{ Auth::user()->name }}</div>
                            <div class="text-[10px] text-slate-500">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                    @endauth

                    @if(in_array($currentRole, ['petani', 'pembeli', 'admin']))
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-{{ $roleColors[$currentRole] ?? 'emerald' }}-50 border border-{{ $roleColors[$currentRole] ?? 'emerald' }}-200/60">
                        <i class="fa-solid {{ $roleIcons[$currentRole] ?? 'fa-leaf' }} text-sm text-{{ $roleColors[$currentRole] ?? 'emerald' }}-600"></i>
                        <span class="text-xs font-bold text-{{ $roleColors[$currentRole] ?? 'emerald' }}-700">{{ $roleLabels[$currentRole] ?? 'User' }}</span>
                    </div>
                    @endif

                    @auth
                    <a href="{{ route('logout') }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-500 hover:text-red-500 hover:bg-red-50 transition-colors duration-200">
                        <i class="fa-solid fa-right-from-bracket text-[11px]"></i>
                        <span class="hidden sm:inline">Keluar</span>
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-500 hover:text-brand hover:bg-emerald-50 transition-colors duration-200">
                        <i class="fa-solid fa-right-to-bracket text-[11px]"></i>
                        <span class="hidden sm:inline">Masuk</span>
                    </a>
                    @endauth
                </div>

            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="flex-1 pb-8">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
