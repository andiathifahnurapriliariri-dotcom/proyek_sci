<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\RiwayatPesanan;
use App\Models\Pelanggan;
use App\Models\Pemesanan;
use App\Models\Produksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RiwayatPesananTest extends TestCase
{
    use RefreshDatabase;

    public function test_admins_can_view_completed_order_history_and_summary_metrics(): void
    {
        $this->travelTo('2026-11-08 12:00:00');
        $admin = User::factory()->create(['role' => 'admin']);
        $pelangganTetap = $this->buatPelanggan('Pelanggan Tetap');
        $this->buatPesanan($admin, $pelangganTetap, 'ORD-2025-112', 'Kaos Polos Wangki Drill', 200, '2025-11-12', '2025-11-28', '2025-11-25');
        $this->buatPesanan($admin, $pelangganTetap, 'ORD-2025-098', 'Jaket Varsity', 150, '2025-10-05', '2025-10-24', '2025-10-24');
        $this->buatPesanan($admin, $this->buatPelanggan('Kafe Kopi Senja'), 'ORD-2025-087', 'Kaos Celemek', 60, '2025-09-15', '2025-09-25', '2025-09-27');
        $this->buatPesanan($admin, $this->buatPelanggan('Pesanan Belum Lunas'), 'ORD-2025-086', 'Hoodie Fleece', 20, '2025-09-01', '2025-09-20', '2025-09-18', 'selesai', 'dp');
        $this->buatPesanan($admin, $this->buatPelanggan('Pesanan Aktif'), 'ORD-2025-085', 'Jersey', 30, '2025-08-01', '2025-08-20', null, 'dalam_produksi', 'lunas');
        $this->actingAs($admin);

        $this->get(route('admin.riwayat-pesanan.index'))
            ->assertOk()
            ->assertSee('Riwayat Pesanan Selesai')
            ->assertSee('3')
            ->assertSee('410')
            ->assertSee('66.7%')
            ->assertSee('50%')
            ->assertSee('ORD-2025-112')
            ->assertSee('ORD-2025-098')
            ->assertSee('ORD-2025-087')
            ->assertDontSee('ORD-2025-086')
            ->assertDontSee('ORD-2025-085');
    }

    public function test_year_product_and_search_filters_work_together(): void
    {
        $this->travelTo('2026-11-08 12:00:00');
        $admin = User::factory()->create(['role' => 'admin']);
        $pelanggan = $this->buatPelanggan('Komunitas Basket Bandung');
        $this->buatPesanan($admin, $pelanggan, 'ORD-2025-201', 'Kaos Combed 30s', 40, '2025-06-01', '2025-06-20', '2025-06-19');
        $this->buatPesanan($admin, $this->buatPelanggan('Klub Basket'), 'ORD-2025-202', 'Hoodie Fleece', 25, '2025-05-01', '2025-05-20', '2025-05-18');
        $this->buatPesanan($admin, $this->buatPelanggan('Komunitas Basket Tahun Ini'), 'ORD-2026-203', 'Kaos Combed 30s', 30, '2026-02-01', '2026-02-20', '2026-02-19');
        $this->actingAs($admin);

        Livewire::test(RiwayatPesanan::class)
            ->set('tahun', '2025')
            ->set('jenisPakaian', 'Kaos Combed 30s')
            ->set('search', 'Komunitas Basket')
            ->assertSee('ORD-2025-201')
            ->assertDontSee('ORD-2025-202')
            ->assertDontSee('ORD-2026-203');
    }

    public function test_history_can_be_sorted_oldest_first(): void
    {
        $this->travelTo('2026-11-08 12:00:00');
        $admin = User::factory()->create(['role' => 'admin']);
        $this->buatPesanan($admin, $this->buatPelanggan('Pelanggan Baru'), 'ORD-2025-302', 'Kaos 30s', 10, '2025-12-01', '2025-12-15', '2025-12-14');
        $this->buatPesanan($admin, $this->buatPelanggan('Pelanggan Lama'), 'ORD-2025-301', 'Kaos 30s', 10, '2025-01-01', '2025-01-15', '2025-01-14');
        $this->actingAs($admin);

        Livewire::test(RiwayatPesanan::class)
            ->set('urutan', 'terlama')
            ->assertSeeInOrder(['ORD-2025-301', 'ORD-2025-302']);
    }

    public function test_archive_export_downloads_a_csv(): void
    {
        $this->travelTo('2026-11-08 12:00:00');
        $admin = User::factory()->create(['role' => 'admin']);
        $this->buatPesanan($admin, $this->buatPelanggan('Pelanggan Ekspor'), 'ORD-2025-401', 'Kaos 24s', 12, '2025-04-03', '2025-04-17', '2025-04-16');
        $this->actingAs($admin);

        Livewire::test(RiwayatPesanan::class)
            ->call('eksporArsip')
            ->assertFileDownloaded('arsip-pesanan-selesai-20261108-120000.csv');
    }

    public function test_search_button_dispatches_focus_event_to_the_archive_search(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        Livewire::test(RiwayatPesanan::class)
            ->call('fokusPencarian')
            ->assertDispatched('fokus-pencarian-riwayat');
    }

    public function test_customer_names_are_escaped_in_the_history_table(): void
    {
        $this->travelTo('2026-11-08 12:00:00');
        $admin = User::factory()->create(['role' => 'admin']);
        $this->buatPesanan(
            $admin,
            $this->buatPelanggan("<script>alert('x')</script>"),
            'ORD-2025-501',
            'Kaos 30s',
            1,
            '2025-01-01',
            '2025-01-10',
            '2025-01-09',
        );
        $this->actingAs($admin);

        Livewire::test(RiwayatPesanan::class)
            ->assertSeeHtml('&lt;script&gt;')
            ->assertDontSee("<script>alert('x')</script>", false);
    }

    public function test_production_users_are_redirected_away_from_order_history(): void
    {
        $operator = User::factory()->create(['role' => 'sablon']);

        $this->actingAs($operator)
            ->get(route('admin.riwayat-pesanan.index'))
            ->assertRedirect(route('produksi.sablon'));
    }

    private function buatPelanggan(string $nama): Pelanggan
    {
        return Pelanggan::query()->create([
            'kode_pelanggan' => 'CUST-'.str_replace([' ', '<', '>', '/', "'"], '', $nama).'-'.Pelanggan::query()->count(),
            'nama' => $nama,
            'kategori' => 'Perusahaan',
            'alamat' => 'Jl. Arsip No. 1',
            'kota' => 'Bandung',
            'nomor_telepon' => '081234567890',
        ]);
    }

    private function buatPesanan(
        User $admin,
        Pelanggan $pelanggan,
        string $nomor,
        string $jenisPakaian,
        int $jumlah,
        string $tanggalPesanan,
        string $tanggalEstimasi,
        ?string $tanggalSelesai,
        string $statusPesanan = 'selesai',
        string $statusPembayaran = 'lunas',
    ): Pemesanan {
        $pesanan = Pemesanan::query()->create([
            'nomor_pemesanan' => $nomor,
            'pelanggan_id' => $pelanggan->id,
            'user_id' => $admin->id,
            'jenis_kain' => $jenisPakaian,
            'warna' => 'Hitam',
            'ukuran_baju' => 'M',
            'jumlah' => $jumlah,
            'detail_desain' => 'Desain pesanan',
            'tanggal_pemesanan' => $tanggalPesanan,
            'tanggal_selesai_estimasi' => $tanggalEstimasi,
            'harga_satuan' => 45000,
            'total_harga' => $jumlah * 45000,
            'status_pemesanan' => $statusPesanan,
            'status_pembayaran' => $statusPembayaran,
        ]);

        if ($tanggalSelesai !== null) {
            Produksi::query()->create([
                'kode_produksi' => 'PRD-'.$nomor,
                'pemesanan_id' => $pesanan->id,
                'user_id' => $admin->id,
                'tanggal_mulai' => $tanggalPesanan,
                'tanggal_selesai' => $tanggalSelesai,
                'status_pemotongan_kain' => 'selesai',
                'status_sablon' => 'selesai',
                'status_penjahitan' => 'selesai',
                'status_steam' => 'selesai',
                'tahap_saat_ini' => 'selesai',
                'progress_persen' => 100,
                'status_produksi' => 'selesai',
            ]);
        }

        return $pesanan;
    }
}
