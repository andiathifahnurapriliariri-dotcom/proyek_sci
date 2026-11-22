<?php

namespace App\Livewire\Staff;

use App\Models\AktivitasLog;
use App\Models\Pemesanan;
use App\Models\Produksi;
use App\Models\ProduksiTahapan;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Antrean Cetak Sablon & Kuota Desain - COTTON.id')]
#[Layout('layouts.staff')]
class AntreanSablon extends Component
{
    public string $pesanSukses = '';

    public string $filterKluster = 'semua';

    public function mount(): void
    {
        $role = auth()->user()?->role;
        if (in_array($role, ['pemotongan', 'pemotongan_kain', 'penjahitan', 'steam'])) {
            $this->redirectRoute('staff.dashboard');
        }
    }

    public function filterKlusterSet(string $kode): void
    {
        $this->filterKluster = $kode;
    }

    public function mulaiSablonKluster(string $kodeGambar): void
    {
        $pesanans = Pemesanan::where('kode_gambar', $kodeGambar)
            ->whereIn('status_pemesanan', ['antrian_produksi', 'dalam_produksi'])
            ->get();

        $user = auth()->user();

        foreach ($pesanans as $pesanan) {
            $pesanan->update(['status_sablon_antrean' => 'sedang_cetak']);
            $tahap = $pesanan->produksi?->tahapan->firstWhere('nama_tahap', 'sablon');
            if ($tahap) {
                $tahap->mulai($user, "Cetak sablon kluster {$kodeGambar} sedang berjalan di Meja Cetak B");
            }
        }

        AktivitasLog::catat(
            aksi: "memulai cetak massal sablon kluster {$kodeGambar}",
            referensi: $kodeGambar,
            tipe: 'produksi',
            warna: 'teal',
            namaUser: $user->name,
            keterangan: "Proses sablon kluster dengan screen afdruk terpasang di Meja Cetak B."
        );

        $this->pesanSukses = "Proses sablon kluster {$kodeGambar} berhasil dimulai di Meja Cetak B!";
    }

    public function tambahUjiCoba(string $kodeGambar): void
    {
        $pesanan = Pemesanan::where('kode_gambar', $kodeGambar)->first();
        if ($pesanan) {
            $pesanan->increment('kuota_sablon_saat_ini', 2);
            if ($pesanan->kuota_sablon_saat_ini >= 5) {
                $pesanan->update(['status_sablon_antrean' => 'siap_cetak']);
            }
            $this->pesanSukses = "Sampel uji coba 2 pcs berhasil ditambahkan ke kluster {$kodeGambar}!";
        }
    }

    public function perbaruiStatus(): void
    {
        $this->pesanSukses = 'Status antrean kuota sablon telah diperbarui secara real-time!';
    }

    public function render(): View
    {
        // Ambil SPK sablon yang terdaftar
        $queryRincian = Pemesanan::with(['pelanggan', 'produksi'])
            ->where('perlu_sablon', true)
            ->whereIn('nomor_pemesanan', ['ORD-2026-002', 'ORD-2026-004', 'ORD-2026-008'])
            ->orWhere(function ($q) {
                $q->where('perlu_sablon', true)
                  ->whereNotNull('kode_gambar')
                  ->whereIn('status_pemesanan', ['antrian_produksi', 'dalam_produksi']);
            });

        if ($this->filterKluster !== 'semua') {
            $queryRincian->where('kode_gambar', $this->filterKluster);
        }

        $rincianAntrean = $queryRincian->get()->sortBy('nomor_pemesanan');

        return view('livewire.staff.antrean-sablon', [
            'rincianAntrean' => $rincianAntrean,
        ]);
    }
}
