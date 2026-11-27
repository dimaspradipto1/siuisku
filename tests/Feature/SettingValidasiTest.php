<?php

namespace Tests\Feature;

use App\Models\JenisValidasi;
use App\Models\Role;
use App\Models\SettingValidasiStep;
use App\Models\User;
use Database\Seeders\HakAksesSeeder;
use Database\Seeders\JenisValidasiSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingValidasiTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): User
    {
        $user = User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@uis.ac.id',
            'roles' => 'admin',
            'password' => 'password123',
        ]);

        $this->actingAs($user);

        return $user;
    }

    public function test_setting_validasi_index_and_datatable_render(): void
    {
        $this->seed(HakAksesSeeder::class);
        $this->seed(JenisValidasiSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('setting-validasi.index'))
            ->assertOk()
            ->assertSee('Daftar Jenis Validasi')
            ->assertSee('LaravelDataTables["setting-validasi-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('setting-validasi.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 3);
    }

    public function test_can_manage_setting_validasi_steps(): void
    {
        $this->seed(HakAksesSeeder::class);
        $this->seed(JenisValidasiSeeder::class);
        $this->actingAsAdmin();

        $jenis = JenisValidasi::where('kode', 'C')->firstOrFail();
        $rolePimpinan = Role::where('kode', 'pimpinan_universitas')->firstOrFail();

        // Show detail
        $this->get(route('setting-validasi.show', $jenis))
            ->assertOk()
            ->assertSee('Pengajuan Cuti')
            ->assertSee('Validasi Atasan Langsung / Kepala Unit');

        // Store step
        $this->post(route('setting-validasi.steps.store', $jenis), [
            'urutan' => 3,
            'nama' => 'Validasi Wakil Rektor II',
            'role_id' => $rolePimpinan->id,
        ])->assertRedirect(route('setting-validasi.show', $jenis));

        $step = SettingValidasiStep::where('jenis_validasi_id', $jenis->id)
            ->where('urutan', 3)
            ->firstOrFail();
        $this->assertSame('Validasi Wakil Rektor II', $step->nama);

        // Update step
        $this->put(route('setting-validasi.steps.update', [$jenis, $step]), [
            'urutan' => 3,
            'nama' => 'Validasi Rektor / Wakil Rektor',
            'role_id' => $rolePimpinan->id,
        ])->assertRedirect(route('setting-validasi.show', $jenis));

        $this->assertDatabaseHas('setting_validasi_steps', [
            'id' => $step->id,
            'nama' => 'Validasi Rektor / Wakil Rektor',
        ]);

        // Delete step
        $this->delete(route('setting-validasi.steps.destroy', [$jenis, $step]))
            ->assertRedirect(route('setting-validasi.show', $jenis));

        $this->assertDatabaseMissing('setting_validasi_steps', ['id' => $step->id]);
    }
}
