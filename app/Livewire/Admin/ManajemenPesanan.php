<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Traits\WithSpkForm;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use App\Models\PemesananVariasi;
use App\Models\Produk;
use App\Models\Produksi;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

#[Layout('layouts.admin')]
class ManajemenPesanan extends Component
{
    use WithFileUploads, WithPagination, WithSpkForm;

    private const JENIS_PAKAIAN = ['Kaos 30s', 'Kaos 24s', 'Jersey', 'Hoodie', 'Kaos Polos Katun Combed 30s', 'Kaos Katun Combed 24s'];

    // Filter & Search
    public string $search = '';

    public string $tabStatus = 'semua'; // 'semua', 'perlu_konfirmasi', 'sedang_dibuat', 'siap_kirim', 'selesai'

    public string $filterPakaian = 'semua';

    public string $urutan = 'tenggat_terdekat'; // 'tenggat_terdekat', 'terbaru', 'terlama', 'jumlah_terbanyak'

    public int $perPage = 5;

    // Modals Manajemen
    public bool $showModalDetail = false;

    public bool $showModalUpdate = false;

    public ?Pemesanan $selectedPesanan = null;

    // Form Update Status
    public ?int $updatePesananId = null;

    public string $newStatusPemesanan = '';

    public string $newStatusPembayaran = '';

    public string $newTahapProduksi = '';

    public string $newCatatanWorkshop = '';

    public int|string|null $newProgressPersen = 0;

    // Notifikasi
    public string $pesanSukses = '';

