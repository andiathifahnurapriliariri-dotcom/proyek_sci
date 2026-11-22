<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string|null $username
 * @property string $name
 * @property string $email
 * @property string $role
 * @property string|null $telepon
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['username', 'name', 'email', 'password', 'role', 'telepon', 'lokasi_workshop', 'shift', 'stasiun_kerja'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        $initials = Str::initials($this->name, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1).Str::substr($initials, -1)
            : $initials;
    }

    /**
     * Cek apakah pengguna memiliki hak akses admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah pengguna adalah operator produksi atau staf bengkel konveksi.
     */
    public function isOperatorProduksi(): bool
    {
        return in_array($this->role, [
            'operator_produksi', 'staff', 'pemotongan', 'pemotongan_kain', 'sablon', 'penjahitan', 'steam',
        ], true);
    }

    /**
     * Cek apakah pengguna adalah staf produksi.
     */
    public function isStaffProduksi(): bool
    {
        return $this->isOperatorProduksi();
    }

    /**
     * Nama departemen tempat staf bertugas.
     */
    public function getNamaDepartemenAttribute(): string
    {
        return match ($this->role) {
            'pemotongan', 'pemotongan_kain' => 'Departemen Pemotongan',
            'penjahitan' => 'Departemen Penjahitan',
            'steam' => 'Departemen Steam',
            'sablon' => 'Departemen Sablon',
            default => 'Departemen Workshop Produksi',
        };
    }

    /**
     * Judul lantai / area workshop sesuai peran departemen.
     */
    public function getJudulWorkshopAttribute(): string
    {
        return match ($this->role) {
            'pemotongan', 'pemotongan_kain' => 'Departemen Potong',
            'penjahitan' => 'Departemen Jahit',
            'steam' => 'Departemen Steam',
            'sablon' => 'Departemen Sablon',
            default => $this->lokasi_workshop ?: 'Lantai Jahit & Workshop #01',
        };
    }

    /**
     * Jabatan staf produksi sesuai peran masing-masing.
     */
    public function getJabatanAttribute(): string
    {
        return match ($this->role) {
            'pemotongan', 'pemotongan_kain' => 'Operator Pemotongan',
            'penjahitan' => 'Operator Penjahitan',
            'steam' => 'Operator Steam & Finishing',
            'sablon' => 'Operator Sablon',
            'operator_produksi', 'staff' => 'Operator Produksi',
            'admin' => 'Administrator',
            default => ucwords(str_replace('_', ' ', $this->role)),
        };
    }

    /**
     * Keterangan pembuka tugas harian sesuai departemen.
     */
    public function getKeteranganDepartemenAttribute(): string
    {
        return match ($this->role) {
            'pemotongan', 'pemotongan_kain' => 'Selamat Bekerja, Tim Pemotongan! ✂️',
            'penjahitan' => 'Selamat Bekerja, Tim Penjahitan!',
            'steam' => 'Selamat Bekerja, Tim Steam!',
            'sablon' => 'Selamat Bekerja, Tim Sablon!',
            default => 'Berikut ringkasan tugas pengerjaan pakaian di lantai workshop hari ini.',
        };
    }

    /**
     * Nama meja / area kerja spesifik departemen.
     */
    public function getNamaMejaKerjaAttribute(): string
    {
        return match ($this->role) {
            'pemotongan', 'pemotongan_kain' => 'Meja Potong Kain',
            'penjahitan' => 'Meja Mesin Jahit',
            'steam' => 'Area Steam & Finishing',
            'sablon' => 'Meja Sablon & Screen',
            default => 'Meja Potong & Jahit',
        };
    }

    /**
     * Relasi ke detail tahapan produksi yang dikerjakan oleh staf ini.
     */
    public function tahapanDikerjakan(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProduksiTahapan::class, 'user_id');
    }

    /**
     * Relasi ke notifikasi staf.
     */
    public function notifikasiStaff(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(NotifikasiStaff::class, 'user_id');
    }

    /**
     * Relasi ke pemesanan yang dikelola oleh admin ini.
     */
    public function pemesanan(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Pemesanan::class, 'user_id');
    }

    /**
     * Relasi ke pembayaran yang dicatat/diverifikasi oleh admin ini.
     */
    public function pembayaran(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Pembayaran::class, 'user_id');
    }

    /**
     * Relasi ke tugas produksi yang dipegang user/operator ini.
     */
    public function produksi(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Produksi::class, 'user_id');
    }

    /**
     * Relasi ke mutasi stok yang dicatat oleh user ini.
     */
    public function mutasiStok(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MutasiStok::class, 'user_id');
    }
}
