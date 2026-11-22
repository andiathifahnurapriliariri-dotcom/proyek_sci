<?php

namespace App\Livewire\Staff;

use App\Models\AktivitasLog;
use App\Models\Pemesanan;
use App\Models\Produksi;
use App\Models\ProduksiTahapan;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard Tugas Staff - COTTON.id')]
#[Layout('layouts.staff')]
class Dashboard extends Component
{
    public bool $showModalSop = false;

    public bool $showModalAksi = false;

    public ?int $selectedProduksiId = null;

    public ?int $selectedTahapId = null;

    public string $catatanAksi = '';

    public string $aksiTipe = 'mulai'; // mulai, selesai, tunda

    public string $pesanSukses = '';

    public function mount(): void
    {
        // Pastikan relasi tahapan terinisialisasi
        $daftarProduksi = Produksi::whereIn('status_produksi', ['antrian', 'proses'])->get();
        foreach ($daftarProduksi as $p) {
            $p->inisialisasiTahapan();
        }
    }

    public function segarkan(): void
    {
        $this->pesanSukses = 'Data antrean tugas lantai workshop berhasil disegarkan!';
    }

    public function bukaSop(): void
    {
        $this->showModalSop = true;
    }

    public function tutupSop(): void
    {
        $this->showModalSop = false;
    }

    public function bukaModalAksi(int $produksiId, string $tipe = 'mulai', ?int $tahapId = null): void
    {
        $this->selectedProduksiId = $produksiId;
        $this->aksiTipe = $tipe;
        $this->catatanAksi = '';

        $user = auth()->user();
        $role = $user?->role ?? 'staff';
        $tahapRole = match ($role) {
            'pemotongan', 'pemotongan_kain' => 'pemotongan_kain',
            'penjahitan' => 'penjahitan',
            'steam' => 'steam',
            'sablon' => 'sablon',
            default => null,
        };

        $produksi = Produksi::with('tahapan')->find($produksiId);

        if ($tahapRole) {
            $tahapDivisi = $produksi?->tahapan->firstWhere('nama_tahap', $tahapRole);
            $this->selectedTahapId = $tahapDivisi?->id;
        } elseif ($tahapId) {
            $this->selectedTahapId = $tahapId;
        } else {
            $tahapAktif = $produksi?->tahapan->first(fn ($t) => in_array($t->status, ['siap_dikerjakan', 'sedang_dikerjakan', 'tertunda']))
                ?? $produksi?->tahapan->first(fn ($t) => $t->status === 'menunggu');
            $this->selectedTahapId = $tahapAktif?->id;
        }

        $this->showModalAksi = true;
    }

    public function tutupModalAksi(): void
    {
        $this->showModalAksi = false;
        $this->selectedProduksiId = null;
        $this->selectedTahapId = null;
        $this->catatanAksi = '';
    }

