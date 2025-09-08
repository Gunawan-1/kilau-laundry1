<?php
// app/Models/ProfilLaundry.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilLaundry extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_laundry',
        'alamat',
        'nomor_telepon',
        'email',
        'logo',
        'deskripsi_singkat',
    ];
}