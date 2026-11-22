<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\ManajemenPesanan;
use App\Models\Pelanggan;
use App\Models\Pemesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ManajemenPesananTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_orders_create_customer_data_and_save_order_details_and_design(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        Livewire::test(ManajemenPesanan::class)
            ->call('bukaModalCreate')
            ->assertDontSee('Pelanggan dari pesanan selesai')
            ->assertSee('Nama / Instansi')
            ->assertSee('Kontak WhatsApp')
            ->assertSee('Alamat')
            ->set('nama_pelanggan', 'Komunitas Jahit Bandung')
            ->set('nomor_telepon', '+62 812-3456-7890')
            ->set('alamat', 'Jl. Riau No. 128, Bandung')
            ->set('jenis_pakaian', 'Kaos 24s')
            ->set('warna', "Hitam\nPutih")
            ->set('ukuran_baju', "S 10\nM 2\nXL 5")
            ->set('jumlah_pcs', 17)
            ->set('harga_satuan', 55000)
            ->set('tanggal_deadline', now()->addDays(14)->toDateString())
            ->set('catatan_tambahan', 'Sablon plastisol bagian dada')
            ->set('status_pembayaran_awal', 'dp')
            ->set('file_desain', UploadedFile::fake()->image('desain.png'))
            ->call('simpanPesanan')
            ->assertHasNoErrors()
            ->assertSet('showModalCreate', false);

        $pelanggan = Pelanggan::query()->where('nama', 'Komunitas Jahit Bandung')->firstOrFail();
        $pesanan = Pemesanan::query()->where('pelanggan_id', $pelanggan->id)->firstOrFail();

        $this->assertDatabaseHas('pelanggan', [
            'id' => $pelanggan->id,
            'nomor_telepon' => '+62 812-3456-7890',
            'alamat' => 'Jl. Riau No. 128, Bandung',
        ]);
        $this->assertDatabaseHas('pemesanan', [
            'pelanggan_id' => $pelanggan->id,
            'jenis_kain' => 'Kaos 24s',
            'warna' => "Hitam\nPutih",
            'ukuran_baju' => "S 10\nM 2\nXL 5",
            'jumlah' => 17,
            'harga_satuan' => 55000,
            'total_harga' => 935000,
            'catatan' => 'Sablon plastisol bagian dada',
        ]);
        $this->assertDatabaseHas('pembayaran', [
            'pemesanan_id' => $pesanan->id,
            'jenis_pembayaran' => 'DP',
            'jumlah_bayar' => 467500,
        ]);

        Storage::disk('local')->assertExists($pesanan->file_desain);
    }
}
