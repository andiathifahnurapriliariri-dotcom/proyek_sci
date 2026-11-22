<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'pembayaran';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_pembayaran',
        'pemesanan_id',
        'user_id',
        'jenis_pembayaran',
        'jumlah_bayar',
        'tanggal_bayar',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status_konfirmasi',
        'catatan',
    ];

    /**
     * Cast attributes.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'jumlah_bayar' => 'decimal:2',
            'tanggal_bayar' => 'datetime',
        ];
    }

    /**
     * Relasi ke pemesanan yang dibayar.
     */
    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id');
    }

    /**
     * Relasi ke admin yang mencatat/memverifikasi pembayaran.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Lifecycle hooks untuk mencatat aktivitas secara otomatis.
     */
    protected static function booted(): void
    {
        static::created(function (Pembayaran $pembayaran) {
            try {
                $pemesanan = $pembayaran->pemesanan;
                $nomorPesanan = $pemesanan?->nomor_pemesanan ?? ('ORD-#' . $pembayaran->pemesanan_id);
                $user = auth()->user() ?? $pembayaran->admin;
                $namaUser = $user?->name ?? 'Administrator Konveksi COTTON.id';
                $jenis = $pembayaran->jenis_pembayaran ?: 'Pembayaran';

                AktivitasLog::catat(
                    aksi: 'mencatat pembayaran ' . $jenis . ' untuk pesanan',
                    referensi: $nomorPesanan,
                    tipe: 'pembayaran',
                    warna: 'emerald',
                    namaUser: $namaUser,
                    keterangan: 'Jumlah: Rp ' . number_format((float) $pembayaran->jumlah_bayar, 0, ',', '.')
                );
            } catch (\Throwable $e) {
                // Jangan batalkan transaksi jika logging error
            }
        });
    }
}

