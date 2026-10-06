<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - TaniLink</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 flex">
    
    {{-- Left Side: Image/Branding (Hidden on mobile) --}}
    <div class="hidden lg:flex w-1/2 bg-slate-900 relative items-center justify-center overflow-hidden">
        {{-- Background Image with Overlay --}}
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1592982537447-6f23f663cb64?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" alt="Agriculture" class="w-full h-full object-cover opacity-40">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>
        </div>
        
        <div class="relative z-10 w-full max-w-lg p-12 text-white">
            <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-[#05C793] to-[#06B6D4] flex items-center justify-center shadow-xl shadow-emerald-500/30 mb-8 border border-white/10">
                <i class="fa-solid fa-leaf text-4xl text-white"></i>
            </div>
            
            <h1 class="text-5xl font-extrabold tracking-tight mb-6 leading-tight">
                Hubungkan Panen<br>dengan Peluang.
            </h1>
            <p class="text-lg text-slate-300 leading-relaxed mb-8">
                TaniLink adalah platform pre-order agrikultur yang mempertemukan petani dan Horeka secara langsung.
            </p>
            
            <div class="flex items-center gap-4 text-sm font-semibold text-slate-400">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-shield-check text-[#05C793]"></i> Transaksi Aman
                </div>
                <div class="w-1.5 h-1.5 rounded-full bg-slate-700"></div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-handshake text-[#05C793]"></i> Tanpa Perantara
                </div>
            </div>
        </div>
    </div>

    {{-- Right Side: Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 relative overflow-hidden">
        {{-- Decorative blobs for mobile background --}}
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-[#05C793]/10 blur-3xl lg:hidden"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-[#06B6D4]/10 blur-3xl lg:hidden"></div>
        
        <div class="w-full max-w-md relative z-10">
            
            {{-- Mobile Brand --}}
            <div class="lg:hidden text-center mb-10">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-[#05C793] to-[#06B6D4] flex items-center justify-center shadow-lg shadow-emerald-500/20 mb-4">
                    <i class="fa-solid fa-leaf text-2xl text-white"></i>
                </div>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Tani<span class="text-[#05C793]">Link</span></h2>
            </div>
            
            <div class="mb-8">
                <h2 class="text-3xl font-extrabold text-slate-900 mb-2">Selamat Datang 👋</h2>
                <p class="text-slate-500 font-medium">Silakan masuk ke akun Anda untuk melanjutkan.</p>
            </div>

            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-700 p-4 rounded-2xl text-sm mb-6 border border-emerald-100 flex items-start gap-3">
                    <i class="fa-solid fa-circle-check mt-0.5 text-emerald-500"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 text-red-700 p-4 rounded-2xl text-sm mb-6 border border-red-100 flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation mt-0.5 text-red-500"></i>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf

                <div class="space-y-4">
                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            </div>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                                class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 bg-white focus:bg-white focus:border-[#05C793] focus:ring-4 focus:ring-[#05C793]/10 transition-all outline-none font-medium placeholder:text-slate-400 placeholder:font-normal"
                                placeholder="nama@email.com">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                            <a href="#" class="text-xs font-bold text-[#05C793] hover:text-[#04b383]">Lupa Password?</a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">  
                            </div>
                            <input type="password" name="password" id="password" required
                                class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 bg-white focus:bg-white focus:border-[#05C793] focus:ring-4 focus:ring-[#05C793]/10 transition-all outline-none font-medium placeholder:text-slate-400 placeholder:font-normal"
                                placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;">
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="w-full py-4 mt-2 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm shadow-xl shadow-slate-900/20 transition-all duration-200 flex justify-center items-center gap-2">
                    Masuk ke TaniLink <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm font-medium text-slate-500">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-[#05C793] font-bold hover:underline">Daftar sekarang</a>
                </p>
            </div>
            
            {{-- Test Accounts Panel --}}
            <div class="mt-10 p-5 bg-slate-100/80 rounded-2xl border border-slate-200 border-dashed">
                <p class="text-xs font-bold text-slate-800 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-flask text-[#05C793]"></i> Kredensial Demo
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs text-slate-600">
                    <div class="bg-white p-2.5 rounded-xl border border-slate-100 shadow-sm">
                        <span class="block font-bold text-slate-800 mb-0.5">Petani</span>
                        <span class="text-slate-500 select-all">raden@tanilink.id</span>
                    </div>
                    <div class="bg-white p-2.5 rounded-xl border border-slate-100 shadow-sm">
                        <span class="block font-bold text-slate-800 mb-0.5">Pembeli</span>
                        <span class="text-slate-500 select-all">maya@grandhotel.com</span>
                    </div>
                    <div class="bg-white p-2.5 rounded-xl border border-slate-100 shadow-sm">
                        <span class="block font-bold text-slate-800 mb-0.5">Admin</span>
                        <span class="text-slate-500 select-all">admin@tanilink.id</span>
                    </div>
                    <div class="bg-white p-2.5 rounded-xl border border-slate-100 shadow-sm sm:col-span-3 flex justify-between items-center">
                        <div>
                            <span class="block font-bold text-slate-800 mb-0.5">Password Semua Akun</span>
                            <span class="text-slate-500 font-mono select-all">password123</span>
                        </div>
                        <i class="fa-solid fa-key text-slate-300"></i>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
