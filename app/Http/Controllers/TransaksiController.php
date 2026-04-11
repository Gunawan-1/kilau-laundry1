<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Pelanggan;
use App\Models\Layanan;
use App\Models\Diskon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\Snap;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with('pelanggan')->latest()->paginate(10);
        return view('transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::orderBy('nama')->get();
        $layanans = Layanan::orderBy('nama_layanan')->get();

        $diskons = Diskon::where('jenis_aturan', 'tanpa_aturan')
            ->where('status', 1)
            ->get();

        return view('transaksi.create', compact('pelanggans', 'layanans', 'diskons'));
    }

    public function indexPegawai()
    {
        $transaksis = Transaksi::with('pelanggan')->latest()->paginate(10);
        return view('transaksi.index', compact('transaksis'));
    }

    public function createPegawai()
    {
        $pelanggans = Pelanggan::orderBy('nama')->get();
        $layanans = Layanan::orderBy('nama_layanan')->get();

        $diskons = Diskon::where('jenis_aturan', 'tanpa_aturan')
            ->where('status', 1)
            ->get();

        return view('transaksi.create', compact('pelanggans', 'layanans', 'diskons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'metode_pembayaran' => 'required|in:tunai,qris',
            'status' => 'required|in:Baru,Proses,Selesai,Diambil',
            'detail_transaksi' => 'required|array|min:1',
            'detail_transaksi.*.layanan_id' => 'required|exists:layanans,id',
            'detail_transaksi.*.kuantitas' => 'required|numeric|min:0.1',
            'manual_diskon_id' => 'nullable|exists:diskons,id'
        ]);

        try {
            DB::beginTransaction();

            $subtotal = 0;
            $items = [];
            $detailData = [];

            foreach ($request->detail_transaksi as $detail) {
                $layanan = Layanan::findOrFail($detail['layanan_id']);

                $itemSubtotal = $layanan->harga_per_kg * $detail['kuantitas'];
                $subtotal += $itemSubtotal;

                $items[] = [
                    'id' => $layanan->id,
                    'price' => (int) round($itemSubtotal),
                    'quantity' => 1,
                    'name' => $layanan->nama_layanan . ' (' . $detail['kuantitas'] . ' kg)'
                ];

                $detailData[] = [
                    'layanan_id' => $layanan->id,
                    'kuantitas' => $detail['kuantitas'],
                    'harga' => $layanan->harga_per_kg,
                    'subtotal' => $itemSubtotal
                ];
            }

            $diskon = $this->calculateBestDiscount($items, $subtotal, $request->manual_diskon_id);
            $totalBayar = $subtotal - $diskon;

            $transaksi = Transaksi::create([
                'kode_invoice' => 'INV-' . Carbon::now()->format('Ymd') . strtoupper(uniqid()),
                'pelanggan_id' => $request->pelanggan_id,
                'user_id' => Auth::id(),
                'tanggal_masuk' => now(),
                'subtotal' => $subtotal,
                'diskon' => $diskon,
                'total_bayar' => $totalBayar,
                'status' => $request->status,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_pembayaran' => ($request->metode_pembayaran == 'tunai' ? 'Lunas' : 'Belum Lunas'),
            ]);

            foreach ($detailData as $data) {
                $transaksi->detailTransaksis()->create($data);
            }

            // MIDTRANS
            if ($request->metode_pembayaran == 'qris' && $totalBayar > 0) {

                Config::$serverKey = config('services.midtrans.serverKey');
                Config::$isProduction = config('services.midtrans.isProduction');
                Config::$isSanitized = config('services.midtrans.isSanitized');
                Config::$is3ds = config('services.midtrans.is3ds');

                $params = [
                    'transaction_details' => [
                        'order_id' => $transaksi->kode_invoice,
                        'gross_amount' => (int) round($totalBayar),
                    ],
                    'customer_details' => [
                        'first_name' => $transaksi->pelanggan->nama,
                        'email' => $transaksi->pelanggan->email ?? 'customer@mail.com',
                    ],
                    'item_details' => $items,
                ];

                $snapToken = Snap::getSnapToken($params);
                $transaksi->update(['snap_token' => $snapToken]);
            }

            DB::commit();

            if (auth()->user()->role === 'pegawai') {
                if ($request->metode_pembayaran === 'qris' && $transaksi->snap_token) {
                    return redirect()->route('pegawai.transaksi.show', $transaksi->id)->with('success', 'Transaksi berhasil dibuat! Silakan lanjutkan pembayaran QRIS.');
                }

                return redirect()->route('pegawai.transaksi.index')->with('success', 'Transaksi berhasil dibuat!');
            }

            return redirect()->route('admin.transaksi.show', $transaksi->id)->with('success', 'Transaksi berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    private function calculateBestDiscount(array $items, float $subtotal, ?int $manualDiskonId): float
    {
        $potonganManual = 0;
        $potonganOtomatis = 0;

        // Diskon manual
        if ($manualDiskonId) {
            $diskon = Diskon::find($manualDiskonId);
            if ($diskon) {
                $potonganManual = ($diskon->tipe == 'persen')
                    ? ($diskon->nilai / 100) * $subtotal
                    : $diskon->nilai;
            }
        }

        // Diskon otomatis
        $maxOtomatis = 0;

        foreach ($items as $item) {
            $diskon = Diskon::where('status', 1)
                ->where('jenis_aturan', 'berdasarkan_layanan_berat')
                ->where('layanan_id_aturan', $item['id'])
                ->where('minimal_berat_aturan', '<=', $item['quantity'])
                ->first();

            if ($diskon) {
                $potongan = ($diskon->tipe == 'persen')
                    ? ($diskon->nilai / 100) * ($item['price'] * $item['quantity'])
                    : $diskon->nilai;

                if ($potongan > $maxOtomatis) {
                    $maxOtomatis = $potongan;
                }
            }
        }

        $potonganOtomatis = $maxOtomatis;

        return max($potonganManual, $potonganOtomatis);
    }

    public function cekDiskon(Request $request)
    {
        $request->validate([
            'items' => 'nullable|array',
            'items.*.layanan_id' => 'required_with:items|exists:layanans,id',
            'items.*.kuantitas' => 'required_with:items|numeric|min:0.1',
        ]);

        $items = $request->input('items', []);
        $subtotal = 0;
        $formattedItems = [];

        foreach ($items as $item) {
            $layanan = Layanan::find($item['layanan_id']);

            if (! $layanan) {
                continue;
            }

            $quantity = (float) $item['kuantitas'];
            $subtotal += $layanan->harga_per_kg * $quantity;

            $formattedItems[] = [
                'id' => $layanan->id,
                'price' => $layanan->harga_per_kg,
                'quantity' => $quantity,
                'name' => $layanan->nama_layanan,
            ];
        }

        $potongan = $this->calculateBestDiscount($formattedItems, $subtotal, null);

        return response()->json([
            'success' => true,
            'potongan' => $potongan,
            'subtotal' => $subtotal,
        ]);
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi->load('pelanggan', 'user', 'detailTransaksis.layanan');
        return view('transaksi.show', compact('transaksi'));
    }

    public function updatePaymentStatus(Request $request, Transaksi $transaksi)
    {
        $user = auth()->user();

        if ($user->role === 'pelanggan') {
            if ($transaksi->pelanggan_id !== $user->pelanggan->id) {
                abort(403, 'Akses ditolak.');
            }
        }

        if (!in_array($user->role, ['admin', 'pegawai', 'pelanggan'])) {
            abort(403, 'Akses ditolak.');
        }

        if ($transaksi->status_pembayaran !== 'Lunas' && $transaksi->metode_pembayaran === 'qris') {
            $transaksi->update(['status_pembayaran' => 'Lunas']);
        }

        return response()->json([
            'success' => true,
            'status_pembayaran' => $transaksi->status_pembayaran,
        ]);
    }

    public function destroy(Transaksi $transaksi)
    {
        DB::transaction(function () use ($transaksi) {
            $transaksi->detailTransaksis()->delete();
            $transaksi->delete();
        });

        return redirect()->route('admin.transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}