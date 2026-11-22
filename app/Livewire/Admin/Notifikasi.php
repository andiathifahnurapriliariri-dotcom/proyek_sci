<?php

namespace App\Livewire\Admin;

use App\Models\NotifikasiAdmin;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Pusat Notifikasi - COTTON.id')]
#[Layout('layouts.admin')]
class Notifikasi extends Component
{
    use WithPagination;

    public string $filterKategori = 'semua';

    public string $filterStatus = 'semua';

    public string $search = '';

    public string $pesanSukses = '';

    public function mount(): void
    {
        // Sinkronkan data aktual sistem ke tabel notifikasi admin
        NotifikasiAdmin::sinkronkanDariDataSistem();

        if (request()->has('kategori')) {
            $this->filterKategori = (string) request()->query('kategori');
        }

        if (request()->has('status')) {
            $this->filterStatus = (string) request()->query('status');
        }

        if (request()->has('search')) {
            $this->search = (string) request()->query('search');
        }
    }

    public function updatingFilterKategori(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['filterKategori', 'filterStatus', 'search']);
        $this->filterKategori = 'semua';
        $this->filterStatus = 'semua';
        $this->search = '';
        $this->resetPage();
    }

    public function tandaiDibaca(int $id): void
    {
        $notif = NotifikasiAdmin::find($id);
        if ($notif) {
            $notif->tandaiDibaca();
            $this->pesanSukses = 'Notifikasi telah ditandai sebagai sudah dibaca.';
        }
    }

    public function tandaiSemuaDibaca(): void
    {
        NotifikasiAdmin::belumDibaca()->update([
            'dibaca' => true,
            'dibaca_pada' => now(),
        ]);

        $this->pesanSukses = 'Seluruh pemberitahuan berhasil ditandai sebagai telah dibaca.';
    }

    public function bukaNotifikasi(int $id): mixed
    {
        $notif = NotifikasiAdmin::find($id);
        if ($notif) {
            $notif->tandaiDibaca();
            if ($notif->link) {
                return redirect()->to($notif->link);
            }
        }

        return null;
    }

    public function hapusNotifikasi(int $id): void
    {
        $notif = NotifikasiAdmin::find($id);
        if ($notif) {
            $notif->delete();
            $this->pesanSukses = 'Notifikasi berhasil dihapus.';
        }
    }

    public function render(): View
    {
        $query = NotifikasiAdmin::query();

        // Filter status dibaca
        if ($this->filterStatus === 'belum_dibaca') {
            $query->belumDibaca();
        } elseif ($this->filterStatus === 'sudah_dibaca') {
            $query->dibaca();
        }

        // Filter kategori
        if ($this->filterKategori !== 'semua') {
            $query->kategori($this->filterKategori);
        }

        // Pencarian teks judul & isi pesan
        if (filled($this->search)) {
            $term = '%' . trim($this->search) . '%';
            $query->where(function (Builder $q) use ($term): void {
                $q->where('judul', 'like', $term)
                    ->orWhere('pesan', 'like', $term);
            });
        }

        $daftarNotifikasi = $query->latest('id')->paginate(12);

        // Hitung statistik untuk badge & ringkasan
        $totalSemua = NotifikasiAdmin::count();
        $totalBelumDibaca = NotifikasiAdmin::hitungBelumDibaca();
        $totalSudahDibaca = $totalSemua - $totalBelumDibaca;

        $countKategori = [
            'pesanan' => NotifikasiAdmin::kategori('pesanan')->count(),
            'pembayaran' => NotifikasiAdmin::kategori('pembayaran')->count(),
            'produksi' => NotifikasiAdmin::kategori('produksi')->count(),
            'stok_rop' => NotifikasiAdmin::kategori('stok_rop')->count(),
            'selesai' => NotifikasiAdmin::kategori('selesai')->count(),
        ];

        return view('livewire.admin.notifikasi', [
            'daftarNotifikasi' => $daftarNotifikasi,
            'totalSemua' => $totalSemua,
            'totalBelumDibaca' => $totalBelumDibaca,
            'totalSudahDibaca' => $totalSudahDibaca,
            'countKategori' => $countKategori,
        ]);
    }
}
