<?php

namespace App\Livewire\Staff;

use App\Models\AktivitasLog;
use App\Models\Pemesanan;
use App\Models\Produksi;
use App\Models\ProduksiTahapan;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Antrean & Alur Pengerjaan Pakaian - COTTON.id')]
#[Layout('layouts.staff')]
class AntreanProduksi extends Component
{
    public string $viewMode = 'departemen'; // departemen, kanban, tabel

    public string $filterLini = 'semua'; // semua, potong, jahit, steam, sablon

    public string $pesanSukses = '';

    public bool $showModalSupervisor = false;

    public string $pesanSupervisor = '';

    public function mount(): void
    {
        $role = auth()->user()?->role;
        $filterReq = request()->query('filter');

        if (in_array($role, ['pemotongan', 'pemotongan_kain', 'penjahitan', 'steam', 'sablon'])) {
            $this->viewMode = 'departemen';
        } else {
            $this->viewMode = 'kanban';
        }

        if ($filterReq && in_array($filterReq, ['potong', 'jahit', 'steam', 'sablon'])) {
            $this->filterLini = $filterReq;
        } elseif ($role === 'pemotongan' || $role === 'pemotongan_kain') {
            $this->filterLini = 'potong';
        } elseif ($role === 'penjahitan') {
            $this->filterLini = 'jahit';
        } elseif ($role === 'steam') {
            $this->filterLini = 'steam';
        } elseif ($role === 'sablon') {
            $this->filterLini = 'sablon';
        }
    }

    public function gantiView(string $mode): void
    {
        $this->viewMode = $mode;
    }

    public function mulaiTahap(int $produksiId, string $tahapNama): void
    {
        $user = auth()->user();
        $role = $user?->role;
        $tahapRole = match ($role) {
            'pemotongan', 'pemotongan_kain' => 'pemotongan_kain',
            'penjahitan' => 'penjahitan',
            'steam' => 'steam',
            'sablon' => 'sablon',
            default => null,
        };

        if ($tahapRole && $tahapNama !== $tahapRole) {
            $this->pesanSukses = "Anda hanya memiliki wewenang untuk memulai pengerjaan tahap {$tahapRole}.";
            return;
        }

        $produksi = Produksi::with(['pemesanan', 'tahapan'])->find($produksiId);
        if (! $produksi) {
            return;
        }

        // Validasi urutan tahap
        $tahapanSemua = $produksi->tahapan()->orderBy('urutan')->get();
        if ($tahapNama === 'penjahitan') {
            $prev = $tahapanSemua->firstWhere('nama_tahap', 'pemotongan_kain');
            if ($prev && $prev->status !== 'selesai') {
                $this->pesanSukses = 'Tahap pemotongan kain belum selesai! Harap menunggu giliran dari tim pemotongan.';
                return;
            }
        } elseif ($tahapNama === 'steam') {
            $prev = $tahapanSemua->firstWhere('nama_tahap', 'penjahitan');
            if ($prev && $prev->status !== 'selesai') {
                $this->pesanSukses = 'Tahap penjahitan belum selesai! Harap menunggu giliran dari tim penjahitan.';
                return;
            }
        } elseif ($tahapNama === 'sablon') {
            $prev = $tahapanSemua->firstWhere('nama_tahap', 'steam');
            if ($prev && $prev->status !== 'selesai') {
                $this->pesanSukses = 'Tahap steam belum selesai! Harap menunggu giliran dari tim steam.';
                return;
            }
        }

        $nomorSpk = $produksi->pemesanan?->nomor_pemesanan ?? $produksi->kode_produksi;

        $tahap = $produksi->tahapan->firstWhere('nama_tahap', $tahapNama);
        if (! $tahap) {
            $produksi->inisialisasiTahapan();
            $tahap = $produksi->tahapan()->where('nama_tahap', $tahapNama)->first();
        }

        if ($tahap) {
            $tahap->mulai($user, "Operator memulai pengerjaan tahap {$tahap->label_tahap}.");
            $this->pesanSukses = "Pengerjaan tahap {$tahap->label_tahap} untuk SPK {$nomorSpk} telah dimulai!";

            AktivitasLog::catat(
                aksi: "memulai pengerjaan {$tahap->label_tahap}",
                referensi: $nomorSpk,
                tipe: 'produksi',
                warna: 'teal',
                namaUser: $user->name,
                keterangan: "Operator {$user->name} memulai proses {$tahap->label_tahap} di lantai workshop."
            );
        }
    }

