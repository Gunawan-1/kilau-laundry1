<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', 
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function pelanggan()
    {
        return $this->hasOne(Pelanggan::class);
    }

    // Fungsi untuk menampilkan foto di profil bulat
    public function adminlte_image()
    {
        // Membuat inisial otomatis berdasarkan nama user
        // Background biru (#007bff), teks putih (fff)
    // Mengambil inisial dari nama user, background biru, tulisan putih
    return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=007bff&color=fff';
}

    // Fungsi untuk menampilkan deskripsi di bawah nama (Email & Role)
    public function adminlte_desc()
    {
        return $this->email . ' | ' . strtoupper($this->role);
    }
}




