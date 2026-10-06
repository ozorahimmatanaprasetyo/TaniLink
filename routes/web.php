<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaniLinkController;

// Auth Routes (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [TaniLinkController::class, 'showLogin'])->name('login');
    Route::post('/login', [TaniLinkController::class, 'login'])->name('login.post');
    Route::get('/register', [TaniLinkController::class, 'showRegister'])->name('register');
    Route::post('/register', [TaniLinkController::class, 'register'])->name('register.post');
});

// Protected Routes (Authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/', [TaniLinkController::class, 'index'])->name('home');
    Route::get('/logout', [TaniLinkController::class, 'logout'])->name('logout');
    
    // Onboarding / Personalisasi
    Route::get('/onboarding', [TaniLinkController::class, 'onboarding'])->name('onboarding');
    Route::post('/onboarding', [TaniLinkController::class, 'setOnboarding'])->name('onboarding.post');

    // 1. Portal Petani (Produsen)
    Route::get('/petani', [TaniLinkController::class, 'petani'])->name('portal.petani');
    Route::post('/petani/jualan/store', [TaniLinkController::class, 'storeListing'])->name('petani.jualan.store');
    Route::post('/petani/progress/store', [TaniLinkController::class, 'storeProgress'])->name('petani.progress.store');

    // 2. Portal Pembeli (Horeka)
    Route::get('/pembeli', [TaniLinkController::class, 'pembeli'])->name('portal.pembeli');
    Route::post('/pembeli/booking', [TaniLinkController::class, 'storeBooking'])->name('pembeli.booking.store');

    // 3. Portal Admin (Platform & Escrow)
    Route::get('/admin', [TaniLinkController::class, 'admin'])->name('portal.admin');
    Route::post('/admin/escrow/{orderId}/release', [TaniLinkController::class, 'releaseEscrow'])->name('admin.escrow.release');
});
