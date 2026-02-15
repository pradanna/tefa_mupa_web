<?php

namespace Tests\Feature;

use App\Models\Category;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AdminCategorySettingTest extends TestCase
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

    protected function loginAsAdmin(): void
    {
        $this->seed(UserSeeder::class);
        $credentials = $this->getAdminCredentials();
        $this->post(route('auth'), [
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            '_token' => csrf_token(),
        ]);
        $this->assertAuthenticated();
    }

    public function test_guest_tidak_bisa_akses_halaman_daftar_kategori(): void
    {
        $response = $this->get(route('categories.index'));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_akses_halaman_tambah_kategori(): void
    {
        $response = $this->get(route('categories.create'));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_akses_halaman_edit_kategori(): void
    {
        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test',
            'type' => 'catalog',
        ]);
        $response = $this->get(route('categories.edit', $category));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_menyimpan_kategori(): void
    {
        $response = $this->post(route('categories.store'), [
            '_token' => csrf_token(),
            'name' => 'Kategori dari Guest',
            'slug' => 'kategori-dari-guest',
            'type' => 'catalog',
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $this->assertDatabaseMissing('categories', ['slug' => 'kategori-dari-guest']);
    }

    public function test_guest_tidak_bisa_update_kategori(): void
    {
        $category = Category::create([
            'name' => 'Kategori Asli',
            'slug' => 'kategori-asli',
            'type' => 'catalog',
        ]);

        $response = $this->put(route('categories.update', $category), [
            '_token' => csrf_token(),
            'name' => 'Kategori Diubah Guest',
            'slug' => 'kategori-diubah-guest',
            'type' => 'content',
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $category->refresh();
        $this->assertSame('Kategori Asli', $category->name);
        $this->assertSame('kategori-asli', $category->slug);
    }

    public function test_guest_tidak_bisa_hapus_kategori(): void
    {
        $category = Category::create([
            'name' => 'Kategori Untuk Dihapus',
            'slug' => 'kategori-untuk-dihapus',
            'type' => 'catalog',
        ]);

        $response = $this->delete(route('categories.destroy', $category), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_admin_dapat_akses_halaman_daftar_kategori(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('categories.index'));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.category.index');
        $response->assertSee('Kategori', false);
        $response->assertSee('Tambah Kategori', false);
    }

    public function test_admin_dapat_akses_halaman_tambah_kategori(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('categories.create'));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.category.create');
        $response->assertSee('Form Tambah Kategori', false);
        $response->assertSee('Nama Kategori', false);
        $response->assertSee('Slug', false);
        // $response->assertSee('Type', false);
    }

    public function test_admin_dapat_menyimpan_kategori_baru(): void
    {
        $this->loginAsAdmin();
        $response = $this->post(route('categories.store'), [
            '_token' => csrf_token(),
            'name' => 'Katalog Produk',
            'slug' => 'katalog-produk',
            'type' => 'catalog',
            'icon' => 'fa-box',
            'description' => 'Kategori untuk katalog produk',
        ]);

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('success', 'Category created successfully');

        $this->assertDatabaseHas('categories', [
            'name' => 'Katalog Produk',
            'slug' => 'katalog-produk',
            'type' => 'catalog',
        ]);
    }

    public function test_admin_dapat_menyimpan_kategori_tipe_berita(): void
    {
        $this->loginAsAdmin();
        $response = $this->post(route('categories.store'), [
            '_token' => csrf_token(),
            'name' => 'Berita Utama',
            'slug' => 'berita-utama',
            'type' => 'content',
            'description' => 'Kategori berita',
        ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Berita Utama',
            'slug' => 'berita-utama',
            'type' => 'content',
        ]);
    }

    public function test_store_kategori_gagal_jika_nama_kosong(): void
    {
        $this->loginAsAdmin();
        $response = $this->post(route('categories.store'), [
            '_token' => csrf_token(),
            'name' => '',
            'slug' => 'tanpa-nama',
            'type' => 'catalog',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('categories', ['slug' => 'tanpa-nama']);
    }

    public function test_store_kategori_gagal_jika_type_tidak_valid(): void
    {
        $this->loginAsAdmin();
        $response = $this->post(route('categories.store'), [
            '_token' => csrf_token(),
            'name' => 'Kategori Invalid',
            'slug' => 'kategori-invalid',
            'type' => 'invalid_type',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('categories', ['slug' => 'kategori-invalid']);
    }

    public function test_store_kategori_gagal_jika_slug_duplikat(): void
    {
        $this->loginAsAdmin();
        Category::create([
            'name' => 'Kategori Lama',
            'slug' => 'slug-sama',
            'type' => 'catalog',
        ]);

        $response = $this->post(route('categories.store'), [
            '_token' => csrf_token(),
            'name' => 'Kategori Baru',
            'slug' => 'slug-sama',
            'type' => 'catalog',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertEquals(1, Category::where('slug', 'slug-sama')->count());
    }

    public function test_admin_dapat_akses_halaman_edit_kategori(): void
    {
        $this->loginAsAdmin();
        $category = Category::create([
            'name' => 'Kategori Edit',
            'slug' => 'kategori-edit',
            'type' => 'catalog',
        ]);

        $response = $this->get(route('categories.edit', $category));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.category.edit');
        $response->assertSee('Form Edit Kategori', false);
        $response->assertSee('Kategori Edit', false);
    }

    public function test_admin_dapat_update_kategori(): void
    {
        $this->loginAsAdmin();
        $category = Category::create([
            'name' => 'Kategori Awal',
            'slug' => 'kategori-awal',
            'type' => 'catalog',
        ]);

        $response = $this->put(route('categories.update', $category), [
            '_token' => csrf_token(),
            'name' => 'Kategori Diubah',
            'slug' => 'kategori-diubah',
            'type' => 'content',
            'description' => 'Deskripsi baru',
        ]);

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('success', 'Category update success');

        $category->refresh();
        $this->assertSame('Kategori Diubah', $category->name);
        $this->assertSame('kategori-diubah', $category->slug);
        $this->assertSame('content', $category->type);
    }

    public function test_edit_kategori_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('categories.edit', 99999));
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_update_kategori_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $response = $this->put(route('categories.update', 99999), [
            '_token' => csrf_token(),
            'name' => 'Nama',
            'slug' => 'slug-baru',
            'type' => 'catalog',
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_halaman_daftar_kategori_menampilkan_data_kategori(): void
    {
        $this->loginAsAdmin();
        Category::create([
            'name' => 'Katalog',
            'slug' => 'katalog',
            'type' => 'catalog',
        ]);
        Category::create([
            'name' => 'Berita',
            'slug' => 'berita',
            'type' => 'content',
        ]);

        $response = $this->get(route('categories.index'));
        $response->assertStatus(200);
        $response->assertSee('Katalog');
        $response->assertSee('Berita');
    }

    public function test_admin_dapat_hapus_kategori(): void
    {
        $this->loginAsAdmin();
        $category = Category::create([
            'name' => 'Kategori Untuk Dihapus',
            'slug' => 'kategori-untuk-dihapus',
            'type' => 'catalog',
        ]);

        $response = $this->delete(route('categories.destroy', $category), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('success', 'Category deleted successfully');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_hapus_kategori_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $response = $this->delete(route('categories.destroy', 99999), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
