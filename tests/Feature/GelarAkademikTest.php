<?php

namespace Tests\Feature;

use App\Models\GelarAkademik;
use App\Models\User;
use Database\Seeders\GelarAkademikSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GelarAkademikTest extends TestCase
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

    public function test_gelar_akademik_index_and_datatable_render(): void
    {
        $this->seed(GelarAkademikSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('gelar-akademik.index'))
            ->assertOk()
            ->assertSee('Daftar Gelar Akademik')
            ->assertSee('LaravelDataTables["gelar-akademik-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('gelar-akademik.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 45);
    }

    public function test_can_store_update_and_delete_gelar_akademik(): void
    {
        $this->seed(GelarAkademikSeeder::class);
        $this->actingAsAdmin();

        // Create
        $this->post(route('gelar-akademik.store'), [
            'gelar' => 'M.Stat.',
            'nama' => 'Magister Statistika',
        ])->assertRedirect(route('gelar-akademik.index'));

        $item = GelarAkademik::where('gelar', 'M.Stat.')->firstOrFail();
        $this->assertSame('Magister Statistika', $item->nama);

        // Update
        $this->put(route('gelar-akademik.update', $item), [
            'gelar' => 'M.Stat.',
            'nama' => 'Magister Statistika Terapan',
        ])->assertRedirect(route('gelar-akademik.index'));

        $this->assertDatabaseHas('gelar_akademiks', [
            'id' => $item->id,
            'nama' => 'Magister Statistika Terapan',
        ]);

        // Delete
        $this->delete(route('gelar-akademik.destroy', $item))
            ->assertRedirect(route('gelar-akademik.index'));

        $this->assertDatabaseMissing('gelar_akademiks', ['id' => $item->id]);
    }
}
