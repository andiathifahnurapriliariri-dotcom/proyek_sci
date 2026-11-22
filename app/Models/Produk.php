<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Produk extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'produk';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'jenis_kain',
        'warna',
        'ukuran_baju',
        'harga_satuan',
        'deskripsi',
    ];

    /**
     * Cast attributes.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'harga_satuan' => 'decimal:2',
        ];
    }

    /**
     * Relasi ke data stok produk (ROP & jumlah fisik).
     */
    public function stok(): HasOne
    {
        return $this->hasOne(StokProduk::class, 'produk_id');
    }

    /**
     * Relasi ke riwayat pemesanan yang menggunakan produk ini.
     */
    public function pemesanan(): HasMany
    {
        return $this->hasMany(Pemesanan::class, 'produk_id');
    }

    /**
     * Generate kode produk / SKU unik berikutnya secara berurutan (misal: PRD-007).
     */
    public static function generateNextKodeProduk(): string
    {
        $maxNumber = (int) self::query()
            ->pluck('kode_produk')
            ->map(function ($code) {
                if (preg_match('/(\d+)/', (string) $code, $matches)) {
                    return (int) $matches[1];
                }
                return 0;
            })
            ->max();

        $nextNumber = max($maxNumber + 1, 1);
        do {
            $candidate = sprintf('PRD-%03d', $nextNumber);
            $exists = self::query()->where('kode_produk', $candidate)->exists();
            if (! $exists) {
                return $candidate;
            }
            $nextNumber++;
        } while (true);
    }

    /**
     * Cari produk yang cocok atau buat produk baru dan stok persediaan secara otomatis
     * sehingga terintegrasi dengan kode SKU ukuran pesanan.
     */
    public static function findOrCreateForVariasi(
        string $namaProduk,
        string $warna,
        string $ukuran,
        int|float $hargaSatuan = 0
    ): self {
        $namaProduk = trim($namaProduk);
        $warna = trim($warna);
        $ukuran = trim($ukuran);

        $keyword = match (true) {
            str_contains(strtolower($namaProduk), '30s') || str_contains(strtolower($namaProduk), '30 s') => '30s',
            str_contains(strtolower($namaProduk), '24s') || str_contains(strtolower($namaProduk), '24 s') => '24s',
            str_contains(strtolower($namaProduk), 'jersey') => 'Jersey',
            str_contains(strtolower($namaProduk), 'hoodie') || str_contains(strtolower($namaProduk), 'fleece') => 'Hoodie',
            default => $namaProduk,
        };

        // 1. Cari kecocokan exact: nama/jenis_kain + warna + ukuran
        $existing = self::query()
            ->where('warna', $warna)
            ->where('ukuran_baju', $ukuran)
            ->where(function ($q) use ($namaProduk, $keyword) {
                $q->where('nama_produk', 'like', "%{$keyword}%")
                    ->orWhere('jenis_kain', 'like', "%{$keyword}%")
                    ->orWhere('nama_produk', 'like', "%{$namaProduk}%");
            })
            ->first();

        if ($existing) {
            return $existing;
        }

        // 2. Jika belum ada produk untuk variasi ukuran ini, buat produk baru dengan kode otomatis
        $kodeProdukBaru = self::generateNextKodeProduk();

        $namaLengkap = str_contains($namaProduk, 'Kaos') || str_contains($namaProduk, 'Hoodie') || str_contains($namaProduk, 'Jersey')
            ? $namaProduk
            : "Kaos {$namaProduk}";

        $produk = new self([
            'kode_produk' => $kodeProdukBaru,
            'nama_produk' => $namaLengkap,
            'jenis_kain' => $namaProduk,
            'warna' => $warna,
            'ukuran_baju' => $ukuran,
            'harga_satuan' => $hargaSatuan > 0 ? $hargaSatuan : 60000,
            'deskripsi' => "Varian {$namaLengkap} {$warna} ukuran {$ukuran} (Integrasi Pesanan SPK).",
        ]);
        $produk->save();

        // 3. Otomatis buat data stok per ukuran di persediaan produk
        $stok = new StokProduk([
            'nama_produk' => $produk->nama_produk,
            'warna' => $produk->warna,
            'ukuran' => $produk->ukuran_baju,
            'sisa_stok' => 0,
            'batas_rop' => 10,
            'status_rop' => 'Habis',
        ]);
        $produk->stok()->save($stok);

        return $produk;
    }
}

