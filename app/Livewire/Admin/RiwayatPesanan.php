<?php

namespace App\Livewire\Admin;

use App\Models\Pemesanan;
use DateTimeInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Title('Riwayat & Arsip Pemesanan - COTTON.id')]
#[Layout('layouts.admin')]
class RiwayatPesanan extends Component
{
    use WithPagination;

    public string $search = '';
    public string $tahun = 'semua';
    public string $filterStatus = 'semua_selesai'; // 'semua_selesai', 'selesai', 'siap_diambil', 'semua'
    public string $jenisPakaian = 'semua';
    public string $urutan = 'terbaru';
    public int $perPage = 10;

    // Modal Detail Pesanan
    public bool $showModalDetail = false;
    public ?int $selectedPesananId = null;

    protected $queryString = [
        'search'       => ['except' => ''],
        'tahun'        => ['except' => 'semua'],
        'filterStatus' => ['except' => 'semua_selesai'],
        'jenisPakaian' => ['except' => 'semua'],
        'urutan'       => ['except' => 'terbaru'],
    ];

    public function mount(): void
    {
        if ($this->tahun === '') {
            $this->tahun = 'semua';
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingTahun(string $tahun): void
    {
        if ($tahun !== 'semua' && ! preg_match('/^\d{4}$/', $tahun)) {
            throw ValidationException::withMessages([
                'tahun' => 'Tahun pesanan tidak valid.',
            ]);
        }

        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatingJenisPakaian(string $jenisPakaian): void
    {
        if ($jenisPakaian !== 'semua' && ! in_array($jenisPakaian, $this->jenisPakaianTersedia(), true)) {
            throw ValidationException::withMessages([
                'jenisPakaian' => 'Jenis pakaian yang dipilih tidak tersedia.',
            ]);
        }

        $this->resetPage();
    }

    public function updatingUrutan(string $urutan): void
    {
        if (! in_array($urutan, ['terbaru', 'terlama'], true)) {
            throw ValidationException::withMessages([
                'urutan' => 'Urutan riwayat pesanan tidak valid.',
            ]);
        }

        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'tahun', 'filterStatus', 'jenisPakaian', 'urutan']);
        $this->tahun = 'semua';
        $this->filterStatus = 'semua_selesai';
        $this->jenisPakaian = 'semua';
        $this->urutan = 'terbaru';
        $this->resetPage();
    }

    public function lihatDetail(int $id): void
    {
        $this->selectedPesananId = $id;
        $this->showModalDetail = true;
    }

    public function tutupModalDetail(): void
    {
        $this->showModalDetail = false;
        $this->selectedPesananId = null;
    }

    public function eksporArsip(): StreamedResponse
    {
        $query = $this->queryArsip();

        return response()->streamDownload(function () use ($query): void {
            $stream = fopen('php://output', 'w');

            if (! is_resource($stream)) {
                throw new RuntimeException('Arsip pesanan gagal disiapkan.');
            }

            // UTF-8 BOM untuk kompatibilitas Microsoft Excel
            fwrite($stream, "\xEF\xBB\xBF");
            fputcsv($stream, [
                'No. Pemesanan',
                'Nama Pelanggan',
                'No. Telepon / WA',
                'Jenis Kain / Bahan',
                'Warna',
                'Ukuran',
                'Jumlah (pcs)',
                'Tanggal Masuk SPK',
                'Estimasi Batas Waktu',
                'Tanggal Selesai Aktual',
                'Total Nilai (Rp)',
                'Status Pemesanan',
                'Status Pembayaran',
            ]);

            foreach ($query->lazy(200) as $pesanan) {
                $pelanggan = $pesanan->getRelation('pelanggan');
                $produksi = $pesanan->getRelation('produksi');

                fputcsv($stream, [
                    $pesanan->nomor_pemesanan,
                    $this->amanUntukCsv($pelanggan?->nama),
                    $this->amanUntukCsv($pelanggan?->nomor_telepon),
                    $this->amanUntukCsv($pesanan->jenis_kain),
                    $this->amanUntukCsv($pesanan->warna),
                    $this->amanUntukCsv($pesanan->ukuran_baju),
                    $pesanan->jumlah,
                    $this->formatTanggal($pesanan->getAttribute('tanggal_pemesanan')),
                    $this->formatTanggal($pesanan->getAttribute('tanggal_selesai_estimasi')),
                    $this->formatTanggal($produksi?->getAttribute('tanggal_selesai')),
                    $pesanan->total_harga,
                    ucfirst(str_replace('_', ' ', $pesanan->status_pemesanan)),
                    ucfirst($pesanan->status_pembayaran),
                ]);
            }

            fclose($stream);
        }, 'arsip-riwayat-pemesanan-'.now()->format('Ymd-His').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Query builder untuk arsip riwayat pesanan.
     *
     * @return Builder<Pemesanan>
     */
    private function queryArsip(): Builder
    {
        $query = Pemesanan::query()
            ->with(['pelanggan', 'produksi', 'pembayaran', 'admin']);

        // Filter Status Riwayat
        match ($this->filterStatus) {
            'semua_selesai' => $query->whereIn('status_pemesanan', ['selesai', 'siap_diambil']),
            'selesai'       => $query->where('status_pemesanan', 'selesai'),
            'siap_diambil'  => $query->where('status_pemesanan', 'siap_diambil'),
            'semua'         => null,
            default         => $query->whereIn('status_pemesanan', ['selesai', 'siap_diambil']),
        };

        // Filter Tahun
        if ($this->tahun !== 'semua' && preg_match('/^\d{4}$/', $this->tahun)) {
            $query->whereYear('tanggal_pemesanan', (int) $this->tahun);
        }

        // Filter Jenis Pakaian
        if ($this->jenisPakaian !== 'semua' && in_array($this->jenisPakaian, $this->jenisPakaianTersedia(), true)) {
            $query->where('jenis_kain', $this->jenisPakaian);
        }

        // Filter Pencarian Teks
        if (filled($this->search)) {
            $term = '%'.trim($this->search).'%';
            $query->where(function (Builder $pesanan) use ($term): void {
                $pesanan->where('nomor_pemesanan', 'like', $term)
                    ->orWhere('jenis_kain', 'like', $term)
                    ->orWhere('warna', 'like', $term)
                    ->orWhere('catatan', 'like', $term)
                    ->orWhereHas('pelanggan', function (Builder $pelanggan) use ($term): void {
                        $pelanggan->where('nama', 'like', $term)
                            ->orWhere('nomor_telepon', 'like', $term);
                    });
            });
        }

        return $this->urutan === 'terlama'
            ? $query->oldest('tanggal_pemesanan')->oldest('id')
            : $query->latest('tanggal_pemesanan')->latest('id');
    }

    /**
     * @return list<string>
     */
    private function jenisPakaianTersedia(): array
    {
        return array_values(Pemesanan::query()
            ->distinct()
            ->whereNotNull('jenis_kain')
            ->orderBy('jenis_kain')
            ->pluck('jenis_kain')
            ->map(static fn (mixed $jenis): string => (string) $jenis)
            ->values()
            ->all());
    }

    /**
     * @return list<int>
     */
    private function tahunTersedia(): array
    {
        $rentang = DB::table('pemesanan')
            ->selectRaw('MIN(tanggal_pemesanan) as tanggal_awal, MAX(tanggal_pemesanan) as tanggal_akhir')
            ->first();

        $tahunAwal = $rentang?->tanggal_awal
            ? (int) substr((string) $rentang->tanggal_awal, 0, 4)
            : now()->year;
        $tahunAkhir = $rentang?->tanggal_akhir
            ? (int) substr((string) $rentang->tanggal_akhir, 0, 4)
            : now()->year;

        $daftarTahun = range($tahunAkhir, $tahunAwal);
        if (!in_array(now()->year, $daftarTahun, true)) {
            $daftarTahun[] = now()->year;
        }

        return array_values(array_unique($daftarTahun));
    }

    private function amanUntukCsv(?string $value): string
    {
        $value = $value ?? '';
        return preg_match('/^[\s]*[=+\-@]/u', $value) === 1 ? "'".$value : $value;
    }

    private function formatTanggal(mixed $tanggal): ?string
    {
        if ($tanggal instanceof DateTimeInterface) {
            return $tanggal->format('Y-m-d');
        }

        return is_string($tanggal) && $tanggal !== ''
            ? Carbon::parse($tanggal)->format('Y-m-d')
            : null;
    }

    public function render(): View
    {
        $query = $this->queryArsip();
        $totalPesanan = (clone $query)->count();
        $totalPcs = (int) (clone $query)->sum('jumlah');
        $totalNilaiPesanan = (float) (clone $query)->sum('total_harga');

        $jumlahPesananDenganTanggalAktual = (clone $query)
            ->whereNotNull('pemesanan.tanggal_selesai_estimasi')
            ->whereHas('produksi', function (Builder $produksi): void {
                $produksi->whereNotNull('tanggal_selesai');
            })
            ->count();

        $pesananTepatWaktu = (clone $query)
            ->whereHas('produksi', function (Builder $produksi): void {
                $produksi->whereColumn('produksi.tanggal_selesai', '<=', 'pemesanan.tanggal_selesai_estimasi');
            })
            ->count();

        $persentaseTepatWaktu = $jumlahPesananDenganTanggalAktual > 0
            ? round($pesananTepatWaktu / $jumlahPesananDenganTanggalAktual * 100, 1)
            : 100.0;

        $selectedPesanan = $this->selectedPesananId
            ? Pemesanan::with(['pelanggan', 'produksi', 'pembayaran', 'admin'])->find($this->selectedPesananId)
            : null;

        return view('livewire.admin.riwayat-pesanan', [
            'totalPesanan'         => $totalPesanan,
            'totalPcs'             => $totalPcs,
            'totalNilaiPesanan'    => $totalNilaiPesanan,
            'persentaseTepatWaktu' => $persentaseTepatWaktu,
            'pesananTepatWaktu'    => $pesananTepatWaktu,
            'jenisPakaianTersedia' => $this->jenisPakaianTersedia(),
            'tahunTersedia'        => $this->tahunTersedia(),
            'daftarPesanan'        => $query->paginate($this->perPage),
            'selectedPesanan'      => $selectedPesanan,
        ]);
    }
}
