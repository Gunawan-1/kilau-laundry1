<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Layanan;

class LayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Layanan::create([
            'nama_layanan' => 'Cuci Kering Setrika',
            'harga_per_kg' => 8000,
            'estimasi_waktu' => '2 Hari'
        ]);

        Layanan::create([
            'nama_layanan' => 'Cuci Kering Lipat',
            'harga_per_kg' => 6000,
            'estimasi_waktu' => '2 Hari'
        ]);

        Layanan::create([
            'nama_layanan' => 'Setrika Saja',
            'harga_per_kg' => 5000,
            'estimasi_waktu' => '1 Hari'
        ]);

        Layanan::create([
            'nama_layanan' => 'Layanan Express 1 Hari',
            'harga_per_kg' => 15000,
            'estimasi_waktu' => '1 Hari'
        ]);

        Layanan::create([
            'nama_layanan' => 'Cuci Bed Cover',
            'harga_per_kg' => 12000,
            'estimasi_waktu' => '3 Hari'
        ]);
    }
}
