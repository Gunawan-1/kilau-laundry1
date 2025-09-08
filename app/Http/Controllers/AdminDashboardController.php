<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Pelanggan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama untuk admin.
     */
    public function index()
    {
        // Statistik untuk Info Box
        $pesananBaru = Transaksi::where('status', 'Baru')->count();
        $pesananDiproses = Transaksi::where('status', 'Proses')->count();
        $totalPelanggan = Pelanggan::count();

        // Data Pendapatan Bulan Ini
        $pendapatanBulanIni = Transaksi::where('status_pembayaran', 'Lunas')
                                      ->whereMonth('tanggal_masuk', Carbon::now()->month)
                                      ->whereYear('tanggal_masuk', Carbon::now()->year)
                                      ->sum('total_bayar');

        // Transaksi Terbaru (5 terakhir)
        $transaksiTerbaru = Transaksi::with('pelanggan')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'pesananBaru',
            'pesananDiproses',
            'totalPelanggan',
            'pendapatanBulanIni',
            'transaksiTerbaru'
        ));
    }

    /**
     * Menampilkan halaman laporan transaksi dengan filter tanggal.
     */
    public function laporan(Request $request)
    {
        // Set tanggal default: awal bulan ini sampai hari ini
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggalSelesai = $request->input('tanggal_selesai', Carbon::now()->format('Y-m-d'));

        // Query dasar untuk transaksi dalam rentang tanggal yang dipilih
        $query = Transaksi::with('pelanggan')
                          ->whereDate('tanggal_masuk', '>=', $tanggalMulai)
                          ->whereDate('tanggal_masuk', '<=', $tanggalSelesai);
                          
        // Ambil data transaksi yang sudah difilter
        $laporans = $query->latest()->get();

        // Hitung total untuk ringkasan
        $totalPendapatan = (clone $query)->where('status_pembayaran', 'Lunas')->sum('total_bayar');
        $totalSubtotal = $laporans->sum('subtotal');
        $totalDiskon = $laporans->sum('diskon');

        return view('laporan.index', compact(
            'laporans', 
            'tanggalMulai', 
            'tanggalSelesai',
            'totalPendapatan',
            'totalSubtotal',
            'totalDiskon'
        ));
    }
}
