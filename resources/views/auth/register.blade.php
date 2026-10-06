<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - TaniLink</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 flex flex-row-reverse">
    
    {{-- Right Side: Image/Branding (Hidden on mobile) --}}
    <div class="hidden lg:flex w-1/2 bg-slate-900 relative items-center justify-center overflow-hidden">
        {{-- Background Image with Overlay --}}
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1574943320219-553eb213f72d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" alt="Agriculture" class="w-full h-full object-cover opacity-40">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>
        </div>
        
        <div class="relative z-10 w-full max-w-lg p-12 text-white">
            <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-[#05C793] to-[#06B6D4] flex items-center justify-center shadow-xl shadow-emerald-500/30 mb-8 border border-white/10">
                <i class="fa-solid fa-leaf text-4xl text-white"></i>
            </div>
            
            <h1 class="text-5xl font-extrabold tracking-tight mb-6 leading-tight">
                Mulai Perjalanan<br>Agrikultur Anda.
            </h1>
            <p class="text-lg text-slate-300 leading-relaxed mb-8">
                Bergabung dengan komunitas TaniLink untuk memperluas jangkauan pasar dan mendapatkan komoditas terbaik.
            </p>
            
            <div class="flex items-center gap-4 text-sm font-semibold text-slate-400">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-users text-[#05C793]"></i> Ribuan Pengguna
                </div>
                <div class="w-1.5 h-1.5 rounded-full bg-slate-700"></div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-[#05C793]"></i> Transaksi Cepat
                </div>
            </div>
        </div>
    </div>

    {{-- Left Side: Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 relative overflow-hidden">
        {{-- Decorative blobs for mobile background --}}
        <div class="absolute top-0 left-0 -ml-20 -mt-20 w-64 h-64 rounded-full bg-[#05C793]/10 blur-3xl lg:hidden"></div>
        <div class="absolute bottom-0 right-0 -mr-20 -mb-20 w-80 h-80 rounded-full bg-[#06B6D4]/10 blur-3xl lg:hidden"></div>
        
        <div class="w-full max-w-md relative z-10">
            
            {{-- Mobile Brand --}}
            <div class="lg:hidden text-center mb-10">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-[#05C793] to-[#06B6D4] flex items-center justify-center shadow-lg shadow-emerald-500/20 mb-4">
                    <i class="fa-solid fa-leaf text-2xl text-white"></i>
                </div>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Tani<span class="text-[#05C793]">Link</span></h2>
            </div>
            
            <div class="mb-8 lg:mt-0 mt-2">
                <h2 class="text-3xl font-extrabold text-slate-900 mb-2">Buat Akun Baru</h2>
                <p class="text-slate-500 font-medium">Lengkapi data di bawah ini untuk mendaftar.</p>
            </div>

            @if($errors->any())
                <div class="bg-red-50 text-red-700 p-4 rounded-2xl text-sm mb-6 border border-red-100 flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation mt-0.5 text-red-500"></i>
                    <div>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" class="space-y-5">
                @csrf

                <div class="space-y-4">
                    {{-- Nama --}}
                    <div>
                        <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                
                            </div>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                                class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 bg-white focus:bg-white focus:border-[#05C793] focus:ring-4 focus:ring-[#05C793]/10 transition-all outline-none font-medium placeholder:text-slate-400 placeholder:font-normal"
                                placeholder="Nama Lengkap Anda">
                        </div>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            </div>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 bg-white focus:bg-white focus:border-[#05C793] focus:ring-4 focus:ring-[#05C793]/10 transition-all outline-none font-medium placeholder:text-slate-400 placeholder:font-normal"
                                placeholder="nama@email.com">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            </div>
                            <input type="password" name="password" id="password" required minlength="8"
                                class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 bg-white focus:bg-white focus:border-[#05C793] focus:ring-4 focus:ring-[#05C793]/10 transition-all outline-none font-medium placeholder:text-slate-400 placeholder:font-normal"
                                placeholder="Minimal 8 karakter">
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="w-full py-4 mt-4 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm shadow-xl shadow-slate-900/20 transition-all duration-200 flex justify-center items-center gap-2 cursor-pointer">
                    Daftar Sekarang <i class="fa-solid fa-user-plus"></i>
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm font-medium text-slate-500">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="text-slate-900 font-bold hover:underline">Masuk di sini</a>
                </p>
            </div>
            
        </div>
    </div>
</body>
</html>

