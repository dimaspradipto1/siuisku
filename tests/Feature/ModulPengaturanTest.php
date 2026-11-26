<?php

namespace Tests\Feature;

use App\Models\Modul;
use App\Models\User;
use Database\Seeders\ModulSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModulPengaturanTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): User
    {
        $user = User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@uis.ac.id',
            'roles' => 'admin',
            'password' => 'password123',
        ]);

        $this->actingAs($user);

        return $user;
    }

    public function test_pengaturan_modul_page_renders(): void
    {
        $this->seed(ModulSeeder::class);
        $this->actingAsAdmin();

        $response = $this->get(route('pengaturan.modul.index'));

        $response->assertOk();
        $response->assertSee('Pengaturan Modul');
        $response->assertSee('SIM Kepegawaian');
    }

    public function test_can_create_update_toggle_and_delete_modul(): void
    {
        $this->actingAsAdmin();

        $storeResponse = $this->post(route('pengaturan.modul.store'), [
            'kode' => 'keuangan',
            'nama' => 'SIM Keuangan',
            'singkatan' => 'SIMKEU',
            'icon' => 'bi-cash-coin',
            'warna' => '#15803d',
            'warna_bg' => '#f0fdf4',
            'url' => '/dashboard',
            'urutan' => 7,
            'is_active' => true,
        ]);

        $storeResponse->assertRedirect(route('pengaturan.modul.index'));
        $modul = Modul::where('kode', 'keuangan')->firstOrFail();
        $this->assertTrue($modul->is_active);

        $updateResponse = $this->put(route('pengaturan.modul.update', $modul), [
            'kode' => 'keuangan',
            'nama' => 'SIM Keuangan Kampus',
            'singkatan' => 'SIMKEU',
            'icon' => 'bi-cash-coin',
            'warna' => '#15803d',
            'warna_bg' => '#f0fdf4',
            'url' => '/dashboard',
            'urutan' => 2,
            'is_active' => true,
        ]);

        $updateResponse->assertRedirect(route('pengaturan.modul.index'));
        $this->assertDatabaseHas('moduls', [
            'id' => $modul->id,
            'nama' => 'SIM Keuangan Kampus',
            'urutan' => 2,
        ]);

        $toggleResponse = $this->patch(route('pengaturan.modul.toggle', $modul));
        $toggleResponse->assertRedirect(route('pengaturan.modul.index'));
        $this->assertFalse($modul->fresh()->is_active);

        $deleteResponse = $this->delete(route('pengaturan.modul.destroy', $modul));
        $deleteResponse->assertRedirect(route('pengaturan.modul.index'));
        $this->assertDatabaseMissing('moduls', ['id' => $modul->id]);
    }

    public function test_per_user_module_access_hides_unauthorized_modules(): void
    {
        $this->seed(ModulSeeder::class);
        $admin = $this->actingAsAdmin();

        $simpeg = Modul::where('kode', 'simpeg')->firstOrFail();
        $siakad = Modul::where('kode', 'siakad')->firstOrFail();
        $siakad->update(['is_active' => true, 'url' => 'https://siakadku.uis.ac.id/gate/login']);

        $staff = User::create([
            'name' => 'Staff Humas',
            'username' => 'staffhumas',
            'email' => 'humas@uis.ac.id',
            'roles' => 'staff',
            'password' => 'password123',
        ]);

        // Only grant SIMPEG to staff via Pengaturan Modul -> syncUsers
        $this->put(route('pengaturan.modul.users.sync', $simpeg), [
            'user_ids' => [$staff->id],
        ])->assertRedirect(route('pengaturan.modul.index'));

        $this->actingAs($staff);
        $this->get(route('modul'))
            ->assertOk()
            ->assertSee('SIM Kepegawaian')
            ->assertDontSee('SIM Akademik');

        // Grant SIAKAD as well
        $this->actingAs($admin);
        $this->put(route('pengaturan.modul.users.sync', $siakad), [
            'user_ids' => [$staff->id],
        ])->assertRedirect(route('pengaturan.modul.index'));

        $this->actingAs($staff);
        $this->get(route('modul'))
            ->assertOk()
            ->assertSee('SIM Kepegawaian')
            ->assertSee('SIM Akademik');
    }
}
