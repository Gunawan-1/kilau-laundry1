<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diskon extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_diskon',
        'tipe',
        'nilai',
        'status',
        'jenis_aturan',
        'layanan_id_aturan',
        'minimal_berat_aturan',
    ];

    /**
     * Mendefinisikan relasi ke model Layanan.
     */
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id_aturan');
    }
}
