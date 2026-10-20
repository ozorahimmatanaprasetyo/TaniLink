<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaniLinkController;

// Public / Landing Page
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('home');
    }
    return view('welcome');
})->name('landing');

// Auth Routes (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [TaniLinkController::class, 'showLogin'])->name('login');
    Route::post('/login', [TaniLinkController::class, 'login'])->name('login.post');
    Route::get('/register', [TaniLinkController::class, 'showRegister'])->name('register');
    Route::post('/register', [TaniLinkController::class, 'register'])->name('register.post');
});

// Protected Routes (Authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [TaniLinkController::class, 'index'])->name('home');
    Route::match(['get', 'post'], '/logout', [TaniLinkController::class, 'logout'])->name('logout');
    
    // Onboarding / Personalisasi
    Route::get('/onboarding', [TaniLinkController::class, 'onboarding'])->name('onboarding');
    Route::post('/onboarding', [TaniLinkController::class, 'setOnboarding'])->name('onboarding.post');

    // 1. Portal Petani (Gapoktan)
    Route::get('/petani', [TaniLinkController::class, 'petani'])->name('portal.petani');
    Route::get('/petani/komoditas', [TaniLinkController::class, 'petaniListings'])->name('petani.komoditas');
    Route::get('/petani/komoditas/tambah', [TaniLinkController::class, 'petaniCreateListing'])->name('petani.komoditas.create');
    Route::post('/petani/jualan/store', [TaniLinkController::class, 'storeListing'])->name('petani.jualan.store');
    Route::get('/petani/pesanan', [TaniLinkController::class, 'petaniOrders'])->name('petani.pesanan');
    Route::get('/petani/pesanan/{id}', [TaniLinkController::class, 'petaniOrderProgress'])->name('petani.pesanan.show');
    Route::post('/petani/progress/store', [TaniLinkController::class, 'storeProgress'])->name('petani.progress.store');
    Route::get('/petani/profil', [TaniLinkController::class, 'petaniProfile'])->name('petani.profil');
    Route::post('/petani/profil', [TaniLinkController::class, 'updatePetaniProfile'])->name('petani.profil.update');

    // 2. Portal Pembeli (Horeka)
    Route::get('/pembeli', [TaniLinkController::class, 'pembeli'])->name('portal.pembeli');
    Route::get('/pembeli/katalog', [TaniLinkController::class, 'pembeliCatalog'])->name('pembeli.katalog');
    Route::get('/pembeli/komoditas/{id}', [TaniLinkController::class, 'pembeliCommodityDetail'])->name('pembeli.komoditas.show');
    Route::get('/pembeli/preorder/{id}', [TaniLinkController::class, 'pembeliPreorderForm'])->name('pembeli.preorder');
    Route::post('/pembeli/booking', [TaniLinkController::class, 'storeBooking'])->name('pembeli.booking.store');
    Route::get('/pembeli/pesanan', [TaniLinkController::class, 'pembeliOrders'])->name('pembeli.pesanan');
    Route::get('/pembeli/pesanan/{id}/tracking', [TaniLinkController::class, 'pembeliTracking'])->name('pembeli.tracking');

    // 3. Portal Admin (Platform & Escrow)
    Route::get('/admin', [TaniLinkController::class, 'admin'])->name('portal.admin');
    Route::get('/admin/pengguna', [TaniLinkController::class, 'adminUsers'])->name('admin.pengguna');
    Route::get('/admin/komoditas', [TaniLinkController::class, 'adminListings'])->name('admin.komoditas');
    Route::get('/admin/pesanan', [TaniLinkController::class, 'adminOrders'])->name('admin.pesanan');
    Route::get('/admin/laporan', [TaniLinkController::class, 'adminReports'])->name('admin.laporan');
    Route::post('/admin/escrow/{orderId}/release', [TaniLinkController::class, 'releaseEscrow'])->name('admin.escrow.release');

    // 4. Common (Pengaturan & Bantuan)
    Route::get('/pengaturan', [TaniLinkController::class, 'settings'])->name('pengaturan');
    Route::post('/pengaturan', [TaniLinkController::class, 'updateSettings'])->name('pengaturan.update');
    Route::get('/bantuan', [TaniLinkController::class, 'helpCenter'])->name('bantuan');
});
