<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pemesanan extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'pemesanan';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nomor_pemesanan',
        'pelanggan_id',
        'user_id',
        'produk_id',
        'jenis_kain',
        'warna',
        'ukuran_baju',
        'jumlah',
        'detail_desain',
        'file_desain',
        'tanggal_pemesanan',
        'tanggal_selesai_estimasi',
        'harga_satuan',
        'total_harga',
        'status_pemesanan',
        'status_pembayaran',
        'catatan',
        'is_prioritas',
        'kesiapan_bahan',
        'lokasi_rak',
        'perlu_sablon',
        'jenis_sablon',
        'status_sablon_antrean',
        'kuota_sablon_saat_ini',
        'kuota_sablon_target',
        'kode_gambar',
        'kategori_produk',
        'pic_pelanggan',
        'instruksi_kemasan',
        'titik_sablon',
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
            'total_harga' => 'decimal:2',
            'tanggal_pemesanan' => 'date',
            'tanggal_selesai_estimasi' => 'date',
            'is_prioritas' => 'boolean',
            'perlu_sablon' => 'boolean',
            'kuota_sablon_saat_ini' => 'integer',
            'kuota_sablon_target' => 'integer',
        ];
    }

    /**
     * Relasi ke data pelanggan.
     */
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    /**
     * Relasi ke admin yang mengelola pemesanan ini.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke produk (jika memilih katalog produk).
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    /**
     * Relasi ke riwayat pembayaran (DP & Pelunasan).
     */
    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'pemesanan_id');
    }

    /**
     * Relasi ke proses monitoring produksi pesanan ini.
     */
    public function produksi(): HasOne
    {
        return $this->hasOne(Produksi::class, 'pemesanan_id');
    }

    /**
     * Relasi ke rincian variasi (kategori ukuran, warna, ukuran, jumlah pcs).
     */
    public function variasi(): HasMany
    {
        return $this->hasMany(PemesananVariasi::class, 'pemesanan_id');
    }

    /**
     * Menghitung total dana yang sudah terbayar (berstatus Diterima).
     */
    public function totalTerbayar(): float
    {
        return (float) $this->pembayaran()
            ->where('status_konfirmasi', 'Diterima')
            ->sum('jumlah_bayar');
    }

    /**
     * Menghitung sisa tagihan pembayaran.
     */
    public function sisaTagihan(): float
    {
        $sisa = (float) $this->total_harga - $this->totalTerbayar();
        return max(0, $sisa);
    }

    /**
     * Memperbarui status pembayaran secara otomatis berdasarkan riwayat pembayaran.
     */
    public function perbaruiStatusPembayaran(): void
    {
        $terbayar = $this->totalTerbayar();

        if ($terbayar >= (float) $this->total_harga && (float) $this->total_harga > 0) {
            $this->status_pembayaran = 'lunas';
        } elseif ($terbayar > 0) {
            $this->status_pembayaran = 'dp';
        } else {
            $this->status_pembayaran = 'belum_bayar';
        }

        $this->save();
    }

    /**
     * Scope untuk pemesanan yang sedang dalam proses produksi.
     */
    public function scopeDalamProduksi(Builder $query): Builder
    {
        return $query->where('status_pemesanan', 'dalam_produksi');
    }

    /**
     * Lifecycle hooks untuk mencatat aktivitas pemesanan secara otomatis.
     */
    protected static function booted(): void
    {
        static::created(function (Pemesanan $pesanan) {
            try {
                $user = auth()->user() ?? $pesanan->admin;
                $namaUser = $user?->name ?? 'Administrator Konveksi COTTON.id';

                AktivitasLog::catat(
                    aksi: 'menerbitkan SPK pesanan baru',
                    referensi: $pesanan->nomor_pemesanan,
                    tipe: 'pesanan',
                    warna: 'emerald',
                    namaUser: $namaUser,
                    keterangan: "{$pesanan->jumlah} pcs {$pesanan->jenis_kain} - Pelanggan: " . ($pesanan->pelanggan?->nama ?? 'Umum')
                );
            } catch (\Throwable $e) {
                // Ignore logging failure to prevent breaking main business flow
            }
        });

        static::updated(function (Pemesanan $pesanan) {
            try {
                if ($pesanan->wasChanged('status_pemesanan')) {
                    $user = auth()->user() ?? $pesanan->admin;
                    $namaUser = $user?->name ?? 'Administrator Konveksi COTTON.id';
                    $status = $pesanan->status_pemesanan;

                    $aksi = match ($status) {
                        'selesai' => 'menyelesaikan pengerjaan pesanan',
                        'dalam_produksi' => 'memulai proses produksi untuk pesanan',
                        'dibatalkan' => 'membatalkan pesanan',
                        default => 'mengubah status pesanan',
                    };

                    $warna = match ($status) {
                        'selesai' => 'emerald',
                        'dibatalkan' => 'rose',
                        default => 'blue',
                    };

                    AktivitasLog::catat(
                        aksi: $aksi,
                        referensi: $pesanan->nomor_pemesanan,
                        tipe: 'pesanan',
                        warna: $warna,
                        namaUser: $namaUser,
                        keterangan: "Status baru: " . ucwords(str_replace('_', ' ', $status))
                    );
                }
            } catch (\Throwable $e) {
                // Ignore logging failure
            }
        });
    }
}

