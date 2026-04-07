<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // 1. Ambil data dari model User
        $pegawais = User::whereIn('role', ['admin', 'pegawai', 'owner'])->get();

        // 2. Kirim data ke view
        return view('admin.pegawai.index', compact('pegawais'));
    }
    public function create()
{
    return view('admin.pegawai.create');
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'role' => 'required',
        'nomor_telepon' => 'nullable',
    ]);

    \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt('password123'), // Password default
        'role' => $request->role,
        'nomor_telepon' => $request->nomor_telepon,
        'status' => 'Aktif',
    ]);

    return redirect()->route('admin.pegawai.index')->with('success', 'Pegawai berhasil ditambah!');
}

public function edit($id)
{
    $pegawai = \App\Models\User::findOrFail($id);
    return view('admin.pegawai.edit', compact('pegawai'));
}

public function update(Request $request, $id)
{
    $pegawai = \App\Models\User::findOrFail($id);
    $pegawai->update($request->all());
    return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil diupdate!');
}

public function destroy($id)
{
    $pegawai = \App\Models\User::findOrFail($id);
    $pegawai->delete();
    return redirect()->route('admin.pegawai.index')->with('success', 'Pegawai berhasil dihapus!');
}
}