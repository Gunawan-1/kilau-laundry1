<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;
use App\Models\JamKerja; // Pastikan Model JamKerja sudah dibuat
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index()
    {
        // Menampilkan data absensi hari ini
        $absensis = Absensi::with('user')->whereDate('tanggal', Carbon::today())->get();
        return view('absensi.index', compact('absensis'));
    }

    public function scanner()
    {
        $prosesScanUrl = auth()->user()->role === 'pegawai'
            ? url('pegawai/absensi/proses-scan')
            : url('admin/absensi/proses-scan');

        return view('absensi.scanner', compact('prosesScanUrl'));
    }

    // --- TAMBAHKAN FUNGSI INI UNTUK MEMPERBAIKI ERROR ---
    public function setupJam()
{
    // Jika data tidak ada, kirim objek kosong (null)
    $jamKerja = \App\Models\JamKerja::first() ?? new \App\Models\JamKerja();
    return view('absensi.jam_kerja', compact('jamKerja'));
}

    public function updateJam(Request $request)
    {
        $request->validate([
            'jam_masuk' => 'required',
            'jam_pulang' => 'required',
        ]);

        // Simpan atau update pengaturan jam kerja
        JamKerja::updateOrCreate(
            ['id' => 1],
            [
                'jam_masuk' => $request->jam_masuk,
                'jam_pulang' => $request->jam_pulang,
                'toleransi_terlambat' => $request->toleransi_terlambat ?? 0
            ]
        );

        return back()->with('success', 'Pengaturan jam kerja berhasil diperbarui!');
    }

    public function prosesScan(Request $request)
    {
        $qrCode = $request->qr_code;
        $user = User::where('email', $qrCode)->orWhere('id', $qrCode)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Pegawai tidak ditemukan!']);
        }

        $config = JamKerja::first();
        $hariIni = Carbon::today()->format('Y-m-d');
        $jamSekarang = Carbon::now();

        $absen = Absensi::where('user_id', $user->id)->where('tanggal', $hariIni)->first();

        if (!$absen) {
            // Cek Status Terlambat
            $status = 'Hadir';
            if ($config) {
                $batasMasuk = Carbon::parse($config->jam_masuk)->addMinutes($config->toleransi_terlambat);
                if ($jamSekarang->gt($batasMasuk)) {
                    $status = 'Terlambat';
                }
            }

            Absensi::create([
                'user_id' => $user->id,
                'tanggal' => $hariIni,
                'jam_masuk' => $jamSekarang->format('H:i:s'),
                'status' => $status 
            ]);

            return response()->json([
                'success' => true, 
                'nama' => $user->name, 
                'waktu' => 'Masuk: ' . $jamSekarang->format('H:i:s') . ' (' . $status . ')'
            ]);
        } else {
            if ($absen->jam_keluar == null) {
                $absen->update(['jam_keluar' => $jamSekarang->format('H:i:s')]);
                return response()->json([
                    'success' => true, 
                    'nama' => $user->name, 
                    'waktu' => 'Pulang: ' . $jamSekarang->format('H:i:s')
                ]);
            } else {
                return response()->json(['success' => false, 'message' => 'Sudah absen masuk & pulang.']);
            }
        }
    }
}