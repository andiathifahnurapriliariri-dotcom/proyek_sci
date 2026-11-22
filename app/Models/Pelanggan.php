<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelanggan extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'pelanggan';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_pelanggan',
        'nama',
        'kategori',
        'alamat',
        'kota',
        'nomor_telepon',
        'email',
    ];

    /**
     * Relasi ke riwayat pemesanan pelanggan.
     */
    public function pemesanan(): HasMany
    {
        return $this->hasMany(Pemesanan::class, 'pelanggan_id');
    }
}
