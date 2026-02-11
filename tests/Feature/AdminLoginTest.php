<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function getAdminCredentials(): array
    {
        $jsonPath = database_path('seeders/data/users.json');
        $this->assertFileExists($jsonPath, 'File users.json harus ada di database/seeders/data/');

        $users = json_decode(File::get($jsonPath), true);
        $this->assertNotEmpty($users, 'users.json harus berisi minimal satu user');

        $admin = $users[0];
        $this->assertArrayHasKey('email', $admin);
        $this->assertArrayHasKey('password', $admin);

        return [
            'email' => $admin['email'],
            'password' => $admin['password'],
        ];
    }

    public function test_halaman_login_backoffice_dapat_diakses(): void
    {
        $response = $this->get(route('login-backoffice'));

        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.login.index');
        $response->assertSee('Login to your account', false);
    }

    public function test_admin_dapat_login_dengan_kredensial_dari_users_json(): void
    {
        $this->seed(UserSeeder::class);
        $credentials = $this->getAdminCredentials();

        $response = $this->post(route('auth'), [
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }

    public function test_setelah_login_admin_dapat_akses_dashboard(): void
    {
        $this->seed(UserSeeder::class);
        $credentials = $this->getAdminCredentials();

        $this->post(route('auth'), [
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            '_token' => csrf_token(),
        ]);

        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
    }

    public function test_login_gagal_dengan_password_salah(): void
    {
        $this->seed(UserSeeder::class);
        $credentials = $this->getAdminCredentials();

        $response = $this->post(route('auth'), [
            'email' => $credentials['email'],
            'password' => 'PasswordSalah123!',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_login_gagal_dengan_email_tidak_terdaftar(): void
    {
        $response = $this->post(route('auth'), [
            'email' => 'tidakada@example.com',
            'password' => '@Superdev123',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_admin_dapat_logout_dan_diarahkan_ke_halaman_login(): void
    {
        $this->seed(UserSeeder::class);
        $credentials = $this->getAdminCredentials();

        $this->post(route('auth'), [
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            '_token' => csrf_token(),
        ]);

        $this->assertAuthenticated();

        $response = $this->post(route('logout'), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $this->assertGuest();
    }
}