    public function konfirmasiSelesaiTahap(int $produksiId, string $tahapNama, string $catatan = ''): void
    {
        $user = auth()->user();
        $role = $user?->role;
        $tahapRole = match ($role) {
            'pemotongan', 'pemotongan_kain' => 'pemotongan_kain',
            'penjahitan' => 'penjahitan',
            'steam' => 'steam',
            'sablon' => 'sablon',
            default => null,
        };

        if ($tahapRole && $tahapNama !== $tahapRole) {
            $this->pesanSukses = "Anda hanya memiliki wewenang untuk menyelesaikan tahap {$tahapRole}.";
            return;
        }

        $produksi = Produksi::with(['pemesanan', 'tahapan'])->find($produksiId);
        if (! $produksi) {
            return;
        }

        // Validasi urutan tahap
        $tahapanSemua = $produksi->tahapan()->orderBy('urutan')->get();
        if ($tahapNama === 'penjahitan') {
            $prev = $tahapanSemua->firstWhere('nama_tahap', 'pemotongan_kain');
            if ($prev && $prev->status !== 'selesai') {
                $this->pesanSukses = 'Tahap pemotongan kain belum selesai! Harap menunggu giliran dari tim pemotongan.';
                return;
            }
        } elseif ($tahapNama === 'steam') {
            $prev = $tahapanSemua->firstWhere('nama_tahap', 'penjahitan');
            if ($prev && $prev->status !== 'selesai') {
                $this->pesanSukses = 'Tahap penjahitan belum selesai! Harap menunggu giliran dari tim penjahitan.';
                return;
            }
        } elseif ($tahapNama === 'sablon') {
            $prev = $tahapanSemua->firstWhere('nama_tahap', 'steam');
            if ($prev && $prev->status !== 'selesai') {
                $this->pesanSukses = 'Tahap steam belum selesai! Harap menunggu giliran dari tim steam.';
                return;
            }
        }

        $nomorSpk = $produksi->pemesanan?->nomor_pemesanan ?? $produksi->kode_produksi;

        $tahap = $produksi->tahapan->firstWhere('nama_tahap', $tahapNama);
        if (! $tahap) {
            $produksi->inisialisasiTahapan();
            $tahap = $produksi->tahapan()->where('nama_tahap', $tahapNama)->first();
        }

        if ($tahap) {
            $tahap->selesaikan($user, $catatan ?: "Tahap {$tahap->label_tahap} selesai dan dikonfirmasi operator.");

            // Pesan feedback informatif alur estafet antar departemen
            if ($tahapNama === 'pemotongan_kain') {
                $this->pesanSukses = "Pemotongan kain untuk {$nomorSpk} berhasil diselesaikan! Tugas Anda selesai dan pesanan otomatis masuk ke antrean Penjahitan.";
                $tahapBerikut = 'Penjahitan';
            } elseif ($tahapNama === 'penjahitan') {
                $this->pesanSukses = "Penjahitan untuk {$nomorSpk} berhasil diselesaikan! Tugas Anda selesai dan pesanan otomatis masuk ke antrean Steam.";
                $tahapBerikut = 'Steam';
            } elseif ($tahapNama === 'steam') {
                $perluSablon = $produksi->pemesanan?->perlu_sablon ?? true;
                if ($perluSablon) {
                    $this->pesanSukses = "Steam untuk {$nomorSpk} berhasil diselesaikan! Tugas Anda selesai dan pesanan otomatis masuk ke antrean Sablon.";
                    $tahapBerikut = 'Sablon';
                } else {
                    $this->pesanSukses = "Pesanan {$nomorSpk} telah selesai diproduksi seluruhnya! Konfirmasi penyelesaian telah dikirim ke Admin.";
                    $tahapBerikut = 'Selesai Produksi (Admin)';
                }
            } elseif ($tahapNama === 'sablon') {
                $this->pesanSukses = "Proses Sablon untuk {$nomorSpk} selesai! Seluruh pesanan telah selesai diproduksi dan konfirmasi dikirim ke Admin.";
                $tahapBerikut = 'Selesai Produksi (Admin)';
            } else {
                $this->pesanSukses = "Tahap {$tahap->label_tahap} untuk {$nomorSpk} berhasil diselesaikan!";
                $tahapBerikut = 'Tahap Berikutnya';
            }

            AktivitasLog::catat(
                aksi: "menyelesaikan tahap {$tahap->label_tahap}",
                referensi: $nomorSpk,
                tipe: 'produksi',
                warna: 'emerald',
                namaUser: $user->name,
                keterangan: "Pengerjaan {$tahap->label_tahap} selesai. Pesanan diteruskan ke: {$tahapBerikut}."
            );
        }
    }

