<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\DataPelanggan;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DataPelangganTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrators_can_view_the_customer_directory(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Pelanggan::create([
            'kode_pelanggan' => 'CUST-001',
            'nama' => 'Study Club Informatika',
            'kategori' => 'Komunitas',
            'alamat' => 'Jl. Riau No. 128',
            'kota' => 'Bandung',
            'nomor_telepon' => '+62 812-3456-7890',
            'email' => 'klien@example.test',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.pelanggan.index'))
            ->assertOk()
            ->assertSee('Daftar Pelanggan')
            ->assertSee('Study Club Informatika')
            ->assertSee('Bandung')
            ->assertDontSee('Tambah Pelanggan Baru');
    }

    public function test_customer_search_and_category_filters_work_together(): void
    {
        Pelanggan::create([
            'kode_pelanggan' => 'CUST-001',
            'nama' => 'Study Club Informatika',
            'kategori' => 'Komunitas',
            'alamat' => 'Jl. Riau No. 128',
            'kota' => 'Bandung',
            'nomor_telepon' => '081234567890',
        ]);
        Pelanggan::create([
            'kode_pelanggan' => 'CUST-002',
            'nama' => 'CV Media Berkah',
            'kategori' => 'Perusahaan',
            'alamat' => 'Kawasan Industri',
            'kota' => 'Semarang',
            'nomor_telepon' => '081298765432',
        ]);

        Livewire::test(DataPelanggan::class)
            ->set('filterKategori', 'Komunitas')
            ->set('search', 'Bandung')
            ->assertSee('Study Club Informatika')
            ->assertDontSee('CV Media Berkah');
    }

}
