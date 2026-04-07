<?php

namespace App\Http\Controllers;

use App\Models\Diskon;
use App\Models\Layanan;
use Illuminate\Http\Request;

class DiskonController extends Controller
{
    public function index()
    {
        $diskons = Diskon::with('layanan')->latest()->paginate(10);
        return view('diskon.index', compact('diskons'));
    }

    public function create()
    {
        $layanans = Layanan::all();
        return view('diskon.create', compact('layanans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_diskon' => 'required|string|max:255',
            'tipe' => 'required|in:persen,tetap',
            'nilai' => 'required|integer|min:0',
            'status' => 'required|boolean',
            'jenis_aturan' => 'required|in:tanpa_aturan,berdasarkan_layanan_berat',
            'layanan_id_aturan' => 'required_if:jenis_aturan,berdasarkan_layanan_berat|nullable|exists:layanans,id',
            'minimal_berat_aturan' => 'required_if:jenis_aturan,berdasarkan_layanan_berat|nullable|numeric|min:0',
        ]);

        Diskon::create($request->all());

        return redirect()->route('admin.diskon.index')
                         ->with('success', 'Diskon berhasil ditambahkan.');
    }

    public function edit(Diskon $diskon)
    {
        $layanans = Layanan::all();
        return view('diskon.edit', compact('diskon', 'layanans'));
    }

    public function update(Request $request, Diskon $diskon)
    {
        $request->validate([
            'nama_diskon' => 'required|string|max:255',
            'tipe' => 'required|in:persen,tetap',
            'nilai' => 'required|integer|min:0',
            'status' => 'required|boolean',
            'jenis_aturan' => 'required|in:tanpa_aturan,berdasarkan_layanan_berat',
            'layanan_id_aturan' => 'required_if:jenis_aturan,berdasarkan_layanan_berat|nullable|exists:layanans,id',
            'minimal_berat_aturan' => 'required_if:jenis_aturan,berdasarkan_layanan_berat|nullable|numeric|min:0',
        ]);
        
        $data = $request->all();
        if ($data['jenis_aturan'] === 'tanpa_aturan') {
            $data['layanan_id_aturan'] = null;
            $data['minimal_berat_aturan'] = null;
        }

        $diskon->update($data);

        return redirect()->route('admin.diskon.index')
                         ->with('success', 'Data diskon berhasil diperbarui.');
    }

    public function destroy(Diskon $diskon)
    {
        $diskon->delete();

        return redirect()->route('admin.diskon.index')
                         ->with('success', 'Diskon berhasil dihapus.');
    }

    // Fungsi Tambahan untuk Toggle Status Otomatis
    public function updateStatus($id)
{
    $diskon = Diskon::findOrFail($id);
    $diskon->status = !$diskon->status;
    $diskon->save();

    // Jika request datang dari AJAX, kirim respons JSON
    if (request()->ajax()) {
        return response()->json([
            'success' => true,
            'status' => $diskon->status
        ]);
    }

    // Fallback jika diakses manual
    return back()->with('success', 'Status diskon berhasil diubah.');
}
}