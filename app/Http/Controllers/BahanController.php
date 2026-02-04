<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



namespace App\Http\Controllers;

use App\Models\Bahan;
use Illuminate\Http\Request;

class BahanController extends Controller
{
    // Menampilkan daftar stok bahan
    public function index()
    {
        $bahans = Bahan::all();
        return view('pegawai.bahan.index', compact('bahans'));
    }

    // Form tambah bahan baru (misal: ada jenis sabun baru)
    public function create()
    {
        return view('pegawai.bahan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'stok' => 'required|integer',
            'satuan' => 'required|string',
        ]);

        Bahan::create($request->all());
        return redirect()->route('pegawai.bahan.index')->with('success', 'Bahan baru berhasil ditambahkan.');
    }

    // Update stok (misal: update manual jika ada kebocoran atau belanja baru)
    public function updateStok(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer',
            'tipe' => 'required|in:tambah,kurang' // Tambah jika belanja, kurang jika terbuang
        ]);

        $bahan = Bahan::findOrFail($id);
        
        if ($request->tipe == 'tambah') {
            $bahan->increment('stok', $request->jumlah);
        } else {
            $bahan->decrement('stok', $request->jumlah);
        }

        return redirect()->back()->with('success', 'Stok ' . $bahan->nama_bahan . ' berhasil diupdate.');
    }
}
