<?php

namespace Tests\Feature;

use App\Models\Catalog;
use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AdminCatalogSettingTest extends TestCase
{
    use RefreshDatabase;

    /** @var list<string> File path yang dibuat saat testing, akan dihapus di tearDown() */
    protected array $filesToClean = [];

    protected function tearDown(): void
    {
        $baseDir = public_path('images/catalog');
        foreach ($this->filesToClean as $path) {
            if ($path !== '' && str_starts_with($path, $baseDir) && File::exists($path)) {
                File::delete($path);
            }
        }
        $this->filesToClean = [];
        parent::tearDown();
    }

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

    protected function getCategoryIds(): array
    {
        $this->seed(CategorySeeder::class);
        $category = Category::where('type', 'catalog')->first();
        $subCategory = Category::where('type', 'sub_catalog')->first();
        $this->assertNotNull($category, 'Harus ada kategori type catalog');
        $this->assertNotNull($subCategory, 'Harus ada sub kategori type sub_catalog');
        return [
            'id_category' => $category->id,
            'id_sub_category' => $subCategory->id,
        ];
    }

    protected function createCatalog(array $overrides = []): Catalog
    {
        $ids = $this->getCategoryIds();
        $defaults = [
            'title' => 'Produk/Jasa Test',
            'image' => 'test-catalog.jpg',
            'path' => asset('images/catalog'),
            'id_category' => $ids['id_category'],
            'id_sub_category' => $ids['id_sub_category'],
            'desc' => 'Deskripsi test',
            'id_user' => 1,
        ];
        return Catalog::create(array_merge($defaults, $overrides));
    }

    public function test_guest_tidak_bisa_akses_halaman_daftar_katalog(): void
    {
        $response = $this->get(route('catalog.index'));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_akses_halaman_tambah_katalog(): void
    {
        $response = $this->get(route('catalog.create'));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_akses_halaman_edit_katalog(): void
    {
        $this->seed(CategorySeeder::class);
        $catalog = $this->createCatalog();
        $response = $this->get(route('catalog.edit', $catalog));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_menyimpan_katalog(): void
    {
        $this->seed(CategorySeeder::class);
        $ids = $this->getCategoryIds();
        $file = UploadedFile::fake()->image('catalog-guest.jpg');

        $response = $this->post(route('catalog.store'), [
            '_token' => csrf_token(),
            'title' => 'Katalog dari Guest',
            'id_category' => $ids['id_category'],
            'id_sub_category' => $ids['id_sub_category'],
            'desc' => 'Deskripsi guest',
            'file' => $file,
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $this->assertDatabaseMissing('catalogs', ['title' => 'Katalog dari Guest']);
    }

    public function test_guest_tidak_bisa_update_katalog(): void
    {
        $this->seed(CategorySeeder::class);
        $catalog = $this->createCatalog(['title' => 'Katalog Asli']);

        $response = $this->put(route('catalog.update', $catalog), [
            '_token' => csrf_token(),
            'title' => 'Katalog Diubah Guest',
            'id_category' => $catalog->id_category,
            'id_sub_category' => $catalog->id_sub_category,
            'desc' => $catalog->desc,
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $catalog->refresh();
        $this->assertSame('Katalog Asli', $catalog->title);
    }

    public function test_guest_tidak_bisa_hapus_katalog(): void
    {
        $this->seed(CategorySeeder::class);
        $catalog = $this->createCatalog();

        $response = $this->delete(route('catalog.destroy', $catalog), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $this->assertDatabaseHas('catalogs', ['id' => $catalog->id]);
    }

    public function test_admin_dapat_akses_halaman_daftar_katalog(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('catalog.index'));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.catalog.index');
        $response->assertSee('Katalog', false);
        $response->assertSee('Tambah Produk/Jasa', false);
    }

    public function test_admin_dapat_akses_halaman_tambah_katalog(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('catalog.create'));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.catalog.create');
        $response->assertSee('Form Tambah Produk/Jasa', false);
        $response->assertSee('Judul Produk/Jasa', false);
        $response->assertSee('Kategori', false);
        $response->assertSee('Deskripsi', false);
    }

    public function test_admin_dapat_menyimpan_katalog_baru(): void
    {
        $this->loginAsAdmin();
        $ids = $this->getCategoryIds();
        $file = UploadedFile::fake()->image('catalog-new.jpg');

        $response = $this->post(route('catalog.store'), [
            '_token' => csrf_token(),
            'title' => 'Produk/Jasa Baru',
            'id_category' => $ids['id_category'],
            'id_sub_category' => $ids['id_sub_category'],
            'desc' => 'Deskripsi produk/jasa baru',
            'file' => $file,
        ]);

        $response->assertRedirect(route('catalog.index'));
        $response->assertSessionHas('message', 'success create catalog');

        $this->assertDatabaseHas('catalogs', [
            'title' => 'Produk/Jasa Baru',
            'desc' => 'Deskripsi produk/jasa baru',
        ]);

        $catalog = Catalog::where('title', 'Produk/Jasa Baru')->first();
        if ($catalog && $catalog->image) {
            $this->filesToClean[] = public_path('images/catalog/' . $catalog->image);
        }
    }

    public function test_store_katalog_gagal_jika_file_tidak_ada(): void
    {
        $this->loginAsAdmin();
        $ids = $this->getCategoryIds();
        $response = $this->post(route('catalog.store'), [
            '_token' => csrf_token(),
            'title' => 'Katalog Tanpa Gambar',
            'id_category' => $ids['id_category'],
            'id_sub_category' => $ids['id_sub_category'],
            'desc' => 'Deskripsi',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('catalogs', ['title' => 'Katalog Tanpa Gambar']);
    }

    public function test_store_katalog_gagal_jika_title_kosong(): void
    {
        $this->loginAsAdmin();
        $ids = $this->getCategoryIds();
        $file = UploadedFile::fake()->image('catalog.jpg');

        $response = $this->post(route('catalog.store'), [
            '_token' => csrf_token(),
            'title' => '',
            'id_category' => $ids['id_category'],
            'id_sub_category' => $ids['id_sub_category'],
            'desc' => 'Deskripsi validasi title kosong',
            'file' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('catalogs', ['desc' => 'Deskripsi validasi title kosong']);
    }

    public function test_admin_dapat_akses_halaman_edit_katalog(): void
    {
        $this->loginAsAdmin();
        $catalog = $this->createCatalog(['title' => 'Katalog Edit']);

        $response = $this->get(route('catalog.edit', $catalog));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.catalog.edit');
        $response->assertSee('Form Edit Produk/Jasa', false);
        $response->assertSee('Katalog Edit', false);
    }

    public function test_admin_dapat_update_katalog(): void
    {
        $this->loginAsAdmin();
        $catalog = $this->createCatalog(['title' => 'Katalog Awal']);

        $response = $this->put(route('catalog.update', $catalog), [
            '_token' => csrf_token(),
            'title' => 'Katalog Diubah',
            'id_category' => $catalog->id_category,
            'id_sub_category' => $catalog->id_sub_category,
            'desc' => 'Deskripsi baru',
        ]);

        $response->assertRedirect(route('catalog.index'));
        $response->assertSessionHas('message', 'success update catalog');

        $catalog->refresh();
        $this->assertSame('Katalog Diubah', $catalog->title);
        $this->assertSame('Deskripsi baru', $catalog->desc);
    }

    public function test_update_katalog_dengan_gambar_baru_file_lama_terhapus(): void
    {
        $this->loginAsAdmin();
        $oldImageName = 'test-old-catalog-' . uniqid() . '.jpg';
        $catalog = $this->createCatalog(['title' => 'Katalog Update Gambar', 'image' => $oldImageName]);

        $oldFilePath = public_path('images/catalog/' . $oldImageName);
        File::ensureDirectoryExists(dirname($oldFilePath));
        File::put($oldFilePath, 'fake old image content');
        $this->assertTrue(File::exists($oldFilePath), 'File gambar lama katalog harus ada di disk sebelum update');

        $newFile = UploadedFile::fake()->image('katalog-baru.jpg');
        $response = $this->put(route('catalog.update', $catalog), [
            '_token' => csrf_token(),
            'title' => 'Katalog Gambar Diubah',
            'id_category' => $catalog->id_category,
            'id_sub_category' => $catalog->id_sub_category,
            'desc' => $catalog->desc,
            'file' => $newFile,
        ]);

        $response->assertRedirect(route('catalog.index'));
        $response->assertSessionHas('message', 'success update catalog');
        $this->assertFalse(File::exists($oldFilePath), 'File gambar lama katalog harus terhapus dari disk setelah update dengan gambar baru');

        $catalog->refresh();
        if ($catalog->image) {
            $this->filesToClean[] = public_path('images/catalog/' . $catalog->image);
        }
    }

    public function test_edit_katalog_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('catalog.edit', 99999));
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_update_katalog_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $this->seed(CategorySeeder::class);
        $category = Category::where('type', 'catalog')->first();
        $subCategory = Category::where('type', 'sub_catalog')->first();

        $response = $this->put(route('catalog.update', 99999), [
            '_token' => csrf_token(),
            'title' => 'Katalog Baru',
            'id_category' => $category->id,
            'id_sub_category' => $subCategory->id,
            'desc' => 'Deskripsi',
        ]);
        // Bisa redirect dengan error atau 404 dari model not found
        $this->assertTrue($response->isRedirect() || $response->getStatusCode() === 404);
        if ($response->isRedirect()) {
            $response->assertSessionHas('error');
        }
    }

    public function test_halaman_daftar_katalog_menampilkan_data_katalog(): void
    {
        $this->loginAsAdmin();
        $this->createCatalog(['title' => 'Produk Satu']);
        $this->createCatalog(['title' => 'Produk Dua']);

        $response = $this->get(route('catalog.index'));
        $response->assertStatus(200);
        $response->assertSee('Produk Satu');
        $response->assertSee('Produk Dua');
    }

    public function test_admin_dapat_hapus_katalog(): void
    {
        $this->loginAsAdmin();
        $imageName = 'test-delete-catalog-' . uniqid() . '.jpg';
        $catalog = $this->createCatalog(['title' => 'Katalog Untuk Dihapus', 'image' => $imageName]);

        $filePath = public_path('images/catalog/' . $imageName);
        File::ensureDirectoryExists(dirname($filePath));
        File::put($filePath, 'fake image content');
        $this->filesToClean[] = $filePath;
        $this->assertTrue(File::exists($filePath), 'File gambar katalog harus ada di disk sebelum dihapus');

        $response = $this->delete(route('catalog.destroy', $catalog), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('catalog.index'));
        $response->assertSessionHas('success', 'Catalog berhasil dihapus.');
        $this->assertDatabaseMissing('catalogs', ['id' => $catalog->id]);
        $this->assertFalse(File::exists($filePath), 'File gambar katalog harus terhapus dari disk setelah data dihapus');
    }

    public function test_hapus_katalog_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $response = $this->delete(route('catalog.destroy', 99999), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
