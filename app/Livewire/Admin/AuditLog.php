<?php

namespace App\Livewire\Admin;

use App\Models\AktivitasLog;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Title('Audit Log & Riwayat Sistem - COTTON.id')]
#[Layout('layouts.admin')]
class AuditLog extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterTipe = 'semua';
    public string $filterRentang = 'semua';
    public string $filterUser = 'semua';
    public int $perPage = 15;

    public bool $showModalDetail = false;
    public ?int $selectedLogId = null;

    protected $queryString = [
        'search'        => ['except' => ''],
        'filterTipe'    => ['except' => 'semua'],
        'filterRentang' => ['except' => 'semua'],
        'filterUser'    => ['except' => 'semua'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterTipe(): void
    {
        $this->resetPage();
    }

    public function updatingFilterRentang(): void
    {
        $this->resetPage();
    }

    public function updatingFilterUser(): void
    {
        $this->resetPage();
    }

    public function resetFilter(): void
    {
        $this->reset(['search', 'filterTipe', 'filterRentang', 'filterUser']);
        $this->resetPage();
    }

    public function lihatDetail(int $id): void
    {
        $this->selectedLogId = $id;
        $this->showModalDetail = true;
    }

    public function tutupModalDetail(): void
    {
        $this->showModalDetail = false;
        $this->selectedLogId = null;
    }

    public function eksporCsv(): StreamedResponse
    {
        $logs = $this->queryLog()->get();
        $namaFile = 'audit-log-cotton-id-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($logs): void {
            $handle = fopen('php://output', 'w');
            // Menulis UTF-8 BOM agar terbaca rapi di Microsoft Excel
            fputs($handle, "\xEF\xBB\xBF");

            // Header CSV
            fputcsv($handle, [
                'ID Log',
                'Waktu & Tanggal',
                'Pengguna / PIC',
                'Kategori / Modul',
                'Aksi / Aktivitas',
                'Nomor Referensi',
                'Keterangan Lengkap',
            ]);

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->id,
                    $log->created_at ? $log->created_at->translatedFormat('d F Y, H:i:s') : '-',
                    $log->nama_user ?? 'Sistem',
                    ucfirst($log->tipe ?? 'Umum'),
                    $log->aksi,
                    $log->referensi ?? '-',
                    $log->keterangan ?? '-',
                ]);
            }

            fclose($handle);
        }, $namaFile, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function queryLog(): Builder
    {
        return AktivitasLog::query()
            ->when($this->search !== '', function (Builder $query): void {
                $search = trim($this->search);
                $query->where(function (Builder $sub) use ($search): void {
                    $sub->where('aksi', 'like', "%{$search}%")
                        ->orWhere('referensi', 'like', "%{$search}%")
                        ->orWhere('keterangan', 'like', "%{$search}%")
                        ->orWhere('nama_user', 'like', "%{$search}%");
                });
            })
            ->when($this->filterTipe !== 'semua', function (Builder $query): void {
                $query->where('tipe', $this->filterTipe);
            })
            ->when($this->filterUser !== 'semua', function (Builder $query): void {
                $query->where('nama_user', $this->filterUser);
            })
            ->when($this->filterRentang !== 'semua', function (Builder $query): void {
                match ($this->filterRentang) {
                    'hari_ini'  => $query->whereDate('created_at', today()),
                    '7_hari'    => $query->where('created_at', '>=', now()->subDays(7)),
                    '30_hari'   => $query->where('created_at', '>=', now()->subDays(30)),
                    'bulan_ini' => $query->whereMonth('created_at', now()->month)
                                         ->whereYear('created_at', now()->year),
                    default     => null,
                };
            })
            ->latest('created_at')
            ->latest('id');
    }

    public function render(): View
    {
        $totalSemua = AktivitasLog::count();
        $totalHariIni = AktivitasLog::whereDate('created_at', today())->count();
        $totalKeuangan = AktivitasLog::where('tipe', 'pembayaran')->count();
        $totalOperasional = AktivitasLog::whereIn('tipe', ['produksi', 'pesanan', 'stok'])->count();

        $daftarUser = AktivitasLog::query()
            ->whereNotNull('nama_user')
            ->distinct()
            ->orderBy('nama_user')
            ->pluck('nama_user');

        $logs = $this->queryLog()->paginate($this->perPage);

        $selectedLog = $this->selectedLogId ? AktivitasLog::find($this->selectedLogId) : null;

        return view('livewire.admin.audit-log', [
            'logs'             => $logs,
            'totalSemua'       => $totalSemua,
            'totalHariIni'     => $totalHariIni,
            'totalKeuangan'    => $totalKeuangan,
            'totalOperasional' => $totalOperasional,
            'daftarUser'       => $daftarUser,
            'selectedLog'      => $selectedLog,
        ]);
    }
}

