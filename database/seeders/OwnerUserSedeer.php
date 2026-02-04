<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OwnerUserSeeder extends Seeder
{
    public function run()
    {
        // Cek apakah user owner sudah ada berdasarkan email
        if (!User::where('email', 'owner@laundry.com')->exists()) {
            User::create([
                'name' => 'Owner Laundry',
                'email' => 'owner@laundry.com',
                'password' => Hash::make('password'),
                'role' => 'owner',
            ]);
        }
    }
}
