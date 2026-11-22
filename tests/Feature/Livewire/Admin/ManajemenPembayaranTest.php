<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\ManajemenPembayaran;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ManajemenPembayaranTest extends TestCase
{
    use RefreshDatabase;

    public function test_admins_can_view_payment_records_and_summary(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pesanan = $this->buatPesanan($admin);
        Pembayaran::create([
            'kode_pembayaran' => 'PAY-20261108-ABC12345',
            'pemesanan_id' => $pesanan->id,
            'user_id' => $admin->id,
            'jenis_pembayaran' => 'DP',
            'jumlah_bayar' => 250000,
            'tanggal_bayar' => now(),
            'metode_pembayaran' => 'Transfer Bank',
            'status_konfirmasi' => 'Diterima',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.pembayaran.index'))
            ->assertOk()
            ->assertSee('Catatan Pembayaran Pelanggan')
            ->assertSee('PAY-20261108-ABC12345')
            ->assertSee('Pelanggan Uji');
    }

    public function test_production_users_are_redirected_away_from_payment_records(): void
    {
        $operator = User::factory()->create(['role' => 'pemotongan_kain']);

        $this->actingAs($operator)
            ->get(route('admin.pembayaran.index'))
            ->assertRedirect(route('produksi.pemotongan'));
    }

    public function test_admins_can_export_the_payment_register(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pesanan = $this->buatPesanan($admin);
        Pembayaran::create([
            'kode_pembayaran' => 'PAY-20261108-EXPORT01',
            'pemesanan_id' => $pesanan->id,
            'user_id' => $admin->id,
            'jenis_pembayaran' => 'DP',
            'jumlah_bayar' => 250000,
            'tanggal_bayar' => now(),
            'metode_pembayaran' => 'Transfer Bank',
            'status_konfirmasi' => 'Diterima',
        ]);
        $this->actingAs($admin);

        Livewire::test(ManajemenPembayaran::class)
            ->call('eksporRekap')
            ->assertFileDownloaded();
    }

    public function test_receiving_a_payment_updates_the_order_balance_and_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pesanan = $this->buatPesanan($admin);
        $this->actingAs($admin);

        Livewire::test(ManajemenPembayaran::class)
            ->call('bukaModalCreate')
            ->set('pemesananId', $pesanan->id)
            ->set('jenisPembayaran', 'DP')
            ->set('jumlahBayar', '400000')
            ->set('tanggalBayar', now()->format('Y-m-d\TH:i'))
            ->set('metodePembayaran', 'Transfer Bank')
            ->set('statusKonfirmasi', 'Diterima')
            ->call('simpanPembayaran')
            ->assertHasNoErrors()
            ->assertSet('showModalCreate', false);

        $this->assertDatabaseHas('pembayaran', [
            'pemesanan_id' => $pesanan->id,
            'jenis_pembayaran' => 'DP',
            'jumlah_bayar' => 400000,
            'status_konfirmasi' => 'Diterima',
            'user_id' => $admin->id,
        ]);
        $this->assertDatabaseHas('pemesanan', [
            'id' => $pesanan->id,
            'status_pembayaran' => 'dp',
            'status_pemesanan' => 'antrian_produksi',
        ]);
    }

    public function test_payment_cannot_exceed_the_unpaid_balance(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pesanan = $this->buatPesanan($admin);
        $this->actingAs($admin);

        Livewire::test(ManajemenPembayaran::class)
            ->call('bukaModalCreate')
            ->set('pemesananId', $pesanan->id)
            ->set('jenisPembayaran', 'Pelunasan')
            ->set('jumlahBayar', '1000001')
            ->set('tanggalBayar', now()->format('Y-m-d\TH:i'))
            ->call('simpanPembayaran')
            ->assertHasErrors(['jumlahBayar']);

        $this->assertDatabaseCount('pembayaran', 0);
    }

    public function test_rejecting_a_pending_payment_does_not_change_the_order_balance(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pesanan = $this->buatPesanan($admin);
        $pembayaran = Pembayaran::create([
            'kode_pembayaran' => 'PAY-20261108-REJECT01',
            'pemesanan_id' => $pesanan->id,
            'user_id' => $admin->id,
            'jenis_pembayaran' => 'DP',
            'jumlah_bayar' => 400000,
            'tanggal_bayar' => now(),
            'metode_pembayaran' => 'Transfer Bank',
            'status_konfirmasi' => 'Menunggu Verifikasi',
        ]);
        $this->actingAs($admin);

        Livewire::test(ManajemenPembayaran::class)
            ->call('konfirmasiPembayaran', $pembayaran->id, 'Ditolak')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('pembayaran', [
            'id' => $pembayaran->id,
            'status_konfirmasi' => 'Ditolak',
        ]);
        $this->assertDatabaseHas('pemesanan', [
            'id' => $pesanan->id,
            'status_pembayaran' => 'belum_bayar',
            'status_pemesanan' => 'menunggu_pembayaran',
        ]);
    }

    public function test_pending_payments_do_not_update_orders_until_verified_and_their_receipts_are_private(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create(['role' => 'admin']);
        $pesanan = $this->buatPesanan($admin);
        $this->actingAs($admin);

        Livewire::test(ManajemenPembayaran::class)
            ->call('bukaModalCreate')
            ->set('pemesananId', $pesanan->id)
            ->set('jenisPembayaran', 'DP')
            ->set('jumlahBayar', '400000')
            ->set('tanggalBayar', now()->format('Y-m-d\TH:i'))
            ->set('statusKonfirmasi', 'Menunggu Verifikasi')
            ->set('buktiPembayaran', UploadedFile::fake()->image('bukti.png'))
            ->call('simpanPembayaran')
            ->assertHasNoErrors();

        $pembayaran = Pembayaran::query()->firstOrFail();
        $this->assertDatabaseHas('pemesanan', [
            'id' => $pesanan->id,
            'status_pembayaran' => 'belum_bayar',
            'status_pemesanan' => 'menunggu_pembayaran',
        ]);
        Storage::disk('local')->assertExists($pembayaran->bukti_pembayaran);

        $this->get(route('admin.pembayaran.bukti', $pembayaran))->assertOk();

        Livewire::test(ManajemenPembayaran::class)
            ->call('konfirmasiPembayaran', $pembayaran->id, 'Diterima')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('pembayaran', [
            'id' => $pembayaran->id,
            'status_konfirmasi' => 'Diterima',
        ]);
        $this->assertDatabaseHas('pemesanan', [
            'id' => $pesanan->id,
            'status_pembayaran' => 'dp',
            'status_pemesanan' => 'antrian_produksi',
        ]);
    }

    private function buatPesanan(User $admin): Pemesanan
    {
        $pelanggan = Pelanggan::create([
            'kode_pelanggan' => 'CUST-TEST-001',
            'nama' => 'Pelanggan Uji',
            'kategori' => 'Perusahaan',
            'alamat' => 'Jl. Contoh No. 1',
            'kota' => 'Bandung',
            'nomor_telepon' => '081234567890',
        ]);

        return Pemesanan::create([
            'nomor_pemesanan' => 'ORD-TEST-001',
            'pelanggan_id' => $pelanggan->id,
            'user_id' => $admin->id,
            'jenis_kain' => 'Kaos 24s',
            'warna' => 'Hitam',
            'ukuran_baju' => 'M 10',
            'jumlah' => 10,
            'detail_desain' => 'Desain uji',
            'tanggal_pemesanan' => now()->toDateString(),
            'tanggal_selesai_estimasi' => now()->addDays(14)->toDateString(),
            'harga_satuan' => 100000,
            'total_harga' => 1000000,
            'status_pemesanan' => 'menunggu_pembayaran',
            'status_pembayaran' => 'belum_bayar',
        ]);
    }
}
