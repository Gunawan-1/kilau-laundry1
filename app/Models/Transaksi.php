<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_invoice',
        'pelanggan_id',
        'user_id',
        'tanggal_masuk',
        'tanggal_selesai',
        'subtotal',
        'diskon',
        'total_bayar',
        'status',
        'status_pembayaran',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }

    // Tambahan relasi agar sinkron dengan data Admin/Layanan
    public function detail_transaksi() 
    {
        return $this->hasOne(DetailTransaksi::class);
    }
}