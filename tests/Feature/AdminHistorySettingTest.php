<?php

namespace Tests\Feature;

use App\Models\History;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AdminHistorySettingTest extends TestCase
{
    use RefreshDatabase;

    /** @var list<string> File path yang dibuat saat testing, akan dihapus di tearDown() */
    protected array $filesToClean = [];

    protected function tearDown(): void
    {
        $baseDir = public_path('images/history');
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

    protected function createHistory(array $overrides = []): History
    {
        $defaults = [
            'title' => 'Sejarah Test',
            'body' => 'Konten sejarah test',
            'path' => asset('images/history'),
            'image' => 'test-history.jpg',
        ];
        return History::create(array_merge($defaults, $overrides));
    }

    public function test_guest_tidak_bisa_akses_halaman_history(): void
    {
        $response = $this->get(route('history.index'));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_akses_halaman_tambah_history(): void
    {
        $response = $this->get(route('history.create'));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_menyimpan_history(): void
    {
        $file = UploadedFile::fake()->image('sejarah-guest.jpg');

        $response = $this->post(route('history.store'), [
            '_token' => csrf_token(),
            'title' => 'Sejarah dari Guest',
            'body' => 'Deskripsi sejarah',
            'file' => $file,
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $this->assertDatabaseMissing('histories', ['title' => 'Sejarah dari Guest']);
    }

    public function test_guest_tidak_bisa_update_history(): void
    {
        $history = $this->createHistory(['title' => 'Sejarah Asli']);

        $response = $this->put(route('history.update', $history), [
            '_token' => csrf_token(),
            'title' => 'Sejarah Diubah Guest',
            'body' => $history->body,
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $history->refresh();
        $this->assertSame('Sejarah Asli', $history->title);
    }

    public function test_admin_dapat_akses_halaman_history(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('history.index'));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.history.index');
        $response->assertSee('Profile / Sejarah', false);
    }

    public function test_admin_dapat_akses_halaman_tambah_history_ketika_belum_ada_data(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('history.index'));
        $response->assertStatus(200);
        $response->assertSee('Tambah', false);
        $response->assertSee('Profile/Sejarah', false);
        $response->assertSee('Judul', false);
        $response->assertSee('Body', false);
    }

    public function test_admin_dapat_menyimpan_history_baru(): void
    {
        $this->loginAsAdmin();
        $file = UploadedFile::fake()->image('sejarah-baru.jpg');

        $response = $this->post(route('history.store'), [
            '_token' => csrf_token(),
            'title' => 'Sejarah Baru',
            'body' => 'Konten sejarah baru.',
            'file' => $file,
        ]);

        $response->assertRedirect(route('history.index'));
        $response->assertSessionHas('success', 'Data sejarah berhasil dibuat.');

        $this->assertDatabaseHas('histories', [
            'title' => 'Sejarah Baru',
        ]);

        $history = History::where('title', 'Sejarah Baru')->first();
        if ($history && $history->image) {
            $this->filesToClean[] = public_path('images/history/' . $history->image);
        }
    }

    public function test_store_history_gagal_jika_data_sudah_ada(): void
    {
        $this->loginAsAdmin();
        $this->createHistory(['title' => 'Sejarah Sudah Ada']);
        $file = UploadedFile::fake()->image('sejarah-dua.jpg');

        $response = $this->post(route('history.store'), [
            '_token' => csrf_token(),
            'title' => 'Sejarah Kedua',
            'body' => 'Konten.',
            'file' => $file,
        ]);

        $response->assertRedirect(route('history.index'));
        $response->assertSessionHas('error', 'Data sejarah sudah ada. Silakan edit data yang ada.');
        $this->assertDatabaseMissing('histories', ['title' => 'Sejarah Kedua']);
    }

    public function test_store_history_gagal_jika_file_tidak_ada(): void
    {
        $this->loginAsAdmin();
        $response = $this->post(route('history.store'), [
            '_token' => csrf_token(),
            'title' => 'Sejarah Tanpa Gambar',
            'body' => 'Konten.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Gambar wajib diunggah.');
        $this->assertDatabaseMissing('histories', ['title' => 'Sejarah Tanpa Gambar']);
    }

    public function test_store_history_gagal_jika_title_kosong(): void
    {
        $this->loginAsAdmin();
        $file = UploadedFile::fake()->image('sejarah.jpg');

        $response = $this->post(route('history.store'), [
            '_token' => csrf_token(),
            'title' => '',
            'body' => 'Konten body',
            'file' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertDatabaseCount('histories', 0);
    }

    public function test_admin_dapat_akses_halaman_history_dan_melihat_form_edit_ketika_data_ada(): void
    {
        $this->loginAsAdmin();
        $this->createHistory(['title' => 'Sejarah Edit', 'body' => 'Body sejarah.']);

        $response = $this->get(route('history.index'));
        $response->assertStatus(200);
        $response->assertSee('Edit', false);
        $response->assertSee('Sejarah Edit');
        $response->assertSee('Body sejarah.');
    }

    public function test_admin_dapat_update_history(): void
    {
        $this->loginAsAdmin();
        $history = $this->createHistory(['title' => 'Sejarah Awal']);

        $response = $this->put(route('history.update', $history), [
            '_token' => csrf_token(),
            'title' => 'Sejarah Diubah',
            'body' => 'Body diperbarui.',
        ]);

        $response->assertRedirect(route('history.index'));
        $response->assertSessionHas('success', 'History updated successfully');

        $history->refresh();
        $this->assertSame('Sejarah Diubah', $history->title);
        $this->assertSame('Body diperbarui.', $history->body);
    }

    public function test_admin_dapat_update_history_dengan_gambar_baru(): void
    {
        $this->loginAsAdmin();
        $history = $this->createHistory(['title' => 'Sejarah Update Gambar', 'image' => 'lama.jpg']);
        $newFile = UploadedFile::fake()->image('sejarah-baru.jpg');

        $response = $this->put(route('history.update', $history), [
            '_token' => csrf_token(),
            'title' => $history->title,
            'body' => $history->body,
            'file' => $newFile,
        ]);

        $response->assertRedirect(route('history.index'));
        $response->assertSessionHas('success', 'History updated successfully');

        $history->refresh();
        $this->assertNotSame('lama.jpg', $history->image);
        if ($history->image) {
            $this->filesToClean[] = public_path('images/history/' . $history->image);
        }
    }

    public function test_update_history_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $history = $this->createHistory();

        $response = $this->put(route('history.update', 99999), [
            '_token' => csrf_token(),
            'title' => 'Sejarah Baru',
            'body' => $history->body,
        ]);

        $this->assertTrue($response->isRedirect() || $response->getStatusCode() === 404);
        if ($response->isRedirect()) {
            $response->assertSessionHas('error', 'History not found');
        }
    }
}