    public function lanjutkanTahap(int $produksiId, string $tahapNama): void
    {
        $produksi = Produksi::with(['pemesanan', 'tahapan'])->find($produksiId);
        if (! $produksi) {
            return;
        }

        $tahap = $produksi->tahapan->firstWhere('nama_tahap', $tahapNama);
        if ($tahap && $tahap->status === 'sedang_dikerjakan') {
            $this->konfirmasiSelesaiTahap($produksiId, $tahapNama);
        } else {
            $this->mulaiTahap($produksiId, $tahapNama);
        }
    }

    public function panggilSupervisor(): void
    {
        $this->validate([
            'pesanSupervisor' => 'required|min:5',
        ], [
            'pesanSupervisor.required' => 'Tuliskan pesan bantuan untuk supervisor.',
            'pesanSupervisor.min' => 'Pesan minimal 5 karakter.',
        ]);

        $user = auth()->user();
        AktivitasLog::create([
            'user_id' => $user->id,
            'nama_user' => $user->name,
            'tipe' => 'produksi',
            'aksi' => 'Panggilan Bantuan Supervisor Lantai Workshop',
            'referensi' => 'Lantai Workshop #01',
            'warna' => 'amber',
            'keterangan' => $this->pesanSupervisor,
            'created_at' => now(),
        ]);

        $this->showModalSupervisor = false;
        $this->pesanSupervisor = '';
        $this->pesanSukses = 'Panggilan bantuan telah dikirim ke Supervisor Lantai Workshop!';
    }

