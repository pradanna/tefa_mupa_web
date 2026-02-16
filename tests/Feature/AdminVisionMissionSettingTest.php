<?php

namespace Tests\Feature;

use App\Models\VisionMission;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AdminVisionMissionSettingTest extends TestCase
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

    protected function createVisionMission(array $overrides = []): VisionMission
    {
        $defaults = [
            'type' => 'vision',
            'content' => 'Konten visi test',
        ];
        return VisionMission::create(array_merge($defaults, $overrides));
    }

    public function test_guest_tidak_bisa_akses_halaman_daftar_visi_misi(): void
    {
        $response = $this->get(route('vision-missions.index'));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_akses_halaman_tambah_visi_misi(): void
    {
        $response = $this->get(route('vision-missions.create'));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_akses_halaman_edit_visi_misi(): void
    {
        $vm = $this->createVisionMission();
        $response = $this->get(route('vision-missions.edit', $vm));
        $response->assertRedirect(route('login-backoffice'));
    }

    public function test_guest_tidak_bisa_menyimpan_visi_misi(): void
    {
        $response = $this->post(route('vision-missions.store'), [
            '_token' => csrf_token(),
            'type' => 'vision',
            'content' => 'Konten dari guest',
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $this->assertDatabaseMissing('vision_missions', ['content' => 'Konten dari guest']);
    }

    public function test_guest_tidak_bisa_update_visi_misi(): void
    {
        $vm = $this->createVisionMission(['content' => 'Konten asli']);

        $response = $this->put(route('vision-missions.update', $vm), [
            '_token' => csrf_token(),
            'type' => $vm->type,
            'content' => 'Konten diubah guest',
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $vm->refresh();
        $this->assertSame('Konten asli', $vm->content);
    }

    public function test_guest_tidak_bisa_hapus_visi_misi(): void
    {
        $vm = $this->createVisionMission();

        $response = $this->delete(route('vision-missions.destroy', $vm), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('login-backoffice'));
        $this->assertDatabaseHas('vision_missions', ['id' => $vm->id]);
    }

    public function test_admin_dapat_akses_halaman_daftar_visi_misi(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('vision-missions.index'));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.vision-mission.index');
        $response->assertSee('Visi', false);
        $response->assertSee('Misi', false);
        $response->assertSee('Tambah Visi/Misi', false);
    }

    public function test_admin_dapat_akses_halaman_tambah_visi_misi(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('vision-missions.create'));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.vision-mission.create');
        $response->assertSee('Tambah Visi/Misi', false);
        $response->assertSee('Tipe', false);
        $response->assertSee('Konten', false);
    }

    public function test_admin_dapat_menyimpan_visi_misi_baru(): void
    {
        $this->loginAsAdmin();

        $response = $this->post(route('vision-missions.store'), [
            '_token' => csrf_token(),
            'type' => 'vision',
            'content' => 'Visi perusahaan kami.',
        ]);

        $response->assertRedirect(route('vision-missions.index'));
        $response->assertSessionHas('success', 'Data visi/misi berhasil dibuat');

        $this->assertDatabaseHas('vision_missions', [
            'type' => 'vision',
            'content' => 'Visi perusahaan kami.',
        ]);
    }

    public function test_admin_dapat_menyimpan_visi_misi_tipe_mission(): void
    {
        $this->loginAsAdmin();

        $response = $this->post(route('vision-missions.store'), [
            '_token' => csrf_token(),
            'type' => 'mission',
            'content' => 'Misi perusahaan kami.',
        ]);

        $response->assertRedirect(route('vision-missions.index'));
        $response->assertSessionHas('success', 'Data visi/misi berhasil dibuat');
        $this->assertDatabaseHas('vision_missions', ['type' => 'mission', 'content' => 'Misi perusahaan kami.']);
    }

    public function test_store_visi_misi_gagal_jika_type_kosong(): void
    {
        $this->loginAsAdmin();

        $response = $this->post(route('vision-missions.store'), [
            '_token' => csrf_token(),
            'type' => '',
            'content' => 'Konten',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertDatabaseCount('vision_missions', 0);
    }

    public function test_store_visi_misi_gagal_jika_type_tidak_valid(): void
    {
        $this->loginAsAdmin();

        $response = $this->post(route('vision-missions.store'), [
            '_token' => csrf_token(),
            'type' => 'invalid',
            'content' => 'Konten',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertDatabaseCount('vision_missions', 0);
    }

    public function test_admin_dapat_akses_halaman_edit_visi_misi(): void
    {
        $this->loginAsAdmin();
        $vm = $this->createVisionMission(['type' => 'vision', 'content' => 'Visi untuk diedit']);

        $response = $this->get(route('vision-missions.edit', $vm));
        $response->assertStatus(200);
        $response->assertViewIs('backoffice.pages.vision-mission.edit');
        $response->assertSee('Visi untuk diedit');
    }

    public function test_admin_dapat_update_visi_misi(): void
    {
        $this->loginAsAdmin();
        $vm = $this->createVisionMission(['content' => 'Konten awal']);

        $response = $this->put(route('vision-missions.update', $vm), [
            '_token' => csrf_token(),
            'type' => 'mission',
            'content' => 'Konten diperbarui.',
        ]);

        $response->assertRedirect(route('vision-missions.index'));
        $response->assertSessionHas('success', 'Data visi/misi berhasil diperbarui');

        $vm->refresh();
        $this->assertSame('mission', $vm->type);
        $this->assertSame('Konten diperbarui.', $vm->content);
    }

    public function test_edit_visi_misi_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('vision-missions.edit', 99999));
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_update_visi_misi_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $vm = $this->createVisionMission();

        $response = $this->put(route('vision-missions.update', 99999), [
            '_token' => csrf_token(),
            'type' => 'vision',
            'content' => $vm->content,
        ]);

        $this->assertTrue($response->isRedirect());
        $response->assertSessionHas('error');
        $errorMsg = $response->getSession()->get('error');
        $this->assertContains($errorMsg, [
            'Data visi/misi tidak ditemukan',
            'Terjadi kesalahan saat mengubah visi/misi',
        ]);
    }

    public function test_halaman_daftar_visi_misi_menampilkan_data(): void
    {
        $this->loginAsAdmin();
        $this->createVisionMission(['type' => 'vision', 'content' => 'Visi Satu']);
        $this->createVisionMission(['type' => 'mission', 'content' => 'Misi Satu']);

        $response = $this->get(route('vision-missions.index'));
        $response->assertStatus(200);
        $response->assertSee('Visi Satu');
        $response->assertSee('Misi Satu');
    }

    public function test_admin_dapat_hapus_visi_misi(): void
    {
        $this->loginAsAdmin();
        $vm = $this->createVisionMission(['content' => 'Visi untuk dihapus']);

        $response = $this->delete(route('vision-missions.destroy', $vm), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('vision-missions.index'));
        $response->assertSessionHas('success', 'Data visi/misi berhasil dihapus');
        $this->assertDatabaseMissing('vision_missions', ['id' => $vm->id]);
    }

    public function test_hapus_visi_misi_yang_tidak_ada_redirect_dengan_error(): void
    {
        $this->loginAsAdmin();
        $response = $this->delete(route('vision-missions.destroy', 99999), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $errorMsg = $response->getSession()->get('error');
        $this->assertContains($errorMsg, [
            'Data visi/misi tidak ditemukan',
            'Gagal menghapus data visi/misi',
        ]);
    }
}
