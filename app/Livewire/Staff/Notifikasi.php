<?php

namespace App\Livewire\Staff;

use App\Models\NotifikasiStaff;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Notifikasi Staff - COTTON.id')]
#[Layout('layouts.staff')]
class Notifikasi extends Component
{
    use WithPagination;

    public string $filterTipe = 'semua';

    public string $pesanSukses = '';

    public function updatingFilterTipe(): void
    {
        $this->resetPage();
    }

    public function tandaiDibaca(int $id): void
    {
        $notif = NotifikasiStaff::untukUser(auth()->id())->find($id);
        if ($notif) {
            $notif->tandaiDibaca();
            $this->pesanSukses = 'Notifikasi telah ditandai sudah dibaca.';
        }
    }

    public function tandaiSemuaDibaca(): void
    {
        NotifikasiStaff::untukUser(auth()->id())
            ->belumDibaca()
            ->update([
                'dibaca' => true,
                'dibaca_pada' => now(),
            ]);

        $this->pesanSukses = 'Seluruh notifikasi telah ditandai sudah dibaca.';
    }

    public function render(): View
    {
        $userId = auth()->id();

        $query = NotifikasiStaff::untukUser($userId);

        if ($this->filterTipe === 'belum_dibaca') {
            $query->belumDibaca();
        } elseif ($this->filterTipe !== 'semua') {
            $query->where('tipe', $this->filterTipe);
        }

        $daftarNotifikasi = $query->orderBy('created_at', 'desc')->paginate(12);

        $totalBelumDibaca = NotifikasiStaff::untukUser($userId)->belumDibaca()->count();

        return view('livewire.staff.notifikasi', [
            'daftarNotifikasi' => $daftarNotifikasi,
            'totalBelumDibaca' => $totalBelumDibaca,
        ]);
    }
}
