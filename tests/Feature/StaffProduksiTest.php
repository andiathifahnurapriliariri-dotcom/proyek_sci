<?php

namespace Tests\Feature;

use App\Models\AktivitasLog;
use App\Models\Pemesanan;
use App\Models\Produksi;
use App\Models\ProduksiTahapan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffProduksiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_guest_cannot_access_staff_routes(): void
    {
        $response = $this->get(route('staff.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_staff_cannot_access_admin_dashboard(): void
    {
        $staff = User::where('role', 'operator_produksi')->first() ?? User::factory()->create(['role' => 'operator_produksi']);

        $response = $this->actingAs($staff)->get(route('admin.dashboard'));
        // Harap dialihkan ke staff.dashboard
        $response->assertRedirect(route('staff.dashboard'));
    }

    public function test_staff_can_access_all_staff_pages(): void
    {
        $staff = User::where('role', 'operator_produksi')->first() ?? User::factory()->create(['role' => 'operator_produksi']);

        $this->actingAs($staff)->get(route('staff.dashboard'))->assertStatus(200);
        $this->actingAs($staff)->get(route('staff.detail-pekerjaan'))->assertStatus(200);
        $this->actingAs($staff)->get(route('staff.antrean-produksi'))->assertStatus(200);
        $this->actingAs($staff)->get(route('staff.antrean-sablon'))->assertStatus(200);
        $this->actingAs($staff)->get(route('staff.riwayat-pekerjaan'))->assertStatus(200);
        $this->actingAs($staff)->get(route('staff.riwayat-saya'))->assertStatus(200);
        $this->actingAs($staff)->get(route('staff.notifikasi'))->assertStatus(200);
        $this->actingAs($staff)->get(route('staff.profil'))->assertStatus(200);
    }

    public function test_staff_can_start_and_complete_production_stage(): void
    {
        $staff = User::where('role', 'operator_produksi')->first();
        $pesanan = Pemesanan::first();
        $produksi = $pesanan->produksi;

        $produksi->inisialisasiTahapan();
        $tahap1 = $produksi->tahapan()->where('nama_tahap', 'pemotongan_kain')->first();

        // Mulai tahap
        $tahap1->mulai($staff, 'Pola siap dipotong di meja');
        $tahap1->refresh();

        $this->assertEquals('sedang_dikerjakan', $tahap1->status);
        $this->assertNotNull($tahap1->waktu_mulai);
        $this->assertEquals($staff->id, $tahap1->user_id);

        // Selesaikan tahap
        $tahap1->selesaikan($staff, 'Potongan 30 pcs selesai rapi');
        $tahap1->refresh();

        $this->assertEquals('selesai', $tahap1->status);
        $this->assertNotNull($tahap1->waktu_selesai);

        // Cek sinkronisasi ke tabel produksi induk
        $produksi->refresh();
        $this->assertEquals('selesai', $produksi->status_pemotongan_kain);
        $this->assertGreaterThan(0, $produksi->progress_persen);
    }
}
