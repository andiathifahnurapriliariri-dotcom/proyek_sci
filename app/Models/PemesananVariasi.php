<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PemesananVariasi extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'pemesanan_variasi';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pemesanan_id',
        'produk_id',
        'nama_produk',
        'kategori_ukuran',
        'warna',
        'ukuran',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    /**
     * Cast attributes.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
            'harga_satuan' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    /**
     * Relasi ke data pemesanan utama.
     */
    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id');
    }

    /**
     * Relasi ke master produk jika terhubung.
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    /**
     * Kode SKU ukuran terintegrasi dengan master produk (misal: PRD-001-L).
     */
    public function getKodeSkuUkuranAttribute(): string
    {
        if ($this->produk && $this->produk->kode_produk) {
            return $this->produk->kode_produk . '-' . $this->ukuran;
        }

        return 'SKU-' . $this->ukuran;
    }
}
