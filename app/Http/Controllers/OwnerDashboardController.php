<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Pelanggan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OwnerDashboardController extends Controller
{
    /**
     * Dashboard utama OWNER
     * Fokus: keuangan & monitoring
     */
    public function index()
{
    // 1. Ringkasan Keuangan
    $pendapatanHariIni = Transaksi::where('status_pembayaran', 'Lunas')
        ->whereDate('tanggal_masuk', Carbon::today())
        ->sum('total_bayar');

    $pendapatanBulanIni = Transaksi::where('status_pembayaran', 'Lunas')
        ->whereMonth('tanggal_masuk', Carbon::now()->month)
        ->whereYear('tanggal_masuk', Carbon::now()->year)
        ->sum('total_bayar');

    $totalTransaksi = Transaksi::count();
    $totalPelanggan = Pelanggan::count();

    // 2. Grafik Pendapatan
    $grafikPendapatan = Transaksi::select(
            DB::raw('DATE(tanggal_masuk) as tanggal'),
            DB::raw('SUM(total_bayar) as total')
        )
        ->where('status_pembayaran', 'Lunas')
        ->whereMonth('tanggal_masuk', Carbon::now()->month)
        ->groupBy('tanggal')
        ->orderBy('tanggal')
        ->get();

    // Ambil Top 5 Layanan Terlaris berdasarkan detail transaksi
$layananTerlaris = DB::table('detail_transaksis')
    ->join('layanans', 'detail_transaksis.layanan_id', '=', 'layanans.id')
    ->select('layanans.nama_layanan', DB::raw('COUNT(detail_transaksis.layanan_id) as total'))
    ->groupBy('layanans.nama_layanan', 'detail_transaksis.layanan_id')
    ->orderByDesc('total')
    ->take(5)
    ->get();

    return view('owner.dashboard', compact(
        'pendapatanHariIni',
        'pendapatanBulanIni',
        'totalTransaksi',
        'totalPelanggan',
        'grafikPendapatan',
        'layananTerlaris'
    ));
}
}
