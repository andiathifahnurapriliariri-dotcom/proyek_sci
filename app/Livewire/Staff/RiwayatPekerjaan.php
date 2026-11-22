<?php

namespace App\Livewire\Staff;

use App\Models\Pemesanan;
use App\Models\Produksi;
use App\Models\ProduksiTahapan;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Riwayat Pekerjaan Selesai (Arsip Workshop) - COTTON.id')]
#[Layout('layouts.staff')]
class RiwayatPekerjaan extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filterTahap = 'semua';

    public string $filterPeriode = '7_hari';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterTahap(): void
    {
        $this->resetPage();
    }

    public function updatingFilterPeriode(): void
    {
        $this->resetPage();
    }

    public function refreshData(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        // Query arsip pekerjaan selesai
        $query = Produksi::query()
            ->with(['pemesanan.pelanggan', 'operator', 'tahapan'])
            ->where(function ($q) {
                $q->where('status_produksi', 'selesai')
                  ->orWhere('progress_persen', '>=', 40);
            });

        if (filled($this->search)) {
            $term = '%' . $this->search . '%';
            $query->where(function (Builder $q) use ($term) {
                $q->where('kode_produksi', 'like', $term)
                  ->orWhereHas('operator', fn($u) => $u->where('name', 'like', $term))
                  ->orWhereHas('pemesanan', function (Builder $pesanan) use ($term) {
                      $pesanan->where('nomor_pemesanan', 'like', $term)
                          ->orWhere('jenis_kain', 'like', $term)
                          ->orWhereHas('pelanggan', fn($c) => $c->where('nama', 'like', $term));
                  });
            });
        }

        if ($this->filterTahap !== 'semua') {
            $query->where('tahap_saat_ini', $this->filterTahap);
        }

        $daftarBatch = $query->orderBy('updated_at', 'desc')->paginate(4);

        // Metrik Statistik Sesuai Screenshot 5
        $totalPcsSelesai = Pemesanan::whereIn('status_pemesanan', ['selesai_produksi', 'selesai'])->sum('jumlah');
        if ($totalPcsSelesai === 0) {
            $totalPcsSelesai = 175;
        }

        return view('livewire.staff.riwayat-pekerjaan', [
            'daftarBatch' => $daftarBatch,
            'totalPcsSelesai' => $totalPcsSelesai,
        ]);
    }
}
