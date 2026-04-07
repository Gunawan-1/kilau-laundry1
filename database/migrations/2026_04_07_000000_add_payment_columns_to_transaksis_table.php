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
        Schema::table('transaksis', function (Blueprint $table) {
            // Menambahkan metode pembayaran
            if (!Schema::hasColumn('transaksis', 'metode_pembayaran')) {
                $table->enum('metode_pembayaran', ['tunai', 'qris'])->default('tunai')->after('status_pembayaran');
            }

            // Menambahkan kolom snap_token untuk Midtrans
            if (!Schema::hasColumn('transaksis', 'snap_token')) {
                $table->string('snap_token')->nullable()->after('metode_pembayaran');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            if (Schema::hasColumn('transaksis', 'metode_pembayaran')) {
                $table->dropColumn('metode_pembayaran');
            }

            if (Schema::hasColumn('transaksis', 'snap_token')) {
                $table->dropColumn('snap_token');
            }
        });
    }
};