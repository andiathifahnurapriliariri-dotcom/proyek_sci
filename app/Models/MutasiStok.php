<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MutasiStok extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'mutasi_stok';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'stok_produk_id',
        'user_id',
        'jenis_mutasi',
        'jumlah_stok_masuk',
        'jumlah_stok_keluar',
        'saldo_sebelum',
        'saldo_sesudah',
        'keterangan',
        'tanggal_mutasi',
    ];

    /**
     * Cast attributes.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'jumlah_stok_masuk' => 'integer',
            'jumlah_stok_keluar' => 'integer',
            'saldo_sebelum' => 'integer',
            'saldo_sesudah' => 'integer',
            'tanggal_mutasi' => 'datetime',
        ];
    }

    /**
     * Relasi ke stok produk terkait.
     */
    public function stokProduk(): BelongsTo
    {
        return $this->belongsTo(StokProduk::class, 'stok_produk_id');
    }

    /**
     * Relasi ke admin/user yang mencatat mutasi stok.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

