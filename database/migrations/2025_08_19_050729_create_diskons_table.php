<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('diskons', function (Blueprint $table) {
            $table->id();
            $table->string('nama_diskon');
            $table->enum('tipe', ['persen', 'tetap']);
            $table->integer('nilai');
            $table->boolean('status')->default(true); // true = Aktif, false = Tidak Aktif

            // Kolom untuk aturan diskon otomatis
            $table->enum('jenis_aturan', ['tanpa_aturan', 'berdasarkan_layanan_berat'])->default('tanpa_aturan');
            $table->foreignId('layanan_id_aturan')->nullable()->constrained('layanans')->onDelete('cascade');
            $table->decimal('minimal_berat_aturan', 8, 2)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diskons');
    }
};
