<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// [TAMBAH] Import model ProfilLaundry
use App\Models\ProfilLaundry;

class LandingPageController extends Controller
{
    /**
     * Menampilkan halaman utama (landing page).
     */
    public function index()
    {
        // [UBAH] Ambil data profil laundry untuk ditampilkan di landing page.
        // Menggunakan first() karena kita hanya punya 1 profil laundry.
        $profil = ProfilLaundry::first();

        // Kirim data profil ke view
        return view('welcome', compact('profil'));
    }
}

