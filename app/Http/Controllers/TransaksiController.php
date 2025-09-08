<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Pelanggan;
use App\Models\Layanan;
use App\Models\Diskon;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    /**
     * Menampilkan daftar semua transaksi.
     */
    public function index()
    {
        $transaksis = Transaksi::with('pelanggan')->latest()->paginate(10);
        return view('transaksi.index', compact('transaksis'));
    }

    /**
     * Menampilkan halaman untuk membuat transaksi baru.
     */
    public function create()
{
    $pelanggans = Pelanggan::orderBy('nama')->get();
    $layanans = Layanan::orderBy('nama_layanan')->get();
    
    $diskons = Diskon::where('jenis_aturan', 'tanpa_aturan')
                     ->where('status', 1) 
                     ->get();

    return view('transaksi.create', compact('pelanggans', 'layanans', 'diskons'));
}

    /**
     * Menyimpan transaksi baru ke database.
     * ---
     * VERSI DIPERBAIKI: Menambahkan kalkulasi ulang di sisi server.
     * ---
     */
    public function store(Request $request)
    {
        // Validasi input awal tetap sama
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'status' => 'required|in:Baru,Proses,Selesai,Diambil',
            'status_pembayaran' => 'required|in:Belum Lunas,Lunas',
            // Validasi untuk subtotal, diskon, dan total bayar bisa dihilangkan
            // karena kita akan menghitungnya di server.
            // 'subtotal' => 'required|numeric',
            // 'total_diskon' => 'required|numeric',
            // 'total_bayar' => 'required|numeric',
            'detail_transaksi' => 'required|array|min:1',
            'detail_transaksi.*.layanan_id' => 'required|exists:layanans,id',
            'detail_transaksi.*.kuantitas' => 'required|numeric|min:0.1',
            // Tambahkan validasi untuk diskon manual jika ada
            'manual_diskon_id' => 'nullable|exists:diskons,id'
        ]);

        try {
            DB::beginTransaction();

            // --- [START] BAGIAN PERHITUNGAN ULANG ---

            $recalculatedSubtotal = 0;
            $itemsForDiscountCheck = [];
            $detailTransaksiData = [];

            // 1. Hitung ulang subtotal berdasarkan data dari database (lebih aman)
            foreach ($request->detail_transaksi as $detail) {
                // Ambil harga asli dari database, bukan dari request
                $layanan = Layanan::find($detail['layanan_id']);
                if (!$layanan) {
                    throw new \Exception("Layanan dengan ID {$detail['layanan_id']} tidak ditemukan.");
                }

                $kuantitas = $detail['kuantitas'];
                $harga = $layanan->harga_per_kg;
                $itemSubtotal = $harga * $kuantitas;
                
                $recalculatedSubtotal += $itemSubtotal;

                // Siapkan data untuk pengecekan diskon otomatis
                $itemsForDiscountCheck[] = [
                    'layanan_id' => $layanan->id,
                    'kuantitas' => $kuantitas,
                    'subtotal' => $itemSubtotal
                ];

                // Siapkan data untuk disimpan ke tabel detail_transaksi nanti
                $detailTransaksiData[] = [
                    'layanan_id' => $layanan->id,
                    'kuantitas' => $kuantitas,
                    'harga' => $harga,
                    'subtotal' => $itemSubtotal
                ];
            }

            // 2. Hitung ulang diskon terbaik di server
            $recalculatedDiskon = $this->calculateBestDiscount(
                $itemsForDiscountCheck, 
                $recalculatedSubtotal, 
                $request->input('manual_diskon_id')
            );
            
            // 3. Hitung ulang total bayar
            $recalculatedTotalBayar = $recalculatedSubtotal - $recalculatedDiskon;

            // --- [END] BAGIAN PERHITUNGAN ULANG ---

            // 4. Membuat data transaksi utama dengan data yang sudah dihitung ulang
            $transaksi = Transaksi::create([
                'kode_invoice' => 'INV-' . Carbon::now()->format('Ymd') . uniqid(),
                'pelanggan_id' => $request->pelanggan_id,
                'user_id' => $request->user()->id,
                'tanggal_masuk' => now(),
                'subtotal' => $recalculatedSubtotal,            // Gunakan nilai hasil hitung ulang
                'diskon' => $recalculatedDiskon,                // Gunakan nilai hasil hitung ulang
                'total_bayar' => $recalculatedTotalBayar,       // Gunakan nilai hasil hitung ulang
                'status' => $request->status,
                'status_pembayaran' => $request->status_pembayaran,
            ]);

            // 5. Menyimpan detail transaksi
            foreach ($detailTransaksiData as $data) {
                $transaksi->detailTransaksis()->create($data);
            }

            DB::commit();

            return redirect()->route('admin.transaksi.show', $transaksi->id)->with('success', 'Transaksi berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
    
    /**
     * [BARU] Fungsi helper private untuk menghitung diskon terbaik di sisi server.
     * Logikanya meniru frontend: memilih potongan terbesar antara diskon manual dan otomatis.
     */
    private function calculateBestDiscount(array $items, float $subtotal, ?int $manualDiskonId): float
    {
        $potonganManual = 0;
        $potonganOtomatis = 0;

        // Hitung potongan dari diskon manual
        if ($manualDiskonId) {
            $diskon = Diskon::find($manualDiskonId);
            if ($diskon) {
                if ($diskon->tipe == 'persen') {
                    $potonganManual = ($diskon->nilai / 100) * $subtotal;
                } else { // tipe 'tetap'
                    $potonganManual = $diskon->nilai;
                }
            }
        }

        // Hitung potongan dari diskon otomatis (logika dari `cekDiskon` dipindahkan ke sini)
        $potonganOtomatisTerbesar = 0;
        foreach ($items as $item) {
             $diskonOtomatis = Diskon::where('status', 1)
                ->where('jenis_aturan', 'berdasarkan_layanan_berat')
                ->where('layanan_id_aturan', $item['layanan_id'])
                ->where('minimal_berat_aturan', '<=', $item['kuantitas'])
                ->first();
            
            if ($diskonOtomatis) {
                $potonganSaatIni = 0;
                if ($diskonOtomatis->tipe == 'persen') {
                    $potonganSaatIni = ($diskonOtomatis->nilai / 100) * $item['subtotal'];
                } else { // tipe 'tetap'
                    $potonganSaatIni = $diskonOtomatis->nilai;
                }

                if ($potonganSaatIni > $potonganOtomatisTerbesar) {
                    $potonganOtomatisTerbesar = $potonganSaatIni;
                }
            }
        }
        $potonganOtomatis = $potonganOtomatisTerbesar;
        
        // Kembalikan nilai diskon yang paling besar
        return max($potonganManual, $potonganOtomatis);
    }


    /**
     * Menampilkan detail transaksi / invoice.
     */
    public function show(Transaksi $transaksi)
    {
        $transaksi->load('pelanggan', 'user', 'detailTransaksis.layanan');
        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * Mengupdate status laundry & pembayaran.
     */
    public function updateStatus(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'status' => 'required|in:Baru,Proses,Selesai,Diambil',
            'status_pembayaran' => 'required|in:Belum Lunas,Lunas',
        ]);

        $transaksi->update([
            'status' => $request->status,
            'status_pembayaran' => $request->status_pembayaran,
        ]);
        
        if ($request->status == 'Selesai' && is_null($transaksi->tanggal_selesai)) {
            $transaksi->update(['tanggal_selesai' => now()]);
        }

        // Redirect kembali ke halaman show untuk melihat perubahan
        return redirect()->route('admin.transaksi.show', $transaksi->id)->with('success', 'Status transaksi berhasil diperbarui.');
    }

    /**
     * Menghapus data transaksi.
     */
    public function destroy(Transaksi $transaksi)
    {
        DB::transaction(function () use ($transaksi) {
            $transaksi->detailTransaksis()->delete();
            $transaksi->delete();
        });

        return redirect()->route('admin.transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    /**
     * Fungsi untuk mengecek diskon otomatis (tetap dibutuhkan untuk AJAX dari frontend).
     */
    public function cekDiskon(Request $request)
    {
        $items = $request->input('items', []);
        $potonganTerbesar = 0;

        foreach ($items as $item) {
            if (empty($item['layanan_id']) || empty($item['kuantitas'])) {
                continue;
            }

            $layananId = $item['layanan_id'];
            $kuantitas = $item['kuantitas'];
            $layanan = Layanan::find($layananId);
            if (!$layanan) continue;

            $harga = $layanan->harga_per_kg;
            $subtotalItem = $harga * $kuantitas;

            $diskon = Diskon::where('status', 1)
                ->where('jenis_aturan', 'berdasarkan_layanan_berat')
                ->where('layanan_id_aturan', $layananId)
                ->where('minimal_berat_aturan', '<=', $kuantitas)
                ->first();

            if ($diskon) {
                $potonganSaatIni = 0;
                if ($diskon->tipe == 'persen') {
                    $potonganSaatIni = ($diskon->nilai / 100) * $subtotalItem;
                } else {
                    $potonganSaatIni = $diskon->nilai;
                }

                if ($potonganSaatIni > $potonganTerbesar) {
                    $potonganTerbesar = $potonganSaatIni;
                }
            }
        }

        return response()->json(['potongan' => $potonganTerbesar]);
    }
}