    public function render(): View
    {
        $roleStaff = auth()->user()?->role ?? 'staff';

        // Pastikan relasi tahapan terinisialisasi untuk semua pesanan aktif
        $daftarProduksi = Produksi::whereIn('status_produksi', ['antrian', 'proses'])->get();
        foreach ($daftarProduksi as $p) {
            $p->inisialisasiTahapan();
        }

        // Ambil SPK untuk masing-masing lini workshop
        // 1. Pemotongan (Tahap 1)
        $spkPemotongan = Produksi::with(['pemesanan.pelanggan', 'pemesanan.variasi.produk', 'tahapan.operator'])
            ->where('tahap_saat_ini', 'pemotongan_kain')
            ->whereIn('status_produksi', ['antrian', 'proses'])
            ->orderBy('id', 'desc')
            ->get();

        // 2. Penjahitan (Tahap 2)
        $spkPenjahitan = Produksi::with(['pemesanan.pelanggan', 'pemesanan.variasi.produk', 'tahapan.operator'])
            ->where('tahap_saat_ini', 'penjahitan')
            ->whereIn('status_produksi', ['antrian', 'proses'])
            ->orderBy('id', 'desc')
            ->get();

        // 3. Steam (Tahap 3)
        $spkSteam = Produksi::with(['pemesanan.pelanggan', 'pemesanan.variasi.produk', 'tahapan.operator'])
            ->where('tahap_saat_ini', 'steam')
            ->whereIn('status_produksi', ['antrian', 'proses'])
            ->orderBy('id', 'desc')
            ->get();

        // 4. Sablon (Tahap 4)
        $spkSablon = Produksi::with(['pemesanan.pelanggan', 'pemesanan.variasi.produk', 'tahapan.operator'])
            ->where('tahap_saat_ini', 'sablon')
            ->whereIn('status_produksi', ['antrian', 'proses'])
            ->orderBy('id', 'desc')
            ->get();

        // Riwayat yang sudah selesai untuk departemen masing-masing
        $riwayatDepartemen = match ($roleStaff) {
            'pemotongan', 'pemotongan_kain' => Produksi::with(['pemesanan.pelanggan', 'tahapan'])
                ->where('status_pemotongan_kain', 'selesai')->orderBy('id', 'desc')->take(10)->get(),
            'penjahitan' => Produksi::with(['pemesanan.pelanggan', 'tahapan'])
                ->where('status_penjahitan', 'selesai')->orderBy('id', 'desc')->take(10)->get(),
            'steam' => Produksi::with(['pemesanan.pelanggan', 'tahapan'])
                ->where('status_steam', 'selesai')->orderBy('id', 'desc')->take(10)->get(),
            'sablon' => Produksi::with(['pemesanan.pelanggan', 'tahapan'])
                ->where('status_sablon', 'selesai')->orderBy('id', 'desc')->take(10)->get(),
            default => collect(),
        };

        // Seluruh antrean untuk mode tabel (jika role umum) atau filter departemen
        $semuaAntrean = match ($roleStaff) {
            'pemotongan', 'pemotongan_kain' => $spkPemotongan,
            'penjahitan' => $spkPenjahitan,
            'steam' => $spkSteam,
            'sablon' => $spkSablon,
            default => Produksi::with(['pemesanan.pelanggan', 'pemesanan.variasi.produk', 'tahapan.operator'])
                ->whereIn('status_produksi', ['antrian', 'proses'])
                ->orderBy('id', 'desc')
                ->get(),
        };

        // Hitung statistik spesifik departemen
        $targetSpk = match ($roleStaff) {
            'pemotongan', 'pemotongan_kain' => $spkPemotongan,
            'penjahitan' => $spkPenjahitan,
            'steam' => $spkSteam,
            'sablon' => $spkSablon,
            default => $spkPemotongan,
        };

        $tahapKey = match ($roleStaff) {
            'pemotongan', 'pemotongan_kain' => 'pemotongan_kain',
            'penjahitan' => 'penjahitan',
            'steam' => 'steam',
            'sablon' => 'sablon',
            default => 'pemotongan_kain',
        };

        $sedangDikerjakanCount = $targetSpk->filter(function ($prod) use ($tahapKey) {
            $t = $prod->tahapan->firstWhere('nama_tahap', $tahapKey);
            return $t && $t->status === 'sedang_dikerjakan';
        })->count();

        $menungguCount = max(0, $targetSpk->count() - $sedangDikerjakanCount);
        $selesaiDepartemenCount = $riwayatDepartemen->count();

        return view('livewire.staff.antrean-produksi', [
            'spkPemotongan' => $spkPemotongan,
            'spkPenjahitan' => $spkPenjahitan,
            'spkSteam' => $spkSteam,
            'spkSablon' => $spkSablon,
            'riwayatDepartemen' => $riwayatDepartemen,
            'semuaAntrean' => $semuaAntrean,
            'roleStaff' => $roleStaff,
            'sedangDikerjakanCount' => $sedangDikerjakanCount,
            'menungguCount' => $menungguCount,
            'selesaiDepartemenCount' => $selesaiDepartemenCount,
        ]);
    }
}
