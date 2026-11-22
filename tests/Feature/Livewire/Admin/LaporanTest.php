<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\Laporan;
use App\Models\Pelanggan;
use App\Models\Pemesanan;
use App\Models\Produk;
use App\Models\Produksi;
use App\Models\StokProduk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LaporanTest extends TestCase
{
    use RefreshDatabase;

    public function test_admins_can_view_monthly_sales_production_and_inventory_metrics(): void
    {
        $this->travelTo('2026-11-08 12:00:00');
        $admin = User::factory()->create(['role' => 'admin']);
        $this->buatPesanan($admin, 'ORD-2026-101', 'Kaos Combed 30s', 100, 50000, '2026-10-10', '2026-10-10');
        $this->buatPesanan($admin, 'ORD-2026-102', 'Polo Lacoste', 50, 40000, '2026-10-19', '2026-10-21');
        $this->buatPesanan($admin, 'ORD-2026-103', 'Hoodie Fleece', 20, 60000, '2026-10-22', '2026-10-23', 'dp');
        $this->buatPesanan($admin, 'ORD-2026-104', 'Kemeja PDH', 30, 70000, '2026-11-03', '2026-11-04');
        $this->buatStok('Kaos aman', 20, 10);
        $this->buatStok('Kaos menipis', 5, 10);
        $this->actingAs($admin);

        Livewire::test(Laporan::class)
            ->set('periode', '2026-10')
            ->assertSee('Laporan Kinerja & Penjualan')
            ->assertSee('Oktober 2026')
            ->assertSee('7.000.000')
            ->assertSee('150')
            ->assertSee('1,0 Hari Kerja')
            ->assertSee('50,0%')
            ->assertSee('1 dari 2 SKU')
            ->assertSee('Kaos Combed 30s')
            ->assertSee('Polo Lacoste')
            ->assertDontSee('Hoodie Fleece')
            ->assertDontSee('Kemeja PDH');
    }

    public function test_admins_can_open_the_report_route_with_an_empty_period(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $this->get(route('admin.laporan.index'))
            ->assertOk()
            ->assertSee('Laporan Kinerja & Penjualan')
            ->assertSee('Tidak ada pesanan selesai dan lunas')
            ->assertSee('0,0 Hari Kerja');
    }

    public function test_changing_the_period_refreshes_report_totals(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->buatPesanan($admin, 'ORD-2026-201', 'Kaos Combed 30s', 20, 50000, '2026-10-10', '2026-10-10');
        $this->buatPesanan($admin, 'ORD-2026-202', 'Jaket Fleece', 10, 80000, '2026-11-10', '2026-11-12');
        $this->actingAs($admin);

        Livewire::test(Laporan::class)
            ->set('periode', '2026-10')
            ->assertSee('1.000.000')
            ->assertSee('20')
            ->set('periode', '2026-11')
            ->assertSee('800.000')
            ->assertSee('10');
    }

    public function test_invalid_report_month_is_rejected(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        Livewire::test(Laporan::class)
            ->set('periode', '2026-13')
            ->assertHasErrors(['periode'])
            ->assertSee('Pilih periode bulan dan tahun yang valid.');
    }

    public function test_admin_can_download_a_pdf_report_for_the_selected_month(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->buatPesanan($admin, 'ORD-2026-301', 'Kaos Combed 30s', 12, 45000, '2026-10-12', '2026-10-14');
        $this->actingAs($admin);

        Livewire::test(Laporan::class)
            ->set('periode', '2026-10')
            ->call('unduhLaporan')
            ->assertFileDownloaded('laporan-kinerja-penjualan-2026-10.pdf');
    }

    public function test_production_users_are_redirected_away_from_reports(): void
    {
        $operator = User::factory()->create(['role' => 'sablon']);

        $this->actingAs($operator)
            ->get(route('admin.laporan.index'))
            ->assertRedirect(route('produksi.sablon'));
    }

    private function buatPesanan(
        User $admin,
        string $nomor,
        string $jenisPakaian,
        int $jumlah,
        int $hargaSatuan,
        string $tanggalEstimasi,
        string $tanggalSelesai,
        string $statusPembayaran = 'lunas',
    ): Pemesanan {
        $pelanggan = Pelanggan::query()->create([
            'kode_pelanggan' => 'CUST-'.$nomor,
            'nama' => 'Pelanggan '.$nomor,
            'alamat' => 'Jl. Laporan No. 1',
            'kota' => 'Bandung',
            'nomor_telepon' => '081234567890',
        ]);
        $pesanan = Pemesanan::query()->create([
            'nomor_pemesanan' => $nomor,
            'pelanggan_id' => $pelanggan->id,
            'user_id' => $admin->id,
            'jenis_kain' => $jenisPakaian,
            'warna' => 'Hitam',
            'ukuran_baju' => 'M',
            'jumlah' => $jumlah,
            'detail_desain' => 'Desain laporan',
            'tanggal_pemesanan' => $tanggalEstimasi,
            'tanggal_selesai_estimasi' => $tanggalEstimasi,
            'harga_satuan' => $hargaSatuan,
            'total_harga' => $jumlah * $hargaSatuan,
            'status_pemesanan' => 'selesai',
            'status_pembayaran' => $statusPembayaran,
        ]);

        Produksi::query()->create([
            'kode_produksi' => 'PRD-'.$nomor,
            'pemesanan_id' => $pesanan->id,
            'user_id' => $admin->id,
            'tanggal_mulai' => $tanggalEstimasi,
            'tanggal_selesai' => $tanggalSelesai,
            'tgl_pemotongan_kain' => $tanggalSelesai.' 08:00:00',
            'tgl_sablon' => $tanggalSelesai.' 09:00:00',
            'tgl_penjahitan' => $tanggalSelesai.' 10:00:00',
            'tgl_steam' => $tanggalSelesai.' 11:00:00',
            'status_pemotongan_kain' => 'selesai',
            'status_sablon' => 'selesai',
            'status_penjahitan' => 'selesai',
            'status_steam' => 'selesai',
            'tahap_saat_ini' => 'selesai',
            'progress_persen' => 100,
            'status_produksi' => 'selesai',
        ]);

        return $pesanan;
    }

    private function buatStok(string $nama, int $sisaStok, int $batasRop): void
    {
        $produk = Produk::query()->create([
            'kode_produk' => 'SKU-'.str_replace(' ', '-', $nama),
            'nama_produk' => $nama,
            'jenis_kain' => 'Cotton Combed',
            'warna' => 'Hitam',
            'ukuran_baju' => 'M',
            'harga_satuan' => 45000,
        ]);

        StokProduk::query()->create([
            'produk_id' => $produk->id,
            'nama_produk' => $nama,
            'warna' => 'Hitam',
            'ukuran' => 'M',
            'sisa_stok' => $sisaStok,
            'batas_rop' => $batasRop,
            'status_rop' => $sisaStok > $batasRop ? 'Aman' : 'Batas ROP Tercapai',
        ]);
    }
}
