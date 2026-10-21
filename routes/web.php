<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaniLinkController;

// Public / Landing Page
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('home');
    }
    $listings = collect();
    if (\Illuminate\Support\Facades\Schema::hasTable('commodity_listings')) {
        $listings = \App\Models\CommodityListing::where('status', 'available')
            ->where('available_kg', '>', 0)
            ->latest()
            ->take(4)
            ->get();
    }
    return view('welcome', compact('listings'));
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

    // 1. Portal Petani
    Route::get('/petani', [TaniLinkController::class, 'petani'])->name('portal.petani');
    Route::get('/petani/komoditas', [TaniLinkController::class, 'petaniListings'])->name('petani.komoditas');
    Route::get('/petani/komoditas/tambah', [TaniLinkController::class, 'petaniCreateListing'])->name('petani.komoditas.create');
    Route::post('/petani/jualan/store', [TaniLinkController::class, 'storeListing'])->name('petani.jualan.store');
    Route::get('/petani/komoditas/{id}/edit', [TaniLinkController::class, 'petaniEditListing'])->name('petani.komoditas.edit');
    Route::put('/petani/komoditas/{id}', [TaniLinkController::class, 'petaniUpdateListing'])->name('petani.komoditas.update');
    Route::delete('/petani/komoditas/{id}', [TaniLinkController::class, 'petaniDestroyListing'])->name('petani.komoditas.destroy');
    Route::get('/petani/pesanan', [TaniLinkController::class, 'petaniOrders'])->name('petani.pesanan');
    Route::get('/petani/pesanan/{id}', [TaniLinkController::class, 'petaniOrderProgress'])->name('petani.pesanan.show');
    Route::post('/petani/progress/store', [TaniLinkController::class, 'storeProgress'])->name('petani.progress.store');
    Route::get('/petani/profil', [TaniLinkController::class, 'petaniProfile'])->name('petani.profil');
    Route::post('/petani/profil', [TaniLinkController::class, 'updatePetaniProfile'])->name('petani.profil.update');

    // 2. Portal Konsumen
    Route::get('/pembeli', [TaniLinkController::class, 'pembeli'])->name('portal.pembeli');
    Route::get('/pembeli/katalog', [TaniLinkController::class, 'pembeliCatalog'])->name('pembeli.katalog');
    Route::get('/pembeli/komoditas/{id}', [TaniLinkController::class, 'pembeliCommodityDetail'])->name('pembeli.komoditas.show');
    Route::get('/pembeli/preorder/{id}', [TaniLinkController::class, 'pembeliPreorderForm'])->name('pembeli.preorder');
    Route::post('/pembeli/booking', [TaniLinkController::class, 'storeBooking'])->name('pembeli.booking');
    Route::post('/pembeli/booking/store', [TaniLinkController::class, 'storeBooking'])->name('pembeli.booking.store');
    Route::get('/pembeli/pesanan', [TaniLinkController::class, 'pembeliOrders'])->name('pembeli.pesanan');
    Route::get('/pembeli/pesanan/{id}/tracking', [TaniLinkController::class, 'pembeliTracking'])->name('pembeli.tracking');
    Route::get('/pembeli/profil', [TaniLinkController::class, 'pembeliProfile'])->name('pembeli.profil');
    Route::post('/pembeli/profil', [TaniLinkController::class, 'updatePembeliProfile'])->name('pembeli.profil.update');

    // 3. Portal Admin (Platform & Escrow)
    Route::get('/admin', [TaniLinkController::class, 'admin'])->name('portal.admin');
    Route::get('/admin/pengguna', [TaniLinkController::class, 'adminUsers'])->name('admin.pengguna');
    Route::delete('/admin/pengguna/{id}', [TaniLinkController::class, 'adminDestroyUser'])->name('admin.pengguna.destroy');
    Route::get('/admin/komoditas', [TaniLinkController::class, 'adminListings'])->name('admin.komoditas');
    Route::delete('/admin/komoditas/{id}', [TaniLinkController::class, 'adminDestroyListing'])->name('admin.komoditas.destroy');
    Route::get('/admin/pesanan', [TaniLinkController::class, 'adminOrders'])->name('admin.pesanan');
    Route::get('/admin/laporan', [TaniLinkController::class, 'adminReports'])->name('admin.laporan');
    Route::post('/admin/escrow/{orderId}/release', [TaniLinkController::class, 'releaseEscrow'])->name('admin.escrow.release');
    Route::get('/admin/bantuan', [TaniLinkController::class, 'adminHelp'])->name('admin.bantuan');
    Route::post('/admin/bantuan', [TaniLinkController::class, 'storeHelpTopic'])->name('admin.bantuan.store');
    Route::post('/admin/bantuan/contact', [TaniLinkController::class, 'updateAdminContact'])->name('admin.bantuan.contact');
    Route::put('/admin/bantuan/{id}', [TaniLinkController::class, 'updateHelpTopic'])->name('admin.bantuan.update');
    Route::delete('/admin/bantuan/{id}', [TaniLinkController::class, 'destroyHelpTopic'])->name('admin.bantuan.destroy');

    // 4. Common (Bantuan & Pengaturan)
    Route::get('/bantuan', [TaniLinkController::class, 'helpCenter'])->name('bantuan');
    Route::get('/pengaturan', [TaniLinkController::class, 'settings'])->name('pengaturan');
    Route::post('/pengaturan', [TaniLinkController::class, 'updateSettings'])->name('pengaturan.update');
});
