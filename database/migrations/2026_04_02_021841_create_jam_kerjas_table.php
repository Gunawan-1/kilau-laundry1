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
    // Pastikan namanya 'jam_kerjas' (dengan huruf 's')
    Schema::create('jam_kerjas', function (Blueprint $table) {
        $table->id();
        $table->time('jam_masuk');
        $table->time('jam_pulang');
        $table->integer('toleransi_terlambat')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jam_kerjas');
    }
};
