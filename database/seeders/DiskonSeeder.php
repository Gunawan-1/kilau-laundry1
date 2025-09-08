<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Diskon;

class DiskonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Diskon Manual
        Diskon::create([
            'nama_diskon' => 'Promo Awal Bulan',
            'tipe' => 'persen',
            'nilai' => 10,
            'status' => true,
            'jenis_aturan' => 'tanpa_aturan',
        ]);

        // Diskon Otomatis
        Diskon::create([
            'nama_diskon' => 'Diskon Cuci Komplit Jumbo',
            'tipe' => 'persen',
            'nilai' => 15,
            'status' => true,
            'jenis_aturan' => 'berdasarkan_layanan_berat',
            'layanan_id_aturan' => 1, // ID untuk 'Cuci Kering Setrika'
            'minimal_berat_aturan' => 5.0, // Minimal 5 Kg
        ]);

        // Diskon Tidak Aktif
        Diskon::create([
            'nama_diskon' => 'Diskon Lebaran (Tidak Aktif)',
            'tipe' => 'tetap',
            'nilai' => 5000,
            'status' => false,
            'jenis_aturan' => 'tanpa_aturan',
        ]);
    }
}
