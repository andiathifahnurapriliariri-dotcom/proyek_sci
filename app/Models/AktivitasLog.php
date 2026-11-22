<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AktivitasLog extends Model
{
    use HasFactory;

    protected $table = 'aktivitas_log';

    protected $fillable = [
        'user_id',
        'nama_user',
        'tipe',
        'aksi',
        'referensi',
        'warna',
        'keterangan',
    ];

    /**
     * Relasi ke pengguna (admin/operator).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Catat aktivitas baru ke log database secara real-time.
     */
    public static function catat(
        string $aksi,
        ?string $referensi = null,
        string $tipe = 'pesanan',
        string $warna = 'emerald',
        ?string $namaUser = null,
        ?string $keterangan = null
    ): self {
        $user = auth()->user();
        $finalNamaUser = $namaUser ?? ($user?->name ?? 'Administrator Konveksi COTTON.id');

        return self::create([
            'user_id'    => $user?->id,
            'nama_user'  => $finalNamaUser,
            'tipe'       => $tipe,
            'aksi'       => $aksi,
            'referensi'  => $referensi,
            'warna'      => $warna,
            'keterangan' => $keterangan,
        ]);
    }
}

