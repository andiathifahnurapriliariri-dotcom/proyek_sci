<?php

namespace App\Livewire\Admin;

use App\Models\Produksi;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Title('Pemantauan Produksi - COTTON.id')]
#[Layout('layouts.admin')]
class PemantauanProduksi extends Component
{
    use WithPagination;

    private const TAHAP = [
        'pemotongan_kain' => 'Pemotongan Kain',
        'sablon' => 'Sablon & Bordir',
        'penjahitan' => 'Penjahitan',
        'steam' => 'Steam & Finishing',
    ];

    public string $search = '';

    public string $filterTahap = 'semua';

    public string $diperbaruiPada = '';

    public bool $showModalSop = false;

    public function mount(): void
    {
        $this->diperbaruiPada = now()->format('H:i');

        if (request()->filled('search')) {
            $this->search = (string) request()->query('search');
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterTahap(string $tahap): void
    {
        if ($tahap !== 'semua' && ! array_key_exists($tahap, self::TAHAP)) {
            throw ValidationException::withMessages([
                'filterTahap' => 'Tahap produksi yang dipilih tidak tersedia.',
            ]);
        }

        $this->resetPage();
        $this->resetErrorBag('filterTahap');
    }

    public function segarkanData(): void
    {
        $this->diperbaruiPada = now()->format('H:i');
    }

    public function lihatSop(): void
    {
        $this->showModalSop = true;
    }

    public function tutupSop(): void
    {
        $this->showModalSop = false;
    }

    public function unduhLembarPantauan(): StreamedResponse
    {
        $query = $this->queryPemantauan();

        return response()->streamDownload(function () use ($query): void {
            $stream = fopen('php://output', 'w');

            if (! is_resource($stream)) {
                throw new RuntimeException('Lembar pemantauan produksi gagal disiapkan.');
            }

            fwrite($stream, "\xEF\xBB\xBF");
            fputcsv($stream, [
                'No. Produksi',
                'No. Pesanan',
                'Pelanggan',
                'Jenis Pakaian',
                'Jumlah (pcs)',
                'Tahap Saat Ini',
                'Penanggung Jawab',
                'Progress (%)',
                'Estimasi Selesai',
            ], ';');

            foreach ($query->lazy(200) as $produksi) {
                $pesanan = $produksi->getRelation('pemesanan');
                $pelanggan = $pesanan?->getRelation('pelanggan');
                $operator = $produksi->getRelation('operator');
                $namaPelanggan = $this->amanUntukCsv($pelanggan?->getAttribute('nama'));
                $jenisKain = $this->amanUntukCsv($pesanan?->getAttribute('jenis_kain'));

                fputcsv($stream, [
                    $produksi->kode_produksi,
                    $pesanan?->nomor_pemesanan,
                    $namaPelanggan,
                    $jenisKain,
                    $pesanan?->jumlah,
                    self::TAHAP[$produksi->tahap_saat_ini] ?? 'Selesai',
                    $this->amanUntukCsv($operator?->getAttribute('name')),
                    $produksi->progress_persen,
                    $pesanan?->tanggal_selesai_estimasi?->format('Y-m-d'),
                ], ';');
            }

            fclose($stream);
        }, 'lembar-pemantauan-produksi-'.now()->format('Ymd-His').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * @return Builder<Produksi>
     */
    private function queryPemantauan(): Builder
    {
        $query = Produksi::query()
            ->with(['pemesanan.pelanggan', 'operator'])
            ->whereIn('status_produksi', ['antrian', 'proses'])
            ->whereHas('pemesanan', function (Builder $pesanan): void {
                $pesanan->whereNotIn('status_pemesanan', ['selesai', 'dibatalkan']);
            });

        if ($this->filterTahap !== 'semua' && array_key_exists($this->filterTahap, self::TAHAP)) {
            $query->where('tahap_saat_ini', $this->filterTahap);
        }

        if (filled($this->search)) {
            $term = '%'.$this->search.'%';
            $query->where(function (Builder $produksi) use ($term): void {
                $produksi->where('kode_produksi', 'like', $term)
                    ->orWhereHas('pemesanan', function (Builder $pesanan) use ($term): void {
                        $pesanan->where('nomor_pemesanan', 'like', $term)
                            ->orWhere('jenis_kain', 'like', $term)
                            ->orWhere('warna', 'like', $term)
                            ->orWhereHas('pelanggan', function (Builder $pelanggan) use ($term): void {
                                $pelanggan->where('nama', 'like', $term);
                            });
                    });
            });
        }

        return $query->orderBy('tanggal_mulai')->orderBy('id');
    }

    private function amanUntukCsv(?string $value): string
    {
        $value = $value ?? '';

        return preg_match('/^[\s]*[=+\-@]/u', $value) === 1 ? "'".$value : $value;
    }

    public function render(): View
    {
        $produksiAktif = Produksi::query()
            ->whereIn('status_produksi', ['antrian', 'proses'])
            ->whereHas('pemesanan', function (Builder $pesanan): void {
                $pesanan->whereNotIn('status_pemesanan', ['selesai', 'dibatalkan']);
            });

        $jumlahPesananAktif = (clone $produksiAktif)->count();
        $jumlahPcsAktif = DB::table('produksi')
            ->join('pemesanan', 'produksi.pemesanan_id', '=', 'pemesanan.id')
            ->whereIn('produksi.status_produksi', ['antrian', 'proses'])
            ->whereNotIn('pemesanan.status_pemesanan', ['selesai', 'dibatalkan'])
            ->sum('pemesanan.jumlah');
        $rataRataProgres = (int) round((float) (clone $produksiAktif)->avg('progress_persen'));

        $jumlahProduksiValid = Produksi::query()
            ->where('status_produksi', '!=', 'dibatalkan')
            ->count();
        $jumlahProduksiSelesai = Produksi::query()
            ->where('status_produksi', 'selesai')
            ->count();
        $persentaseSelesai = $jumlahProduksiValid > 0
            ? round($jumlahProduksiSelesai / $jumlahProduksiValid * 100, 1)
            : 0;

        $pesananTepatWaktu = (clone $produksiAktif)
            ->whereHas('pemesanan', function (Builder $pesanan): void {
                $pesanan->whereNotNull('tanggal_selesai_estimasi')
                    ->whereDate('tanggal_selesai_estimasi', '>=', today());
            })
            ->count();
        $pesananDenganEstimasi = (clone $produksiAktif)
            ->whereHas('pemesanan', function (Builder $pesanan): void {
                $pesanan->whereNotNull('tanggal_selesai_estimasi');
            })
            ->count();
        $persentaseTepatWaktu = $pesananDenganEstimasi > 0
            ? round($pesananTepatWaktu / $pesananDenganEstimasi * 100, 1)
            : 0;

        $jumlahPcsSiapKirim = DB::table('pemesanan')
            ->whereIn('status_pemesanan', ['selesai_produksi', 'siap_diambil'])
            ->sum('jumlah');

        $ringkasanTahap = DB::table('produksi')
            ->join('pemesanan', 'produksi.pemesanan_id', '=', 'pemesanan.id')
            ->whereIn('produksi.status_produksi', ['antrian', 'proses'])
            ->whereNotIn('pemesanan.status_pemesanan', ['selesai', 'dibatalkan'])
            ->select('produksi.tahap_saat_ini')
            ->selectRaw('COUNT(*) as jumlah_pesanan')
            ->selectRaw('SUM(pemesanan.jumlah) as jumlah_pcs')
            ->selectRaw(
                'SUM(CASE WHEN pemesanan.tanggal_selesai_estimasi < ? THEN 1 ELSE 0 END) as jumlah_terlambat',
                [today()->toDateString()],
            )
            ->groupBy('produksi.tahap_saat_ini')
            ->get()
            ->keyBy('tahap_saat_ini');

        $tahapan = collect(self::TAHAP)
            ->map(function (string $nama, string $kode) use ($ringkasanTahap, $jumlahPesananAktif): array {
                $ringkasan = $ringkasanTahap->get($kode);
                $jumlah = (int) ($ringkasan->jumlah_pesanan ?? 0);

                return [
                    'kode' => $kode,
                    'nama' => $nama,
                    'jumlah_pesanan' => $jumlah,
                    'jumlah_pcs' => (int) ($ringkasan->jumlah_pcs ?? 0),
                    'jumlah_terlambat' => (int) ($ringkasan->jumlah_terlambat ?? 0),
                    'porsi_beban' => $jumlahPesananAktif > 0
                        ? round($jumlah / $jumlahPesananAktif * 100)
                        : 0,
                ];
            })
            ->values();

        return view('livewire.admin.pemantauan-produksi', [
            'jumlahPesananAktif' => $jumlahPesananAktif,
            'jumlahPcsAktif' => $jumlahPcsAktif,
            'rataRataProgres' => $rataRataProgres,
            'persentaseSelesai' => $persentaseSelesai,
            'jumlahPcsSiapKirim' => $jumlahPcsSiapKirim,
            'persentaseTepatWaktu' => $persentaseTepatWaktu,
            'jumlahTerlambat' => $ringkasanTahap->sum('jumlah_terlambat'),
            'tahapan' => $tahapan,
            'opsiTahap' => self::TAHAP,
            'daftarProduksi' => $this->queryPemantauan()->paginate(5),
            'jumlahHasil' => $this->queryPemantauan()->count(),
            'labelTahap' => self::TAHAP,
            'labelStatus' => [
                'antrian' => 'Dalam antrean',
                'proses' => 'Sedang berjalan',
                'selesai' => 'Selesai',
                'dibatalkan' => 'Dibatalkan',
            ],
        ]);
    }
}
