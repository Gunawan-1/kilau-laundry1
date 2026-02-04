<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel users (siapa pegawainya)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->date('tanggal');           // Format: 2026-01-19
            $table->time('jam_masuk');         // Format: 08:00:00
            $table->time('jam_pulang')->nullable(); // Boleh kosong jika belum pulang
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};