<?php

namespace Tests\Feature;

use App\Models\JenisHari;
use App\Models\User;
use Database\Seeders\JenisHariSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JenisHariTest extends TestCase
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

    public function test_jenis_hari_index_and_datatable_render(): void
    {
        $this->seed(JenisHariSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('jenis-hari.index'))
            ->assertOk()
            ->assertSee('Daftar Setting Hari')
            ->assertSee('LaravelDataTables["jenis-hari-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('jenis-hari.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 7);
    }

    public function test_can_update_jenis_hari(): void
    {
        $this->seed(JenisHariSeeder::class);
        $this->actingAsAdmin();

        $sabtu = JenisHari::where('kode', 6)->firstOrFail();
        $this->assertSame('Non Efektif', $sabtu->jenis_hari);

        $this->put(route('jenis-hari.update', $sabtu), [
            'jenis_hari' => 'Efektif',
        ])->assertRedirect(route('jenis-hari.index'));

        $this->assertDatabaseHas('jenis_haris', [
            'id' => $sabtu->id,
            'jenis_hari' => 'Efektif',
        ]);
    }
}
