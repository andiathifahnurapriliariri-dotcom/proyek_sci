<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StokProduk extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'stok_produk';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'produk_id',
        'nama_produk',
        'warna',
        'ukuran',
        'sisa_stok',
        'batas_rop',
        'status_rop',
    ];

    /**
     * Cast attributes.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sisa_stok' => 'integer',
            'batas_rop' => 'integer',
        ];
    }

    /**
     * Relasi ke data produk utama.
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    /**
     * Relasi ke riwayat mutasi stok produk ini.
     */
    public function mutasiStok(): HasMany
    {
        return $this->hasMany(MutasiStok::class, 'stok_produk_id');
    }

    /**
     * Menghitung status ROP berdasarkan sisa stok dan batas ROP.
     */
    public function hitungStatusRop(): string
    {
        if ($this->sisa_stok <= 0) {
            return 'Habis';
        }

        if ($this->sisa_stok <= $this->batas_rop) {
            return 'Batas ROP Tercapai';
        }

        return 'Aman';
    }

    /**
     * Update status ROP secara otomatis dan simpan ke database.
     */
    public function perbaruiStatusRop(): void
    {
        $this->status_rop = $this->hitungStatusRop();
        $this->save();
    }

    /**
     * Scope untuk produk yang stoknya habis.
     */
    public function scopeHabis(Builder $query): Builder
    {
        return $query->where('sisa_stok', '<=', 0);
    }

    /**
     * Scope untuk produk yang telah mencapai batas ROP (perlu reorder).
     */
    public function scopePerluReorder(Builder $query): Builder
    {
        return $query->whereColumn('sisa_stok', '<=', 'batas_rop')
                     ->where('sisa_stok', '>', 0);
    }

    /**
     * Scope untuk stok produk dalam batas aman.
     */
    public function scopeAman(Builder $query): Builder
    {
        return $query->whereColumn('sisa_stok', '>', 'batas_rop');
    }
}