    public function mount(): void
    {
        $this->resetForm();
        $this->tanggal_deadline = '';

        if (request()->filled('search')) {
            $this->search = (string) request()->query('search');
        }

        if (request()->filled('detail')) {
            $detailId = request()->integer('detail');
            if ($detailId > 0) {
                $this->lihatDetail($detailId);
            }
        }

        $produkId = request()->integer('produk_id');
        $produk = $produkId > 0 ? Produk::query()->find($produkId) : null;

        if (! $produk) {
            return;
        }

        $jumlah = request()->integer('jumlah_pcs');
        if ($jumlah < 1 || $jumlah > 100000) {
            return;
        }

        $jenisKain = Str::lower($produk->jenis_kain);
        $this->jenis_pakaian = match (true) {
            Str::contains($jenisKain, 'jersey') => 'Jersey',
            Str::contains($jenisKain, ['hoodie', 'fleece']) => 'Hoodie',
            Str::contains($jenisKain, ['24s', '24 s']) => 'Kaos 24s',
            default => 'Kaos 30s',
        };
        $this->warna = $produk->warna;
        $this->ukuran_baju = $produk->ukuran_baju.' '.$jumlah;
        $this->jumlah_pcs = $jumlah;
        $this->harga_satuan = (int) $produk->harga_satuan;
        $this->catatan_tambahan = 'Jadwal produksi ulang stok SKU '.$produk->kode_produk;
        $kategori = (in_array($produk->ukuran_baju, ['2', '4', '6', '8', '10', '12', '14'], true) || Str::contains($produk->ukuran_baju, 'Anak', true))
            ? 'Anak-anak'
            : 'Dewasa';
        $this->variasi = [
            [
                'produk' => $this->jenis_pakaian,
                'kategori' => $kategori,
                'warna' => $this->warna ?: 'Hitam Pekat',
                'ukuran' => $produk->ukuran_baju ?: 'L',
                'jumlah' => $jumlah,
            ],
        ];
        $this->hitungTotalDanHarga();
        $this->showModalCreate = true;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingTabStatus(): void
    {
        $this->resetPage();
    }

    public function updatingFilterPakaian(): void
    {
        $this->resetPage();
    }

    public function setTab(string $tab): void
    {
        $this->tabStatus = $tab;
        $this->resetPage();
    }

        public function unduhDesain(int $pesananId): StreamedResponse
    {
        $pesanan = Pemesanan::query()->findOrFail($pesananId);

        abort_unless(filled($pesanan->file_desain), 404);

        return Storage::disk('local')->download($pesanan->file_desain);
    }

    /**
     * Buka Modal Detail SPK.
     */
    public function lihatDetail(int $id): void
    {
        $this->selectedPesanan = Pemesanan::with(['pelanggan', 'produksi.operator', 'pembayaran', 'admin', 'variasi'])->find($id);
        if ($this->selectedPesanan) {
            $this->showModalDetail = true;
        }
    }

    public function tutupModalDetail(): void
    {
        $this->showModalDetail = false;
        $this->selectedPesanan = null;
    }

    /**
     * Buka Modal Update Status Cepat.
     */
    public function bukaModalUpdate(int $id): void
    {
        $pesanan = Pemesanan::with('produksi')->find($id);
        if ($pesanan) {
            $this->updatePesananId = $pesanan->id;
            $this->newStatusPemesanan = $pesanan->status_pemesanan;
            $this->newStatusPembayaran = $pesanan->status_pembayaran;
            $this->newTahapProduksi = $pesanan->produksi?->tahap_saat_ini ?? 'pemotongan_kain';
            $this->newCatatanWorkshop = $pesanan->produksi?->catatan_produksi ?? 'Meja Sablon B';
            $this->newProgressPersen = $pesanan->produksi?->progress_persen ?? 20;
            $this->showModalUpdate = true;
        }
    }

    public function tutupModalUpdate(): void
    {
        $this->showModalUpdate = false;
        $this->updatePesananId = null;
    }

    /**
     * Simpan Update Status.
     */
    public function simpanUpdateStatus(): void
    {
        if (! $this->updatePesananId) {
            return;
        }

        $pesanan = Pemesanan::with('produksi')->find($this->updatePesananId);
        if ($pesanan) {
            $pesanan->status_pemesanan = $this->newStatusPemesanan;
            $pesanan->status_pembayaran = $this->newStatusPembayaran;
            $pesanan->save();

            if ($pesanan->produksi) {
                $pesanan->produksi->tahap_saat_ini = $this->newTahapProduksi;
                $pesanan->produksi->catatan_produksi = $this->newCatatanWorkshop;
                $pesanan->produksi->progress_persen = $this->newProgressPersen;
                if ($this->newStatusPemesanan === 'selesai' || $this->newTahapProduksi === 'selesai') {
                    $pesanan->produksi->status_produksi = 'selesai';
                    $pesanan->produksi->progress_persen = 100;
                    $pesanan->produksi->tanggal_selesai = now()->toDateString();
                }
                $pesanan->produksi->save();
            }

            $this->showModalUpdate = false;
            $this->pesanSukses = "Status Pesanan {$pesanan->nomor_pemesanan} berhasil diperbarui!";
        }
    }

    /**
     * Aksi Cetak Rekap Pesanan.
     */
    public function cetakRekap(): void
    {
        $this->dispatch('cetak-dokumen');
        $this->pesanSukses = 'Mempersiapkan dokumen rekapitulasi SPK untuk dicetak/unduh...';
    }

    /**
     * Hitung Data Langkah Ringkasan (5 Kartu di Gambar 3).
     */
    protected function getLangkahRingkasan(): array
    {
        // 1. Semua Pesanan Berjalan (Aktif)
        $totalAktif = Pemesanan::whereNotIn('status_pemesanan', ['selesai', 'dibatalkan'])->count();

        // 2. Tahap 1: Menunggu Konfirmasi & DP
        $konfirmasi = Pemesanan::where(function ($q) {
            $q->whereIn('status_pemesanan', ['menunggu_pembayaran'])
                ->orWhere('status_pembayaran', 'belum_bayar');
        })->whereNotIn('status_pemesanan', ['selesai', 'dibatalkan'])->count();

        // 3. Tahap 2: Kain Siap & Masuk Antrean
        $kainSiap = Pemesanan::where('status_pemesanan', 'antrian_produksi')->count();

        // 4. Tahap 3: Sedang Dikerjakan Pabrik (INTI)
        $sedangPabrik = Pemesanan::where('status_pemesanan', 'dalam_produksi')->count();

        // 5. Tahap 4: Selesai / Siap Ambil
        $siapKirim = Pemesanan::whereIn('status_pemesanan', ['siap_diambil', 'selesai_produksi', 'selesai'])->count();

        return [
            'total_aktif'   => $totalAktif,
            'konfirmasi'    => $konfirmasi,
            'kain_siap'     => $kainSiap,
            'sedang_pabrik' => $sedangPabrik,
            'siap_kirim'    => $siapKirim,
            'total_selesai' => Pemesanan::where('status_pemesanan', 'selesai')->count(),
        ];
    }

    public function render(): View
    {
        $ringkasan = $this->getLangkahRingkasan();

        $query = Pemesanan::with(['pelanggan', 'produksi', 'pembayaran']);

        // Filter Tab Cepat
        if ($this->tabStatus === 'perlu_konfirmasi') {
            $query->where(function ($q) {
                $q->whereIn('status_pemesanan', ['menunggu_pembayaran'])
                    ->orWhere('status_pembayaran', 'belum_bayar');
            })->whereNotIn('status_pemesanan', ['selesai', 'dibatalkan']);
        } elseif ($this->tabStatus === 'masuk_antrean') {
            $query->where('status_pemesanan', 'antrian_produksi');
        } elseif ($this->tabStatus === 'sedang_dibuat') {
            $query->where('status_pemesanan', 'dalam_produksi');
        } elseif ($this->tabStatus === 'siap_kirim' || $this->tabStatus === 'selesai') {
            $query->whereIn('status_pemesanan', ['siap_diambil', 'selesai_produksi', 'selesai']);
        }

        // Filter Jenis Pakaian
        if ($this->filterPakaian !== 'semua') {
            $query->where('jenis_kain', 'like', '%'.$this->filterPakaian.'%');
        }

        // Pencarian
        if (! empty($this->search)) {
            $s = '%'.$this->search.'%';
            $query->where(function ($q) use ($s) {
                $q->where('nomor_pemesanan', 'like', $s)
                    ->orWhere('jenis_kain', 'like', $s)
                    ->orWhere('warna', 'like', $s)
                    ->orWhere('detail_desain', 'like', $s)
                    ->orWhereHas('pelanggan', fn ($sub) => $sub->where('nama', 'like', $s));
            });
        }

        // Sorting
        if ($this->urutan === 'terbaru') {
            $query->latest('tanggal_pemesanan');
        } elseif ($this->urutan === 'terlama') {
            $query->oldest('tanggal_pemesanan');
        } elseif ($this->urutan === 'jumlah_terbanyak') {
            $query->orderByDesc('jumlah');
        } else {
            // Default: Batas Waktu Terdekat
            $query->orderBy('tanggal_selesai_estimasi', 'asc');
        }

        $daftarPesanan = $query->paginate($this->perPage);

        return view('livewire.admin.manajemen-pesanan', [
            'ringkasan'     => $ringkasan,
            'daftarPesanan' => $daftarPesanan,
        ]);
    }
}
