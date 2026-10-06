@extends('layouts.app')

@section('title', 'TaniLink - Platform Pre-Order Pertanian')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6 sm:space-y-8" x-data="{
    activeRole: '{{ $initialRole ?? 'petani' }}'
}">

    <!-- Clean Compact Banner -->
    <div class="rounded-3xl bg-brand-gradient text-white p-6 sm:p-10 shadow-lg relative overflow-hidden">
        <div class="absolute -right-12 -top-12 w-64 h-64 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
        <div class="relative z-10 max-w-2xl">
            <span class="inline-block px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-white/20 backdrop-blur-md mb-3">
                Pre-Order Agriculture B2B
            </span>
            <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight">
                Kepastian Harga & Pasokan Sebelum Musim Tanam
            </h1>
            <p class="text-white/90 text-xs sm:text-sm mt-2 font-medium leading-relaxed">
                Hubungkan petani lokal dengan industri Horeka melalui kontrak harga tetap dan rekening bersama escrow.
            </p>
        </div>
    </div>

    <!-- Clean Modern Role Switcher Pills -->
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div>
            <h2 class="text-lg sm:text-xl font-extrabold text-slate-900">Pilih Portal Pengguna</h2>
            <p class="text-xs text-slate-400">Pilih peran untuk melihat simulasi antarmuka</p>
        </div>

        <!-- 3 Role Tabs -->
        <div class="flex items-center p-1 bg-slate-200/60 rounded-2xl text-xs font-bold w-full sm:w-auto">
            <button @click="activeRole = 'petani'" 
                    :class="activeRole === 'petani' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                    class="flex-1 sm:flex-initial flex items-center justify-center gap-2 px-4 sm:px-5 py-2.5 rounded-xl transition-all cursor-pointer">
                <i class="fa-solid fa-person-digging text-[#05C793]"></i>
                <span>Petani</span>
            </button>
            <button @click="activeRole = 'horeka'" 
                    :class="activeRole === 'horeka' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                    class="flex-1 sm:flex-initial flex items-center justify-center gap-2 px-4 sm:px-5 py-2.5 rounded-xl transition-all cursor-pointer">
                <i class="fa-solid fa-hotel text-cyan-600"></i>
                <span>Horeka</span>
            </button>
            <button @click="activeRole = 'kurir'" 
                    :class="activeRole === 'kurir' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                    class="flex-1 sm:flex-initial flex items-center justify-center gap-2 px-4 sm:px-5 py-2.5 rounded-xl transition-all cursor-pointer">
                <i class="fa-solid fa-truck-fast text-amber-500"></i>
                <span>Kurir</span>
            </button>
        </div>
    </div>

    <!-- Active View Dynamic Content -->
    <div class="transition-all duration-200">
        <div x-show="activeRole === 'petani'">
            @include('tanilink.partials.petani_view')
        </div>
        <div x-show="activeRole === 'horeka'">
            @include('tanilink.partials.horeka_view')
        </div>
        <div x-show="activeRole === 'kurir'">
            @include('tanilink.partials.kurir_view')
        </div>
    </div>

</div>
@endsection
