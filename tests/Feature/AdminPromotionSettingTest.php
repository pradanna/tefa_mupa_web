<?php

namespace Tests\Feature;

use App\Models\Promotion;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AdminPromotionSettingTest extends TestCase
{
    use RefreshDatabase;

    /** @var list<string> File path yang dibuat saat testing, akan dihapus di tearDown() */
    protected array $filesToClean = [];

    protected function tearDown(): void
    {
        $baseDir = public_path('images/promotions');
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

    protected function createPromotion(array $overrides = []): Promotion
    {
        $defaults = [
            'name' => 'Promosi Test',
            'desc' => 'Deskripsi promosi test',
            'image' => 'test-promotion.jpg',
            'code' => 'KODE123',
            'expired' => now()->addDays(7)->format('Y-m-d'),
        ];
        return Promotion::create(array_merge($defaults, $overrides));
    }

    public function test_guest_tidak_bisa_akses_halaman_daftar_promosi(): void
    {
        $response = $this->get(route('promotions.index'));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_akses_halaman_tambah_promosi(): void
    {
        $response = $this->get(route('promotions.create'));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_akses_halaman_edit_promosi(): void
    {
        $promotion = $this->createPromotion();
        $response = $this->get(route('promotions.edit', $promotion));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_menyimpan_promosi(): void
    {
        $file = UploadedFile::fake()->image('promosi-guest.jpg');

        $response = $this->post(route('promotions.store'), [
            '_token' => csrf_token(),
            'name' => 'Promosi dari Guest',
            'desc' => 'Deskripsi',
            'code' => 'GUEST01',
            'expired' => now()->addDays(5)->format('Y-m-d'),
            'image' => $file,
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $this->assertDatabaseMissing('promotions', ['code' => 'GUEST01']);
    }

    public function test_guest_tidak_bisa_update_promosi(): void
    {
        $promotion = $this->createPromotion(['name' => 'Promosi Asli']);

        $response = $this->put(route('promotions.update', $promotion), [
            '_token' => csrf_token(),
            'name' => 'Promosi Diubah Guest',
            'desc' => $promotion->desc,
            'code' => $promotion->code,
            'expired' => $promotion->expired,
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $promotion->refresh();
        $this->assertSame('Promosi Asli', $promotion->name);
    }

    public function test_guest_tidak_bisa_hapus_promosi(): void
    {
        $promotion = $this->createPromotion();

        $response = $this->delete(route('promotions.destroy', $promotion), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $this->assertDatabaseHas('promotions', ['id' => $promotion->id]);
    }

    public function test_admin_dapat_akses_halaman_daftar_promosi(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('promotions.index'));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.promotions.index');
        $response->assertSee('Promosi', false);
        $response->assertSee('Tambah Promosi', false);
    }

    public function test_admin_dapat_akses_halaman_tambah_promosi(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('promotions.create'));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.promotions.create');
        $response->assertSee('Form Tambah Promosi', false);
        $response->assertSee('Nama Promosi', false);
        $response->assertSee('Kode Promosi', false);
    }

    public function test_admin_dapat_menyimpan_promosi_baru(): void
    {
        $this->loginAsAdmin();
        $file = UploadedFile::fake()->image('promosi-baru.jpg');
        $expired = now()->addDays(10)->format('Y-m-d');

        $response = $this->post(route('promotions.store'), [
            '_token' => csrf_token(),
            'name' => 'Promosi Baru',
            'desc' => 'Deskripsi promosi baru',
            'code' => 'PROMO-NEW',
            'expired' => $expired,
            'image' => $file,
        ]);

        $response->assertRedirect(route('promotions.index'));
        $response->assertSessionHas('success', 'Promotion created successfully');

        $this->assertDatabaseHas('promotions', [
            'name' => 'Promosi Baru',
            'code' => 'PROMO-NEW',
        ]);

        $promotion = Promotion::where('code', 'PROMO-NEW')->first();
        if ($promotion && $promotion->image) {
            $this->filesToClean[] = public_path('images/promotions/' . $promotion->image);
        }
    }

    public function test_store_promosi_gagal_jika_file_tidak_ada(): void
    {
        $this->loginAsAdmin();
        $response = $this->post(route('promotions.store'), [
            '_token' => csrf_token(),
            'name' => 'Promosi Tanpa Gambar',
            'desc' => 'Deskripsi',
            'code' => 'NO-IMAGE',
            'expired' => now()->addDays(5)->format('Y-m-d'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Image is required');
        $this->assertDatabaseMissing('promotions', ['code' => 'NO-IMAGE']);
    }

    public function test_store_promosi_gagal_jika_nama_kosong(): void
    {
        $this->loginAsAdmin();
        $file = UploadedFile::fake()->image('promosi.jpg');

        $response = $this->post(route('promotions.store'), [
            '_token' => csrf_token(),
            'name' => '',
            'desc' => 'Deskripsi',
            'code' => 'PROMO-EMPTY-NAME',
            'expired' => now()->addDays(5)->format('Y-m-d'),
            'image' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('promotions', ['code' => 'PROMO-EMPTY-NAME']);
    }

    public function test_admin_dapat_akses_halaman_edit_promosi(): void
    {
        $this->loginAsAdmin();
        $promotion = $this->createPromotion(['name' => 'Promosi Edit']);

        $response = $this->get(route('promotions.edit', $promotion));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.promotions.edit');
        $response->assertSee('Form Edit Promosi', false);
        $response->assertSee('Promosi Edit', false);
    }

    public function test_admin_dapat_update_promosi(): void
    {
        $this->loginAsAdmin();
        $promotion = $this->createPromotion(['name' => 'Promosi Awal']);

        $response = $this->put(route('promotions.update', $promotion), [
            '_token' => csrf_token(),
            'name' => 'Promosi Diubah',
            'desc' => $promotion->desc,
            'code' => 'KODE-UPDATED',
            'expired' => $promotion->expired,
        ]);

        $response->assertRedirect(route('promotions.index'));
        $response->assertSessionHas('success', 'Promotion updated successfully');

        $promotion->refresh();
        $this->assertSame('Promosi Diubah', $promotion->name);
        $this->assertSame('KODE-UPDATED', $promotion->code);
    }

    public function test_update_promosi_dengan_gambar_baru_file_lama_terhapus(): void
    {
        $this->loginAsAdmin();
        $oldImageName = 'test-old-promo-' . uniqid() . '.jpg';
        $promotion = $this->createPromotion(['name' => 'Promosi Update Gambar', 'image' => $oldImageName]);

        $oldFilePath = public_path('images/promotions/' . $oldImageName);
        File::ensureDirectoryExists(dirname($oldFilePath));
        File::put($oldFilePath, 'fake old image content');
        $this->assertTrue(File::exists($oldFilePath), 'File gambar lama promosi harus ada di disk sebelum update');

        $newFile = UploadedFile::fake()->image('promosi-baru.jpg');
        $response = $this->put(route('promotions.update', $promotion), [
            '_token' => csrf_token(),
            'name' => 'Promosi Gambar Diubah',
            'desc' => $promotion->desc,
            'code' => $promotion->code,
            'expired' => $promotion->expired,
            'image' => $newFile,
        ]);

        $response->assertRedirect(route('promotions.index'));
        $response->assertSessionHas('success', 'Promotion updated successfully');
        $this->assertFalse(File::exists($oldFilePath), 'File gambar lama promosi harus terhapus dari disk setelah update dengan gambar baru');

        $promotion->refresh();
        if ($promotion->image) {
            $this->filesToClean[] = public_path('images/promotions/' . $promotion->image);
        }
    }

    public function test_edit_promosi_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('promotions.edit', 99999));
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_update_promosi_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $promotion = $this->createPromotion();

        $response = $this->put(route('promotions.update', 99999), [
            '_token' => csrf_token(),
            'name' => 'Promosi Baru',
            'desc' => $promotion->desc,
            'code' => $promotion->code,
            'expired' => $promotion->expired,
        ]);

        $this->assertTrue($response->isRedirect());
        $response->assertSessionHas('error');
    }

    public function test_halaman_daftar_promosi_menampilkan_data_promosi(): void
    {
        $this->loginAsAdmin();
        $this->createPromotion(['name' => 'Promosi Satu', 'code' => 'PROMO-1']);
        $this->createPromotion(['name' => 'Promosi Dua', 'code' => 'PROMO-2']);

        $response = $this->get(route('promotions.index'));
        $response->assertStatus(200);
        $response->assertSee('Promosi Satu');
        $response->assertSee('Promosi Dua');
    }

    public function test_admin_dapat_hapus_promosi(): void
    {
        $this->loginAsAdmin();
        $imageName = 'test-delete-promo-' . uniqid() . '.jpg';
        $promotion = $this->createPromotion(['name' => 'Promosi Untuk Dihapus', 'image' => $imageName]);

        $filePath = public_path('images/promotions/' . $imageName);
        File::ensureDirectoryExists(dirname($filePath));
        File::put($filePath, 'fake image content');
        $this->filesToClean[] = $filePath;
        $this->assertTrue(File::exists($filePath), 'File gambar promosi harus ada di disk sebelum dihapus');

        $response = $this->delete(route('promotions.destroy', $promotion), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('promotions.index'));
        $response->assertSessionHas('success', 'Promotion deleted successfully');
        $this->assertDatabaseMissing('promotions', ['id' => $promotion->id]);
        $this->assertFalse(File::exists($filePath), 'File gambar promosi harus terhapus dari disk setelah data dihapus');
    }

    public function test_hapus_promosi_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $response = $this->delete(route('promotions.destroy', 99999), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
