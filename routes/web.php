<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfilLaundryController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\DiskonController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PelangganDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\PelangganProfilController;
use App\Http\Controllers\OwnerDashboardController;
use App\Http\Controllers\PegawaiDashboardController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\UserController;

// 1. HALAMAN AWAL & DASHBOARD REDIRECTOR
Route::get('/', [LandingPageController::class, 'index']);

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role == 'admin') return redirect()->route('admin.dashboard');
    if ($user->role == 'owner') return redirect()->route('owner.dashboard');
    if ($user->role == 'pegawai') return redirect()->route('pegawai.dashboard');
    if ($user->role == 'pelanggan') return redirect()->route('pelanggan.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 2. GRUP AUTENTIKASI (Harus Login)
Route::middleware('auth')->group(function () {

    // --- A. GRUP KHUSUS ADMIN ---
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        
        // Utama
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profil', [ProfilLaundryController::class, 'edit'])->name('profil.edit');
        Route::patch('/profil', [ProfilLaundryController::class, 'update'])->name('profil.update');
        Route::get('/laporan', [AdminDashboardController::class, 'laporan'])->name('laporan.index');

        // Master Data
        Route::resource('pelanggan', PelangganController::class);
        Route::resource('layanan', LayananController::class);
        Route::resource('pegawai', UserController::class); // Data Pegawai di Admin
        
        // Diskon
        Route::resource('diskon', DiskonController::class);
        Route::patch('/diskon/{id}/status', [DiskonController::class, 'updateStatus'])->name('diskon.update-status');

        // Transaksi Admin
        Route::resource('transaksi', TransaksiController::class)->except(['edit', 'update']);
        Route::patch('transaksi/{transaksi}/update-status', [TransaksiController::class, 'updateStatus'])->name('transaksi.updateStatus');
        Route::post('transaksi/cek-diskon', [TransaksiController::class, 'cekDiskon'])->name('transaksi.cekDiskon');
        Route::get('transaksi/{id}/edit', [TransaksiController::class, 'edit'])->name('transaksi.edit');
        Route::put('transaksi/{id}', [TransaksiController::class, 'update'])->name('transaksi.update');

        // Fitur Absensi (Scanner, dll)
        Route::prefix('absensi')->name('absensi.')->group(function () {
            Route::get('/', [AbsensiController::class, 'index'])->name('index');
            Route::get('/jam-kerja', [AbsensiController::class, 'setupJam'])->name('jam-kerja');
            Route::get('/scanner', [AbsensiController::class, 'scanner'])->name('scanner');
            Route::post('/proses-scan', [AbsensiController::class, 'prosesScan'])->name('proses-scan');
            Route::post('/jam-kerja/update', [AbsensiController::class, 'updateJam'])->name('jam-kerja.update');
        });
    });

    // --- B. GRUP KHUSUS OWNER ---
    Route::middleware('role:owner')->prefix('owner')->name('owner.')->group(function () {
        Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/laporan', [AdminDashboardController::class, 'laporan'])->name('laporan');
        Route::resource('pegawai', UserController::class); // Data Pegawai di Owner
    });

    Route::post('/transaksi/{transaksi}/update-payment-status', [TransaksiController::class, 'updatePaymentStatus'])->name('transaksi.updatePaymentStatus');

   // --- C. GRUP KHUSUS PEGAWAI ---
Route::middleware('role:pegawai')->prefix('pegawai')->name('pegawai.')->group(function () {
    Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('dashboard');

    // Absensi Pegawai
    Route::get('/absensi/scanner', [AbsensiController::class, 'scanner'])->name('absensi.scanner');
    Route::post('/absensi/proses-scan', [AbsensiController::class, 'prosesScan'])->name('absensi.proses-scan');

    // Transaksi Pegawai
    Route::get('/transaksi/create', [TransaksiController::class, 'createPegawai'])->name('transaksi.create');
    Route::get('/transaksi', [TransaksiController::class, 'indexPegawai'])->name('transaksi.index');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
});

    // --- D. GRUP KHUSUS PELANGGAN ---
    Route::middleware('role:pelanggan')->prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/dashboard', [PelangganDashboardController::class, 'index'])->name('dashboard');
        Route::get('/pesanan/baru', [PelangganDashboardController::class, 'create'])->name('pesanan.create');
        Route::post('/pesanan', [PelangganDashboardController::class, 'store'])->name('pesanan.store');
        Route::get('/pesanan/{transaksi}', [PelangganDashboardController::class, 'show'])->name('pesanan.show');
        Route::post('/pesanan/{transaksi}/update-payment-status', [PelangganDashboardController::class, 'updatePaymentStatus'])->name('pesanan.updatePaymentStatus');
        Route::post('/cek-diskon', [PelangganDashboardController::class, 'cekDiskon'])->name('cekDiskon');
        
        // Profil Pelanggan
        Route::get('/profil', [PelangganProfilController::class, 'edit'])->name('profil.edit');
        Route::post('/profil', [PelangganProfilController::class, 'update'])->name('profil.update');
    });
});

// 3. AUTH ROUTES (Login, Register, Logout dari Laravel Breeze/Jetstream)
require __DIR__.'/auth.php';