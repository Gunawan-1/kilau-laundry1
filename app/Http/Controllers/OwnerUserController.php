<?php

namespace App\Http\Controllers\Owner;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // 1. Ambil data dari model User
        $pegawais = User::whereIn('role', ['admin', 'pegawai', 'owner'])->get();

        // 2. Kirim data ke view
        return view('owner.pegawai.index', compact('pegawais'));
    }
}