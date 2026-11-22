<?php

namespace App\Livewire\Admin;

use App\Models\Pelanggan;
use App\Models\Pemesanan;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.admin')]
class DataPelanggan extends Component
{
    use WithPagination;

    private const KATEGORI = ['Perusahaan', 'Komunitas', 'Instansi', 'Area Prepare'];

    public string $search = '';

    public string $filterKategori = 'semua';

    public string $filterKota = 'semua';

    public int $perPage = 5;

    public string $pesanSukses = '';

    public function mount(): void
    {
        if (request()->filled('search')) {
            $this->search = (string) request()->query('search');
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterKategori(): void
    {
        $this->resetPage();
    }

    public function updatingFilterKota(): void
    {
        $this->resetPage();
    }

    public function setFilterKategori(string $kategori): void
    {
        $this->filterKategori = in_array($kategori, self::KATEGORI, true) ? $kategori : 'semua';
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'filterKategori', 'filterKota']);
        $this->filterKategori = 'semua';
        $this->filterKota = 'semua';
        $this->resetPage();
    }

    public function exportData(): StreamedResponse
    {
        return response()->streamDownload(function (): void {
            $handle = fopen('php://output', 'wb');

            if ($handle === false) {
                throw new \RuntimeException('Tidak dapat membuka aliran ekspor data pelanggan.');
            }

            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Kode Pelanggan', 'Nama', 'Kategori', 'Nomor WhatsApp', 'Email', 'Alamat', 'Kota', 'Total Pesanan', 'Nilai Transaksi']);

            foreach ($this->pelangganQuery()->withCount('pemesanan')->withSum('pemesanan', 'total_harga')->lazyById(500) as $pelanggan) {
                fputcsv($handle, [
                    $pelanggan->kode_pelanggan,
                    $pelanggan->nama,
                    $pelanggan->kategori,
                    $pelanggan->nomor_telepon,
                    $pelanggan->email,
                    $pelanggan->alamat,
                    $pelanggan->kota,
                    $pelanggan->pemesanan_count,
                    $pelanggan->pemesanan_sum_total_harga ?? 0,
                ]);
            }

            fclose($handle);
        }, 'data-pelanggan.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function render(): View
    {
        $totalPelanggan = Pelanggan::query()->count();
        $pelangganAktif = Pelanggan::query()
            ->whereHas('pemesanan', fn (Builder $query) => $query->whereDate('tanggal_pemesanan', '>=', now()->subMonths(3)->toDateString()))
            ->count();
        $pelangganBaru = Pelanggan::query()
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();
        $rataRataPesanan = (float) Pemesanan::query()->avg('total_harga');

        $pelanggan = $this->pelangganQuery()
            ->withCount('pemesanan')
            ->withSum('pemesanan', 'total_harga')
            ->orderBy('nama')
            ->paginate($this->perPage);

        $daftarKota = Pelanggan::query()
            ->whereNotNull('kota')
            ->where('kota', '<>', '')
            ->distinct()
            ->orderBy('kota')
            ->pluck('kota');

        return view('livewire.admin.data-pelanggan', [
            'pelanggan' => $pelanggan,
            'daftarKota' => $daftarKota,
            'kategoriOptions' => self::KATEGORI,
            'totalPelanggan' => $totalPelanggan,
            'pelangganAktif' => $pelangganAktif,
            'pelangganBaru' => $pelangganBaru,
            'rataRataPesanan' => $rataRataPesanan,
        ]);
    }

    /**
     * @return Builder<Pelanggan>
     */
    private function pelangganQuery(): Builder
    {
        return Pelanggan::query()
            ->when($this->filterKategori !== 'semua', fn (Builder $query) => $query->where('kategori', $this->filterKategori))
            ->when($this->filterKota !== 'semua', fn (Builder $query) => $query->where('kota', $this->filterKota))
            ->when(trim($this->search) !== '', function (Builder $query): void {
                $kataKunci = '%'.trim($this->search).'%';

                $query->where(function (Builder $query) use ($kataKunci): void {
                    $query->where('kode_pelanggan', 'like', $kataKunci)
                        ->orWhere('nama', 'like', $kataKunci)
                        ->orWhere('nomor_telepon', 'like', $kataKunci)
                        ->orWhere('email', 'like', $kataKunci)
                        ->orWhere('alamat', 'like', $kataKunci)
                        ->orWhere('kota', 'like', $kataKunci);
                });
            });
    }
}
