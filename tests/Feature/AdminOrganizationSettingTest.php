<?php

namespace Tests\Feature;

use App\Models\OrganizationStructure;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AdminOrganizationSettingTest extends TestCase
{
    use RefreshDatabase;

    /** @var list<string> File path yang dibuat saat testing, akan dihapus di tearDown() */
    protected array $filesToClean = [];

    protected function tearDown(): void
    {
        $baseDir = public_path('images/organization');
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

    protected function createOrganization(array $overrides = []): OrganizationStructure
    {
        $defaults = [
            'name' => 'Nama Organisasi Test',
            'position' => 'Jabatan Test',
            'path' => asset('images/organization'),
            'image' => 'test-org.jpg',
            'instagram' => null,
            'linkedin' => null,
            'email' => null,
            'order' => 1,
        ];
        return OrganizationStructure::create(array_merge($defaults, $overrides));
    }

    public function test_guest_tidak_bisa_akses_halaman_daftar_organisasi(): void
    {
        $response = $this->get(route('organizations.index'));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_akses_halaman_tambah_organisasi(): void
    {
        $response = $this->get(route('organizations.create'));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_akses_halaman_edit_organisasi(): void
    {
        $org = $this->createOrganization();
        $response = $this->get(route('organizations.edit', $org));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_menyimpan_organisasi(): void
    {
        $file = UploadedFile::fake()->image('org-guest.jpg');

        $response = $this->post(route('organizations.store'), [
            '_token' => csrf_token(),
            'name' => 'Organisasi dari Guest',
            'position' => 'Jabatan',
            'order' => 1,
            'image' => $file,
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $this->assertDatabaseMissing('organization_structures', ['name' => 'Organisasi dari Guest']);
    }

    public function test_guest_tidak_bisa_update_organisasi(): void
    {
        $org = $this->createOrganization(['name' => 'Nama Asli']);

        $response = $this->put(route('organizations.update', $org), [
            '_token' => csrf_token(),
            'name' => 'Nama Diubah Guest',
            'position' => $org->position,
            'order' => $org->order,
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $org->refresh();
        $this->assertSame('Nama Asli', $org->name);
    }

    public function test_guest_tidak_bisa_hapus_organisasi(): void
    {
        $org = $this->createOrganization();

        $response = $this->delete(route('organizations.destroy', $org), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $this->assertDatabaseHas('organization_structures', ['id' => $org->id]);
    }

    public function test_admin_dapat_akses_halaman_daftar_organisasi(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('organizations.index'));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.organization.index');
        $response->assertSee('Organisasi', false);
        $response->assertSee('Tambah Organisasi', false);
    }

    public function test_admin_dapat_akses_halaman_tambah_organisasi(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('organizations.create'));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.organization.create');
        $response->assertSee('Form Tambah Organisasi', false);
        $response->assertSee('Nama', false);
        $response->assertSee('Posisi', false);
        $response->assertSee('Order', false);
    }

    public function test_admin_dapat_menyimpan_organisasi_baru(): void
    {
        $this->loginAsAdmin();
        $file = UploadedFile::fake()->image('org-baru.jpg');

        $response = $this->post(route('organizations.store'), [
            '_token' => csrf_token(),
            'name' => 'Kepala Sekolah',
            'position' => 'Kepala SMK',
            'email' => 'kepala@example.com',
            'instagram' => '@kepalasmk',
            'linkedin' => '',
            'order' => 1,
            'image' => $file,
        ]);

        $response->assertRedirect(route('organizations.index'));
        $response->assertSessionHas('success', 'Organization created successfully');

        $this->assertDatabaseHas('organization_structures', [
            'name' => 'Kepala Sekolah',
            'position' => 'Kepala SMK',
        ]);

        $org = OrganizationStructure::where('name', 'Kepala Sekolah')->first();
        if ($org && $org->image) {
            $this->filesToClean[] = public_path('images/organization/' . $org->image);
        }
    }

    public function test_store_organisasi_gagal_jika_file_tidak_ada(): void
    {
        $this->loginAsAdmin();
        $response = $this->post(route('organizations.store'), [
            '_token' => csrf_token(),
            'name' => 'Organisasi Tanpa Gambar',
            'position' => 'Jabatan',
            'order' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Image is required');
        $this->assertDatabaseMissing('organization_structures', ['name' => 'Organisasi Tanpa Gambar']);
    }

    public function test_store_organisasi_gagal_jika_nama_kosong(): void
    {
        $this->loginAsAdmin();
        $file = UploadedFile::fake()->image('org.jpg');

        $response = $this->post(route('organizations.store'), [
            '_token' => csrf_token(),
            'name' => '',
            'position' => 'Jabatan',
            'order' => 1,
            'image' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertDatabaseCount('organization_structures', 0);
    }

    public function test_store_organisasi_gagal_jika_order_kosong(): void
    {
        $this->loginAsAdmin();
        $file = UploadedFile::fake()->image('org.jpg');

        $response = $this->post(route('organizations.store'), [
            '_token' => csrf_token(),
            'name' => 'Nama Valid',
            'position' => 'Posisi',
            'order' => '',
            'image' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('organization_structures', ['name' => 'Nama Valid']);
    }

    public function test_store_organisasi_gagal_jika_order_kurang_dari_satu(): void
    {
        $this->loginAsAdmin();
        $file = UploadedFile::fake()->image('org.jpg');

        $response = $this->post(route('organizations.store'), [
            '_token' => csrf_token(),
            'name' => 'Nama Valid',
            'position' => 'Posisi',
            'order' => 0,
            'image' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('organization_structures', ['name' => 'Nama Valid']);
    }

    public function test_update_organisasi_gagal_jika_order_kurang_dari_satu(): void
    {
        $this->loginAsAdmin();
        $org = $this->createOrganization(['name' => 'Org Awal']);

        $response = $this->put(route('organizations.update', $org), [
            '_token' => csrf_token(),
            'name' => $org->name,
            'position' => $org->position,
            'email' => $org->email,
            'instagram' => $org->instagram,
            'linkedin' => $org->linkedin,
            'order' => -1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $org->refresh();
        $this->assertSame(1, (int) $org->order);
    }

    public function test_simpan_organisasi_dengan_order_tidak_urut_tampil_berurutan(): void
    {
        $this->loginAsAdmin();
        $this->createOrganization(['name' => 'Order Lima', 'position' => 'Jabatan', 'order' => 5]);
        $this->createOrganization(['name' => 'Order Satu', 'position' => 'Jabatan', 'order' => 1]);
        $this->createOrganization(['name' => 'Order Tiga', 'position' => 'Jabatan', 'order' => 3]);

        $response = $this->get(route('organizations.index'));
        $response->assertStatus(200);
        $organizations = $response->viewData('organizations');
        $this->assertNotNull($organizations);
        $items = $organizations->items();
        $this->assertCount(3, $items, 'Harus ada 3 organisasi');
        $this->assertSame(1, (int) $items[0]->order, 'Item pertama harus order 1');
        $this->assertSame(3, (int) $items[1]->order, 'Item kedua harus order 3');
        $this->assertSame(5, (int) $items[2]->order, 'Item ketiga harus order 5');
    }

    public function test_organisasi_dengan_order_sudah_ada_tidak_bisa_tersimpan(): void
    {
        $this->loginAsAdmin();
        $fileA = UploadedFile::fake()->image('org-a.jpg');
        $this->post(route('organizations.store'), [
            '_token' => csrf_token(),
            'name' => 'Organisasi Order Satu',
            'position' => 'Jabatan',
            'order' => 1,
            'image' => $fileA,
        ]);
        $this->assertDatabaseHas('organization_structures', ['name' => 'Organisasi Order Satu', 'order' => 1]);

        $fileB = UploadedFile::fake()->image('org-b.jpg');
        $response = $this->post(route('organizations.store'), [
            '_token' => csrf_token(),
            'name' => 'Organisasi Order Satu Duplikat',
            'position' => 'Jabatan',
            'order' => 1,
            'image' => $fileB,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('organization_structures', ['name' => 'Organisasi Order Satu Duplikat']);
        $this->assertSame(1, OrganizationStructure::where('order', 1)->count());
    }

    public function test_admin_dapat_akses_halaman_edit_organisasi(): void
    {
        $this->loginAsAdmin();
        $org = $this->createOrganization(['name' => 'Nama Edit', 'position' => 'Wakil Kepala']);

        $response = $this->get(route('organizations.edit', $org));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.organization.edit');
        $response->assertSee('Nama Edit');
        $response->assertSee('Wakil Kepala');
    }

    public function test_admin_dapat_update_organisasi(): void
    {
        $this->loginAsAdmin();
        $org = $this->createOrganization(['name' => 'Nama Awal']);

        $response = $this->put(route('organizations.update', $org), [
            '_token' => csrf_token(),
            'name' => 'Nama Diubah',
            'position' => 'Posisi Diubah',
            'email' => $org->email,
            'instagram' => $org->instagram,
            'linkedin' => $org->linkedin,
            'order' => 2,
        ]);

        $response->assertRedirect(route('organizations.index'));
        $response->assertSessionHas('success', 'Organization updated successfully');

        $org->refresh();
        $this->assertSame('Nama Diubah', $org->name);
        $this->assertSame('Posisi Diubah', $org->position);
        $this->assertSame(2, (int) $org->order);
    }

    public function test_update_organisasi_dengan_gambar_baru_file_lama_terhapus(): void
    {
        $this->loginAsAdmin();
        $oldImageName = 'test-old-org-' . uniqid() . '.jpg';
        $org = $this->createOrganization(['name' => 'Org Update Gambar', 'image' => $oldImageName]);

        $oldFilePath = public_path('images/organization/' . $oldImageName);
        File::ensureDirectoryExists(dirname($oldFilePath));
        File::put($oldFilePath, 'fake old image content');
        $this->assertTrue(File::exists($oldFilePath), 'File gambar lama organisasi harus ada di disk sebelum update');

        $newFile = UploadedFile::fake()->image('org-baru.jpg');
        $response = $this->put(route('organizations.update', $org), [
            '_token' => csrf_token(),
            'name' => $org->name,
            'position' => $org->position,
            'email' => $org->email,
            'instagram' => $org->instagram,
            'linkedin' => $org->linkedin,
            'order' => $org->order,
            'image' => $newFile,
        ]);

        $response->assertRedirect(route('organizations.index'));
        $response->assertSessionHas('success', 'Organization updated successfully');
        $this->assertFalse(File::exists($oldFilePath), 'File gambar lama organisasi harus terhapus dari disk setelah update dengan gambar baru');

        $org->refresh();
        if ($org->image) {
            $this->filesToClean[] = public_path('images/organization/' . $org->image);
        }
    }

    public function test_edit_organisasi_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('organizations.edit', 99999));
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_update_organisasi_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $org = $this->createOrganization();

        $response = $this->put(route('organizations.update', 99999), [
            '_token' => csrf_token(),
            'name' => 'Nama Baru',
            'position' => $org->position,
            'order' => $org->order,
        ]);

        $this->assertTrue($response->isRedirect() || $response->getStatusCode() === 404);
        if ($response->isRedirect()) {
            $response->assertSessionHas('error');
        }
    }

    public function test_halaman_daftar_organisasi_menampilkan_data(): void
    {
        $this->loginAsAdmin();
        $this->createOrganization(['name' => 'Kepala Sekolah', 'position' => 'Kepala']);
        $this->createOrganization(['name' => 'Wakil Kurikulum', 'position' => 'Wakil']);

        $response = $this->get(route('organizations.index'));
        $response->assertStatus(200);
        $response->assertSee('Kepala Sekolah');
        $response->assertSee('Wakil Kurikulum');
    }

    public function test_admin_dapat_hapus_organisasi(): void
    {
        $this->loginAsAdmin();
        $imageName = 'test-delete-org-' . uniqid() . '.jpg';
        $org = $this->createOrganization(['name' => 'Org Untuk Dihapus', 'image' => $imageName]);

        $filePath = public_path('images/organization/' . $imageName);
        File::ensureDirectoryExists(dirname($filePath));
        File::put($filePath, 'fake image content');
        $this->filesToClean[] = $filePath;
        $this->assertTrue(File::exists($filePath), 'File gambar organisasi harus ada di disk sebelum dihapus');

        $response = $this->delete(route('organizations.destroy', $org), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('organizations.index'));
        $response->assertSessionHas('success', 'Organization deleted successfully');
        $this->assertDatabaseMissing('organization_structures', ['id' => $org->id]);
        $this->assertFalse(File::exists($filePath), 'File gambar organisasi harus terhapus dari disk setelah data dihapus');
    }

    public function test_hapus_organisasi_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $response = $this->delete(route('organizations.destroy', 99999), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
