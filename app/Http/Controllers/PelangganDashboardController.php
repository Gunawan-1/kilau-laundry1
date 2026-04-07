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
use Midtrans\Config;
use Midtrans\Snap;

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
            'manual_diskon_id' => 'nullable|exists:diskons,id',
            'metode_pembayaran' => ['required', 'in:tunai,qris']
        ]);

        try {
            // Check if user has pelanggan profile
            if (!Auth::user()->pelanggan) {
                throw new \Exception('Profil pelanggan tidak ditemukan. Silakan lengkapi profil Anda.');
            }

            DB::beginTransaction();

            $recalculatedSubtotal = 0;
            $itemsForDiscountCheck = [];
            $detailTransaksiData = [];

            $itemsForMidtrans = [];

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

                $itemsForMidtrans[] = [
                    'id' => (string) $layanan->id,
                    'price' => (int) round($harga * $kuantitas),
                    'quantity' => 1,
                    'name' => $layanan->nama_layanan . ' (' . $kuantitas . ' kg)'
                ];
            }

            $recalculatedDiskon = $this->calculateBestDiscount(
                $itemsForDiscountCheck,
                $recalculatedSubtotal,
                $request->input('manual_diskon_id')
            );

            $recalculatedTotalBayar = $recalculatedSubtotal - $recalculatedDiskon;

            // Get metode pembayaran
            $metodePembayaran = $request->metode_pembayaran ?? 'tunai';

            // Validasi minimal pembelian untuk QRIS
            if ($metodePembayaran == 'qris' && $recalculatedTotalBayar <= 0) {
                throw new \Exception('Total bayar harus lebih dari 0 untuk pembayaran QRIS.');
            }

            $pelanggan = Auth::user()->pelanggan;
            $adminUser = User::where('role', 'admin')->first();

            if (!$adminUser) {
                throw new \Exception("Tidak ada user admin yang dapat menangani pesanan ini.");
            }
            $statusPembayaran = $metodePembayaran === 'tunai' ? 'Lunas' : 'Belum Lunas';

            $transaksi = Transaksi::create([
                'kode_invoice' => 'INV-' . Carbon::now()->format('Ymd') . uniqid(),
                'pelanggan_id' => $pelanggan->id,
                'user_id' => $adminUser->id,
                'tanggal_masuk' => now(),
                'subtotal' => $recalculatedSubtotal,
                'diskon' => $recalculatedDiskon,
                'total_bayar' => $recalculatedTotalBayar,
                'status' => 'Baru',
                'metode_pembayaran' => $metodePembayaran,
                'status_pembayaran' => $statusPembayaran,
            ]);

            foreach ($detailTransaksiData as $data) {
                $transaksi->detailTransaksis()->create($data);
            }

            // Integrasi Midtrans
            if ($metodePembayaran == 'qris' && $recalculatedTotalBayar > 0) {
                try {
                    Config::$serverKey = config('services.midtrans.serverKey');
                    Config::$isProduction = config('services.midtrans.isProduction');
                    Config::$isSanitized = config('services.midtrans.isSanitized');
                    Config::$is3ds = config('services.midtrans.is3ds');

                    // Validasi konfigurasi tidak kosong
                    if (!Config::$serverKey) {
                        throw new \Exception('Midtrans Server Key tidak dikonfigurasi. Hubungi administrator.');
                    }

                    $params = [
                        'transaction_details' => [
                            'order_id' => $transaksi->kode_invoice,
                            'gross_amount' => (int) round($recalculatedTotalBayar),
                        ],
                        'customer_details' => [
                            'first_name' => Auth::user()->name,
                            'email' => Auth::user()->email ?? 'pelanggan@laundry.com',
                        ],
                        'item_details' => $itemsForMidtrans,
                    ];

                    $snapToken = Snap::getSnapToken($params);
                    
                    if (!$snapToken) {
                        throw new \Exception('Gagal mendapatkan snap token dari Midtrans. Silakan coba lagi.');
                    }
                    
                    $transaksi->update(['snap_token' => $snapToken]);
                } catch (\Exception $midtransError) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', 'Kesalahan Midtrans: ' . $midtransError->getMessage())
                        ->withInput();
                }
            }

            DB::commit();

            if ($request->metode_pembayaran == 'qris') {
                return redirect()->route('pelanggan.pesanan.show', $transaksi->id)->with('success', 'Pesanan Anda berhasil dibuat. Silakan lanjutkan pembayaran QRIS.');
            }

            return redirect()->route('pelanggan.dashboard')->with('success', 'Pesanan Anda berhasil dibuat! Silakan antar pakaian Anda ke lokasi kami.');

        } catch (\Exception $e) {
            DB::rollBack();
            $errorMsg = $e->getMessage();
            
            // Log error untuk debugging
            \Log::error('Kesalahan pembuatan pesanan: ' . $errorMsg, [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membuat pesanan: ' . $errorMsg)
                ->withInput();
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

    public function updatePaymentStatus(Transaksi $transaksi)
    {
        if ($transaksi->pelanggan_id !== Auth::user()->pelanggan->id) {
            abort(403, 'Akses Ditolak');
        }

        if ($transaksi->metode_pembayaran !== 'qris') {
            return response()->json(['success' => false, 'message' => 'Transaksi bukan QRIS.']);
        }

        if ($transaksi->status_pembayaran === 'Lunas') {
            return response()->json(['success' => true, 'message' => 'Pembayaran sudah lunas.']);
        }

        $transaksi->update(['status_pembayaran' => 'Lunas']);

        return response()->json(['success' => true, 'status_pembayaran' => 'Lunas']);
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

    /**
     * Mengecek diskon untuk items yang dipilih (AJAX endpoint)
     */
    public function cekDiskon(Request $request)
    {
        try {
            $items = $request->input('items', []);
            
            // Hitung subtotal dari items
            $subtotal = 0;
            foreach ($items as $item) {
                $layanan = Layanan::find($item['layanan_id']);
                if ($layanan) {
                    $subtotal += $layanan->harga_per_kg * $item['kuantitas'];
                }
            }

            // Cek diskon otomatis
            $bestDiskon = $this->calculateBestDiscount($items, $subtotal);

            return response()->json([
                'success' => true,
                'potongan' => $bestDiskon,
                'subtotal' => $subtotal
            ]);
        } catch (\Exception $e) {
            \Log::error('Error checking discount: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'potongan' => 0,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
