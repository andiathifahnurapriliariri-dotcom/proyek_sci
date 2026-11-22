<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotifikasiAdmin extends Model
{
    use HasFactory;

    protected $table = 'notifikasi_admin';

    protected $fillable = [
        'user_id',
        'kategori',
        'judul',
        'pesan',
        'link',
        'referensi_tipe',
        'referensi_id',
        'event_key',
        'dibaca',
        'dibaca_pada',
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

    public function scopeBelumDibaca(Builder $query): Builder
    {
        return $query->where('dibaca', false);
    }

    public function scopeDibaca(Builder $query): Builder
    {
        return $query->where('dibaca', true);
    }

    public function scopeKategori(Builder $query, string $kategori): Builder
    {
        return $query->where('kategori', $kategori);
    }

    public function tandaiDibaca(): void
    {
        if (! $this->dibaca) {
            $this->update([
                'dibaca' => true,
                'dibaca_pada' => now(),
            ]);
        }
    }

    public static function hitungBelumDibaca(): int
    {
        return self::where('dibaca', false)->count();
    }

    /**
     * Catat notifikasi admin baru dengan pencegahan duplikasi melalui event_key.
     */
    public static function catatNotifikasi(
        string $kategori,
        string $judul,
        string $pesan,
        ?string $link = null,
        ?string $eventKey = null,
        ?string $refTipe = null,
        ?int $refId = null
    ): ?self {
        if ($eventKey) {
            return self::firstOrCreate(
                ['event_key' => $eventKey],
                [
                    'kategori' => $kategori,
                    'judul' => $judul,
                    'pesan' => $pesan,
                    'link' => $link,
                    'referensi_tipe' => $refTipe,
                    'referensi_id' => $refId,
                    'dibaca' => false,
                ]
            );
        }

        return self::create([
            'kategori' => $kategori,
            'judul' => $judul,
            'pesan' => $pesan,
            'link' => $link,
            'referensi_tipe' => $refTipe,
            'referensi_id' => $refId,
            'dibaca' => false,
        ]);
    }

    /**
     * Sinkronkan event sistem aktual ke notifikasi admin tanpa menduplikasi data.
     */
    public static function sinkronkanDariDataSistem(): void
    {
        // 1. Pesanan Baru & Selesai
        $semuaPesanan = Pemesanan::with('pelanggan')->latest('id')->limit(20)->get();
        foreach ($semuaPesanan as $pesanan) {
            // Notifikasi Pesanan Masuk / Aktif
            self::firstOrCreate(
                ['event_key' => 'pesanan_masuk_' . $pesanan->id],
                [
                    'kategori' => 'pesanan',
                    'judul' => 'Pesanan Baru Masuk #' . $pesanan->nomor_pemesanan,
                    'pesan' => 'Pesanan dari ' . ($pesanan->pelanggan?->nama ?? 'Pelanggan Umum') . ' untuk ' . $pesanan->jumlah . ' pcs ' . ($pesanan->jenis_kain ?? 'pakaian konveksi') . ' terdaftar dengan status ' . str_replace('_', ' ', $pesanan->status_pemesanan) . '.',
                    'link' => route('admin.pesanan.index', ['search' => $pesanan->nomor_pemesanan, 'detail' => $pesanan->id]),
                    'referensi_tipe' => Pemesanan::class,
                    'referensi_id' => $pesanan->id,
                    'dibaca' => false,
                    'created_at' => $pesanan->created_at ?? now(),
                ]
            );

            // Notifikasi Pesanan Selesai / Siap Ambil
            if (in_array($pesanan->status_pemesanan, ['selesai', 'siap_diambil'], true)) {
                self::firstOrCreate(
                    ['event_key' => 'pesanan_selesai_' . $pesanan->id],
                    [
                        'kategori' => 'selesai',
                        'judul' => 'Pesanan Selesai Produksi #' . $pesanan->nomor_pemesanan,
                        'pesan' => 'Pesanan #' . $pesanan->nomor_pemesanan . ' atas nama ' . ($pesanan->pelanggan?->nama ?? 'Pelanggan Umum') . ' telah selesai diproduksi dan siap diserahkan kepada pelanggan.',
                        'link' => route('admin.pesanan.index', ['search' => $pesanan->nomor_pemesanan, 'detail' => $pesanan->id]),
                        'referensi_tipe' => Pemesanan::class,
                        'referensi_id' => $pesanan->id,
                        'dibaca' => false,
                        'created_at' => $pesanan->updated_at ?? now(),
                    ]
                );
            }
        }

        // 2. Pembayaran Masuk / Berubah Status
        $semuaPembayaran = Pembayaran::with(['pemesanan.pelanggan'])->latest('id')->limit(20)->get();
        foreach ($semuaPembayaran as $pembayaran) {
            $nomorSpk = $pembayaran->pemesanan?->nomor_pemesanan ?? 'SPK';
            $namaPel = $pembayaran->pemesanan?->pelanggan?->nama ?? 'Pelanggan';
            $nominal = 'Rp ' . number_format($pembayaran->jumlah_bayar, 0, ',', '.');
            $statusLabel = ucwords(str_replace('_', ' ', $pembayaran->status_pembayaran));

            self::firstOrCreate(
                ['event_key' => 'pembayaran_' . $pembayaran->id . '_' . $pembayaran->status_pembayaran],
                [
                    'kategori' => 'pembayaran',
                    'judul' => 'Pembayaran ' . $statusLabel . ': ' . ($pembayaran->nomor_pembayaran ?: 'INV-' . $pembayaran->id),
                    'pesan' => 'Pembayaran ' . $nominal . ' untuk pesanan ' . $nomorSpk . ' (' . $namaPel . ') tercatat dengan status ' . $statusLabel . '.',
                    'link' => route('admin.pembayaran.index', ['search' => $nomorSpk]),
                    'referensi_tipe' => Pembayaran::class,
                    'referensi_id' => $pembayaran->id,
                    'dibaca' => false,
                    'created_at' => $pembayaran->created_at ?? now(),
                ]
            );
        }

        // 3. Progres / Status Produksi
        $semuaProduksi = Produksi::with(['pemesanan.pelanggan'])->latest('id')->limit(20)->get();
        foreach ($semuaProduksi as $produksi) {
            $nomorSpk = $produksi->pemesanan?->nomor_pemesanan ?? 'SPK';
            $namaPel = $produksi->pemesanan?->pelanggan?->nama ?? 'Pelanggan';
            $tahapLabel = ucwords(str_replace('_', ' ', $produksi->tahap_saat_ini ?: 'Antrean'));

            self::firstOrCreate(
                ['event_key' => 'produksi_' . $produksi->id . '_' . $produksi->tahap_saat_ini . '_' . $produksi->progress_persen],
                [
                    'kategori' => 'produksi',
                    'judul' => 'Progres Produksi: ' . $produksi->kode_produksi . ' (' . $nomorSpk . ')',
                    'pesan' => 'Pengerjaan ' . $nomorSpk . ' (' . $namaPel . ') saat ini di tahap ' . $tahapLabel . ' dengan progres ' . $produksi->progress_persen . '%.',
                    'link' => route('admin.produksi.index', ['search' => $nomorSpk]),
                    'referensi_tipe' => Produksi::class,
                    'referensi_id' => $produksi->id,
                    'dibaca' => false,
                    'created_at' => $produksi->updated_at ?? now(),
                ]
            );
        }

        // 4. Peringatan Stok ROP
        $stokRopKritis = StokProduk::with('produk')
            ->where(function ($q) {
                $q->whereColumn('sisa_stok', '<=', 'batas_rop')
                  ->orWhere('status_rop', 'menipis')
                  ->orWhere('status_rop', 'habis');
            })
            ->get();

        foreach ($stokRopKritis as $stok) {
            $sku = $stok->produk?->kode_produk ?? 'SKU-' . $stok->id;
            $sisaText = $stok->sisa_stok <= 0 ? 'HABIS (0 pcs)' : 'tersisa ' . $stok->sisa_stok . ' pcs';

            self::firstOrCreate(
                ['event_key' => 'stok_rop_' . $stok->id . '_' . $stok->sisa_stok],
                [
                    'kategori' => 'stok_rop',
                    'judul' => 'Peringatan Batas ROP: ' . $stok->nama_produk . ' (' . $stok->ukuran . ')',
                    'pesan' => 'Stok pakaian ' . $stok->nama_produk . ' warna ' . $stok->warna . ' ukuran ' . $stok->ukuran . ' (' . $sku . ') ' . $sisaText . '. Batas aman ROP: ' . $stok->batas_rop . ' pcs. Segera jadwalkan produksi ulang.',
                    'link' => route('admin.persediaan.index', ['tab' => 'persediaan', 'search' => $sku]),
                    'referensi_tipe' => StokProduk::class,
                    'referensi_id' => $stok->id,
                    'dibaca' => false,
                    'created_at' => $stok->updated_at ?? now(),
                ]
            );
        }
    }
}