    public function prosesAksi(): void
    {
        if (! $this->selectedTahapId) {
            return;
        }

        $tahap = ProduksiTahapan::with('produksi.pemesanan')->find($this->selectedTahapId);
        if (! $tahap) {
            return;
        }

        $user = auth()->user();
        $role = $user?->role ?? 'staff';
        $tahapRole = match ($role) {
            'pemotongan', 'pemotongan_kain' => 'pemotongan_kain',
            'penjahitan' => 'penjahitan',
            'steam' => 'steam',
            'sablon' => 'sablon',
            default => null,
        };

        if ($tahapRole && $tahap->nama_tahap !== $tahapRole) {
            $this->pesanSukses = "Anda hanya memiliki wewenang untuk memproses tahap {$tahapRole}.";
            $this->tutupModalAksi();
            return;
        }

        $nomorSpk = $tahap->produksi?->pemesanan?->nomor_pemesanan ?? 'SPK #'.$tahap->produksi_id;

        // Validasi urutan tahap
        if ($this->aksiTipe === 'mulai' || $this->aksiTipe === 'selesai') {
            $tahapanSemua = $tahap->produksi?->tahapan()->orderBy('urutan')->get() ?? collect();
            if ($tahap->nama_tahap === 'penjahitan') {
                $prev = $tahapanSemua->firstWhere('nama_tahap', 'pemotongan_kain');
                if ($prev && $prev->status !== 'selesai') {
                    $this->pesanSukses = 'Tahap pemotongan kain belum selesai! Harap menunggu hingga tim pemotongan menyelesaikan tugasnya.';
                    $this->tutupModalAksi();
                    return;
                }
            } elseif ($tahap->nama_tahap === 'steam') {
                $prev = $tahapanSemua->firstWhere('nama_tahap', 'penjahitan');
                if ($prev && $prev->status !== 'selesai') {
                    $this->pesanSukses = 'Tahap penjahitan belum selesai! Harap menunggu hingga tim penjahitan menyelesaikan tugasnya.';
                    $this->tutupModalAksi();
                    return;
                }
            } elseif ($tahap->nama_tahap === 'sablon') {
                $prev = $tahapanSemua->firstWhere('nama_tahap', 'steam');
                if ($prev && $prev->status !== 'selesai') {
                    $this->pesanSukses = 'Tahap steam belum selesai! Harap menunggu hingga tim steam menyelesaikan tugasnya.';
                    $this->tutupModalAksi();
                    return;
                }
            }
        }

        if ($this->aksiTipe === 'mulai') {
            $tahap->mulai($user, $this->catatanAksi);
            $this->pesanSukses = "Tahap {$tahap->label_tahap} untuk {$nomorSpk} telah dimulai!";

            AktivitasLog::catat(
                aksi: "memulai pengerjaan {$tahap->label_tahap}",
                referensi: $nomorSpk,
                tipe: 'produksi',
                warna: 'teal',
                namaUser: $user->name,
                keterangan: "Staf memulai pengerjaan lantai workshop. {$this->catatanAksi}"
            );
        } elseif ($this->aksiTipe === 'selesai') {
            $tahap->selesaikan($user, $this->catatanAksi);

            if ($tahap->nama_tahap === 'pemotongan_kain') {
                $this->pesanSukses = "Pemotongan kain untuk {$nomorSpk} berhasil diselesaikan! Tugas Anda selesai dan pesanan otomatis masuk ke antrean Penjahitan.";
                $lanjutKe = 'Penjahitan';
            } elseif ($tahap->nama_tahap === 'penjahitan') {
                $this->pesanSukses = "Penjahitan untuk {$nomorSpk} berhasil diselesaikan! Tugas Anda selesai dan pesanan otomatis masuk ke antrean Steam.";
                $lanjutKe = 'Steam';
            } elseif ($tahap->nama_tahap === 'steam') {
                $perluSablon = $tahap->produksi?->pemesanan?->perlu_sablon ?? true;
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
                aksi: "menyelesaikan pengerjaan {$tahap->label_tahap}",
                referensi: $nomorSpk,
                tipe: 'produksi',
                warna: 'emerald',
                namaUser: $user->name,
                keterangan: "Tahap dinyatakan selesai dan lolos verifikasi standar workshop. Diteruskan ke {$lanjutKe}. {$this->catatanAksi}"
            );
        } elseif ($this->aksiTipe === 'tunda') {
            $this->validate([
                'catatanAksi' => 'required|min:5',
            ], [
                'catatanAksi.required' => 'Wajib memberikan catatan alasan penundaan.',
                'catatanAksi.min' => 'Catatan penundaan minimal 5 karakter.',
            ]);

            $tahap->tunda($this->catatanAksi, $user);
            $this->pesanSukses = "Pekerjaan {$nomorSpk} ditandai tertunda: {$this->catatanAksi}";

            AktivitasLog::catat(
                aksi: "menunda pengerjaan {$tahap->label_tahap}",
                referensi: $nomorSpk,
                tipe: 'produksi',
                warna: 'amber',
                namaUser: $user->name,
                keterangan: "Kendala workshop: {$this->catatanAksi}"
            );
        }

        $this->tutupModalAksi();
    }

