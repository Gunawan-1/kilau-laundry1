<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pelanggan; // <-- Import model Pelanggan
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // <-- Import DB Facade

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // 1. Membuat Akun Admin
            if (!User::where('email', 'admin@gmail.com')->exists()) {
                User::create([
                    'name' => 'Admin Laundry',
                    'email' => 'admin@gmail.com',
                    'password' => Hash::make('12345678'),
                    'role' => 'admin',
                ]);
            }

            if (!User::where('email', 'owner@gmail.com')->exists()) {
                User::create([
                    'name' => 'Owner Laundry',
                    'email' => 'owner@gmail.com',
                    'password' => Hash::make('12345678'),
                    'role' => 'owner',
                ]);
            }

            if (!User::where('email', 'pegawai@gmail.com')->exists()) {
                User::create([
                    'name' => 'Pegawai Laundry',
                    'email' => 'pegawai@gmail.com',
                    'password' => Hash::make('12345678'),
                    'role' => 'pegawai',
                ]);
            }


            // 2. Membuat Akun Pelanggan 1
            if (!User::where('email', 'budi@example.com')->exists()) {
                $pelanggan1 = User::create([
                    'name' => 'Budi Santoso',
                    'email' => 'budi@example.com',
                    'password' => Hash::make('password'),
                    'role' => 'pelanggan',
                ]);
                Pelanggan::create([
                    'user_id' => $pelanggan1->id,
                    'nama' => $pelanggan1->name,
                    'alamat' => 'Jl. Dago Asri No. 15, Bandung',
                    'nomor_telepon' => '081223344556'
                ]);
            }

            // 3. Membuat Akun Pelanggan 2
            if (!User::where('email', 'citra@example.com')->exists()) {
                $pelanggan2 = User::create([
                    'name' => 'Citra Lestari',
                    'email' => 'citra@example.com',
                    'password' => Hash::make('password'),
                    'role' => 'pelanggan',
                ]);
                Pelanggan::create([
                    'user_id' => $pelanggan2->id,
                    'nama' => $pelanggan2->name,
                    'alamat' => 'Jl. Setiabudi No. 45, Bandung',
                    'nomor_telepon' => '087812345678'
                ]);
            }
        });
    }
}
