<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Pelanggan;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PegawaiDashboardController extends Controller
{
    /**
     * Menampilkan dashboard untuk pegawai.
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

        // Cek status absen hari ini
        $userId = Auth::id();
        $hariIni = Carbon::now()->format('Y-m-d');
        $absenHariIni = Absensi::where('user_id', $userId)->where('tanggal', $hariIni)->first();

        return view('pegawai.dashboard', compact(
            'pesananBaru',
            'pesananDiproses',
            'totalPelanggan',
            'pendapatanBulanIni',
            'transaksiTerbaru',
            'absenHariIni'
        ));
    }

    /**
     * Menampilkan halaman list absensi & riwayat
     */
    public function indexAbsensi()
    {
        $userId = Auth::id();
        $hariIni = Carbon::now()->toDateString();
        
        $absenHariIni = Absensi::where('user_id', $userId)
                               ->where('tanggal', $hariIni)
                               ->first();
        
        $riwayatAbsensi = Absensi::where('user_id', $userId)
                                 ->latest()
                                 ->take(10)
                                 ->get();

        return view('pegawai.absensi.index', compact('absenHariIni', 'riwayatAbsensi'));
    }

    /**
     * Proses Absen Masuk
     */
    public function absenMasuk(Request $request)
    {
        $userId = Auth::id();
        $hariIni = Carbon::now()->toDateString();

        // Validasi ganda agar tidak bisa spam insert
        $exists = Absensi::where('user_id', $userId)->where('tanggal', $hariIni)->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'Anda sudah absen masuk hari ini.');
        }

        Absensi::create([
            'user_id'   => $userId,
            'tanggal'   => $hariIni,
            'jam_masuk' => Carbon::now()->toTimeString(),
            'status'    => 'Hadir',
        ]);

        return redirect()->back()->with('success', 'Berhasil absen masuk. Selamat bekerja!');
    }

    /**
     * Proses Absen Pulang
     */
    public function absenPulang(Request $request)
    {
        $userId = Auth::id();
        $hariIni = Carbon::now()->toDateString();

        $absen = Absensi::where('user_id', $userId)
                        ->where('tanggal', $hariIni)
                        ->whereNull('jam_pulang')
                        ->first();

        if (!$absen) {
            return redirect()->back()->with('error', 'Data absen masuk tidak ditemukan atau Anda sudah absen pulang.');
        }

        $absen->update([
            'jam_pulang' => Carbon::now()->toTimeString(),
        ]);

        return redirect()->back()->with('success', 'Berhasil absen pulang. Hati-hati di jalan!');
    }
}