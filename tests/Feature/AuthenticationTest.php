<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('STORPIFY');
    }

    public function test_register_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('STORPIFY');
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
            'phone' => '01711112233',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/customer/dashboard');

        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
            'role' => 'customer',
        ]);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create([
            'email' => 'loginuser@example.com',
            'password' => Hash::make('Password@123'),
            'role' => 'customer',
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'email' => 'loginuser@example.com',
            'password' => 'Password@123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/customer/dashboard');
    }

    public function test_staff_users_are_redirected_to_admin_dashboard_on_login(): void
    {
        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin_test@storpify.com',
            'password' => Hash::make('AdminSecret@2026!'),
            'role' => 'super_admin',
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin_test@storpify.com',
            'password' => 'AdminSecret@2026!',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/admin/dashboard');
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create(['status' => 'active']);

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'wrongpass@example.com',
            'password' => Hash::make('CorrectPassword123'),
        ]);

        $this->post('/login', [
            'email' => 'wrongpass@example.com',
            'password' => 'WrongPassword',
        ]);

        $this->assertGuest();
    }
}
