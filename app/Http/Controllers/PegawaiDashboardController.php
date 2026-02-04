<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Pelanggan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PegawaiDashboardController extends Controller
{
    /**
     * Menampilkan dashboard untuk pegawai (disamakan dengan admin).
     */
    public function index()
    {
        // Statistik untuk Info Box (Sama dengan Admin)
        $pesananBaru = Transaksi::where('status', 'Baru')->count();
        $pesananDiproses = Transaksi::where('status', 'Proses')->count();
        $totalPelanggan = Pelanggan::count();

        // Data Pendapatan Bulan Ini (Sama dengan Admin)
        $pendapatanBulanIni = Transaksi::where('status_pembayaran', 'Lunas')
                                      ->whereMonth('tanggal_masuk', Carbon::now()->month)
                                      ->whereYear('tanggal_masuk', Carbon::now()->year)
                                      ->sum('total_bayar');

        // Transaksi Terbaru (5 terakhir)
        $transaksiTerbaru = Transaksi::with('pelanggan')->latest()->take(5)->get();

        // Data khusus Absensi Pegawai (Tetap dipertahankan jika ada)
        $user = Auth::user();
        // Misal kamu punya logic cek absen hari ini di sini

        return view('pegawai.dashboard', compact(
            'pesananBaru',
            'pesananDiproses',
            'totalPelanggan',
            'pendapatanBulanIni',
            'transaksiTerbaru'
        ));
    }

    // Fungsi absenMasuk dan absenPulang tetap diletakkan di bawah sini...
}