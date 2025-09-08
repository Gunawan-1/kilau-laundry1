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

// Halaman Awal
Route::get('/', [LandingPageController::class, 'index']);

// Halaman Dashboard Default (untuk semua user yang login)
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role == 'admin') {
        return redirect()->route('admin.dashboard');
    }
    if ($user->role == 'pelanggan') {
        return redirect()->route('pelanggan.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Grup untuk Rute yang Memerlukan Autentikasi
Route::middleware('auth')->group(function () {

    // Grup khusus untuk ADMIN
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/profil', [ProfilLaundryController::class, 'edit'])->name('profil.edit');
    Route::patch('/profil', [ProfilLaundryController::class, 'update'])->name('profil.update');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/laporan', [AdminDashboardController::class, 'laporan'])->name('laporan.index');
        Route::resource('pelanggan', PelangganController::class);
        Route::resource('layanan', LayananController::class);
        Route::resource('diskon', DiskonController::class);
        Route::resource('transaksi', TransaksiController::class)->except(['edit', 'update']);
        Route::patch('transaksi/{transaksi}/update-status', [TransaksiController::class, 'updateStatus'])->name('transaksi.updateStatus');
        Route::post('transaksi/cek-diskon', [TransaksiController::class, 'cekDiskon'])->name('transaksi.cekDiskon');
    });

    // Grup khusus untuk PELANGGAN
    Route::middleware('role:pelanggan')->prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/dashboard', [PelangganDashboardController::class, 'index'])->name('dashboard');
        Route::get('/pesanan/baru', [PelangganDashboardController::class, 'create'])->name('pesanan.create');
        Route::post('/pesanan', [PelangganDashboardController::class, 'store'])->name('pesanan.store');
        Route::post('/cek-diskon', [TransaksiController::class, 'cekDiskon'])->name('cekDiskon');
        Route::get('/pesanan/{transaksi}', [PelangganDashboardController::class, 'show'])->name('pesanan.show');
    });
});

require __DIR__.'/auth.php';
// ...existing code...