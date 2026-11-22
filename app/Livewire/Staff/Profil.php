<?php

namespace App\Livewire\Staff;

use App\Models\AktivitasLog;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Profil & Akun Staff - COTTON.id')]
#[Layout('layouts.staff')]
class Profil extends Component
{
    // Profile fields
    public string $name = '';

    public string $username = '';

    public string $email = '';

    public string $telepon = '';

    public string $lokasi_workshop = '';

    public string $shift = '';

    public string $stasiun_kerja = '';

    // Password fields
    public string $current_password = '';

    public string $new_password = '';

    public string $new_password_confirmation = '';

    public string $pesanSuksesProfil = '';

    public string $pesanSuksesPassword = '';

    public function mount(): void
    {
        $user = auth()->user();
        $this->name = $user->name ?? '';
        $this->username = $user->username ?? '';
        $this->email = $user->email ?? '';
        $this->telepon = $user->telepon ?? '';
        $this->lokasi_workshop = $user->lokasi_workshop ?? 'Lantai Jahit & Workshop #01';
        $this->shift = $user->shift ?? 'Shift Pagi (08:00 - 17:00 WIB)';
        $this->stasiun_kerja = $user->stasiun_kerja ?? 'Stasiun Kerja Budi';
    }

    public function simpanProfil(): void
    {
        $user = auth()->user();

        $this->validate([
            'name' => 'required|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'lokasi_workshop' => 'required|string|max:100',
            'shift' => 'required|string|max:100',
            'stasiun_kerja' => 'required|string|max:100',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'lokasi_workshop.required' => 'Lokasi workshop wajib diisi.',
            'shift.required' => 'Informasi shift wajib diisi.',
            'stasiun_kerja.required' => 'Stasiun kerja wajib diisi.',
        ]);

        $user->update([
            'name' => $this->name,
            'telepon' => $this->telepon,
            'lokasi_workshop' => $this->lokasi_workshop,
            'shift' => $this->shift,
            'stasiun_kerja' => $this->stasiun_kerja,
        ]);

        AktivitasLog::catat(
            aksi: 'memperbarui informasi profil staff',
            referensi: $user->username ?? $user->email,
            tipe: 'pengguna',
            warna: 'teal',
            namaUser: $user->name,
            keterangan: 'Pembaruan nama, telepon, dan stasiun kerja workshop.'
        );

        $this->pesanSuksesProfil = 'Profil dan preferensi workshop Anda berhasil diperbarui!';
    }

    public function simpanPassword(): void
    {
        $user = auth()->user();

        $this->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        AktivitasLog::catat(
            aksi: 'memperbarui kata sandi akun staff',
            referensi: $user->username ?? $user->email,
            tipe: 'pengguna',
            warna: 'teal',
            namaUser: $user->name,
            keterangan: 'Kata sandi akun operator produksi berhasil diperbarui secara mandiri.'
        );

        $this->pesanSuksesPassword = 'Kata sandi berhasil diperbarui dengan aman!';
    }

    public function render(): View
    {
        return view('livewire.staff.profil');
    }
}
