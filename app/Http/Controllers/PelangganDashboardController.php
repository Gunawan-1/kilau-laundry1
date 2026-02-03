<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;
use App\Models\Layanan;
use App\Models\DetailTransaksi;
use App\Models\User;
use App\Models\Diskon;
use Carbon\Carbon;

class PelangganDashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard untuk pelanggan.
     */
    public function index()
    {
        $user = Auth::user();
        $transaksiBerjalan = collect(); // Default ke data kosong

        if ($user->pelanggan) {
            $pelangganId = $user->pelanggan->id;
            $transaksiBerjalan = Transaksi::where('pelanggan_id', $pelangganId)
                                          ->where('status', '!=', 'Diambil')
                                          ->latest()
                                          ->get();
        }

        return view('pelanggan-dashboard.index', compact('transaksiBerjalan'));
    }

    /**
     * Menampilkan form untuk membuat pesanan baru.
     */
    public function create()
    {
        $layanans = Layanan::orderBy('nama_layanan')->get();
        $diskons = Diskon::where('status', true)->get();

        return view('pelanggan-dashboard.create', compact('layanans', 'diskons'));
    }

    /**
     * Menyimpan pesanan baru yang dibuat oleh pelanggan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'detail_transaksi' => ['required', 'array', 'min:1'],
            'detail_transaksi.*.layanan_id' => ['required', 'exists:layanans,id'],
            'detail_transaksi.*.kuantitas' => ['required', 'numeric', 'min:0.1'],
            'manual_diskon_id' => 'nullable|exists:diskons,id'
        ]);

        try {
            DB::beginTransaction();

            $recalculatedSubtotal = 0;
            $itemsForDiscountCheck = [];
            $detailTransaksiData = [];

            foreach ($request->detail_transaksi as $detail) {
                $layanan = Layanan::find($detail['layanan_id']);
                if (!$layanan) {
                    throw new \Exception("Layanan tidak ditemukan.");
                }

                $kuantitas = $detail['kuantitas'];
                $harga = $layanan->harga_per_kg;
                $itemSubtotal = $harga * $kuantitas;

                $recalculatedSubtotal += $itemSubtotal;

                $itemsForDiscountCheck[] = [
                    'layanan_id' => $layanan->id,
                    'kuantitas' => $kuantitas,
                    'subtotal' => $itemSubtotal
                ];

                $detailTransaksiData[] = [
                    'layanan_id' => $layanan->id,
                    'kuantitas' => $kuantitas,
                    'harga' => $harga,
                    'subtotal' => $itemSubtotal
                ];
            }

            $recalculatedDiskon = $this->calculateBestDiscount(
                $itemsForDiscountCheck,
                $recalculatedSubtotal,
                $request->input('manual_diskon_id')
            );

            $recalculatedTotalBayar = $recalculatedSubtotal - $recalculatedDiskon;

            $pelanggan = Auth::user()->pelanggan;
            $adminUser = User::where('role', 'admin')->first();

            if (!$adminUser) {
                throw new \Exception("Tidak ada user admin yang dapat menangani pesanan ini.");
            }

            $transaksi = Transaksi::create([
                'kode_invoice' => 'INV-' . Carbon::now()->format('Ymd') . uniqid(),
                'pelanggan_id' => $pelanggan->id,
                'user_id' => $adminUser->id,
                'tanggal_masuk' => now(),
                'subtotal' => $recalculatedSubtotal,
                'diskon' => $recalculatedDiskon,
                'total_bayar' => $recalculatedTotalBayar,
                'status' => 'Baru',
                'status_pembayaran' => 'Belum Lunas',
            ]);

            foreach ($detailTransaksiData as $data) {
                $transaksi->detailTransaksis()->create($data);
            }

            DB::commit();

            return redirect()->route('pelanggan.dashboard')->with('success', 'Pesanan Anda berhasil dibuat! Silakan antar pakaian Anda ke lokasi kami.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat pesanan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan detail transaksi tertentu.
     */
    public function show(Transaksi $transaksi)
    {
        if ($transaksi->pelanggan_id !== Auth::user()->pelanggan->id) {
            abort(403, 'Akses Ditolak');
        }

        $transaksi->load('detailTransaksis.layanan');

        return view('pelanggan-dashboard.show', compact('transaksi'));
    }

    /**
     * Menghitung diskon terbaik (manual atau otomatis) berdasarkan data transaksi.
     */
    private function calculateBestDiscount(array $items, float $subtotal, $manualDiskonId = null)
    {
        $bestDiskon = 0;

        // Cek diskon manual terlebih dahulu
        if ($manualDiskonId) {
            $diskon = Diskon::find($manualDiskonId);
            if ($diskon) {
                if ($diskon->tipe === 'persen') {
                    $bestDiskon = ($subtotal * $diskon->nilai) / 100;
                } elseif ($diskon->tipe === 'tetap') {
                    $bestDiskon = $diskon->nilai;
                }
            }
        } else {
            // Cek diskon otomatis yang aktif
            $diskons = Diskon::where('status', true)
                            ->where('jenis_aturan', '!=', 'tanpa_aturan')
                            ->get();

            foreach ($diskons as $diskon) {
                if ($diskon->jenis_aturan === 'berdasarkan_layanan_berat') {
                    foreach ($items as $item) {
                        if (
                            $item['layanan_id'] == $diskon->layanan_id_aturan &&
                            $item['kuantitas'] >= $diskon->minimal_berat_aturan
                        ) {
                            $potensiDiskon = $diskon->tipe === 'persen'
                                ? ($item['subtotal'] * $diskon->nilai) / 100
                                : $diskon->nilai;

                            if ($potensiDiskon > $bestDiskon) {
                                $bestDiskon = $potensiDiskon;
                            }
                        }
                    }
                }
            }
        }

        return $bestDiskon;
    }
}