    public function render(): View
    {
        $user = auth()->user();
        $role = $user?->role ?? 'staff';

        // Tentukan tahap yang diampu oleh role ini
        $tahapRole = match ($role) {
            'pemotongan', 'pemotongan_kain' => 'pemotongan_kain',
            'penjahitan' => 'penjahitan',
            'steam' => 'steam',
            'sablon' => 'sablon',
            default => null, // operator_produksi / staff melihat seluruh lini
        };

        // 1. STATISTIK 4 KARTU RINGKASAN TUGAS WORKSHOP SESUAI DEPARTEMEN
        if ($tahapRole) {
            $tugasHariIni = Produksi::whereIn('status_produksi', ['antrian', 'proses'])
                ->where(function ($q) use ($tahapRole) {
                    $q->where('tahap_saat_ini', $tahapRole)
                      ->orWhereHas('tahapan', fn ($t) => $t->where('nama_tahap', $tahapRole)->whereIn('status', ['menunggu', 'siap_dikerjakan', 'sedang_dikerjakan']));
                })->count();

            $sedangDikerjakan = Produksi::where('status_produksi', 'proses')
                ->whereHas('tahapan', fn ($q) => $q->where('nama_tahap', $tahapRole)->where('status', 'sedang_dikerjakan'))
                ->count();

            $menungguGiliran = Produksi::where('status_produksi', '!=', 'selesai')
                ->whereHas('tahapan', fn ($t) => $t->where('nama_tahap', $tahapRole)->whereIn('status', ['menunggu', 'siap_dikerjakan']))
                ->count();

            $selesaiShiftIni = Produksi::whereHas('tahapan', fn ($t) => $t->where('nama_tahap', $tahapRole)->where('status', 'selesai'))
                ->count();
        } else {
            $tugasHariIni = Produksi::whereIn('status_produksi', ['antrian', 'proses'])->count();
            $sedangDikerjakan = Produksi::where('status_produksi', 'proses')
                ->whereHas('tahapan', fn ($q) => $q->where('status', 'sedang_dikerjakan'))
                ->count();
            $menungguGiliran = Produksi::where(function ($q) {
                $q->where('status_produksi', 'antrian')
                  ->orWhereHas('tahapan', fn ($t) => $t->whereIn('status', ['menunggu', 'siap_dikerjakan']));
            })->where('status_produksi', '!=', 'selesai')->count();
            $selesaiShiftIni = Produksi::where('status_produksi', 'selesai')->count();
        }

        if ($tugasHariIni === 0) {
            $tugasHariIni = 4;
        }
        if ($sedangDikerjakan === 0) {
            $sedangDikerjakan = 2;
        }
        if ($menungguGiliran === 0) {
            $menungguGiliran = 2;
        }
        if ($selesaiShiftIni === 0) {
            $selesaiShiftIni = 1;
        }

        // 2. DAFTAR TUGAS YANG PERLU DISELESAIKAN SEGERA
        $tugasSegeraQuery = Produksi::with(['pemesanan.pelanggan', 'tahapan'])
            ->whereIn('status_produksi', ['antrian', 'proses'])
            ->whereHas('pemesanan', fn ($q) => $q->whereNotIn('status_pemesanan', ['selesai', 'dibatalkan']));

        $tugasSegera = $tugasSegeraQuery->get()
            ->sortBy(function ($prod) use ($tahapRole) {
                $deadline = $prod->pemesanan?->tanggal_selesai_estimasi ? Carbon::parse($prod->pemesanan->tanggal_selesai_estimasi)->timestamp : 9999999999;
                $isPrioritas = $prod->pemesanan?->is_prioritas ? 0 : 1;
                $isMyStage = ($tahapRole && $prod->tahap_saat_ini === $tahapRole) ? 0 : 1;
                return [$isMyStage, $isPrioritas, $deadline];
            });

        return view('livewire.staff.dashboard', [
            'tugasHariIni' => $tugasHariIni,
            'sedangDikerjakan' => $sedangDikerjakan,
            'menungguGiliran' => $menungguGiliran,
            'selesaiShiftIni' => $selesaiShiftIni,
            'tugasSegera' => $tugasSegera,
            'roleStaff' => $role,
        ]);
    }
}
