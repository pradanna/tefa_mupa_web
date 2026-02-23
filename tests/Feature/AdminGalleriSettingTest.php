<?php

namespace Tests\Feature;

use App\Models\Galleri;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AdminGalleriSettingTest extends TestCase
{
    use RefreshDatabase;

    /** @var list<string> File path yang dibuat saat testing, akan dihapus di tearDown() */
    protected array $filesToClean = [];

    protected function tearDown(): void
    {
        // Pastikan semua gambar yang dibuat saat testing dihapus setelah test selesai (lulus atau gagal)
        $baseDir = public_path('images/galleri');
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

    protected function createGalleri(array $overrides = []): Galleri
    {
        $defaults = [
            'image' => 'test-gallery-' . uniqid() . '.jpg',
            'path' => asset('images/galleri'),
        ];
        return Galleri::create(array_merge($defaults, $overrides));
    }

    public function test_guest_tidak_bisa_akses_halaman_daftar_galleri(): void
    {
        $response = $this->get(route('album.index'));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_akses_halaman_tambah_galleri(): void
    {
        $response = $this->get(route('album.create'));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_menyimpan_galleri(): void
    {
        $response = $this->post(route('album.store'), [
            '_token' => csrf_token(),
            'file' => UploadedFile::fake()->image('gallery-guest.jpg'),
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $this->assertDatabaseCount('gallers', 0);
    }

    public function test_guest_tidak_bisa_hapus_galleri(): void
    {
        $galleri = $this->createGalleri();

        $response = $this->delete(route('album.destroy', $galleri), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $this->assertDatabaseHas('gallers', ['id' => $galleri->id]);
    }

    public function test_admin_dapat_akses_halaman_daftar_galleri(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('album.index'));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.galleri.index');
        $response->assertSee('Galleri', false);
        $response->assertSee('Tambah Gambar', false);
    }

    public function test_admin_dapat_akses_halaman_tambah_galleri(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('album.create'));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.galleri.create');
        $response->assertSee('Form Tambah Galleri', false);
        $response->assertSee('Upload Gambar', false);
    }

    public function test_admin_dapat_menyimpan_gambar_via_api(): void
    {
        $this->loginAsAdmin();
        $file = UploadedFile::fake()->image('gallery-new.jpg');

        $response = $this->post(route('api.save-image'), [
            'file' => $file,
            '_token' => csrf_token(),
        ], [
            'Accept' => 'application/json',
        ]);

        $galleri = Galleri::orderBy('id', 'desc')->first();
        if ($galleri && $galleri->image) {
            $this->filesToClean[] = public_path('images/galleri/' . $galleri->image);
        }

        $response->assertStatus(201);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('gallers', [
            'path' => asset('images/galleri'),
        ]);
    }

    public function test_save_image_api_tidak_menyimpan_jika_file_tidak_ada(): void
    {
        $this->loginAsAdmin();
        $response = $this->post(route('api.save-image'), [
            '_token' => csrf_token(),
        ], [
            'Accept' => 'application/json',
        ]);

        $this->assertNotSame(201, $response->getStatusCode(), 'Tanpa file tidak boleh return 201 Created');
        $this->assertDatabaseCount('gallers', 0);
    }

    public function test_halaman_daftar_galleri_menampilkan_data_galleri(): void
    {
        $this->loginAsAdmin();
        $this->createGalleri(['image' => 'img1.jpg', 'path' => asset('images/galleri')]);
        $this->createGalleri(['image' => 'img2.jpg', 'path' => asset('images/galleri')]);

        $response = $this->get(route('album.index'));
        $response->assertStatus(200);
        $response->assertSee('img1.jpg', false);
        $response->assertSee('img2.jpg', false);
    }

    public function test_admin_dapat_hapus_galleri(): void
    {
        $this->loginAsAdmin();
        $imageName = 'test-delete-galleri-' . uniqid() . '.jpg';
        $galleri = $this->createGalleri(['image' => $imageName]);

        $filePath = public_path('images/galleri/' . $imageName);
        File::ensureDirectoryExists(dirname($filePath));
        File::put($filePath, 'fake image content');
        $this->filesToClean[] = $filePath;
        $this->assertTrue(File::exists($filePath), 'File gambar galleri harus ada di disk sebelum dihapus');

        $response = $this->delete(route('album.destroy', $galleri), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('album.index'));
        $response->assertSessionHas('success', 'Gambar berhasil dihapus');
        $this->assertDatabaseMissing('gallers', ['id' => $galleri->id]);
    }

    public function test_hapus_galleri_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $response = $this->delete(route('album.destroy', 99999), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
