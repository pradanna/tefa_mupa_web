<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\News;
use Database\Seeders\CategorySeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AdminNewsSettingTest extends TestCase
{
    use RefreshDatabase;

    /** @var list<string> File path yang dibuat saat testing, akan dihapus di tearDown() */
    protected array $filesToClean = [];

    protected function tearDown(): void
    {
        $baseDir = public_path('images/news');
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

    protected function getCategoryId(): int
    {
        $this->seed(CategorySeeder::class);
        $category = Category::first();
        $this->assertNotNull($category, 'Harus ada kategori');
        return $category->id;
    }

    protected function createNews(array $overrides = []): News
    {
        $categoryId = $this->getCategoryId();
        $defaults = [
            'title' => 'Berita Test',
            'slug' => 'berita-test',
            'id_category' => $categoryId,
            'image' => 'test-news.jpg',
            'path' => asset('images/news'),
            'content' => 'Konten berita test',
            'date' => now()->format('Y-m-d'),
            'status' => 'publis',
            'id_user' => 1,
        ];
        return News::create(array_merge($defaults, $overrides));
    }

    public function test_guest_tidak_bisa_akses_halaman_daftar_berita(): void
    {
        $response = $this->get(route('articles.index'));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_akses_halaman_tambah_berita(): void
    {
        $response = $this->get(route('articles.create'));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_akses_halaman_edit_berita(): void
    {
        $this->seed(CategorySeeder::class);
        $news = $this->createNews();
        $response = $this->get(route('articles.edit', $news));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_menyimpan_berita(): void
    {
        $this->seed(CategorySeeder::class);
        $categoryId = $this->getCategoryId();
        $file = UploadedFile::fake()->image('berita-guest.jpg');

        $response = $this->post(route('articles.store'), [
            '_token' => csrf_token(),
            'title' => 'Berita dari Guest',
            'slug' => 'berita-dari-guest',
            'id_category' => $categoryId,
            'content' => 'Konten',
            'date' => now()->format('Y-m-d'),
            'status' => 'publis',
            'file' => $file,
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $this->assertDatabaseMissing('news', ['slug' => 'berita-dari-guest']);
    }

    public function test_guest_tidak_bisa_update_berita(): void
    {
        $this->seed(CategorySeeder::class);
        $news = $this->createNews(['title' => 'Berita Asli']);

        $response = $this->put(route('articles.update', $news), [
            '_token' => csrf_token(),
            'title' => 'Berita Diubah Guest',
            'slug' => $news->slug,
            'id_category' => $news->id_category,
            'content' => $news->content,
            'date' => $news->date,
            'status' => $news->status,
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $news->refresh();
        $this->assertSame('Berita Asli', $news->title);
    }

    public function test_guest_tidak_bisa_hapus_berita(): void
    {
        $this->seed(CategorySeeder::class);
        $news = $this->createNews();

        $response = $this->delete(route('articles.destroy', $news), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $this->assertDatabaseHas('news', ['id' => $news->id]);
    }

    public function test_admin_dapat_akses_halaman_daftar_berita(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('articles.index'));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.news.index');
        $response->assertSee('Berita', false);
        $response->assertSee('Tambah Berita', false);
    }

    public function test_admin_dapat_akses_halaman_tambah_berita(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('articles.create'));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.news.create');
        $response->assertSee('Form Tambah Berita', false);
        $response->assertSee('Judul Berita', false);
        $response->assertSee('Slug', false);
        $response->assertSee('Kategori', false);
    }

    public function test_admin_dapat_menyimpan_berita_baru(): void
    {
        $this->loginAsAdmin();
        $categoryId = $this->getCategoryId();
        $file = UploadedFile::fake()->image('berita-baru.jpg');

        $response = $this->post(route('articles.store'), [
            '_token' => csrf_token(),
            'title' => 'Berita Baru',
            'slug' => 'berita-baru',
            'id_category' => $categoryId,
            'content' => 'Konten berita baru',
            'date' => now()->format('Y-m-d'),
            'status' => 'publis',
            'file' => $file,
        ]);

        $response->assertRedirect(route('articles.index'));
        $response->assertSessionHas('success', 'News created successfully');

        $this->assertDatabaseHas('news', [
            'title' => 'Berita Baru',
            'slug' => 'berita-baru',
        ]);

        $news = News::where('slug', 'berita-baru')->first();
        if ($news && $news->image) {
            $this->filesToClean[] = public_path('images/news/' . $news->image);
        }
    }

    public function test_store_berita_gagal_jika_file_tidak_ada(): void
    {
        $this->loginAsAdmin();
        $categoryId = $this->getCategoryId();
        $response = $this->post(route('articles.store'), [
            '_token' => csrf_token(),
            'title' => 'Berita Tanpa Gambar',
            'slug' => 'berita-tanpa-gambar',
            'id_category' => $categoryId,
            'content' => 'Konten',
            'date' => now()->format('Y-m-d'),
            'status' => 'publis',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Image is required');
        $this->assertDatabaseMissing('news', ['slug' => 'berita-tanpa-gambar']);
    }

    public function test_store_berita_gagal_jika_title_kosong(): void
    {
        $this->loginAsAdmin();
        $categoryId = $this->getCategoryId();
        $file = UploadedFile::fake()->image('berita.jpg');

        $response = $this->post(route('articles.store'), [
            '_token' => csrf_token(),
            'title' => '',
            'slug' => 'berita-title-kosong',
            'id_category' => $categoryId,
            'content' => 'Konten',
            'date' => now()->format('Y-m-d'),
            'status' => 'publis',
            'file' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('news', ['slug' => 'berita-title-kosong']);
    }

    public function test_admin_dapat_akses_halaman_edit_berita(): void
    {
        $this->loginAsAdmin();
        $news = $this->createNews(['title' => 'Berita Edit']);

        $response = $this->get(route('articles.edit', $news));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.news.edit');
        $response->assertSee('Form Edit Berita', false);
        $response->assertSee('Berita Edit', false);
    }

    public function test_admin_dapat_update_berita(): void
    {
        $this->loginAsAdmin();
        $news = $this->createNews(['title' => 'Berita Awal']);

        $response = $this->put(route('articles.update', $news), [
            '_token' => csrf_token(),
            'title' => 'Berita Diubah',
            'slug' => $news->slug,
            'id_category' => $news->id_category,
            'content' => 'Konten baru',
            'date' => $news->date,
            'status' => 'unpublis',
        ]);

        $response->assertRedirect(route('articles.index'));
        $response->assertSessionHas('success', 'News updated successfully');

        $news->refresh();
        $this->assertSame('Berita Diubah', $news->title);
        $this->assertSame('unpublis', $news->status);
    }

    public function test_update_berita_dengan_gambar_baru_file_lama_terhapus(): void
    {
        $this->loginAsAdmin();
        $oldImageName = 'test-old-news-' . uniqid() . '.jpg';
        $news = $this->createNews(['title' => 'Berita Update Gambar', 'image' => $oldImageName]);

        $oldFilePath = public_path('images/news/' . $oldImageName);
        File::ensureDirectoryExists(dirname($oldFilePath));
        File::put($oldFilePath, 'fake old image content');
        $this->assertTrue(File::exists($oldFilePath), 'File gambar lama berita harus ada di disk sebelum update');

        $newFile = UploadedFile::fake()->image('berita-baru.jpg');
        $response = $this->put(route('articles.update', $news), [
            '_token' => csrf_token(),
            'title' => 'Berita Gambar Diubah',
            'slug' => $news->slug,
            'id_category' => $news->id_category,
            'content' => $news->content,
            'date' => $news->date,
            'status' => $news->status,
            'file' => $newFile,
        ]);

        $response->assertRedirect(route('articles.index'));
        $response->assertSessionHas('success', 'News updated successfully');
        $this->assertFalse(File::exists($oldFilePath), 'File gambar lama berita harus terhapus dari disk setelah update dengan gambar baru');

        $news->refresh();
        if ($news->image) {
            $this->filesToClean[] = public_path('images/news/' . $news->image);
        }
    }

    public function test_edit_berita_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('articles.edit', 99999));
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_update_berita_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $this->seed(CategorySeeder::class);
        $category = Category::first();

        $response = $this->put(route('articles.update', 99999), [
            '_token' => csrf_token(),
            'title' => 'Berita Baru',
            'slug' => 'berita-baru',
            'id_category' => $category->id,
            'content' => 'Konten',
            'date' => now()->format('Y-m-d'),
            'status' => 'publis',
        ]);
        $this->assertTrue($response->isRedirect() || $response->getStatusCode() === 404);
        if ($response->isRedirect()) {
            $response->assertSessionHas('error');
        }
    }

    public function test_halaman_daftar_berita_menampilkan_data_berita(): void
    {
        $this->loginAsAdmin();
        $this->createNews(['title' => 'Berita Satu', 'slug' => 'berita-satu']);
        $this->createNews(['title' => 'Berita Dua', 'slug' => 'berita-dua']);

        $response = $this->get(route('articles.index'));
        $response->assertStatus(200);
        $response->assertSee('Berita Satu');
        $response->assertSee('Berita Dua');
    }

    public function test_admin_dapat_hapus_berita(): void
    {
        $this->loginAsAdmin();
        $imageName = 'test-delete-news-' . uniqid() . '.jpg';
        $news = $this->createNews(['title' => 'Berita Untuk Dihapus', 'image' => $imageName]);

        $filePath = public_path('images/news/' . $imageName);
        File::ensureDirectoryExists(dirname($filePath));
        File::put($filePath, 'fake image content');
        $this->filesToClean[] = $filePath;
        $this->assertTrue(File::exists($filePath), 'File gambar berita harus ada di disk sebelum dihapus');

        $response = $this->delete(route('articles.destroy', $news), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('articles.index'));
        $response->assertSessionHas('success', 'News deleted successfully');
        $this->assertDatabaseMissing('news', ['id' => $news->id]);
        $this->assertFalse(File::exists($filePath), 'File gambar berita harus terhapus dari disk setelah data dihapus');
    }

    public function test_hapus_berita_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $response = $this->delete(route('articles.destroy', 99999), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
