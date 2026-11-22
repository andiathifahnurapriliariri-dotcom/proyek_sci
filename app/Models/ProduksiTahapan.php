<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProduksiTahapan extends Model
{
    use HasFactory;

    protected $table = 'produksi_tahapan';

    protected $fillable = [
        'produksi_id',
        'nama_tahap',
        'label_tahap',
        'urutan',
        'is_required',
        'status',
        'user_id',
        'waktu_mulai',
        'waktu_selesai',
        'catatan_pengerjaan',
        'catatan_penundaan',
    ];

    protected function casts(): array
    {
        return [
            'urutan' => 'integer',
            'is_required' => 'boolean',
            'waktu_mulai' => 'datetime',
            'waktu_selesai' => 'datetime',
        ];
    }

    public const TAHAPAN_DEFAULT = [
        1 => ['nama' => 'pemotongan_kain', 'label' => 'Pemotongan Kain', 'is_required' => true],
        2 => ['nama' => 'penjahitan', 'label' => 'Penjahitan', 'is_required' => true],
        3 => ['nama' => 'steam', 'label' => 'Steam & Penyetrikaan', 'is_required' => true],
        4 => ['nama' => 'sablon', 'label' => 'Sablon & Desain', 'is_required' => true],
    ];

    public function produksi(): BelongsTo
    {
        return $this->belongsTo(Produksi::class, 'produksi_id');
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Hitung durasi pengerjaan dalam format yang ramah dibaca (e.g. "1 jam 25 menit").
     */
    public function getDurasiLabelAttribute(): string
    {
        if (! $this->waktu_mulai) {
            return '-';
        }

        $akhir = $this->waktu_selesai ?? now();
        $totalMenit = (int) $this->waktu_mulai->diffInMinutes($akhir);

        if ($totalMenit < 60) {
            return "{$totalMenit} menit";
        }

        $jam = intdiv($totalMenit, 60);
        $sisaMenit = $totalMenit % 60;

        return $sisaMenit > 0 ? "{$jam} jam {$sisaMenit} mnt" : "{$jam} jam";
    }

    /**
     * Memulai tahap pengerjaan.
     */
    public function mulai(?User $user = null, ?string $catatan = null): void
    {
        $this->status = 'sedang_dikerjakan';
        $this->waktu_mulai = now();
        $this->user_id = $user?->id ?? auth()->id();
        if ($catatan) {
            $this->catatan_pengerjaan = $catatan;
        }
        $this->save();

        $this->sinkronkanKeProduksi();
    }

    /**
     * Menyelesaikan tahap pengerjaan dan mengaktifkan tahap berikutnya jika ada.
     */
    public function selesaikan(?User $user = null, ?string $catatan = null): void
    {
        $this->status = 'selesai';
        $this->waktu_selesai = now();
        if (! $this->waktu_mulai) {
            $this->waktu_mulai = now()->subMinutes(15);
        }
        $this->user_id = $user?->id ?? auth()->id() ?? $this->user_id;
        if ($catatan) {
            $this->catatan_pengerjaan = $catatan;
        }
        $this->save();

        // Aktifkan tahap berikutnya yang masih berstatus menunggu
        $tahapBerikutnya = ProduksiTahapan::where('produksi_id', $this->produksi_id)
            ->where('urutan', '>', $this->urutan)
            ->where('is_required', true)
            ->where('status', 'menunggu')
            ->orderBy('urutan')
            ->first();

        if ($tahapBerikutnya) {
            $tahapBerikutnya->update(['status' => 'siap_dikerjakan']);
        }

        $this->sinkronkanKeProduksi();
    }

    /**
     * Menandai penundaan tahap dengan catatan alasan.
     */
    public function tunda(string $alasan, ?User $user = null): void
    {
        $this->status = 'tertunda';
        $this->catatan_penundaan = $alasan;
        $this->user_id = $user?->id ?? auth()->id() ?? $this->user_id;
        $this->save();

        $this->sinkronkanKeProduksi();
    }

    /**
     * Sinkronisasi progres & status ke tabel produksi induk agar Admin Dashboard & Pemantauan sinkron.
     */
    public function sinkronkanKeProduksi(): void
    {
        $produksi = $this->produksi;
        if (! $produksi) {
            return;
        }

        $semuaTahap = ProduksiTahapan::where('produksi_id', $produksi->id)
            ->where('is_required', true)
            ->orderBy('urutan')
            ->get();

        $totalTahap = $semuaTahap->count();
        if ($totalTahap === 0) {
            return;
        }

        $selesaiCount = $semuaTahap->where('status', 'selesai')->count();
        $sedangCount = $semuaTahap->where('status', 'sedang_dikerjakan')->count();

        // Progres dihitung proporsional dari 7 tahapan
        $persenPerTahap = 100 / $totalTahap;
        $progres = (int) round(($selesaiCount * $persenPerTahap) + ($sedangCount * ($persenPerTahap * 0.4)));
        $progres = min(100, max(0, $progres));

        $tahapPotong = $semuaTahap->firstWhere('nama_tahap', 'pemotongan_kain');
        $tahapSablon = $semuaTahap->firstWhere('nama_tahap', 'sablon');
        $tahapJahit = $semuaTahap->firstWhere('nama_tahap', 'penjahitan');
        $tahapSteam = $semuaTahap->firstWhere('nama_tahap', 'steam');
        $tahapPacking = $semuaTahap->firstWhere('nama_tahap', 'packing');

        // Map status ke enum produksi lama: 'belum_mulai', 'sedang_diproses', 'selesai'
        $mapStatus = fn (?ProduksiTahapan $t) => match ($t?->status) {
            'selesai' => 'selesai',
            'sedang_dikerjakan' => 'sedang_diproses',
            default => 'belum_mulai',
        };

        $produksi->status_pemotongan_kain = $mapStatus($tahapPotong);
        $produksi->status_sablon = $mapStatus($tahapSablon);
        $produksi->status_penjahitan = $mapStatus($tahapJahit);
        $produksi->status_steam = $mapStatus($tahapSteam);
        $produksi->progress_persen = $progres;

        // Tentukan tahap_saat_ini untuk Admin
        $tahapAktif = $semuaTahap->first(fn ($t) => in_array($t->status, ['sedang_dikerjakan', 'siap_dikerjakan', 'tertunda']))
            ?? $semuaTahap->first(fn ($t) => $t->status === 'menunggu');

        if ($selesaiCount === $totalTahap) {
            $produksi->tahap_saat_ini = 'selesai';
            $produksi->status_produksi = 'selesai';
            $produksi->tanggal_selesai = now()->toDateString();
            $produksi->save();

            // Perbarui status pemesanan
            $pemesanan = $produksi->pemesanan;
            if ($pemesanan && $pemesanan->status_pemesanan !== 'selesai') {
                $pemesanan->update(['status_pemesanan' => 'selesai_produksi']);
            }
        } else {
            $produksi->status_produksi = ($sedangCount > 0 || $selesaiCount > 0) ? 'proses' : 'antrian';
            
            // Map tahap aktif ke enum tahap_saat_ini admin: 'pemotongan_kain', 'sablon', 'penjahitan', 'steam', 'selesai'
            $adminTahap = match ($tahapAktif?->nama_tahap) {
                'pemotongan_kain' => 'pemotongan_kain',
                'sablon' => 'sablon',
                'penjahitan' => 'penjahitan',
                'pembersihan_benang', 'steam', 'pelipatan', 'packing' => 'steam',
                default => 'pemotongan_kain',
            };
            $produksi->tahap_saat_ini = $adminTahap;
            $produksi->save();

            $pemesanan = $produksi->pemesanan;
            if ($pemesanan && $pemesanan->status_pemesanan === 'antrian_produksi' && $produksi->status_produksi === 'proses') {
                $pemesanan->update(['status_pemesanan' => 'dalam_produksi']);
            }
        }
    }
}
