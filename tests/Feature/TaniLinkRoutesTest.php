<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaniLinkRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_tanilink_routes_render_successfully(): void
    {
        $this->seed();

        // Public routes
        $publicRoutes = ['/', '/login', '/register'];
        foreach ($publicRoutes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }

        // Authenticated Petani
        $petani = User::where('role', 'petani')->first();
        if ($petani) {
            $this->actingAs($petani)->get('/petani')->assertStatus(200);
            $this->actingAs($petani)->get('/petani/komoditas')->assertStatus(200);
        }

        // Authenticated Pembeli / Konsumen
        $pembeli = User::where('role', 'pembeli')->first();
        if ($pembeli) {
            $this->actingAs($pembeli)->get('/pembeli')->assertStatus(200);
            $this->actingAs($pembeli)->get('/pembeli/katalog')->assertStatus(200);
        }

        // Authenticated Admin
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $this->actingAs($admin)->get('/admin')->assertStatus(200);
            $this->actingAs($admin)->get('/admin/bantuan')->assertStatus(200);
            $this->actingAs($admin)->post('/admin/bantuan/contact', [
                'admin_phone' => '089988776655',
                'admin_email' => 'admin.official@tanilink.id',
                'admin_hours' => 'Senin - Minggu 24 Jam',
            ])->assertRedirect();
        }

        // Test login with phone number (as guest)
        auth()->logout();
        $userWithPhone = User::factory()->create([
            'phone' => '081299988877',
            'password' => bcrypt('secret123'),
            'role' => 'petani',
        ]);
        $response = $this->post('/login', [
            'login' => '081299988877',
            'password' => 'secret123',
        ]);
        $response->assertRedirect('/petani');
    }
}
