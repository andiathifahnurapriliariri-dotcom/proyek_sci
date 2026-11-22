<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Produksi extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'produksi';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_produksi',
        'pemesanan_id',
        'user_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'status_pemotongan_kain',
        'tgl_pemotongan_kain',
        'catatan_pemotongan_kain',
        'status_sablon',
        'tgl_sablon',
        'catatan_sablon',
        'status_penjahitan',
        'tgl_penjahitan',
        'catatan_penjahitan',
        'status_steam',
        'tgl_steam',
        'catatan_steam',
        'tahap_saat_ini',
        'progress_persen',
        'status_produksi',
        'catatan_produksi',
    ];

    /**
     * Cast attributes.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'tgl_pemotongan_kain' => 'datetime',
            'tgl_sablon' => 'datetime',
            'tgl_penjahitan' => 'datetime',
            'tgl_steam' => 'datetime',
            'progress_persen' => 'integer',
        ];
    }

    /**
     * Relasi ke data pemesanan yang sedang diproduksi.
     */
    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id');
    }

    /**
     * Relasi ke operator / penanggung jawab produksi.
     */
    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke detail 7 tahapan pengerjaan produksi konveksi.
     */
    public function tahapan(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProduksiTahapan::class, 'produksi_id')->orderBy('urutan');
    }

    /**
     * Memastikan ke-4 tahapan utama produksi terinisialisasi:
     * 1. Pemotongan Kain -> 2. Penjahitan -> 3. Steam -> 4. Sablon (jika ada) -> Selesai
     */
    public function inisialisasiTahapan(?bool $perluSablon = null): void
    {
        if ($this->tahapan()->exists()) {
            return;
        }

        $perlu = $perluSablon ?? ($this->pemesanan?->perlu_sablon ?? true);

        // Alur Workshop Konveksi COTTON.id:
        // 1. Pemotongan -> 2. Penjahitan -> 3. Steam -> 4. Sablon (jika ada) -> Selesai
        $daftar = [
            1 => ['nama' => 'pemotongan_kain', 'label' => 'Pemotongan Kain', 'req' => true],
            2 => ['nama' => 'penjahitan', 'label' => 'Penjahitan', 'req' => true],
            3 => ['nama' => 'steam', 'label' => 'Steam & Penyetrikaan', 'req' => true],
            4 => ['nama' => 'sablon', 'label' => 'Sablon & Desain', 'req' => $perlu],
        ];

        $prevSelesai = true;
        foreach ($daftar as $urutan => $item) {
            $status = 'menunggu';

            if ($item['nama'] === 'pemotongan_kain') {
                if ($this->status_pemotongan_kain === 'selesai') {
                    $status = 'selesai';
                } elseif ($this->status_pemotongan_kain === 'sedang_diproses') {
                    $status = 'sedang_dikerjakan';
                    $prevSelesai = false;
                } else {
                    $status = 'siap_dikerjakan';
                    $prevSelesai = false;
                }
            } elseif ($item['nama'] === 'penjahitan') {
                if ($this->status_penjahitan === 'selesai') {
                    $status = 'selesai';
                } elseif ($this->status_penjahitan === 'sedang_diproses') {
                    $status = 'sedang_dikerjakan';
                    $prevSelesai = false;
                } elseif ($prevSelesai) {
                    $status = 'siap_dikerjakan';
                    $prevSelesai = false;
                }
            } elseif ($item['nama'] === 'steam') {
                if ($this->status_steam === 'selesai') {
                    $status = 'selesai';
                } elseif ($this->status_steam === 'sedang_diproses') {
                    $status = 'sedang_dikerjakan';
                    $prevSelesai = false;
                } elseif ($prevSelesai) {
                    $status = 'siap_dikerjakan';
                    $prevSelesai = false;
                }
            } elseif ($item['nama'] === 'sablon') {
                if (! $item['req']) {
                    $status = 'selesai';
                } elseif ($this->status_sablon === 'selesai') {
                    $status = 'selesai';
                } elseif ($this->status_sablon === 'sedang_diproses') {
                    $status = 'sedang_dikerjakan';
                    $prevSelesai = false;
                } elseif ($prevSelesai) {
                    $status = 'siap_dikerjakan';
                    $prevSelesai = false;
                }
            }

            ProduksiTahapan::create([
                'produksi_id' => $this->id,
                'nama_tahap' => $item['nama'],
                'label_tahap' => $item['label'],
                'urutan' => $urutan,
                'is_required' => $item['req'],
                'status' => $status,
                'user_id' => $this->user_id,
                'waktu_mulai' => $status !== 'menunggu' ? $this->created_at : null,
                'waktu_selesai' => $status === 'selesai' ? ($this->tanggal_selesai ?? now()) : null,
            ]);
        }
    }

    /**
     * Menghitung kemajuan tahapan produksi konveksi (0% - 100%).
     * 4 Tahapan Utama:
     * 1. Pemotongan Kain (25%)
     * 2. Sablon (25%)
     * 3. Penjahitan (25%)
     * 4. Steam / Finishing (25%)
     */
    public function hitungProgress(): int
    {
        $persen = 0;

        if ($this->status_pemotongan_kain === 'selesai') {
            $persen += 25;
        } elseif ($this->status_pemotongan_kain === 'sedang_diproses') {
            $persen += 10;
        }

        if ($this->status_sablon === 'selesai') {
            $persen += 25;
        } elseif ($this->status_sablon === 'sedang_diproses') {
            $persen += 10;
        }

        if ($this->status_penjahitan === 'selesai') {
            $persen += 25;
        } elseif ($this->status_penjahitan === 'sedang_diproses') {
            $persen += 10;
        }

        if ($this->status_steam === 'selesai') {
            $persen += 25;
        } elseif ($this->status_steam === 'sedang_diproses') {
            $persen += 10;
        }

        return min(100, $persen);
    }

    /**
     * Memperbarui progress persen dan status tahapan saat ini.
     * Alur: 1. Pemotongan -> 2. Penjahitan -> 3. Steam -> 4. Sablon (jika ada) -> Selesai
     */
    public function perbaruiProgressDanTahap(): void
    {
        $this->progress_persen = $this->hitungProgress();
        $perluSablon = $this->pemesanan?->perlu_sablon ?? true;

        if ($perluSablon) {
            if ($this->status_sablon === 'selesai') {
                $this->tahap_saat_ini = 'selesai';
                $this->status_produksi = 'selesai';
                $this->tanggal_selesai = now()->toDateString();
            } elseif ($this->status_steam === 'selesai' || $this->status_sablon === 'sedang_diproses') {
                $this->tahap_saat_ini = 'sablon';
                $this->status_produksi = 'proses';
            } elseif ($this->status_penjahitan === 'selesai' || $this->status_steam === 'sedang_diproses') {
                $this->tahap_saat_ini = 'steam';
                $this->status_produksi = 'proses';
            } elseif ($this->status_pemotongan_kain === 'selesai' || $this->status_penjahitan === 'sedang_diproses') {
                $this->tahap_saat_ini = 'penjahitan';
                $this->status_produksi = 'proses';
            } else {
                $this->tahap_saat_ini = 'pemotongan_kain';
                $this->status_produksi = $this->status_pemotongan_kain === 'sedang_diproses' ? 'proses' : 'antrian';
            }
        } else {
            // Tanpa sablon: Pemotongan -> Penjahitan -> Steam -> Selesai
            if ($this->status_steam === 'selesai') {
                $this->tahap_saat_ini = 'selesai';
                $this->status_produksi = 'selesai';
                $this->tanggal_selesai = now()->toDateString();
            } elseif ($this->status_penjahitan === 'selesai' || $this->status_steam === 'sedang_diproses') {
                $this->tahap_saat_ini = 'steam';
                $this->status_produksi = 'proses';
            } elseif ($this->status_pemotongan_kain === 'selesai' || $this->status_penjahitan === 'sedang_diproses') {
                $this->tahap_saat_ini = 'penjahitan';
                $this->status_produksi = 'proses';
            } else {
                $this->tahap_saat_ini = 'pemotongan_kain';
                $this->status_produksi = $this->status_pemotongan_kain === 'sedang_diproses' ? 'proses' : 'antrian';
            }
        }

        $this->save();

        if ($this->status_produksi === 'selesai') {
            $pemesanan = $this->pemesanan;
            if ($pemesanan && $pemesanan->status_pemesanan !== 'selesai') {
                $pemesanan->update(['status_pemesanan' => 'selesai_produksi']);
            }
        }
    }

    /**
     * Scope untuk produksi yang sedang berjalan (proses).
     */
    public function scopeSedangProses(Builder $query): Builder
    {
        return $query->where('status_produksi', 'proses');
    }

    /**
     * Scope untuk produksi yang telah selesai.
     */
    public function scopeSelesai(Builder $query): Builder
    {
        return $query->where('status_produksi', 'selesai');
    }

    /**
     * Lifecycle hooks untuk mencatat aktivitas produksi secara otomatis.
     */
    protected static function booted(): void
    {
        static::updated(function (Produksi $produksi) {
            try {
                if ($produksi->wasChanged(['tahap_saat_ini', 'progress_persen', 'status_produksi', 'catatan_produksi'])) {
                    $pemesanan = $produksi->pemesanan;
                    $nomorPesanan = $pemesanan?->nomor_pemesanan ?? ('ID #' . $produksi->pemesanan_id);
                    $user = auth()->user() ?? $produksi->operator;
                    $namaUser = $user?->name ?? 'Administrator Konveksi COTTON.id';

                    $tahapLabel = ucwords(str_replace('_', ' ', $produksi->tahap_saat_ini ?? ''));
                    $catatan = $produksi->catatan_produksi ?: 'Tahap ' . $tahapLabel;

                    AktivitasLog::catat(
                        aksi: 'memperbarui progres pengerjaan pesanan',
                        referensi: $nomorPesanan,
                        tipe: 'produksi',
                        warna: 'blue',
                        namaUser: $namaUser,
                        keterangan: "Tahap {$tahapLabel} ({$produksi->progress_persen}%) - {$catatan}"
                    );
                }
            } catch (\Throwable $e) {
                // Jangan batalkan transaksi jika logging error
            }
        });
    }
}

