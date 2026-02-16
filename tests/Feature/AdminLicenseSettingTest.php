<?php

namespace Tests\Feature;

use App\Models\License;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AdminLicenseSettingTest extends TestCase
{
    use RefreshDatabase;

    /** @var list<string> File path yang dibuat saat testing, akan dihapus di tearDown() */
    protected array $filesToClean = [];

    protected function tearDown(): void
    {
        $baseDir = public_path('images/licenses');
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

    protected function createLicense(array $overrides = []): License
    {
        $defaults = [
            'name' => 'Lisensi Test',
            'code' => 'LIC-001',
            'type' => 'Sertifikasi',
            'file' => 'test-license.jpg',
        ];
        return License::create(array_merge($defaults, $overrides));
    }

    public function test_guest_tidak_bisa_akses_halaman_daftar_lisensi(): void
    {
        $response = $this->get(route('licenses.index'));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_akses_halaman_tambah_lisensi(): void
    {
        $response = $this->get(route('licenses.create'));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_akses_halaman_edit_lisensi(): void
    {
        $license = $this->createLicense();
        $response = $this->get(route('licenses.edit', $license));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_menyimpan_lisensi(): void
    {
        $file = UploadedFile::fake()->image('lisensi-guest.jpg');

        $response = $this->post(route('licenses.store'), [
            '_token' => csrf_token(),
            'name' => 'Lisensi dari Guest',
            'code' => 'GUEST-LIC',
            'type' => 'Tipe',
            'file' => $file,
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $this->assertDatabaseMissing('licenses', ['code' => 'GUEST-LIC']);
    }

    public function test_guest_tidak_bisa_update_lisensi(): void
    {
        $license = $this->createLicense(['name' => 'Lisensi Asli']);

        $response = $this->put(route('licenses.update', $license), [
            '_token' => csrf_token(),
            'name' => 'Lisensi Diubah Guest',
            'code' => $license->code,
            'type' => $license->type,
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $license->refresh();
        $this->assertSame('Lisensi Asli', $license->name);
    }

    public function test_guest_tidak_bisa_hapus_lisensi(): void
    {
        $license = $this->createLicense();

        $response = $this->delete(route('licenses.destroy', $license), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $this->assertDatabaseHas('licenses', ['id' => $license->id]);
    }

    public function test_admin_dapat_akses_halaman_daftar_lisensi(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('licenses.index'));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.license.index');
        $response->assertSee('Lisensi', false);
        $response->assertSee('Tambah Lisensi', false);
    }

    public function test_admin_dapat_akses_halaman_tambah_lisensi(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('licenses.create'));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.license.create');
        $response->assertSee('Form Tambah Lisensi', false);
        $response->assertSee('Nama Lisensi', false);
        $response->assertSee('Kode Lisensi', false);
        $response->assertSee('Tipe Lisensi', false);
    }

    public function test_admin_dapat_menyimpan_lisensi_baru(): void
    {
        $this->loginAsAdmin();
        $file = UploadedFile::fake()->image('lisensi-baru.jpg');

        $response = $this->post(route('licenses.store'), [
            '_token' => csrf_token(),
            'name' => 'Lisensi Baru',
            'code' => 'LIC-NEW',
            'type' => 'Sertifikasi',
            'file' => $file,
        ]);

        $response->assertRedirect(route('licenses.index'));
        $response->assertSessionHas('success', 'Lisensi berhasil dibuat');

        $this->assertDatabaseHas('licenses', [
            'name' => 'Lisensi Baru',
            'code' => 'LIC-NEW',
        ]);

        $license = License::where('code', 'LIC-NEW')->first();
        if ($license && $license->file) {
            $this->filesToClean[] = public_path('images/licenses/' . $license->file);
        }
    }

    public function test_store_lisensi_gagal_jika_file_tidak_ada(): void
    {
        $this->loginAsAdmin();
        $response = $this->post(route('licenses.store'), [
            '_token' => csrf_token(),
            'name' => 'Lisensi Tanpa File',
            'code' => 'NO-FILE',
            'type' => 'Tipe',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('licenses', ['code' => 'NO-FILE']);
    }

    public function test_store_lisensi_gagal_jika_nama_kosong(): void
    {
        $this->loginAsAdmin();
        $file = UploadedFile::fake()->image('lisensi.jpg');

        $response = $this->post(route('licenses.store'), [
            '_token' => csrf_token(),
            'name' => '',
            'code' => 'LIC-EMPTY-NAME',
            'type' => 'Tipe',
            'file' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('licenses', ['code' => 'LIC-EMPTY-NAME']);
    }

    public function test_admin_dapat_akses_halaman_edit_lisensi(): void
    {
        $this->loginAsAdmin();
        $license = $this->createLicense(['name' => 'Lisensi Edit']);

        $response = $this->get(route('licenses.edit', $license));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.license.edit');
        $response->assertSee('Lisensi Edit');
    }

    public function test_admin_dapat_update_lisensi(): void
    {
        $this->loginAsAdmin();
        $license = $this->createLicense(['name' => 'Lisensi Awal']);

        $response = $this->put(route('licenses.update', $license), [
            '_token' => csrf_token(),
            'name' => 'Lisensi Diubah',
            'code' => 'LIC-UPDATED',
            'type' => $license->type,
        ]);

        $response->assertRedirect(route('licenses.index'));
        $response->assertSessionHas('success', 'Lisensi berhasil diperbarui');

        $license->refresh();
        $this->assertSame('Lisensi Diubah', $license->name);
        $this->assertSame('LIC-UPDATED', $license->code);
    }

    public function test_update_lisensi_dengan_file_baru_file_lama_terhapus(): void
    {
        $this->loginAsAdmin();
        $oldFileName = 'test-old-license-' . uniqid() . '.jpg';
        $license = $this->createLicense(['name' => 'Lisensi Update File', 'file' => $oldFileName]);

        $oldFilePath = public_path('images/licenses/' . $oldFileName);
        File::ensureDirectoryExists(dirname($oldFilePath));
        File::put($oldFilePath, 'fake old file content');
        $this->assertTrue(File::exists($oldFilePath), 'File lama lisensi harus ada di disk sebelum update');

        $newFile = UploadedFile::fake()->image('lisensi-baru.jpg');
        $response = $this->put(route('licenses.update', $license), [
            '_token' => csrf_token(),
            'name' => 'Lisensi File Diubah',
            'code' => $license->code,
            'type' => $license->type,
            'file' => $newFile,
        ]);

        $response->assertRedirect(route('licenses.index'));
        $response->assertSessionHas('success', 'Lisensi berhasil diperbarui');
        $this->assertFalse(File::exists($oldFilePath), 'File lama lisensi harus terhapus dari disk setelah update dengan file baru');

        $license->refresh();
        if ($license->file) {
            $this->filesToClean[] = public_path('images/licenses/' . $license->file);
        }
    }

    public function test_edit_lisensi_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('licenses.edit', 99999));
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_update_lisensi_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $license = $this->createLicense();

        $response = $this->put(route('licenses.update', 99999), [
            '_token' => csrf_token(),
            'name' => 'Lisensi Baru',
            'code' => $license->code,
            'type' => $license->type,
        ]);

        $this->assertTrue($response->isRedirect());
        $response->assertSessionHas('error');
    }

    public function test_halaman_daftar_lisensi_menampilkan_data(): void
    {
        $this->loginAsAdmin();
        $this->createLicense(['name' => 'Lisensi Satu', 'code' => 'LIC-1']);
        $this->createLicense(['name' => 'Lisensi Dua', 'code' => 'LIC-2']);

        $response = $this->get(route('licenses.index'));
        $response->assertStatus(200);
        $response->assertSee('Lisensi Satu');
        $response->assertSee('Lisensi Dua');
    }

    public function test_admin_dapat_hapus_lisensi(): void
    {
        $this->loginAsAdmin();
        $fileName = 'test-delete-license-' . uniqid() . '.jpg';
        $license = $this->createLicense(['name' => 'Lisensi Untuk Dihapus', 'file' => $fileName]);

        $filePath = public_path('images/licenses/' . $fileName);
        File::ensureDirectoryExists(dirname($filePath));
        File::put($filePath, 'fake file content');
        $this->filesToClean[] = $filePath;
        $this->assertTrue(File::exists($filePath), 'File lisensi harus ada di disk sebelum dihapus');

        $response = $this->delete(route('licenses.destroy', $license), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('licenses.index'));
        $response->assertSessionHas('success', 'Lisensi berhasil dihapus');
        $this->assertDatabaseMissing('licenses', ['id' => $license->id]);
        $this->assertFalse(File::exists($filePath), 'File lisensi harus terhapus dari disk setelah data dihapus');
    }

    public function test_hapus_lisensi_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $response = $this->delete(route('licenses.destroy', 99999), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
