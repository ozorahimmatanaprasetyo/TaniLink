<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personalisasi - TaniLink</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center p-4 relative overflow-hidden" x-data="{ selectedRole: '' }">

    {{-- Background Decorations --}}
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-[#05C793]/10 blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-[#06B6D4]/10 blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-2xl relative z-10">
        {{-- Brand / Header --}}
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-[#05C793] to-[#06B6D4] shadow-xl shadow-emerald-500/30 mb-5">
                <i class="fa-solid fa-leaf text-2xl text-white"></i>
            </div>
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight mb-3">
                Halo, {{ explode(' ', Auth::user()->name)[0] }}! 👋
            </h1>
            <p class="text-slate-500 text-lg">Pilih peran Anda untuk menyesuaikan pengalaman di TaniLink.</p>
        </div>

        {{-- Onboarding Card --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-white p-6 sm:p-10">
            
            <form action="{{ route('onboarding.post') }}" method="POST" class="space-y-8">
                @csrf
                <input type="hidden" name="purpose" :value="selectedRole">

                {{-- Role Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Petani --}}
                    <div @click="selectedRole = 'petani'"
                        :class="selectedRole === 'petani' 
                            ? 'border-[#05C793] bg-emerald-50/50 ring-4 ring-[#05C793]/10 scale-[1.02]' 
                            : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50 hover:scale-[1.01] grayscale-[0.5] hover:grayscale-0'"
                        class="relative flex flex-col p-6 rounded-3xl border-2 transition-all duration-300 cursor-pointer h-full">
                        
                        <div class="absolute top-5 right-5">
                            <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors"
                                 :class="selectedRole === 'petani' ? 'border-[#05C793] bg-[#05C793]' : 'border-slate-300'">
                                <i class="fa-solid fa-check text-white text-[10px]" x-show="selectedRole === 'petani'"></i>
                            </div>
                        </div>

                        <div class="w-16 h-16 rounded-2xl bg-emerald-100 text-[#05C793] flex items-center justify-center text-3xl mb-5 shadow-inner">
                            <i class="fa-solid fa-person-digging"></i>
                        </div>
                        
                        <h3 class="font-extrabold text-xl text-slate-900 mb-2">Saya Petani</h3>
                        <p class="text-sm text-slate-500 leading-relaxed flex-1">
                            Tawarkan hasil panen Anda, terima pesanan pre-order, dan kelola proses tanam hingga panen.
                        </p>
                    </div>

                    {{-- Pembeli --}}
                    <div @click="selectedRole = 'pembeli'"
                        :class="selectedRole === 'pembeli' 
                            ? 'border-cyan-500 bg-cyan-50/50 ring-4 ring-cyan-500/10 scale-[1.02]' 
                            : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50 hover:scale-[1.01] grayscale-[0.5] hover:grayscale-0'"
                        class="relative flex flex-col p-6 rounded-3xl border-2 transition-all duration-300 cursor-pointer h-full">
                        
                        <div class="absolute top-5 right-5">
                            <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors"
                                 :class="selectedRole === 'pembeli' ? 'border-cyan-500 bg-cyan-500' : 'border-slate-300'">
                                <i class="fa-solid fa-check text-white text-[10px]" x-show="selectedRole === 'pembeli'"></i>
                            </div>
                        </div>

                        <div class="w-16 h-16 rounded-2xl bg-cyan-100 text-cyan-600 flex items-center justify-center text-3xl mb-5 shadow-inner">
                            <i class="fa-solid fa-hotel"></i>
                        </div>
                        
                        <h3 class="font-extrabold text-xl text-slate-900 mb-2">Saya Horeka</h3>
                        <p class="text-sm text-slate-500 leading-relaxed flex-1">
                            Cari komoditas pertanian terbaik, lakukan pre-order, dan pantau proses tanam secara transparan.
                        </p>
                    </div>
                </div>

                @if($errors->any())
                    <div class="text-red-500 text-sm text-center font-semibold bg-red-50 p-3 rounded-xl border border-red-100">
                        <i class="fa-solid fa-circle-exclamation mr-1"></i> Silakan pilih salah satu peran untuk melanjutkan.
                    </div>
                @endif

                {{-- Action Area --}}
                <div class="pt-4 border-t border-slate-100 flex flex-col items-center gap-4">
                    {{-- Explicit Confirm Button --}}
                    <button type="submit" 
                        :disabled="!selectedRole"
                        :class="selectedRole ? 'bg-slate-900 hover:bg-slate-800 shadow-xl shadow-slate-900/20 translate-y-0 text-white' : 'bg-slate-200 text-slate-400 cursor-not-allowed translate-y-1'"
                        class="w-full sm:w-2/3 py-4 rounded-2xl font-extrabold text-lg transition-all duration-300 flex items-center justify-center gap-3">
                        <span x-text="selectedRole ? 'Konfirmasi Pilihan' : 'Pilih Peran Dahulu'"></span>
                        <i class="fa-solid fa-arrow-right" x-show="selectedRole"></i>
                    </button>
                    
                    <a href="{{ route('logout') }}" class="text-sm font-medium text-slate-400 hover:text-slate-600 transition-colors">
                        Bukan {{ explode(' ', Auth::user()->name)[0] }}? Keluar akun
                    </a>
                </div>
            </form>
        </div>
        
    </div>

</body>
</html>
