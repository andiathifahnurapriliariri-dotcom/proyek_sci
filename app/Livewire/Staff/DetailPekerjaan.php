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

#[Title('Detail Pekerjaan SPK - COTTON.id')]
#[Layout('layouts.staff')]
class DetailPekerjaan extends Component
{
    public ?int $pemesananId = null;

    public ?Pemesanan $pesanan = null;

    public ?Produksi $produksi = null;

    public string $activeTab = 'alur'; // alur, detail, sablon, log

    public string $catatanOperator = '';

    public string $kendalaLaporan = '';

    public bool $showModalKendala = false;

    public string $pesanSukses = '';

    public function mount(?int $pemesananId = null): void
    {
        if ($pemesananId) {
            $this->pemesananId = $pemesananId;
        } else {
            // Utamakan SPK ORD-2026-001 seperti pada screenshot jika ada
            $spkUtama = Pemesanan::where('nomor_pemesanan', 'ORD-2026-001')->first()
                ?? Pemesanan::whereIn('status_pemesanan', ['antrian_produksi', 'dalam_produksi'])->first()
                ?? Pemesanan::latest()->first();

            $this->pemesananId = $spkUtama?->id;
        }

        $this->muatDataPesanan();
    }

    public function updatedPemesananId(): void
    {
        $this->muatDataPesanan();
    }

    public function muatDataPesanan(): void
    {
        if (! $this->pemesananId) {
            return;
        }

        $this->pesanan = Pemesanan::with(['pelanggan', 'admin', 'produksi.tahapan.operator', 'variasi'])->find($this->pemesananId);

        if ($this->pesanan && ! $this->pesanan->produksi) {
            $this->produksi = Produksi::create([
                'kode_produksi' => 'PRD-' . now()->format('Y') . '-' . str_pad((string) $this->pesanan->id, 4, '0', STR_PAD_LEFT),
                'pemesanan_id' => $this->pesanan->id,
                'user_id' => auth()->id(),
                'tanggal_mulai' => now()->toDateString(),
                'tahap_saat_ini' => 'pemotongan_kain',
                'progress_persen' => 0,
                'status_produksi' => 'proses',
                'catatan_produksi' => 'Mulai pengerjaan workshop',
            ]);
            $this->produksi->inisialisasiTahapan($this->pesanan->perlu_sablon);
        } else {
            $this->produksi = $this->pesanan?->produksi;
            $this->produksi?->inisialisasiTahapan($this->pesanan?->perlu_sablon);
        }
    }

    public function getTahapDepartemenProperty(): ?ProduksiTahapan
    {
        if (! $this->produksi) {
            return null;
        }

        $user = auth()->user();
        $role = $user?->role;
        $tahapRole = match ($role) {
            'pemotongan', 'pemotongan_kain' => 'pemotongan_kain',
            'penjahitan' => 'penjahitan',
            'steam' => 'steam',
            'sablon' => 'sablon',
            default => null,
        };

        $tahapan = $this->produksi->tahapan()->orderBy('urutan')->get();

        if ($tahapRole) {
            return $tahapan->firstWhere('nama_tahap', $tahapRole);
        }

        return $tahapan->firstWhere('status', 'sedang_dikerjakan')
            ?? $tahapan->firstWhere('status', 'siap_dikerjakan')
            ?? $tahapan->first();
    }

    public function bisaKerjakanTahap(?ProduksiTahapan $tahap = null): bool
    {
        $tahap = $tahap ?? $this->tahapDepartemen;
        if (! $tahap || ! $this->produksi) {
            return false;
        }

        $tahapan = $this->produksi->tahapan()->orderBy('urutan')->get();

        // Validasi urutan tahap sebelumnya
        if ($tahap->nama_tahap === 'penjahitan') {
            $prev = $tahapan->firstWhere('nama_tahap', 'pemotongan_kain');
            if ($prev && $prev->status !== 'selesai') {
                return false;
            }
        } elseif ($tahap->nama_tahap === 'steam') {
            $prev = $tahapan->firstWhere('nama_tahap', 'penjahitan');
            if ($prev && $prev->status !== 'selesai') {
                return false;
            }
        } elseif ($tahap->nama_tahap === 'sablon') {
            $prev = $tahapan->firstWhere('nama_tahap', 'steam');
            if ($prev && $prev->status !== 'selesai') {
                return false;
            }
        }

        return true;
    }

