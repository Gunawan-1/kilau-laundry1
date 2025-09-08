<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // <-- [BARU] Tambahkan ini
use App\Models\ProfilLaundry;        // <-- [BARU] Tambahkan ini

class AppServiceProvider extends ServiceProvider
{ 
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // [BARU] Tambahkan kode View Composer di sini
        // Pastikan model ProfilLaundry sudah ada dan bisa diakses
        if (\Illuminate\Support\Facades\Schema::hasTable('profil_laundries')) {
            View::composer('adminlte::page', function ($view) {
                // Ambil data profil, gunakan first() karena hanya ada satu
                $profil = ProfilLaundry::first();
                
                // Kirim data ke view dengan nama variabel 'profilLaundry'
                $view->with('profilLaundry', $profil);
            });
        }
    }
}
