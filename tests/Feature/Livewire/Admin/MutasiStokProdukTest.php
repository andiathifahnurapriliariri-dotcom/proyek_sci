<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\MutasiStokProduk;
use App\Models\MutasiStok;
use App\Models\Pelanggan;
use App\Models\Pemesanan;
use App\Models\Produk;
use App\Models\StokProduk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MutasiStokProdukTest extends TestCase
{
    use RefreshDatabase;

    public function test_admins_can_view_stock_movement_history_and_weekly_summary(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $stok = $this->buatStok();
        $this->buatMutasi($admin, $stok, [
            'jenis_mutasi' => 'masuk',
            'jumlah_stok_masuk' => 40,
            'jumlah_stok_keluar' => 0,
            'saldo_sebelum' => 0,
            'saldo_sesudah' => 40,
            'keterangan' => 'Masuk (Produksi) · Batch QC 42',
        ]);
        $this->actingAs($admin);

        $this->get(route('admin.mutasi-stok.index'))
            ->assertOk()
            ->assertSee('Riwayat Keluar Masuk Baju Jadi')
            ->assertSee('40')
            ->assertSee('Masuk (Produksi)')
            ->assertSee('Kaos Polos Combed 30s');
    }

    public function test_completed_production_adds_stock_and_records_a_movement(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $stok = $this->buatStok(['sisa_stok' => 15]);
        $this->actingAs($admin);

        Livewire::test(MutasiStokProduk::class)
            ->call('bukaModalMasuk')
            ->set('stokProdukId', $stok->id)
            ->set('jumlah', 45)
            ->set('tanggalMutasi', now()->format('Y-m-d\TH:i'))
            ->set('referensi', 'Batch Produksi #42')
            ->set('keterangan', 'QC selesai')
            ->call('simpanMutasi')
            ->assertHasNoErrors()
            ->assertSet('showModalMutasi', false);

        $this->assertDatabaseHas('stok_produk', [
            'id' => $stok->id,
            'sisa_stok' => 60,
            'status_rop' => 'Aman',
        ]);
        $this->assertDatabaseHas('mutasi_stok', [
            'stok_produk_id' => $stok->id,
            'user_id' => $admin->id,
            'jenis_mutasi' => 'masuk',
            'jumlah_stok_masuk' => 45,
            'saldo_sebelum' => 15,
            'saldo_sesudah' => 60,
            'keterangan' => 'Masuk (Produksi) · Batch Produksi #42 · QC selesai',
        ]);
    }

    public function test_shipping_a_customer_order_decreases_stock_and_records_the_order_reference(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $stok = $this->buatStok(['sisa_stok' => 30]);
        $pesanan = $this->buatPesanan($admin);
        $this->actingAs($admin);

        Livewire::test(MutasiStokProduk::class)
            ->call('bukaModalKeluar')
            ->set('stokProdukId', $stok->id)
            ->set('tujuanKeluar', 'Kirim')
            ->set('jumlah', 12)
            ->set('tanggalMutasi', now()->format('Y-m-d\TH:i'))
            ->set('referensi', $pesanan->nomor_pemesanan)
            ->set('keterangan', 'Ekspedisi reguler')
            ->call('simpanMutasi')
            ->assertHasNoErrors()
            ->assertSet('showModalMutasi', false);

        $this->assertDatabaseHas('stok_produk', [
            'id' => $stok->id,
            'sisa_stok' => 18,
            'status_rop' => 'Batas ROP Tercapai',
        ]);
        $this->assertDatabaseHas('mutasi_stok', [
            'stok_produk_id' => $stok->id,
            'jenis_mutasi' => 'keluar',
            'jumlah_stok_keluar' => 12,
            'saldo_sebelum' => 30,
            'saldo_sesudah' => 18,
            'keterangan' => 'Keluar (Kirim) · '.$pesanan->nomor_pemesanan.' · Ekspedisi reguler',
        ]);
    }

    public function test_stock_cannot_be_removed_below_zero(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $stok = $this->buatStok(['sisa_stok' => 5]);
        $pesanan = $this->buatPesanan($admin);
        $this->actingAs($admin);

        Livewire::test(MutasiStokProduk::class)
            ->call('bukaModalKeluar')
            ->set('stokProdukId', $stok->id)
            ->set('tujuanKeluar', 'Diambil')
            ->set('jumlah', 6)
            ->set('tanggalMutasi', now()->format('Y-m-d\TH:i'))
            ->set('referensi', $pesanan->nomor_pemesanan)
            ->call('simpanMutasi')
            ->assertHasErrors(['jumlah']);

        $this->assertDatabaseHas('stok_produk', [
            'id' => $stok->id,
            'sisa_stok' => 5,
        ]);
        $this->assertDatabaseCount('mutasi_stok', 0);
    }

    public function test_outgoing_movements_require_an_existing_order_reference(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $stok = $this->buatStok();
        $this->actingAs($admin);

        Livewire::test(MutasiStokProduk::class)
            ->call('bukaModalKeluar')
            ->set('stokProdukId', $stok->id)
            ->set('tujuanKeluar', 'Kirim')
            ->set('jumlah', 1)
            ->set('tanggalMutasi', now()->format('Y-m-d\TH:i'))
            ->set('referensi', 'ORD-TIDAK-ADA')
            ->call('simpanMutasi')
            ->assertHasErrors(['referensi']);

        $this->assertDatabaseCount('mutasi_stok', 0);
    }

    public function test_movement_history_filters_by_direction_and_search_term(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $stok = $this->buatStok();
        $this->buatMutasi($admin, $stok, [
            'jenis_mutasi' => 'masuk',
            'jumlah_stok_masuk' => 40,
            'jumlah_stok_keluar' => 0,
            'saldo_sebelum' => 0,
            'saldo_sesudah' => 40,
            'keterangan' => 'Masuk (Produksi) · Batch Putih 42',
        ]);
        $this->buatMutasi($admin, $stok, [
            'jenis_mutasi' => 'keluar',
            'jumlah_stok_masuk' => 0,
            'jumlah_stok_keluar' => 5,
            'saldo_sebelum' => 40,
            'saldo_sesudah' => 35,
            'keterangan' => 'Keluar (Kirim) · ORD-2026-001',
        ]);
        $this->actingAs($admin);

        Livewire::test(MutasiStokProduk::class)
            ->set('filterTab', 'keluar')
            ->set('search', 'ORD-2026')
            ->assertSee('ORD-2026-001')
            ->assertDontSee('Batch Putih 42');
    }

    public function test_movement_history_loads_five_additional_records(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $stok = $this->buatStok();

        for ($index = 1; $index <= 6; $index++) {
            $mutasi = $this->buatMutasi($admin, $stok, [
                'jenis_mutasi' => 'masuk',
                'jumlah_stok_masuk' => 1,
                'jumlah_stok_keluar' => 0,
                'saldo_sebelum' => $index - 1,
                'saldo_sesudah' => $index,
                'keterangan' => 'Masuk (Produksi) · Batch #'.$index,
            ]);
            $mutasi->update(['tanggal_mutasi' => now()->subMinutes(7 - $index)]);
        }

        $this->actingAs($admin);

        Livewire::test(MutasiStokProduk::class)
            ->assertSee('Batch #6')
            ->assertDontSee('Batch #1')
            ->assertSee('Menampilkan 5 dari 6 mutasi produk pakaian')
            ->call('muatLebihBanyak')
            ->assertSee('Batch #1')
            ->assertSee('Menampilkan 6 dari 6 mutasi produk pakaian');
    }

    public function test_production_users_are_redirected_away_from_movement_history(): void
    {
        $operator = User::factory()->create(['role' => 'pemotongan_kain']);

        $this->actingAs($operator)
            ->get(route('admin.mutasi-stok.index'))
            ->assertRedirect(route('produksi.pemotongan'));
    }

    /**
     * @param  array<string, int>  $attributes
     */
    private function buatStok(array $attributes = []): StokProduk
    {
        $produk = Produk::query()->create([
            'kode_produk' => 'K30S-BLK-XL',
            'nama_produk' => 'Kaos Polos Combed 30s',
            'jenis_kain' => 'Katun Combed 30s',
            'warna' => 'Hitam',
            'ukuran_baju' => 'XL',
            'harga_satuan' => 45000,
        ]);

        return StokProduk::query()->create([
            'produk_id' => $produk->id,
            'nama_produk' => $produk->nama_produk,
            'warna' => $produk->warna,
            'ukuran' => $produk->ukuran_baju,
            'sisa_stok' => $attributes['sisa_stok'] ?? 50,
            'batas_rop' => 20,
            'status_rop' => 'Aman',
        ]);
    }

    private function buatPesanan(User $admin): Pemesanan
    {
        $pelanggan = Pelanggan::query()->create([
            'kode_pelanggan' => 'CUST-MUTASI-001',
            'nama' => 'Pelanggan Mutasi',
            'kategori' => 'Perusahaan',
            'alamat' => 'Jl. Contoh No. 1',
            'kota' => 'Bandung',
            'nomor_telepon' => '081234567890',
        ]);

        return Pemesanan::query()->create([
            'nomor_pemesanan' => 'ORD-2026-001',
            'pelanggan_id' => $pelanggan->id,
            'user_id' => $admin->id,
            'jenis_kain' => 'Katun Combed 30s',
            'warna' => 'Hitam',
            'ukuran_baju' => 'XL 12',
            'jumlah' => 12,
            'detail_desain' => 'Desain pesanan',
            'tanggal_pemesanan' => now()->toDateString(),
            'tanggal_selesai_estimasi' => now()->addDays(14)->toDateString(),
            'harga_satuan' => 45000,
            'total_harga' => 540000,
            'status_pemesanan' => 'selesai_produksi',
            'status_pembayaran' => 'lunas',
        ]);
    }

    /**
     * @param array{
     *     jenis_mutasi: string,
     *     jumlah_stok_masuk: int,
     *     jumlah_stok_keluar: int,
     *     saldo_sebelum: int,
     *     saldo_sesudah: int,
     *     keterangan: string
     * } $attributes
     */
    private function buatMutasi(User $admin, StokProduk $stok, array $attributes): MutasiStok
    {
        $mutasi = new MutasiStok([
            'user_id' => $admin->id,
            'jenis_mutasi' => $attributes['jenis_mutasi'],
            'jumlah_stok_masuk' => $attributes['jumlah_stok_masuk'],
            'jumlah_stok_keluar' => $attributes['jumlah_stok_keluar'],
            'saldo_sebelum' => $attributes['saldo_sebelum'],
            'saldo_sesudah' => $attributes['saldo_sesudah'],
            'keterangan' => $attributes['keterangan'],
            'tanggal_mutasi' => now(),
        ]);

        $stok->mutasiStok()->save($mutasi);

        return $mutasi;
    }
}
