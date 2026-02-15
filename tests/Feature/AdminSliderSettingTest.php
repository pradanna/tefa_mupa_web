<?php

namespace Tests\Feature;

use App\Models\Slider;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AdminSliderSettingTest extends TestCase
{
    use RefreshDatabase;

    /** @var list<string> File path yang dibuat saat testing, akan dihapus di tearDown() */
    protected array $filesToClean = [];

    protected function tearDown(): void
    {
        $baseDir = public_path('images/slider');
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

    protected function createSlider(array $overrides = []): Slider
    {
        $defaults = [
            'title' => 'Slider Test',
            'subtitle' => 'Subtitle test',
            'file' => 'test-image.jpg',
            'path' => asset('images/slider'),
        ];
        return Slider::create(array_merge($defaults, $overrides));
    }

    public function test_guest_tidak_bisa_akses_halaman_daftar_slider(): void
    {
        $response = $this->get(route('sliders.index'));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_akses_halaman_tambah_slider(): void
    {
        $response = $this->get(route('sliders.create'));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_akses_halaman_edit_slider(): void
    {
        $slider = $this->createSlider();
        $response = $this->get(route('sliders.edit', $slider));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_menyimpan_slider(): void
    {
        $file = UploadedFile::fake()->image('slider-guest.jpg');

        $response = $this->post(route('sliders.store'), [
            '_token' => csrf_token(),
            'title' => 'Slider dari Guest',
            'subtitle' => 'Subtitle guest',
            'file' => $file,
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $this->assertDatabaseMissing('sliders', ['title' => 'Slider dari Guest']);
    }

    public function test_guest_tidak_bisa_update_slider(): void
    {
        $slider = $this->createSlider(['title' => 'Slider Asli']);

        $response = $this->put(route('sliders.update', $slider), [
            '_token' => csrf_token(),
            'title' => 'Slider Diubah Guest',
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $slider->refresh();
        $this->assertSame('Slider Asli', $slider->title);
    }

    public function test_guest_tidak_bisa_hapus_slider(): void
    {
        $slider = $this->createSlider();

        $response = $this->delete(route('sliders.destroy', $slider), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $this->assertDatabaseHas('sliders', ['id' => $slider->id]);
    }

    public function test_admin_dapat_akses_halaman_daftar_slider(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('sliders.index'));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.slider.index');
        $response->assertSee('Slider', false);
        $response->assertSee('Tambah Slider', false);
    }

    public function test_admin_dapat_akses_halaman_tambah_slider(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('sliders.create'));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.slider.created');
        $response->assertSee('Form Tambah Slider', false);
        $response->assertSee('Judul', false);
        $response->assertSee('Sub Judul', false);
        $response->assertSee('Gambar', false);
    }

    public function test_admin_dapat_menyimpan_slider_baru(): void
    {
        $this->loginAsAdmin();
        $file = UploadedFile::fake()->image('slider-new.jpg');

        $response = $this->post(route('sliders.store'), [
            '_token' => csrf_token(),
            'title' => 'Slider Baru',
            'subtitle' => 'Subtitle baru',
            'file' => $file,
        ]);

        $response->assertRedirect(route('sliders.index'));
        $response->assertSessionHas('success', 'Slider created successfully');

        $this->assertDatabaseHas('sliders', [
            'title' => 'Slider Baru',
            'subtitle' => 'Subtitle baru',
        ]);

        $slider = Slider::where('title', 'Slider Baru')->first();
        if ($slider && $slider->file) {
            $this->filesToClean[] = public_path('images/slider/' . $slider->file);
        }
    }

    public function test_store_slider_gagal_jika_file_tidak_ada(): void
    {
        $this->loginAsAdmin();
        $response = $this->post(route('sliders.store'), [
            '_token' => csrf_token(),
            'title' => 'Slider Tanpa File',
            'subtitle' => 'Subtitle',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'File is required');
        $this->assertDatabaseMissing('sliders', ['title' => 'Slider Tanpa File']);
    }

    public function test_store_slider_gagal_jika_sudah_ada_3_slider(): void
    {
        $this->loginAsAdmin();
        $this->createSlider(['title' => 'Slider 1']);
        $this->createSlider(['title' => 'Slider 2']);
        $this->createSlider(['title' => 'Slider 3']);

        $sliderDir = public_path('images/slider');
        File::ensureDirectoryExists($sliderDir);
        $filesBefore = File::exists($sliderDir) ? array_filter(glob($sliderDir . '/*'), 'is_file') : [];

        $file = UploadedFile::fake()->image('slider-keempat.jpg');
        $response = $this->post(route('sliders.store'), [
            '_token' => csrf_token(),
            'title' => 'Slider Keempat',
            'subtitle' => 'Subtitle',
            'file' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertStringContainsString('batas maksimum', $response->getSession()->get('error'));
        $this->assertEquals(3, Slider::count());

        $filesAfter = File::exists($sliderDir) ? array_filter(glob($sliderDir . '/*'), 'is_file') : [];
        foreach (array_diff($filesAfter, $filesBefore) as $newPath) {
            $this->filesToClean[] = $newPath;
        }
    }

    public function test_admin_dapat_akses_halaman_edit_slider(): void
    {
        $this->loginAsAdmin();
        $slider = $this->createSlider(['title' => 'Slider Edit']);

        $response = $this->get(route('sliders.edit', $slider));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.slider.edit');
        $response->assertSee('Form Edit Slider', false);
        $response->assertSee('Slider Edit', false);
    }

    public function test_admin_dapat_update_slider(): void
    {
        $this->loginAsAdmin();
        $slider = $this->createSlider(['title' => 'Slider Awal']);

        $response = $this->put(route('sliders.update', $slider), [
            '_token' => csrf_token(),
            'title' => 'Slider Diubah',
        ]);

        $response->assertRedirect(route('sliders.index'));
        $response->assertSessionHas('success', 'Slider updated successfully');

        $slider->refresh();
        $this->assertSame('Slider Diubah', $slider->title);
    }

    public function test_update_slider_dengan_gambar_baru_file_lama_terhapus(): void
    {
        $this->loginAsAdmin();
        $oldFileName = 'test-old-slider-' . uniqid() . '.jpg';
        $slider = $this->createSlider(['title' => 'Slider Update Gambar', 'file' => $oldFileName]);

        $oldFilePath = public_path('images/slider/' . $oldFileName);
        File::ensureDirectoryExists(dirname($oldFilePath));
        File::put($oldFilePath, 'fake old image content');
        $this->assertTrue(File::exists($oldFilePath), 'File gambar lama slider harus ada di disk sebelum update');

        $newFile = UploadedFile::fake()->image('slider-baru.jpg');
        $response = $this->put(route('sliders.update', $slider), [
            '_token' => csrf_token(),
            'title' => 'Slider Gambar Diubah',
            'file' => $newFile,
        ]);

        $response->assertRedirect(route('sliders.index'));
        $response->assertSessionHas('success', 'Slider updated successfully');
        $this->assertFalse(File::exists($oldFilePath), 'File gambar lama slider harus terhapus dari disk setelah update dengan gambar baru');

        $slider->refresh();
        if ($slider->file) {
            $this->filesToClean[] = public_path('images/slider/' . $slider->file);
        }
    }

    public function test_edit_slider_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('sliders.edit', 99999));
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_update_slider_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $response = $this->put(route('sliders.update', 99999), [
            '_token' => csrf_token(),
            'title' => 'Slider Baru',
        ]);
        // Bisa redirect dengan error atau 404 dari model not found
        $this->assertTrue($response->isRedirect() || $response->getStatusCode() === 404);
        if ($response->isRedirect()) {
            $response->assertSessionHas('error');
        }
    }

    public function test_halaman_daftar_slider_menampilkan_data_slider(): void
    {
        $this->loginAsAdmin();
        $this->createSlider(['title' => 'Slider Satu']);
        $this->createSlider(['title' => 'Slider Dua']);

        $response = $this->get(route('sliders.index'));
        $response->assertStatus(200);
        $response->assertSee('Slider Satu');
        $response->assertSee('Slider Dua');
    }

    public function test_admin_dapat_hapus_slider(): void
    {
        $this->loginAsAdmin();
        $fileName = 'test-delete-slider-' . uniqid() . '.jpg';
        $slider = $this->createSlider(['title' => 'Slider Untuk Dihapus', 'file' => $fileName]);

        $filePath = public_path('images/slider/' . $fileName);
        File::ensureDirectoryExists(dirname($filePath));
        File::put($filePath, 'fake image content');
        $this->filesToClean[] = $filePath;
        $this->assertTrue(File::exists($filePath), 'File slider harus ada di disk sebelum dihapus');

        $response = $this->delete(route('sliders.destroy', $slider), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('sliders.index'));
        $response->assertSessionHas('success', 'Slider deleted successfully');
        $this->assertDatabaseMissing('sliders', ['id' => $slider->id]);
        $this->assertFalse(File::exists($filePath), 'File gambar slider harus terhapus dari disk setelah data dihapus');
    }

    public function test_hapus_slider_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $response = $this->delete(route('sliders.destroy', 99999), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
