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
            User::create([
                'name' => 'Admin Laundry',
                'email' => 'admin@laundry.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]);

            // 2. Membuat Akun Pelanggan 1
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

            // 3. Membuat Akun Pelanggan 2
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
        });
    }
}
