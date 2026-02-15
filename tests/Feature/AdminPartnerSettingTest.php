<?php

namespace Tests\Feature;

use App\Models\Partners;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AdminPartnerSettingTest extends TestCase
{
    use RefreshDatabase;

    /** @var list<string> File path yang dibuat saat testing, akan dihapus di tearDown() */
    protected array $filesToClean = [];

    protected function tearDown(): void
    {
        $baseDir = public_path('images/partners');
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

    protected function createPartner(array $overrides = []): Partners
    {
        $defaults = [
            'name' => 'Partner Test',
            'image' => 'test-partner-' . uniqid() . '.jpg',
        ];
        return Partners::create(array_merge($defaults, $overrides));
    }

    public function test_guest_tidak_bisa_akses_halaman_daftar_partner(): void
    {
        $response = $this->get(route('partners.index'));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_akses_halaman_tambah_partner(): void
    {
        $response = $this->get(route('partners.create'));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_akses_halaman_edit_partner(): void
    {
        $partner = $this->createPartner();
        $response = $this->get(route('partners.edit', $partner));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_menyimpan_partner(): void
    {
        $file = UploadedFile::fake()->image('partner-guest.jpg');

        $response = $this->post(route('partners.store'), [
            '_token' => csrf_token(),
            'name' => 'Partner dari Guest',
            'file' => $file,
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $this->assertDatabaseMissing('partners', ['name' => 'Partner dari Guest']);
    }

    public function test_guest_tidak_bisa_update_partner(): void
    {
        $partner = $this->createPartner(['name' => 'Partner Asli']);

        $response = $this->put(route('partners.update', $partner), [
            '_token' => csrf_token(),
            'name' => 'Partner Diubah Guest',
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $partner->refresh();
        $this->assertSame('Partner Asli', $partner->name);
    }

    public function test_guest_tidak_bisa_hapus_partner(): void
    {
        $partner = $this->createPartner();

        $response = $this->delete(route('partners.destroy', $partner), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $this->assertDatabaseHas('partners', ['id' => $partner->id]);
    }

    public function test_admin_dapat_akses_halaman_daftar_partner(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('partners.index'));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.partner.index');
        $response->assertSee('Partner', false);
        $response->assertSee('Tambah Partner', false);
    }

    public function test_admin_dapat_akses_halaman_tambah_partner(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('partners.create'));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.partner.create');
        $response->assertSee('Form Tambah Partner', false);
        $response->assertSee('Nama Partner', false);
        $response->assertSee('Gambar Partner', false);
    }

    public function test_admin_dapat_menyimpan_partner_baru(): void
    {
        $this->loginAsAdmin();
        $file = UploadedFile::fake()->image('partner-baru.jpg');

        $response = $this->post(route('partners.store'), [
            '_token' => csrf_token(),
            'name' => 'Partner Baru',
            'file' => $file,
        ]);

        $partner = Partners::where('name', 'Partner Baru')->first();
        if ($partner && $partner->image) {
            $this->filesToClean[] = public_path('images/partners/' . $partner->image);
        }

        $response->assertRedirect(route('partners.index'));
        $response->assertSessionHas('success', 'Partner berhasil dibuat');
        $this->assertDatabaseHas('partners', ['name' => 'Partner Baru']);
    }

    public function test_store_partner_gagal_jika_file_tidak_ada(): void
    {
        $this->loginAsAdmin();
        $response = $this->post(route('partners.store'), [
            '_token' => csrf_token(),
            'name' => 'Partner Tanpa Gambar',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Gambar wajib diisi');
        $this->assertDatabaseMissing('partners', ['name' => 'Partner Tanpa Gambar']);
    }

    public function test_store_partner_gagal_jika_nama_kosong(): void
    {
        $this->loginAsAdmin();
        $file = UploadedFile::fake()->image('partner.jpg');

        $response = $this->post(route('partners.store'), [
            '_token' => csrf_token(),
            'name' => '',
            'file' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertDatabaseCount('partners', 0);
    }

    public function test_admin_dapat_akses_halaman_edit_partner(): void
    {
        $this->loginAsAdmin();
        $partner = $this->createPartner(['name' => 'Partner Edit']);

        $response = $this->get(route('partners.edit', $partner));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.partner.edit');
        $response->assertSee('Form Edit Partner', false);
        $response->assertSee('Partner Edit', false);
    }

    public function test_admin_dapat_update_partner(): void
    {
        $this->loginAsAdmin();
        $partner = $this->createPartner(['name' => 'Partner Awal']);

        $response = $this->put(route('partners.update', $partner), [
            '_token' => csrf_token(),
            'name' => 'Partner Diubah',
        ]);

        $response->assertRedirect(route('partners.index'));
        $response->assertSessionHas('success', 'Partner berhasil diperbarui');

        $partner->refresh();
        $this->assertSame('Partner Diubah', $partner->name);
    }

    public function test_update_partner_dengan_gambar_baru_file_lama_terhapus(): void
    {
        $this->loginAsAdmin();
        $oldImageName = 'test-old-partner-' . uniqid() . '.jpg';
        $partner = $this->createPartner(['name' => 'Partner Update Gambar', 'image' => $oldImageName]);

        $oldFilePath = public_path('images/partners/' . $oldImageName);
        File::ensureDirectoryExists(dirname($oldFilePath));
        File::put($oldFilePath, 'fake old image content');
        $this->assertTrue(File::exists($oldFilePath), 'File gambar lama partner harus ada di disk sebelum update');

        $newFile = UploadedFile::fake()->image('partner-baru.jpg');
        $response = $this->put(route('partners.update', $partner), [
            '_token' => csrf_token(),
            'name' => 'Partner Gambar Diubah',
            'file' => $newFile,
        ]);

        $response->assertRedirect(route('partners.index'));
        $response->assertSessionHas('success', 'Partner berhasil diperbarui');
        $this->assertFalse(File::exists($oldFilePath), 'File gambar lama partner harus terhapus dari disk setelah update dengan gambar baru');

        $partner->refresh();
        if ($partner->image) {
            $this->filesToClean[] = public_path('images/partners/' . $partner->image);
        }
    }

    public function test_edit_partner_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('partners.edit', 99999));
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_update_partner_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $response = $this->put(route('partners.update', 99999), [
            '_token' => csrf_token(),
            'name' => 'Partner Baru',
        ]);
        $this->assertTrue($response->isRedirect() || $response->getStatusCode() === 404);
        if ($response->isRedirect()) {
            $response->assertSessionHas('error');
        }
    }

    public function test_halaman_daftar_partner_menampilkan_data_partner(): void
    {
        $this->loginAsAdmin();
        $this->createPartner(['name' => 'Mitra Satu']);
        $this->createPartner(['name' => 'Mitra Dua']);

        $response = $this->get(route('partners.index'));
        $response->assertStatus(200);
        $response->assertSee('Mitra Satu');
        $response->assertSee('Mitra Dua');
    }

    public function test_admin_dapat_hapus_partner(): void
    {
        $this->loginAsAdmin();
        $imageName = 'test-delete-partner-' . uniqid() . '.jpg';
        $partner = $this->createPartner(['name' => 'Partner Untuk Dihapus', 'image' => $imageName]);

        $filePath = public_path('images/partners/' . $imageName);
        File::ensureDirectoryExists(dirname($filePath));
        File::put($filePath, 'fake image content');
        $this->filesToClean[] = $filePath;
        $this->assertTrue(File::exists($filePath), 'File gambar partner harus ada di disk sebelum dihapus');

        $response = $this->delete(route('partners.destroy', $partner), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('partners.index'));
        $response->assertSessionHas('success', 'Partner berhasil dihapus');
        $this->assertDatabaseMissing('partners', ['id' => $partner->id]);
        $this->assertFalse(File::exists($filePath), 'File gambar partner harus terhapus dari disk setelah data dihapus');
    }

    public function test_hapus_partner_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $response = $this->delete(route('partners.destroy', 99999), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
