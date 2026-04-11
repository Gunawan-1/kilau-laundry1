<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;

class OwnerTransaksiController extends Controller
{
    /**
     * Display a listing of all transactions.
     */
    public function index()
    {
        $transaksis = Transaksi::with(['pelanggan', 'detailTransaksis.layanan'])->latest()->paginate(10);
        return view('owner.transaksi.index', compact('transaksis'));
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaksi $transaksi)
    {
        $transaksi->load('pelanggan', 'detailTransaksis.layanan');
        return view('owner.transaksi.show', compact('transaksi'));
    }
}