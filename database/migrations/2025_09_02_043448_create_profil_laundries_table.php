<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('profil_laundries', function (Blueprint $table) {
        $table->id();
        $table->string('nama_laundry');
        $table->text('alamat');
        $table->string('nomor_telepon', 20);
        $table->string('email')->nullable();
        $table->string('logo')->nullable(); // Path ke file logo
        $table->text('deskripsi_singkat')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_laundries');
    }
};
