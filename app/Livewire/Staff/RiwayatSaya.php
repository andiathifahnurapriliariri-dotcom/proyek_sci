<?php

namespace App\Livewire\Staff;

use App\Models\AktivitasLog;
use App\Models\ProduksiTahapan;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Catatan Kerja Saya - COTTON.id')]
#[Layout('layouts.staff')]
class RiwayatSaya extends Component
{
    use WithPagination;

    public string $search = '';

    public string $activeFilter = 'hari_ini'; // hari_ini, minggu_ini, bulan_ini

    public string $filterKategori = 'semua';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function setFilter(string $filter): void
    {
        $this->activeFilter = $filter;
        $this->resetPage();
    }

    public function updatingFilterKategori(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $userId = auth()->id();

        $query = ProduksiTahapan::query()
            ->with(['produksi.pemesanan.pelanggan'])
            ->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhereNull('user_id');
            });

        if (filled($this->search)) {
            $term = '%' . $this->search . '%';
            $query->where(function (Builder $q) use ($term) {
                $q->where('label_tahap', 'like', $term)
                  ->orWhereHas('produksi.pemesanan', function (Builder $pesanan) use ($term) {
                      $pesanan->where('nomor_pemesanan', 'like', $term)
                          ->orWhere('jenis_kain', 'like', $term);
                  });
            });
        }

        $riwayatSaya = $query->orderBy('updated_at', 'desc')->paginate(5);

        return view('livewire.staff.riwayat-saya', [
            'riwayatSaya' => $riwayatSaya,
        ]);
    }
}
