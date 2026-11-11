<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_accessible_for_guest(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Daftar Kemitraan');
    }

    public function test_landing_page_accessible_for_authenticated_users(): void
    {
        $petani = User::factory()->create(['role' => 'petani']);
        $response = $this->actingAs($petani)->get('/');
        $response->assertStatus(200);
        $response->assertSee('Buka Dashboard (Petani)');

        $pending = User::factory()->create(['role' => 'pending']);
        $responsePending = $this->actingAs($pending)->get('/');
        $responsePending->assertStatus(200);
        $responsePending->assertSee('Lanjutkan Onboarding');
    }

    public function test_registration_and_onboarding_flow(): void
    {
        $response = $this->post('/register', [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'phone' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/onboarding');
        $this->assertAuthenticated();

        $user = auth()->user();
        $this->assertEquals('pending', $user->role);

        // Access onboarding page
        $onboardingPage = $this->actingAs($user)->get('/onboarding');
        $onboardingPage->assertStatus(200);

        // Select role petani
        $selectRole = $this->actingAs($user)->post('/onboarding', [
            'purpose' => 'petani',
        ]);
        $selectRole->assertRedirect('/petani');

        $user->refresh();
        $this->assertEquals('petani', $user->role);
    }

    public function test_login_and_logout_flow(): void
    {
        $user = User::factory()->create([
            'email' => 'tani@example.com',
            'phone' => '089912345678',
            'password' => bcrypt('password123'),
            'role' => 'petani',
        ]);

        // Login by email
        $responseEmail = $this->post('/login', [
            'login' => 'tani@example.com',
            'password' => 'password123',
        ]);
        $responseEmail->assertRedirect('/petani');
        $this->assertAuthenticatedAs($user);

        // Logout
        $logoutResponse = $this->get('/logout');
        $logoutResponse->assertRedirect('/login');
        $this->assertGuest();

        // Login by phone
        $responsePhone = $this->post('/login', [
            'login' => '089912345678',
            'password' => 'password123',
        ]);
        $responsePhone->assertRedirect('/petani');
        $this->assertAuthenticatedAs($user);
    }

    public function test_role_middleware_protection(): void
    {
        $petani = User::factory()->create(['role' => 'petani']);
        $pembeli = User::factory()->create(['role' => 'pembeli']);
        $admin = User::factory()->create(['role' => 'admin']);

        // Petani cannot access admin or pembeli portal
        $this->actingAs($petani)->get('/admin')->assertStatus(403);
        $this->actingAs($petani)->get('/pembeli')->assertStatus(403);

        // Pembeli cannot access admin or petani portal
        $this->actingAs($pembeli)->get('/admin')->assertStatus(403);
        $this->actingAs($pembeli)->get('/petani')->assertStatus(403);

        // Admin can access admin portal
        $this->actingAs($admin)->get('/admin')->assertStatus(200);
    }
}
