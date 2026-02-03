<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PelangganProfilController extends Controller
{
    /**
     * Menampilkan form edit profil pelanggan.
     */
    public function edit()
    {
        $user = Auth::user();
        $pelanggan = $user->pelanggan;

        return view('pelanggan-dashboard.edit-profil', compact('user', 'pelanggan'));
    }

    /**
     * Memproses update profil pelanggan.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $pelanggan = $user->pelanggan;

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'nomor_telepon' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'], // password + password_confirmation
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Update user
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // Update pelanggan
        $pelanggan->nama = $request->name;
        $pelanggan->alamat = $request->alamat;
        $pelanggan->nomor_telepon = $request->nomor_telepon;
        $pelanggan->save();

        return redirect()->route('pelanggan.profil.edit')->with('success', 'Profil berhasil diperbarui.');

    }
}