    public function mulaiTahapDepartemen(): void
    {
        if (! $this->produksi) {
            return;
        }

        $tahap = $this->tahapDepartemen;
        if (! $tahap) {
            $this->pesanSukses = 'Tahapan pekerjaan tidak ditemukan.';
            return;
        }

        if (! $this->bisaKerjakanTahap($tahap)) {
            $this->pesanSukses = 'Tahap sebelumnya belum selesai. Harap menunggu hingga tahap sebelumnya selesai dikerjakan.';
            return;
        }

        $user = auth()->user();
        $nomorSpk = $this->pesanan?->nomor_pemesanan ?? ('SPK #' . $this->pemesananId);

        $tahap->mulai($user, "Pengerjaan tahap {$tahap->label_tahap} dimulai oleh {$user->name}");

        AktivitasLog::catat(
            aksi: "memulai {$tahap->label_tahap}",
            referensi: $nomorSpk,
            tipe: 'produksi',
            warna: 'teal',
            namaUser: $user->name,
            keterangan: "Operator memulai pengerjaan lantai workshop."
        );

        $this->pesanSukses = "Tahap {$tahap->label_tahap} untuk {$nomorSpk} berhasil dimulai!";
        $this->muatDataPesanan();
    }

    public function selesaikanTahapDepartemen(): void
    {
        if (! $this->produksi) {
            return;
        }

        $tahap = $this->tahapDepartemen;
        if (! $tahap) {
            $this->pesanSukses = 'Tahapan pekerjaan tidak ditemukan.';
            return;
        }

        if ($tahap->status === 'selesai') {
            $this->pesanSukses = "Tahap {$tahap->label_tahap} untuk SPK ini sudah diselesaikan!";
            return;
        }

        if (! $this->bisaKerjakanTahap($tahap)) {
            $this->pesanSukses = 'Tahap sebelumnya belum selesai. Harap menunggu giliran pengerjaan.';
            return;
        }

        $user = auth()->user();
        $nomorSpk = $this->pesanan?->nomor_pemesanan ?? ('SPK #' . $this->pemesananId);

        $tahap->selesaikan($user, "Tahap {$tahap->label_tahap} diselesaikan oleh {$user->name}");

        // Berikan pesan umpan balik estafet spesifik
        if ($tahap->nama_tahap === 'pemotongan_kain') {
            $this->pesanSukses = "Pemotongan kain untuk {$nomorSpk} berhasil diselesaikan! Tugas Anda selesai dan pesanan otomatis masuk ke antrean Penjahitan.";
            $lanjutKe = 'Penjahitan';
        } elseif ($tahap->nama_tahap === 'penjahitan') {
            $this->pesanSukses = "Penjahitan untuk {$nomorSpk} berhasil diselesaikan! Tugas Anda selesai dan pesanan otomatis masuk ke antrean Steam.";
            $lanjutKe = 'Steam';
        } elseif ($tahap->nama_tahap === 'steam') {
            $perluSablon = $this->pesanan?->perlu_sablon ?? true;
            if ($perluSablon) {
                $this->pesanSukses = "Proses Steam untuk {$nomorSpk} berhasil diselesaikan! Tugas Anda selesai dan pesanan otomatis masuk ke antrean Sablon.";
                $lanjutKe = 'Sablon';
            } else {
                $this->pesanSukses = "Proses Steam untuk {$nomorSpk} selesai! Seluruh rangkaian produksi telah rampung dan konfirmasi telah dikirim ke Admin.";
                $lanjutKe = 'Selesai Produksi (Admin)';
            }
        } elseif ($tahap->nama_tahap === 'sablon') {
            $this->pesanSukses = "Proses Sablon untuk {$nomorSpk} selesai! Seluruh proses produksi pakaian telah rampung dan konfirmasi penyelesaian telah diterima Admin.";
            $lanjutKe = 'Selesai Produksi (Admin)';
        } else {
            $this->pesanSukses = "Tahap {$tahap->label_tahap} untuk {$nomorSpk} berhasil diselesaikan!";
            $lanjutKe = 'Tahap Berikutnya';
        }

        AktivitasLog::catat(
            aksi: "menyelesaikan {$tahap->label_tahap}",
            referensi: $nomorSpk,
            tipe: 'produksi',
            warna: 'emerald',
            namaUser: $user->name,
            keterangan: "Pengerjaan selesai dan diteruskan ke: {$lanjutKe}."
        );

        $this->muatDataPesanan();
    }

