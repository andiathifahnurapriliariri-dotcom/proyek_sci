<?php

namespace App\Livewire\Admin;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Title('Laporan Kinerja & Penjualan - COTTON.id')]
#[Layout('layouts.admin')]
class Laporan extends Component
{
    public string $periode = '';

    public function mount(): void
    {
        $this->periode = now()->format('Y-m');
    }

    public function updatingPeriode(string $periode): void
    {
        if (! $this->formatPeriodeValid($periode)) {
            throw ValidationException::withMessages([
                'periode' => 'Pilih periode bulan dan tahun yang valid.',
            ]);
        }
    }

    public function unduhLaporan(): StreamedResponse
    {
        $periode = $this->validasiPeriode();
        $isiPdf = Pdf::loadView('livewire.admin.laporan-pdf', $this->dataLaporan($periode))
            ->setPaper('a4', 'landscape')
            ->output();

        return response()->streamDownload(
            static function () use ($isiPdf): void {
                echo $isiPdf;
            },
            'laporan-kinerja-penjualan-'.$periode.'.pdf',
            ['Content-Type' => 'application/pdf'],
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function dataLaporan(string $periode): array
    {
        $awalPeriode = Carbon::parse($periode.'-01')->startOfMonth();
        $akhirPeriode = $awalPeriode->copy()->endOfMonth();
        $awal = $awalPeriode->toDateString();
        $akhir = $akhirPeriode->toDateString();

        $pesananSelesai = DB::table('pemesanan')
            ->join('produksi', 'produksi.pemesanan_id', '=', 'pemesanan.id')
            ->where('pemesanan.status_pemesanan', 'selesai')
            ->where('pemesanan.status_pembayaran', 'lunas')
            ->where('produksi.status_produksi', 'selesai')
            ->whereBetween('produksi.tanggal_selesai', [$awal, $akhir]);

        $kategori = (clone $pesananSelesai)
            ->select('pemesanan.jenis_kain')
            ->selectRaw('COUNT(pemesanan.id) as jumlah_pesanan')
            ->selectRaw('SUM(pemesanan.jumlah) as jumlah_pcs')
            ->selectRaw('SUM(pemesanan.total_harga) as nilai_penjualan')
            ->selectRaw('COUNT(pemesanan.tanggal_selesai_estimasi) as pesanan_dengan_estimasi')
            ->selectRaw(
                'SUM(CASE WHEN pemesanan.tanggal_selesai_estimasi IS NOT NULL AND produksi.tanggal_selesai <= pemesanan.tanggal_selesai_estimasi THEN 1 ELSE 0 END) as pesanan_tepat_waktu',
            )
            ->groupBy('pemesanan.jenis_kain')
            ->orderByDesc('jumlah_pcs')
            ->get();

        $rataRataHariKerja = (clone $pesananSelesai)
            ->select('produksi.tanggal_mulai', 'produksi.tanggal_selesai')
            ->get()
            ->map(fn (object $produksi): int => $this->hitungHariKerja(
                (string) $produksi->tanggal_mulai,
                (string) $produksi->tanggal_selesai,
            ))
            ->avg() ?? 0;

        $jumlahPesanan = (int) $kategori->sum('jumlah_pesanan');
        $jumlahPcs = (int) $kategori->sum('jumlah_pcs');
        $nilaiPenjualan = (float) $kategori->sum('nilai_penjualan');
        $jumlahDenganEstimasi = (int) $kategori->sum('pesanan_dengan_estimasi');
        $jumlahTepatWaktu = (int) $kategori->sum('pesanan_tepat_waktu');

        $rincianKategori = $kategori->map(fn (object $baris): array => [
            'nama' => (string) $baris->jenis_kain,
            'jumlah_pesanan' => (int) $baris->jumlah_pesanan,
            'jumlah_pcs' => (int) $baris->jumlah_pcs,
            'nilai_penjualan' => (float) $baris->nilai_penjualan,
            'pesanan_tepat_waktu' => (int) $baris->pesanan_tepat_waktu,
            'pesanan_dengan_estimasi' => (int) $baris->pesanan_dengan_estimasi,
            'efisiensi_waktu' => (int) $baris->pesanan_dengan_estimasi > 0
                ? round((int) $baris->pesanan_tepat_waktu / (int) $baris->pesanan_dengan_estimasi * 100)
                : 0,
            'porsi_output' => $jumlahPcs > 0
                ? round((int) $baris->jumlah_pcs / $jumlahPcs * 100, 1)
                : 0,
        ])->values();

        $ringkasanStok = DB::table('stok_produk')
            ->selectRaw('COUNT(*) as total_sku')
            ->selectRaw('SUM(CASE WHEN sisa_stok > batas_rop THEN 1 ELSE 0 END) as sku_aman')
            ->selectRaw('SUM(CASE WHEN sisa_stok <= batas_rop THEN 1 ELSE 0 END) as sku_perlu_perhatian')
            ->first();

        $totalSku = (int) ($ringkasanStok->total_sku ?? 0);
        $skuAman = (int) ($ringkasanStok->sku_aman ?? 0);

        $aktivitasWorkshop = DB::table('produksi')
            ->where('status_produksi', '!=', 'dibatalkan')
            ->selectRaw('SUM(CASE WHEN tgl_pemotongan_kain BETWEEN ? AND ? THEN 1 ELSE 0 END) as pemotongan', [$awal, $akhir.' 23:59:59'])
            ->selectRaw('SUM(CASE WHEN tgl_sablon BETWEEN ? AND ? THEN 1 ELSE 0 END) as sablon', [$awal, $akhir.' 23:59:59'])
            ->selectRaw('SUM(CASE WHEN tgl_penjahitan BETWEEN ? AND ? THEN 1 ELSE 0 END) as penjahitan', [$awal, $akhir.' 23:59:59'])
            ->selectRaw('SUM(CASE WHEN tgl_steam BETWEEN ? AND ? THEN 1 ELSE 0 END) as steam', [$awal, $akhir.' 23:59:59'])
            ->first();

        $awalPeriode->locale('id');
        $waktuPembuatan = now();
        $waktuPembuatan->locale('id');

        return [
            'labelPeriode' => $awalPeriode->translatedFormat('F Y'),
            'dibuatPada' => $waktuPembuatan->translatedFormat('d F Y, H:i'),
            'jumlahPesanan' => $jumlahPesanan,
            'jumlahPcs' => $jumlahPcs,
            'nilaiPenjualan' => $nilaiPenjualan,
            'rataRataPerPesanan' => $jumlahPesanan > 0 ? $nilaiPenjualan / $jumlahPesanan : 0,
            'rataRataHariKerja' => round((float) $rataRataHariKerja, 1),
            'persentaseRopAman' => $totalSku > 0 ? round($skuAman / $totalSku * 100, 1) : 0,
            'totalSku' => $totalSku,
            'skuAman' => $skuAman,
            'skuPerluPerhatian' => (int) ($ringkasanStok->sku_perlu_perhatian ?? 0),
            'persentaseTepatWaktu' => $jumlahDenganEstimasi > 0
                ? round($jumlahTepatWaktu / $jumlahDenganEstimasi * 100, 1)
                : 0,
            'jumlahTepatWaktu' => $jumlahTepatWaktu,
            'jumlahDenganEstimasi' => $jumlahDenganEstimasi,
            'rincianKategori' => $rincianKategori,
            'distribusiOutput' => $rincianKategori,
            'aktivitasWorkshop' => [
                'pemotongan' => (int) ($aktivitasWorkshop->pemotongan ?? 0),
                'sablon' => (int) ($aktivitasWorkshop->sablon ?? 0),
                'penjahitan' => (int) ($aktivitasWorkshop->penjahitan ?? 0),
                'steam' => (int) ($aktivitasWorkshop->steam ?? 0),
            ],
        ];
    }

    private function hitungHariKerja(string $tanggalMulai, string $tanggalSelesai): int
    {
        $tanggal = Carbon::parse($tanggalMulai)->startOfDay();
        $tanggalAkhir = Carbon::parse($tanggalSelesai)->startOfDay();
        $jumlahHariKerja = 0;

        while ($tanggal->lessThan($tanggalAkhir)) {
            $tanggal->addDay();

            if ($tanggal->isWeekday()) {
                $jumlahHariKerja++;
            }
        }

        return $jumlahHariKerja;
    }

    private function validasiPeriode(): string
    {
        if (! $this->formatPeriodeValid($this->periode)) {
            throw ValidationException::withMessages([
                'periode' => 'Pilih periode bulan dan tahun yang valid.',
            ]);
        }

        return $this->periode;
    }

    private function formatPeriodeValid(string $periode): bool
    {
        return preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $periode) === 1;
    }

    public function render(): View
    {
        $periode = $this->periode;

        if (! $this->formatPeriodeValid($periode)) {
            $this->addError('periode', 'Pilih periode bulan dan tahun yang valid. Menampilkan periode berjalan sementara.');
            $periode = now()->format('Y-m');
        }

        return view('livewire.admin.laporan', $this->dataLaporan($periode));
    }
}
