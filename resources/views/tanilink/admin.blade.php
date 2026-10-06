@extends('layouts.app')

@section('title', 'Admin Dashboard - TaniLink')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

    {{-- Admin Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-lg font-bold">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-slate-900">Dashboard Admin</h1>
                <p class="text-[11px] text-slate-400">TaniLink Central • Kelola platform & escrow</p>
            </div>
        </div>
    </div>

    {{-- Metric Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total GMV</span>
            <div class="text-lg sm:text-2xl font-black text-slate-900 mt-1">Rp {{ number_format($totalGmv, 0, ',', '.') }}</div>
            <span class="text-[10px] font-semibold text-purple-600 flex items-center gap-1 mt-1">
                <i class="fa-solid fa-chart-line"></i> Volume transaksi
            </span>
        </div>
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">DP Tersalurkan</span>
            <div class="text-lg sm:text-2xl font-black text-slate-900 mt-1">Rp {{ number_format($totalDpDisbursed, 0, ',', '.') }}</div>
            <span class="text-[10px] font-semibold text-[#05C793] flex items-center gap-1 mt-1">
                <i class="fa-solid fa-check"></i> Ke petani
            </span>
        </div>
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Escrow Terkunci</span>
            <div class="text-lg sm:text-2xl font-black text-slate-900 mt-1">Rp {{ number_format($totalEscrowLocked, 0, ',', '.') }}</div>
            <span class="text-[10px] font-semibold text-cyan-600 flex items-center gap-1 mt-1">
                <i class="fa-solid fa-lock"></i> Dana aman
            </span>
        </div>
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Pengguna</span>
            <div class="text-lg sm:text-2xl font-black text-slate-900 mt-1">{{ $allUsers->count() }}</div>
            <span class="text-[10px] font-semibold text-slate-500 flex items-center gap-1 mt-1">
                <i class="fa-solid fa-users"></i>
                {{ $allUsers->where('role', 'petani')->count() }} petani • {{ $allUsers->where('role', 'pembeli')->count() }} pembeli
            </span>
        </div>
    </div>

    {{-- Active Orders / Escrow Management --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-extrabold text-sm text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-clipboard-list text-purple-600"></i> Pesanan & Escrow
            </h2>
            <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $allOrders->count() }} pesanan</span>
        </div>

        @if($allOrders->count() > 0)
        <div class="divide-y divide-slate-100">
            @foreach($allOrders as $order)
            <div class="p-5 hover:bg-slate-50/50 transition-colors">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            <span class="px-2 py-0.5 rounded-lg bg-purple-50 text-purple-600 border border-purple-200 text-[10px] font-extrabold uppercase">
                                {{ $order->order_code }}
                            </span>
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase
                                {{ $order->status === 'selesai' ? 'bg-emerald-50 text-emerald-600' : 'bg-cyan-50 text-cyan-600' }}">
                                {{ str_replace('_', ' ', $order->status) }}
                            </span>
                        </div>
                        <h4 class="font-bold text-sm text-slate-900">{{ $order->commodityListing->commodity_name }} — {{ number_format($order->quantity_kg, 0, ',', '.') }} kg</h4>
                        <p class="text-[11px] text-slate-400 mt-0.5">
                            <i class="fa-solid fa-person-digging text-[#05C793]"></i> {{ $order->farmer->name }}
                            <span class="mx-1">→</span>
                            <i class="fa-solid fa-hotel text-cyan-600"></i> {{ $order->buyer->name }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <span class="text-[10px] font-bold text-slate-400 block">Escrow</span>
                            <span class="text-sm font-extrabold text-slate-900">Rp {{ number_format($order->escrow_locked_amount, 0, ',', '.') }}</span>
                        </div>

                        @if($order->status !== 'selesai' && $order->escrow_locked_amount > 0)
                        <form method="POST" action="{{ route('admin.escrow.release', $order->id) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-[11px] font-bold shadow-sm transition-colors cursor-pointer whitespace-nowrap">
                                <i class="fa-solid fa-unlock text-[10px]"></i> Rilis Escrow
                            </button>
                        </form>
                        @else
                        <span class="px-4 py-2 rounded-xl bg-emerald-50 text-emerald-600 text-[11px] font-bold">
                            <i class="fa-solid fa-check"></i> Selesai
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-12 text-center">
            <div class="w-14 h-14 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-xl mx-auto mb-3">
                <i class="fa-solid fa-inbox"></i>
            </div>
            <h4 class="font-bold text-sm text-slate-900">Belum Ada Pesanan</h4>
            <p class="text-[11px] text-slate-400 mt-1">Pesanan dari pembeli akan muncul di sini.</p>
        </div>
        @endif
    </div>

    {{-- Listings Overview --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
        <div class="px-5 py-4 border-b border-slate-100">
            <h2 class="font-extrabold text-sm text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-store text-[#05C793]"></i> Semua Listing Marketplace
            </h2>
        </div>
        <div class="divide-y divide-slate-100">
            @foreach($allListings as $listing)
            <div class="px-5 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-slate-100 overflow-hidden flex-shrink-0">
                        <img src="{{ str_starts_with($listing->image_url ?? '', '/storage') ? asset($listing->image_url) : $listing->image_url }}" 
                             alt="" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h4 class="font-bold text-xs text-slate-900">{{ $listing->commodity_name }}</h4>
                        <p class="text-[10px] text-slate-400">{{ $listing->farmer->name ?? 'Unknown' }} • {{ $listing->location }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-slate-700">Rp {{ number_format($listing->price_per_kg, 0, ',', '.') }}/kg</span>
                    <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold
                        {{ $listing->status === 'available' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                        {{ ucfirst($listing->status) }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Users --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
        <div class="px-5 py-4 border-b border-slate-100">
            <h2 class="font-extrabold text-sm text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-users text-purple-600"></i> Daftar Pengguna
            </h2>
        </div>
        <div class="divide-y divide-slate-100">
            @foreach($allUsers as $user)
            <div class="px-5 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center text-sm font-bold
                        @if($user->role === 'petani') bg-emerald-100 text-[#05C793]
                        @elseif($user->role === 'pembeli') bg-cyan-100 text-cyan-600
                        @else bg-purple-100 text-purple-600
                        @endif">
                        @if($user->role === 'petani') <i class="fa-solid fa-person-digging"></i>
                        @elseif($user->role === 'pembeli') <i class="fa-solid fa-hotel"></i>
                        @else <i class="fa-solid fa-shield-halved"></i>
                        @endif
                    </div>
                    <div>
                        <h4 class="font-bold text-xs text-slate-900">{{ $user->name }}</h4>
                        <p class="text-[10px] text-slate-400">{{ $user->email }} • {{ $user->organization }}</p>
                    </div>
                </div>
                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold uppercase
                    @if($user->role === 'petani') bg-emerald-50 text-emerald-600
                    @elseif($user->role === 'pembeli') bg-cyan-50 text-cyan-600
                    @else bg-purple-50 text-purple-600
                    @endif">
                    {{ $user->role }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

