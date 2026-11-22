<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\ManajemenPesanan;
use App\Livewire\Admin\PersediaanProduk;
use App\Models\MutasiStok;
use App\Models\Produk;
use App\Models\StokProduk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PersediaanProdukTest extends TestCase
{
    use RefreshDatabase;

    public function test_admins_can_view_inventory_and_stock_alerts(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $stok = $this->buatStok($admin, [
            'sisa_stok' => 8,
            'batas_rop' => 20,
            'ukuran' => 'XL',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.persediaan.index'))
            ->assertOk()
            ->assertSee('Persediaan Produk Baju Jadi')
            ->assertSee('Menipis (Di Bawah ROP)')
            ->assertSee('K30S-BLK-XL');

        $this->assertModelExists($stok);
    }

    public function test_adding_a_product_sku_creates_initial_stock_and_a_stock_movement(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        Livewire::test(PersediaanProduk::class)
            ->call('bukaModalCreate')
            ->set('kodeProduk', 'k30s-blk-s')
            ->set('namaProduk', 'Kaos Polos Combed 30s')
            ->set('jenisKain', 'Katun Combed 30s')
            ->set('warna', 'Hitam')
            ->set('ukuran', 'S')
            ->set('hargaSatuan', 45000)
            ->set('stokAwal', 15)
            ->set('batasRop', 20)
            ->call('simpanProduk')
            ->assertHasNoErrors()
            ->assertSet('showModalCreate', false);

        $produk = Produk::query()->where('kode_produk', 'K30S-BLK-S')->firstOrFail();
        $this->assertDatabaseHas('stok_produk', [
            'produk_id' => $produk->id,
            'sisa_stok' => 15,
            'batas_rop' => 20,
            'status_rop' => 'Batas ROP Tercapai',
        ]);
        $this->assertDatabaseHas('mutasi_stok', [
            'user_id' => $admin->id,
            'jenis_mutasi' => 'masuk',
            'jumlah_stok_masuk' => 15,
            'saldo_sebelum' => 0,
            'saldo_sesudah' => 15,
        ]);
    }

    public function test_receiving_finished_goods_updates_stock_status_and_mutation_history(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $stok = $this->buatStok($admin, [
            'sisa_stok' => 8,
            'batas_rop' => 20,
            'ukuran' => 'XL',
        ]);
        $this->actingAs($admin);

        Livewire::test(PersediaanProduk::class)
            ->call('bukaModalMasuk')
            ->set('selectedStokId', $stok->id)
            ->set('jumlahMasuk', 20)
            ->set('tanggalMutasi', now()->format('Y-m-d\TH:i'))
            ->set('keterangan', 'Hasil produksi batch uji')
            ->call('simpanStokMasuk')
            ->assertHasNoErrors()
            ->assertSet('showModalMasuk', false);

        $this->assertDatabaseHas('stok_produk', [
            'id' => $stok->id,
            'sisa_stok' => 28,
            'status_rop' => 'Aman',
        ]);
        $this->assertDatabaseHas('mutasi_stok', [
            'stok_produk_id' => $stok->id,
            'user_id' => $admin->id,
            'jenis_mutasi' => 'masuk',
            'jumlah_stok_masuk' => 20,
            'saldo_sebelum' => 8,
            'saldo_sesudah' => 28,
            'keterangan' => 'Hasil produksi batch uji',
        ]);
    }

    public function test_administrators_can_change_the_reorder_point_for_a_size_variant(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $stok = $this->buatStok($admin, [
            'sisa_stok' => 8,
            'batas_rop' => 20,
            'ukuran' => 'XL',
        ]);
        $this->actingAs($admin);

        Livewire::test(PersediaanProduk::class)
            ->call('bukaModalRop', $stok->id)
            ->set('batasRop', 5)
            ->call('simpanBatasRop')
            ->assertHasNoErrors()
            ->assertSet('showModalRop', false);

        $this->assertDatabaseHas('stok_produk', [
            'id' => $stok->id,
            'batas_rop' => 5,
            'status_rop' => 'Aman',
        ]);
    }

    public function test_inventory_search_and_status_filters_can_be_used_together(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->buatStok($admin, ['ukuran' => 'XL', 'sisa_stok' => 8, 'batas_rop' => 20]);
        $this->buatStok($admin, [
            'kode_produk' => 'K24S-WHT-M',
            'nama_produk' => 'Kaos Combed Putih',
            'jenis_kain' => 'Katun Combed 24s',
            'warna' => 'Putih',
            'ukuran' => 'M',
            'sisa_stok' => 50,
            'batas_rop' => 10,
        ]);

        Livewire::test(PersediaanProduk::class)
            ->set('filterStatus', 'menipis')
            ->set('search', 'K30S-BLK')
            ->assertSee('K30S-BLK-XL')
            ->assertDontSee('K24S-WHT-M');
    }

    public function test_production_users_are_redirected_away_from_inventory(): void
    {
        $operator = User::factory()->create(['role' => 'pemotongan_kain']);

        $this->actingAs($operator)
            ->get(route('admin.persediaan.index'))
            ->assertRedirect(route('produksi.pemotongan'));
    }

    public function test_restocking_a_low_variant_opens_a_prefilled_order_form(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $stok = $this->buatStok($admin, [
            'sisa_stok' => 8,
            'batas_rop' => 20,
            'ukuran' => 'XL',
        ]);
        $this->actingAs($admin);

        Livewire::test(PersediaanProduk::class)
            ->call('jadwalkanProduksi', $stok->id)
            ->assertRedirect(route('admin.pesanan.index', [
                'produk_id' => $stok->produk_id,
                'jumlah_pcs' => 32,
            ]));

        $this->get(route('admin.pesanan.index', [
            'produk_id' => $stok->produk_id,
            'jumlah_pcs' => 32,
        ]))
            ->assertOk()
            ->assertSee('Jadwal produksi ulang stok SKU K30S-BLK-XL')
            ->assertSee('Kaos 30s');
    }

    private function buatStok(User $admin, array $attributes = []): StokProduk
    {
        $kodeProduk = $attributes['kode_produk'] ?? 'K30S-BLK-XL';
        $produk = Produk::query()->create([
            'kode_produk' => $kodeProduk,
            'nama_produk' => $attributes['nama_produk'] ?? 'Kaos Polos Combed 30s',
            'jenis_kain' => $attributes['jenis_kain'] ?? 'Katun Combed 30s',
            'warna' => $attributes['warna'] ?? 'Hitam',
            'ukuran_baju' => $attributes['ukuran'] ?? 'XL',
            'harga_satuan' => 45000,
        ]);

        return StokProduk::query()->create([
            'produk_id' => $produk->id,
            'nama_produk' => $produk->nama_produk,
            'warna' => $produk->warna,
            'ukuran' => $produk->ukuran_baju,
            'sisa_stok' => $attributes['sisa_stok'] ?? 8,
            'batas_rop' => $attributes['batas_rop'] ?? 20,
            'status_rop' => 'Aman',
        ]);
    }
}
