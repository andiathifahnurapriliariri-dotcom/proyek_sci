<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotifikasiStaff extends Model
{
    use HasFactory;

    protected $table = 'notifikasi_staff';

    protected $fillable = [
        'user_id',
        'pemesanan_id',
        'judul',
        'pesan',
        'tipe',
        'dibaca',
        'dibaca_pada',
        'link',
    ];

    protected function casts(): array
    {
        return [
            'dibaca' => 'boolean',
            'dibaca_pada' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id');
    }

    public function scopeBelumDibaca(Builder $query): Builder
    {
        return $query->where('dibaca', false);
    }

    public function scopeUntukUser(Builder $query, ?int $userId = null): Builder
    {
        $id = $userId ?? auth()->id();
        return $query->where(function ($q) use ($id) {
            $q->whereNull('user_id')->orWhere('user_id', $id);
        });
    }

    public function tandaiDibaca(): void
    {
        $this->update([
            'dibaca' => true,
            'dibaca_pada' => now(),
        ]);
    }

    public static function buatNotifikasi(
        string $judul,
        string $pesan,
        string $tipe = 'tugas_baru',
        ?int $userId = null,
        ?int $pemesananId = null,
        ?string $link = null
    ): self {
        return self::create([
            'user_id' => $userId,
            'pemesanan_id' => $pemesananId,
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'dibaca' => false,
            'link' => $link,
        ]);
    }
}
