<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Event; // <-- Tambahkan ini
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu; // <-- Tambahkan ini
use App\Models\ProfilLaundry;

class AppServiceProvider extends ServiceProvider
{
   public function boot(): void
    {
        // 1. FIX CSS & ASSET
        if (config('app.url') !== 'http://localhost') {
            URL::forceRootUrl(config('app.url'));
        }

        // 2. VIEW COMPOSER (PROFIL LAUNDRY)
        if (Schema::hasTable('profil_laundries')) {
            View::composer('*', function ($view) {
                $profil = ProfilLaundry::first();
                $view->with('profilLaundry', $profil);
            });
        }

        // 3. DINAMIS MENU BERDASARKAN ROLE
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
            $user = auth()->user();
            if (!$user) return; // Keamanan jika user belum login

            // --- MENU UNTUK ADMIN ---
            if ($user->role === 'admin') {
                $event->menu->add('MAIN NAVIGATION - ADMIN');
                $event->menu->add(['text' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'fas fa-tachometer-alt']);
                $event->menu->add(['text' => 'Profil Laundry', 'route' => 'admin.profil.edit', 'icon' => 'fas fa-store']);
                $event->menu->add(['text' => 'Pelanggan', 'url' => 'admin/pelanggan', 'icon' => 'fas fa-users']);
                $event->menu->add(['text' => 'Layanan', 'url' => 'admin/layanan', 'icon' => 'fas fa-soap']);
                $event->menu->add(['text' => 'Pegawai', 'url' => 'admin/pegawai', 'icon' => 'fas fa-user-tie']);
                $event->menu->add(['text' => 'Diskon', 'url' => 'admin/diskon', 'icon' => 'fas fa-tags']);
                $event->menu->add([
                    'text'    => 'Transaksi',
                    'icon'    => 'fas fa-cash-register',
                    'submenu' => [
                        ['text' => 'Daftar Transaksi', 'url' => 'admin/transaksi', 'icon' => 'fas fa-list'],
                        ['text' => 'Tambah Transaksi Baru', 'url' => 'admin/transaksi/create', 'icon' => 'fas fa-plus-circle'],
                    ],
                ]);
                $event->menu->add(['text' => 'Laporan', 'route' => 'admin.laporan.index', 'icon' => 'fas fa-file-alt']);
            }

            // --- MENU UNTUK PEGAWAI ---
            if ($user->role === 'pegawai') {
                $event->menu->add('MENU PEGAWAI');
                $event->menu->add(['text' => 'Dashboard', 'route' => 'pegawai.dashboard', 'icon' => 'fas fa-tachometer-alt']);
                $event->menu->add([
                    'text'    => 'Transaksi Laundry',
                    'icon'    => 'fas fa-cash-register',
                    'submenu' => [
                        ['text' => 'Daftar Transaksi', 'route' => 'pegawai.transaksi.index', 'icon' => 'fas fa-list'],
                        ['text' => 'Tambah Transaksi Baru', 'route' => 'admin.transaksi.create', 'icon' => 'fas fa-plus-circle'],
                    ],
                ]);
                $event->menu->add(['text' => 'Antrian Laundry', 'url' => 'pegawai/antrian', 'icon' => 'fas fa-list-ol']);
            }

            // --- MENU UNTUK OWNER ---
            if ($user->role === 'owner') {
                $event->menu->add('MENU OWNER');
                $event->menu->add(['text' => 'Laporan Pendapatan', 'url' => 'owner/laporan', 'icon' => 'fas fa-chart-line']);
                $event->menu->add(['text' => 'Data Pegawai', 'route' => 'owner.pegawai.index', 'icon' => 'fas fa-users-cog']);
            }

            // --- MENU DROPDOWN PROFIL (USER MENU) ---
            // Ini diletakkan di luar IF role agar semua role punya menu ini
            // Ganti ini:
              

// --- MENU DROPDOWN PROFIL (USER MENU) ---
// Gunakan addIn agar masuk ke dalam kotak profil, bukan sidebar!
$event->menu->addIn('user-menu', [
    'text' => 'Biodata Diri',
    'url'  => 'profile/detail',
    'icon' => 'fas fa-fw fa-user',
]);

$event->menu->addIn('user-menu', [
    'text' => 'Ganti Password',
    'url'  => 'profile/password',
    'icon' => 'fas fa-fw fa-lock',
]);
        });
    }
}