    public function selesaikanTahapAktif(): void
    {
        $this->selesaikanTahapDepartemen();
    }

    public function kirimCatatanOperator(): void
    {
        $this->validate([
            'catatanOperator' => 'required|string|min:3',
        ], [
            'catatanOperator.required' => 'Tuliskan catatan terlebih dahulu.',
            'catatanOperator.min' => 'Catatan minimal 3 karakter.',
        ]);

        $user = auth()->user();
        $nomorSpk = $this->pesanan?->nomor_pemesanan ?? ('SPK #' . $this->pemesananId);

        AktivitasLog::create([
            'user_id' => $user->id,
            'nama_user' => $user->name,
            'tipe' => 'produksi',
            'aksi' => 'Catatan Tambahan Operator Meja',
            'referensi' => $nomorSpk,
            'warna' => 'teal',
            'keterangan' => $this->catatanOperator,
            'created_at' => now(),
        ]);

        $this->catatanOperator = '';
        $this->pesanSukses = 'Catatan operator berhasil disimpan ke log pengerjaan SPK!';
        $this->muatDataPesanan();
    }

    public function laporkanKendala(): void
    {
        $this->validate([
            'kendalaLaporan' => 'required|string|min:5',
        ], [
            'kendalaLaporan.required' => 'Jelaskan kendala pengerjaan yang terjadi.',
            'kendalaLaporan.min' => 'Deskripsi kendala minimal 5 karakter.',
        ]);

        $user = auth()->user();
        $nomorSpk = $this->pesanan?->nomor_pemesanan ?? ('SPK #' . $this->pemesananId);

        AktivitasLog::create([
            'user_id' => $user->id,
            'nama_user' => $user->name,
            'tipe' => 'produksi',
            'aksi' => 'Laporan Kendala Pengerjaan Lantai Workshop',
            'referensi' => $nomorSpk,
            'warna' => 'amber',
            'keterangan' => $this->kendalaLaporan,
            'created_at' => now(),
        ]);

        $this->showModalKendala = false;
        $this->kendalaLaporan = '';
        $this->pesanSukses = 'Laporan kendala berhasil dikirim ke supervisor & tercatat di sistem!';
        $this->muatDataPesanan();
    }

    public function render(): View
    {
        $daftarPilihanSpk = Pemesanan::orderBy('id', 'desc')->get();

        $tahapan = $this->produksi?->tahapan()->with('operator')->orderBy('urutan')->get() ?? collect();

        $riwayatLog = collect();
        if ($this->pesanan) {
            $riwayatLog = AktivitasLog::where('referensi', $this->pesanan->nomor_pemesanan)
                ->orWhere('referensi', $this->produksi?->kode_produksi)
                ->orderBy('created_at', 'desc')
                ->take(15)
                ->get();
        }

        return view('livewire.staff.detail-pekerjaan', [
            'daftarPilihanSpk' => $daftarPilihanSpk,
            'tahapan' => $tahapan,
            'riwayatLog' => $riwayatLog,
        ]);
    }
}
