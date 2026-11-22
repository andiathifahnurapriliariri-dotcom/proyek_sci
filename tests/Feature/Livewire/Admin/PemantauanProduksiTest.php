<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\PemantauanProduksi;
use App\Models\Pelanggan;
use App\Models\Pemesanan;
use App\Models\Produksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PemantauanProduksiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admins_can_view_production_metrics_and_station_workloads(): void
    {
        $this->travelTo(now()->setTime(12, 0));
        $admin = User::factory()->create(['role' => 'admin']);
        $this->buatBatch($admin, 'ORD-2026-101', 'Pelanggan Kaos', 'pemotongan_kain', 100, 25, today()->addDay()->toDateString());
        $this->buatBatch($admin, 'ORD-2026-102', 'Pelanggan Jaket', 'penjahitan', 50, 50, today()->subDay()->toDateString());
        $this->buatBatch(
            $admin,
            'ORD-2026-103',
            'Pelanggan Selesai',
            'selesai',
            75,
            100,
            today()->toDateString(),
            'selesai',
            'siap_diambil',
        );
        $this->actingAs($admin);

        $this->get(route('admin.produksi.index'))
            ->assertOk()
            ->assertSee('Pemantauan Produksi')
            ->assertSee('150')
            ->assertSee('38%')
            ->assertSee('33.3%')
            ->assertSee('75')
            ->assertSee('ORD-2026-101')
            ->assertSee('ORD-2026-102')
            ->assertDontSee('ORD-2026-103')
            ->assertSee('1 terlambat');
    }

    public function test_production_filter_and_search_can_be_combined(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->buatBatch($admin, 'ORD-2026-201', 'Komunitas Jahit', 'penjahitan', 30, 50, today()->addDays(3)->toDateString());
        $this->buatBatch($admin, 'ORD-2026-202', 'Klub Basket', 'pemotongan_kain', 40, 10, today()->addDays(5)->toDateString());
        $this->actingAs($admin);

        Livewire::test(PemantauanProduksi::class)
            ->set('filterTahap', 'penjahitan')
            ->set('search', 'Komunitas')
            ->assertSee('ORD-2026-201')
            ->assertSee('Komunitas Jahit')
            ->assertDontSee('ORD-2026-202')
            ->assertDontSee('Klub Basket');
    }

    public function test_unknown_production_filter_is_rejected(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        Livewire::test(PemantauanProduksi::class)
            ->set('filterTahap', 'tahap-tidak-ada')
            ->assertHasErrors(['filterTahap']);
    }

    public function test_refresh_shows_production_records_created_after_the_page_was_opened(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);
        $component = Livewire::test(PemantauanProduksi::class)
            ->assertDontSee('ORD-2026-301');

        $this->buatBatch($admin, 'ORD-2026-301', 'Pelanggan Baru', 'sablon', 25, 30, today()->addDays(2)->toDateString());

        $component->call('segarkanData')
            ->assertSee('ORD-2026-301');
    }

    public function test_download_exports_the_current_production_filter(): void
    {
        $this->travelTo(now()->setTime(12, 0));
        $admin = User::factory()->create(['role' => 'admin']);
        $this->buatBatch($admin, 'ORD-2026-401', 'Pelanggan Ekspor', 'sablon', 60, 35, today()->addDays(4)->toDateString());
        $this->actingAs($admin);

        Livewire::test(PemantauanProduksi::class)
            ->set('filterTahap', 'sablon')
            ->call('unduhLembarPantauan')
            ->assertFileDownloaded('lembar-pemantauan-produksi-'.now()->format('Ymd-His').'.csv');
    }

    public function test_unprivileged_production_users_are_redirected_from_monitoring(): void
    {
        $operator = User::factory()->create(['role' => 'penjahitan']);

        $this->actingAs($operator)
            ->get(route('admin.produksi.index'))
            ->assertRedirect(route('produksi.penjahitan'));
    }

    public function test_customer_names_are_escaped_in_the_production_table(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->buatBatch(
            $admin,
            'ORD-2026-501',
            "<script>alert('x')</script>",
            'steam',
            12,
            80,
            today()->addDay()->toDateString(),
        );
        $this->actingAs($admin);

        Livewire::test(PemantauanProduksi::class)
            ->assertSeeHtml('&lt;script&gt;')
            ->assertDontSee("<script>alert('x')</script>", false);
    }

    public function test_sop_panel_opens_and_closes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        Livewire::test(PemantauanProduksi::class)
            ->call('lihatSop')
            ->assertSet('showModalSop', true)
            ->assertSeeHtml('SOP Sablon & Packing')
            ->assertSee('Cocokkan desain, warna, ukuran, dan jumlah dengan SPK')
            ->call('tutupSop')
            ->assertSet('showModalSop', false)
            ->assertDontSee('Cocokkan desain, warna, ukuran, dan jumlah dengan SPK');
    }

    private function buatBatch(
        User $admin,
        string $nomorPesanan,
        string $namaPelanggan,
        string $tahap,
        int $jumlah,
        int $progres,
        string $tanggalEstimasi,
        string $statusProduksi = 'proses',
        string $statusPesanan = 'dalam_produksi',
    ): Produksi {
        $pelanggan = Pelanggan::query()->create([
            'kode_pelanggan' => 'PEL-'.$nomorPesanan,
            'nama' => $namaPelanggan,
            'alamat' => 'Jl. Workshop No. 1',
            'nomor_telepon' => '081234567890',
        ]);
        $pesanan = Pemesanan::query()->create([
            'nomor_pemesanan' => $nomorPesanan,
            'pelanggan_id' => $pelanggan->id,
            'user_id' => $admin->id,
            'jenis_kain' => 'Kaos Combed 30s',
            'warna' => 'Hitam',
            'ukuran_baju' => 'M',
            'jumlah' => $jumlah,
            'detail_desain' => 'Desain pesanan',
            'tanggal_pemesanan' => today()->toDateString(),
            'tanggal_selesai_estimasi' => $tanggalEstimasi,
            'harga_satuan' => 45000,
            'total_harga' => $jumlah * 45000,
            'status_pemesanan' => $statusPesanan,
            'status_pembayaran' => 'dp',
        ]);

        return Produksi::query()->create([
            'kode_produksi' => 'PRD-'.$nomorPesanan,
            'pemesanan_id' => $pesanan->id,
            'user_id' => $admin->id,
            'tanggal_mulai' => today()->toDateString(),
            'tahap_saat_ini' => $tahap,
            'progress_persen' => $progres,
            'status_produksi' => $statusProduksi,
        ]);
    }
}
