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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_invoice', 50)->unique();
            $table->foreignId('pelanggan_id')->constrained('pelanggans')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Kasir/Admin
            $table->dateTime('tanggal_masuk')->useCurrent();
            $table->dateTime('tanggal_selesai')->nullable();
            $table->integer('subtotal');
            $table->integer('diskon')->default(0);
            $table->integer('total_bayar');
            $table->enum('status', ['Baru', 'Proses', 'Selesai', 'Diambil'])->default('Baru');
            $table->enum('status_pembayaran', ['Belum Lunas', 'Lunas'])->default('Belum Lunas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
