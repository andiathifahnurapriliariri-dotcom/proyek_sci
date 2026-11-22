<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class StaffPagesRenderTest extends TestCase
{
    public function test_all_staff_views_render_successfully(): void
    {
        $staff = User::where('role', 'operator_produksi')->first();
        $this->assertNotNull($staff);

        $response = $this->actingAs($staff)->get(route('staff.dashboard'));
        $response->assertStatus(200);

        $response = $this->actingAs($staff)->get(route('staff.detail-pekerjaan'));
        $response->assertStatus(200);

        $response = $this->actingAs($staff)->get(route('staff.antrean-produksi'));
        $response->assertStatus(200);

        $response = $this->actingAs($staff)->get(route('staff.antrean-sablon'));
        $response->assertStatus(200);

        $response = $this->actingAs($staff)->get(route('staff.riwayat-pekerjaan'));
        $response->assertStatus(200);

        $response = $this->actingAs($staff)->get(route('staff.riwayat-saya'));
        $response->assertStatus(200);

        $response = $this->actingAs($staff)->get(route('staff.notifikasi'));
        $response->assertStatus(200);

        $response = $this->actingAs($staff)->get(route('staff.profil'));
        $response->assertStatus(200);
    }
}
