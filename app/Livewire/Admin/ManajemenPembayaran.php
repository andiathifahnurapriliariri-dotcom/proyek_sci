<?php

namespace App\Livewire\Admin;

use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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
class ManajemenPembayaran extends Component
{
    use WithFileUploads, WithPagination;

    private const TABS = ['semua', 'perlu_dicek', 'dp_diterima', 'sudah_lunas'];

    private const METODE_PEMBAYARAN = ['Transfer Bank', 'Tunai'];

    public string $search = '';

    public string $tabStatus = 'semua';

    public bool $showModalCreate = false;

    public bool $showModalBukti = false;

    public ?int $selectedPaymentId = null;

    public ?int $pemesananId = null;

    public string $jenisPembayaran = 'DP';

    public string $jumlahBayar = '';

    public string $tanggalBayar = '';

    public string $metodePembayaran = 'Transfer Bank';

    public string $statusKonfirmasi = 'Diterima';

    public string $catatan = '';

    public ?TemporaryUploadedFile $buktiPembayaran = null;

    public string $pesanSukses = '';

    public function mount(): void
    {
        $this->tanggalBayar = now()->format('Y-m-d\TH:i');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function setTab(string $tab): void
    {
        if (! in_array($tab, self::TABS, true)) {
            throw ValidationException::withMessages([
                'tabStatus' => 'Filter transaksi yang dipilih tidak tersedia.',
            ]);
        }

        $this->tabStatus = $tab;
        $this->resetPage();
    }

    #[On('buka-modal-pembayaran')]
    public function bukaModalCreate(): void
    {
        $this->resetValidation();
        $this->resetForm();
        $this->showModalCreate = true;
    }

    public function tutupModalCreate(): void
    {
        $this->showModalCreate = false;
        $this->resetValidation();
    }

    protected function resetForm(): void
    {
        $this->pemesananId = null;
        $this->jenisPembayaran = 'DP';
        $this->jumlahBayar = '';
        $this->tanggalBayar = now()->format('Y-m-d\TH:i');
        $this->metodePembayaran = 'Transfer Bank';
        $this->statusKonfirmasi = 'Diterima';
        $this->catatan = '';
        $this->buktiPembayaran = null;
    }

    public function updatedPemesananId(): void
    {
        if (! $this->pemesananId) {
            return;
        }

        $pesanan = Pemesanan::find($this->pemesananId);
        if (! $pesanan) {
            return;
        }

        $jumlahDicadangkan = (float) $pesanan->pembayaran()
            ->where('status_konfirmasi', '!=', 'Ditolak')
            ->sum('jumlah_bayar');
        $sisaTagihan = max(0, (float) $pesanan->total_harga - $jumlahDicadangkan);

        if ($sisaTagihan > 0) {
            if ($jumlahDicadangkan > 0) {
                $this->jenisPembayaran = 'Pelunasan';
                $this->jumlahBayar = number_format($sisaTagihan, 0, ',', '.');
            } else {
                $this->jenisPembayaran = 'DP';
                $this->jumlahBayar = number_format((int) ($sisaTagihan / 2), 0, ',', '.');
            }
        }
    }

    public function simpanPembayaran(): void
    {
        // Bersihkan input nominal dari titik pemisah ribuan, koma, spasi, atau teks 'Rp'
        if (filled($this->jumlahBayar)) {
            $cleaned = preg_replace('/[^0-9]/', '', (string) $this->jumlahBayar);
            $this->jumlahBayar = $cleaned !== '' ? $cleaned : $this->jumlahBayar;
        }

        $data = $this->validate([
            'pemesananId' => ['required', 'integer', Rule::exists('pemesanan', 'id')],
            'jenisPembayaran' => ['required', Rule::in(['DP', 'Pelunasan'])],
            'jumlahBayar' => ['required', 'integer', 'min:1', 'max:9999999999'],
            'tanggalBayar' => ['required', 'date', 'before_or_equal:now'],
            'metodePembayaran' => ['required', Rule::in(self::METODE_PEMBAYARAN)],
            'statusKonfirmasi' => ['required', Rule::in(['Diterima', 'Menunggu Verifikasi'])],
            'catatan' => ['nullable', 'string', 'max:2000'],
            'buktiPembayaran' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
        ], [
            'pemesananId.required' => 'Pesanan yang dibayar wajib dipilih.',
            'jumlahBayar.required' => 'Nominal pembayaran wajib diisi.',
            'jumlahBayar.integer' => 'Nominal pembayaran harus berupa angka rupiah bulat.',
            'jumlahBayar.min' => 'Nominal pembayaran minimal Rp 1.',
            'tanggalBayar.required' => 'Tanggal pembayaran wajib diisi.',
            'tanggalBayar.before_or_equal' => 'Waktu pembayaran tidak boleh di masa depan.',
            'buktiPembayaran.mimes' => 'Bukti pembayaran harus berupa JPG, PNG, WEBP, atau PDF.',
            'buktiPembayaran.max' => 'Ukuran bukti pembayaran maksimal 5 MB.',
        ]);

        $filePath = null;

        if ($this->buktiPembayaran) {
            $filePath = $this->buktiPembayaran->store('bukti-pembayaran', 'local');

            if (! $filePath) {
                throw new RuntimeException('Bukti pembayaran gagal disimpan.');
            }
        }

        try {
            $kodePembayaran = DB::transaction(function () use ($data, $filePath): string {
                $pesanan = Pemesanan::query()
                    ->whereKey($data['pemesananId'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($pesanan->status_pemesanan === 'dibatalkan') {
                    throw ValidationException::withMessages([
                        'pemesananId' => 'Pembayaran tidak dapat dicatat untuk pesanan yang dibatalkan.',
                    ]);
                }

                $jumlahDicadangkan = (float) $pesanan->pembayaran()
                    ->where('status_konfirmasi', '!=', 'Ditolak')
                    ->sum('jumlah_bayar');
                $sisaTagihan = max(0, (float) $pesanan->total_harga - $jumlahDicadangkan);
                $jumlahBayar = (int) $data['jumlahBayar'];

                if ($jumlahBayar > $sisaTagihan) {
                    throw ValidationException::withMessages([
                        'jumlahBayar' => 'Nominal pembayaran melebihi sisa tagihan Rp '.number_format($sisaTagihan, 0, ',', '.').'.',
                    ]);
                }

                if ($data['jenisPembayaran'] === 'Pelunasan' && $jumlahBayar !== (int) $sisaTagihan) {
                    throw ValidationException::withMessages([
                        'jumlahBayar' => 'Nominal pelunasan harus sama dengan sisa tagihan.',
                    ]);
                }

                if ($data['jenisPembayaran'] === 'DP' && $jumlahBayar >= $sisaTagihan) {
                    throw ValidationException::withMessages([
                        'jenisPembayaran' => 'Pilih Pelunasan jika pembayaran mencakup seluruh sisa tagihan.',
                    ]);
                }

                $kodePembayaran = $this->buatKodePembayaran();

                $pembayaran = new Pembayaran([
                    'kode_pembayaran' => $kodePembayaran,
                    'pemesanan_id' => $pesanan->id,
                    'user_id' => auth()->id(),
                    'jenis_pembayaran' => $data['jenisPembayaran'],
                    'jumlah_bayar' => $jumlahBayar,
                    'tanggal_bayar' => $data['tanggalBayar'],
                    'metode_pembayaran' => $data['metodePembayaran'],
                    'bukti_pembayaran' => $filePath,
                    'status_konfirmasi' => $data['statusKonfirmasi'],
                    'catatan' => filled($data['catatan']) ? trim($data['catatan']) : null,
                ]);
                $pesanan->pembayaran()->save($pembayaran);

                if ($pembayaran->status_konfirmasi === 'Diterima') {
                    $this->perbaruiStatusPesanan($pesanan);
                }

                return $kodePembayaran;
            });
        } catch (Throwable $exception) {
            if ($filePath) {
                Storage::disk('local')->delete($filePath);
            }

            throw $exception;
        }

        $this->showModalCreate = false;
        $this->pesanSukses = "Pembayaran {$kodePembayaran} berhasil dicatat.";
        $this->resetForm();
    }

    public function konfirmasiPembayaran(int $pembayaranId, string $status): void
    {
        if (! in_array($status, ['Diterima', 'Ditolak'], true)) {
            throw ValidationException::withMessages([
                'aksi' => 'Status konfirmasi yang dipilih tidak tersedia.',
            ]);
        }

        $kodePembayaran = DB::transaction(function () use ($pembayaranId, $status): string {
            $pembayaran = Pembayaran::query()->whereKey($pembayaranId)->firstOrFail();
            $pesanan = Pemesanan::query()->whereKey($pembayaran->pemesanan_id)->lockForUpdate()->firstOrFail();
            $pembayaran = Pembayaran::query()->whereKey($pembayaran->id)->lockForUpdate()->firstOrFail();

            if ($pembayaran->status_konfirmasi !== 'Menunggu Verifikasi') {
                throw ValidationException::withMessages([
                    'aksi' => 'Transaksi ini sudah diproses sebelumnya.',
                ]);
            }

            if ($status === 'Diterima') {
                $sudahDiterima = (float) $pesanan->pembayaran()
                    ->where('status_konfirmasi', 'Diterima')
                    ->sum('jumlah_bayar');

                if ($sudahDiterima + (float) $pembayaran->jumlah_bayar > (float) $pesanan->total_harga) {
                    throw ValidationException::withMessages([
                        'aksi' => 'Pembayaran tidak dapat diterima karena total pembayaran akan melebihi tagihan.',
                    ]);
                }
            }

            $pembayaran->status_konfirmasi = $status;
            $pembayaran->save();

            if ($status === 'Diterima') {
                $this->perbaruiStatusPesanan($pesanan);
            }

            return $pembayaran->kode_pembayaran;
        });

        $this->pesanSukses = "Pembayaran {$kodePembayaran} berhasil ".($status === 'Diterima' ? 'diterima.' : 'ditolak.');
    }

    public static function terbilang(int|float $angka): string
    {
        $angka = abs((int) $angka);
        $baca = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];

        if ($angka < 12) {
            return $baca[$angka];
        }
        if ($angka < 20) {
            return trim(self::terbilang($angka - 10).' Belas');
        }
        if ($angka < 100) {
            return trim(self::terbilang((int) ($angka / 10)).' Puluh '.self::terbilang($angka % 10));
        }
        if ($angka < 200) {
            return trim('Seratus '.self::terbilang($angka - 100));
        }
        if ($angka < 1000) {
            return trim(self::terbilang((int) ($angka / 100)).' Ratus '.self::terbilang($angka % 100));
        }
        if ($angka < 2000) {
            return trim('Seribu '.self::terbilang($angka - 1000));
        }
        if ($angka < 1000000) {
            return trim(self::terbilang((int) ($angka / 1000)).' Ribu '.self::terbilang($angka % 1000));
        }
        if ($angka < 1000000000) {
            return trim(self::terbilang((int) ($angka / 1000000)).' Juta '.self::terbilang($angka % 1000000));
        }
        if ($angka < 1000000000000) {
            return trim(self::terbilang((int) ($angka / 1000000000)).' Miliar '.self::terbilang($angka % 1000000000));
        }

        return (string) $angka;
    }

    public function lihatBukti(int $pembayaranId): void
    {
        $pembayaran = Pembayaran::query()->findOrFail($pembayaranId);

        $this->selectedPaymentId = $pembayaran->id;
        $this->showModalBukti = true;
    }

    public function tutupModalBukti(): void
    {
        $this->showModalBukti = false;
        $this->selectedPaymentId = null;
    }

    public function eksporRekap(): StreamedResponse
    {
        $query = $this->queryPembayaran()->with(['pemesanan.pelanggan']);

        return response()->streamDownload(function () use ($query): void {
            $stream = fopen('php://output', 'w');

            if (! is_resource($stream)) {
                throw new RuntimeException('Rekap pembayaran gagal disiapkan.');
            }

            fwrite($stream, "\xEF\xBB\xBF");
            fputcsv($stream, ['No. Bukti', 'No. Pesanan', 'Pelanggan', 'Tanggal', 'Jenis', 'Nominal', 'Metode', 'Status'], ';');

            foreach ($query->lazy(200) as $pembayaran) {
                $pesanan = $pembayaran->getRelation('pemesanan');
                $pelanggan = $pesanan?->getRelation('pelanggan');
                $tanggalBayar = $pembayaran->getAttribute('tanggal_bayar');

                fputcsv($stream, [
                    $pembayaran->kode_pembayaran,
                    $pesanan?->getAttribute('nomor_pemesanan'),
                    $pelanggan?->getAttribute('nama'),
                    $tanggalBayar?->format('Y-m-d H:i'),
                    $pembayaran->jenis_pembayaran,
                    $pembayaran->jumlah_bayar,
                    $pembayaran->metode_pembayaran,
                    $pembayaran->status_konfirmasi,
                ], ';');
            }

            fclose($stream);
        }, 'rekap-pembayaran-'.now()->format('Ymd-His').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function buatKodePembayaran(): string
    {
        do {
            $kodePembayaran = 'PAY-'.now()->format('Ymd').'-'.Str::upper(Str::random(8));
        } while (Pembayaran::query()->where('kode_pembayaran', $kodePembayaran)->exists());

        return $kodePembayaran;
    }

    private function perbaruiStatusPesanan(Pemesanan $pesanan): void
    {
        $pesanan->perbaruiStatusPembayaran();

        if ($pesanan->status_pemesanan === 'menunggu_pembayaran' && $pesanan->status_pembayaran !== 'belum_bayar') {
            $pesanan->status_pemesanan = 'antrian_produksi';
            $pesanan->save();
        }
    }

    /**
     * @return Builder<Pembayaran>
     */
    private function queryPembayaran(): Builder
    {
        $query = Pembayaran::query()->with(['pemesanan.pelanggan', 'admin']);

        if ($this->tabStatus === 'perlu_dicek') {
            $query->where('status_konfirmasi', 'Menunggu Verifikasi');
        } elseif ($this->tabStatus === 'dp_diterima') {
            $query->where('jenis_pembayaran', 'DP')
                ->where('status_konfirmasi', 'Diterima');
        } elseif ($this->tabStatus === 'sudah_lunas') {
            $query->whereHas('pemesanan', fn (Builder $pesanan) => $pesanan->where('status_pembayaran', 'lunas'));
        }

        if (filled($this->search)) {
            $term = '%'.$this->search.'%';

            $query->where(function (Builder $pembayaran) use ($term): void {
                $pembayaran->where('kode_pembayaran', 'like', $term)
                    ->orWhereHas('pemesanan', function (Builder $pesanan) use ($term): void {
                        $pesanan->where('nomor_pemesanan', 'like', $term)
                            ->orWhereHas('pelanggan', fn (Builder $pelanggan) => $pelanggan->where('nama', 'like', $term));
                    });
            });
        }

        return $query->latest('tanggal_bayar')->latest('id');
    }

    public function render(): View
    {
        $queryPesananAktif = Pemesanan::query()
            ->whereNotIn('status_pemesanan', ['dibatalkan'])
            ->withSum([
                'pembayaran as total_diterima' => fn (Builder $pembayaran) => $pembayaran->where('status_konfirmasi', 'Diterima'),
            ], 'jumlah_bayar');
        $ringkasanPesanan = (clone $queryPesananAktif)->get(['id', 'total_harga']);
        $totalTagihan = (float) $ringkasanPesanan->sum('total_harga');
        $totalDiterima = (float) $ringkasanPesanan->sum(
            fn (Pemesanan $pesanan): float => (float) $pesanan->getAttribute('total_diterima')
        );
        $totalTagihanTersisa = (float) $ringkasanPesanan->sum(
            fn (Pemesanan $pesanan): float => max(0, (float) $pesanan->total_harga - (float) $pesanan->getAttribute('total_diterima'))
        );

        $pesananTersedia = (clone $queryPesananAktif)
            ->with(['pelanggan'])
            ->withSum([
                'pembayaran as total_dicadangkan' => fn (Builder $pembayaran) => $pembayaran->where('status_konfirmasi', '!=', 'Ditolak'),
            ], 'jumlah_bayar')
            ->latest('tanggal_pemesanan')
            ->get()
            ->filter(fn (Pemesanan $pesanan): bool => (float) $pesanan->total_harga > (float) $pesanan->getAttribute('total_dicadangkan'));

        return view('livewire.admin.manajemen-pembayaran', [
            'pembayaran' => $this->queryPembayaran()->paginate(10),
            'pesananTersedia' => $pesananTersedia,
            'totalDiterimaBulanIni' => Pembayaran::query()
                ->where('status_konfirmasi', 'Diterima')
                ->whereBetween('tanggal_bayar', [now()->startOfMonth(), now()->endOfMonth()])
                ->sum('jumlah_bayar'),
            'jumlahPerluDicek' => Pembayaran::query()->where('status_konfirmasi', 'Menunggu Verifikasi')->count(),
            'nominalPerluDicek' => Pembayaran::query()
                ->where('status_konfirmasi', 'Menunggu Verifikasi')
                ->sum('jumlah_bayar'),
            'totalTagihanTersisa' => $totalTagihanTersisa,
            'persentasePembayaran' => $totalTagihan > 0 ? min(100, round($totalDiterima / $totalTagihan * 100, 1)) : 0,
            'selectedPayment' => $this->showModalBukti && $this->selectedPaymentId
                ? Pembayaran::query()->with(['pemesanan.pelanggan', 'pemesanan.pembayaran', 'admin'])->find($this->selectedPaymentId)
                : null,
        ]);
    }
}
