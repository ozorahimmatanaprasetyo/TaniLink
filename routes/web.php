<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaniLinkController;

// Public / Landing Page
Route::get('/', function () {
    $listings = collect();
    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('commodity_listings')) {
            $listings = \App\Models\CommodityListing::where('status', 'available')
                ->where('available_kg', '>', 0)
                ->latest()
                ->take(4)
                ->get();
        }
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::warning('Landing catalog query failed: ' . $e->getMessage());
    }
    return view('welcome', compact('listings'));
})->name('landing');

// Auth Routes (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [TaniLinkController::class, 'showLogin'])->name('login');
    Route::post('/login', [TaniLinkController::class, 'login'])->name('login.post');
    Route::get('/register', [TaniLinkController::class, 'showRegister'])->name('register');
    Route::post('/register', [TaniLinkController::class, 'register'])->name('register.post');

    // Lupa Password & Reset Password
    Route::get('/forgot-password', [\App\Http\Controllers\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\ForgotPasswordController::class, 'reset'])->name('password.update');
});

// Logout Route (Dapat diakses kapan saja untuk mengakhiri sesi secara bersih)
Route::match(['get', 'post'], '/logout', [TaniLinkController::class, 'logout'])->name('logout');

// Protected Routes (Authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [TaniLinkController::class, 'index'])->name('home');
    
    // Onboarding / Personalisasi
    Route::get('/onboarding', [TaniLinkController::class, 'onboarding'])->name('onboarding');
    Route::post('/onboarding', [TaniLinkController::class, 'setOnboarding'])->name('onboarding.post');

    // 1. Portal Petani
    Route::middleware('role:petani')->group(function () {
        Route::get('/petani', [TaniLinkController::class, 'petani'])->name('portal.petani');
        Route::get('/petani/komoditas', [TaniLinkController::class, 'petaniListings'])->name('petani.komoditas');
        Route::get('/petani/komoditas/tambah', [TaniLinkController::class, 'petaniCreateListing'])->name('petani.komoditas.create');
        Route::post('/petani/jualan/store', [TaniLinkController::class, 'storeListing'])->name('petani.jualan.store');
        Route::get('/petani/komoditas/{id}/edit', [TaniLinkController::class, 'petaniEditListing'])->name('petani.komoditas.edit');
        Route::put('/petani/komoditas/{id}', [TaniLinkController::class, 'petaniUpdateListing'])->name('petani.komoditas.update');
        Route::delete('/petani/komoditas/{id}', [TaniLinkController::class, 'petaniDestroyListing'])->name('petani.komoditas.destroy');
        Route::get('/petani/pesanan', [TaniLinkController::class, 'petaniOrders'])->name('petani.pesanan');
        Route::get('/petani/pesanan/{id}', [TaniLinkController::class, 'petaniOrderProgress'])->name('petani.pesanan.show');
        Route::post('/petani/pesanan/{id}/confirm', [TaniLinkController::class, 'petaniConfirmOrder'])->name('petani.pesanan.confirm');
        Route::post('/petani/pesanan/{id}/reject', [TaniLinkController::class, 'petaniRejectOrder'])->name('petani.pesanan.reject');
        Route::post('/petani/pesanan/{id}/confirm-dp', [TaniLinkController::class, 'petaniConfirmDp'])->name('petani.pesanan.confirm_dp');
        Route::post('/petani/pesanan/{id}/reject-dp', [TaniLinkController::class, 'petaniRejectDp'])->name('petani.pesanan.reject_dp');
        Route::post('/petani/komoditas/{id}/batch', [TaniLinkController::class, 'storeBatch'])->name('petani.batch.store');
        Route::get('/petani/batch/{id}/progress', [TaniLinkController::class, 'petaniBatchProgress'])->name('petani.batch.progress');
        Route::post('/petani/batch/{id}/progress', [TaniLinkController::class, 'storeBatchProgress'])->name('petani.batch.progress.store');
        Route::post('/petani/batch/{id}/complete-phase', [TaniLinkController::class, 'completeBatchPhase'])->name('petani.batch.phase.complete');
        Route::post('/petani/progress/store', [TaniLinkController::class, 'storeProgress'])->name('petani.progress.store');
        Route::get('/petani/profil', [TaniLinkController::class, 'petaniProfile'])->name('petani.profil');
        Route::post('/petani/profil', [TaniLinkController::class, 'updatePetaniProfile'])->name('petani.profil.update');
        Route::get('/petani/pesanan/{id}/riwayat', [TaniLinkController::class, 'showOrderHistory'])->name('petani.pesanan.history');
        Route::get('/petani/pesanan/{id}/pdf', [TaniLinkController::class, 'downloadOrderPdf'])->name('petani.pesanan.pdf');
    });

    // 2. Portal Konsumen
    Route::middleware('role:pembeli')->group(function () {
        Route::get('/pembeli', [TaniLinkController::class, 'pembeli'])->name('portal.pembeli');
        Route::get('/pembeli/katalog', [TaniLinkController::class, 'pembeliCatalog'])->name('pembeli.katalog');
        Route::get('/pembeli/komoditas/{id}', [TaniLinkController::class, 'pembeliCommodityDetail'])->name('pembeli.komoditas.show');
        Route::get('/pembeli/preorder/{id}', [TaniLinkController::class, 'pembeliPreorderForm'])->name('pembeli.preorder');
        Route::post('/pembeli/booking', [TaniLinkController::class, 'storeBooking'])->name('pembeli.booking');
        Route::post('/pembeli/booking/store', [TaniLinkController::class, 'storeBooking'])->name('pembeli.booking.store');
        Route::get('/pembeli/pesanan', [TaniLinkController::class, 'pembeliOrders'])->name('pembeli.pesanan');
        Route::get('/pembeli/pesanan/{id}/tracking', [TaniLinkController::class, 'pembeliTracking'])->name('pembeli.tracking');
        Route::post('/pembeli/pesanan/{id}/upload-dp', [TaniLinkController::class, 'pembeliUploadDpProof'])->name('pembeli.pesanan.upload_dp');
        Route::post('/pembeli/pesanan/{id}/complete', [TaniLinkController::class, 'pembeliCompleteOrder'])->name('pembeli.pesanan.complete');
        Route::get('/pembeli/profil', [TaniLinkController::class, 'pembeliProfile'])->name('pembeli.profil');
        Route::post('/pembeli/profil', [TaniLinkController::class, 'updatePembeliProfile'])->name('pembeli.profil.update');
        Route::get('/pembeli/pesanan/{id}/riwayat', [TaniLinkController::class, 'showOrderHistory'])->name('pembeli.pesanan.history');
        Route::get('/pembeli/pesanan/{id}/pdf', [TaniLinkController::class, 'downloadOrderPdf'])->name('pembeli.pesanan.pdf');
    });

    // 3. Portal Admin (Platform & Escrow)
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', [TaniLinkController::class, 'admin'])->name('portal.admin');
        Route::get('/admin/pengguna', [TaniLinkController::class, 'adminUsers'])->name('admin.pengguna');
        Route::delete('/admin/pengguna/{id}', [TaniLinkController::class, 'adminDestroyUser'])->name('admin.pengguna.destroy');
        Route::get('/admin/komoditas', [TaniLinkController::class, 'adminListings'])->name('admin.komoditas');
        Route::post('/admin/kategori/{id}/approve', [TaniLinkController::class, 'adminApproveCategory'])->name('admin.category.approve');
        Route::post('/admin/kategori/{id}/reject', [TaniLinkController::class, 'adminRejectCategory'])->name('admin.category.reject');
        Route::delete('/admin/komoditas/{id}', [TaniLinkController::class, 'adminDestroyListing'])->name('admin.komoditas.destroy');
        Route::get('/admin/pesanan', [TaniLinkController::class, 'adminOrders'])->name('admin.pesanan');
        Route::get('/admin/stats', [TaniLinkController::class, 'adminStatsApi'])->name('admin.stats.api');
        Route::get('/admin/laporan', [TaniLinkController::class, 'adminReports'])->name('admin.laporan');
        Route::get('/admin/laporan/pdf', [TaniLinkController::class, 'adminReportPdf'])->name('admin.laporan.pdf');
        Route::post('/admin/escrow/{orderId}/release', [TaniLinkController::class, 'releaseEscrow'])->name('admin.escrow.release');
        Route::get('/admin/bantuan', [TaniLinkController::class, 'adminHelp'])->name('admin.bantuan');
        Route::post('/admin/bantuan', [TaniLinkController::class, 'storeHelpTopic'])->name('admin.bantuan.store');
        Route::post('/admin/bantuan/contact', [TaniLinkController::class, 'updateAdminContact'])->name('admin.bantuan.contact');
        Route::put('/admin/bantuan/{id}', [TaniLinkController::class, 'updateHelpTopic'])->name('admin.bantuan.update');
        Route::delete('/admin/bantuan/{id}', [TaniLinkController::class, 'destroyHelpTopic'])->name('admin.bantuan.destroy');
    });

    // 4. Common (Bantuan, Pengaturan, & Dokumen Transaksi)
    Route::get('/pesanan/{id}/bukti-dp', [TaniLinkController::class, 'viewDpProof'])->name('pesanan.bukti_dp');
    Route::get('/bantuan', [TaniLinkController::class, 'helpCenter'])->name('bantuan');
    Route::get('/pengaturan', [TaniLinkController::class, 'settings'])->name('pengaturan');
    Route::post('/pengaturan', [TaniLinkController::class, 'updateSettings'])->name('pengaturan.update');
});
