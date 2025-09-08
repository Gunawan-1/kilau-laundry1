<?php

namespace App\Http\Controllers;

use App\Models\ProfilLaundry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilLaundryController extends Controller
{
    /**
     * Menampilkan halaman untuk mengedit profil laundry.
     */
    public function edit()
    {
        // Ambil data profil pertama, atau buat baru jika belum ada.
        // Ini memastikan selalu ada satu baris data untuk di-update.
        $profil = ProfilLaundry::firstOrCreate(
            ['id' => 1],
            [
                'nama_laundry' => 'Nama Laundry Anda',
                'alamat' => 'Jl. Contoh No. 123',
                'nomor_telepon' => '081234567890',
            ]
        );

        return view('admin.profil.edit', compact('profil'));
    }

    /**
     * Mengupdate data profil laundry di database.
     */
    public function update(Request $request)
    {
        $profil = ProfilLaundry::find(1);
        if (!$profil) {
            return redirect()->back()->with('error', 'Profil laundry tidak ditemukan.');
        }

        // Validasi input dari form
        $request->validate([
            'nama_laundry' => 'required|string|max:255',
            'alamat' => 'required|string',
            'nomor_telepon' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'deskripsi_singkat' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Maksimal 2MB
        ]);

        $data = $request->except('_token', '_method', 'logo');

        // Proses upload logo jika ada file baru
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($profil->logo && Storage::disk('public')->exists($profil->logo)) {
                Storage::disk('public')->delete($profil->logo);
            }

            // Simpan logo baru dan dapatkan path-nya
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo'] = $path;
        }

        // Update data di database
        $profil->update($data);

        return redirect()->route('admin.profil.edit')->with('success', 'Profil laundry berhasil diperbarui.');
    }
}
