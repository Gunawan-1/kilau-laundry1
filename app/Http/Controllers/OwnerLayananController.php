<?php

namespace App\Http\Controllers;

use App\Models\Layanan;

class OwnerLayananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layanans = Layanan::latest()->paginate(10);
        return view('owner.layanan.index', compact('layanans'));
    }
}