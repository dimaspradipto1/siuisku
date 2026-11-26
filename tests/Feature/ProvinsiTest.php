<?php

namespace Tests\Feature;

use App\Models\Provinsi;
use App\Models\User;
use Database\Seeders\ProvinsiSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProvinsiTest extends TestCase
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

    public function test_provinsi_index_and_datatable_render(): void
    {
        $this->seed(ProvinsiSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('provinsi.index'))
            ->assertOk()
            ->assertSee('Daftar Provinsi')
            ->assertSee('LaravelDataTables["provinsi-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('provinsi.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 38);
    }

    public function test_can_store_update_and_delete_provinsi(): void
    {
        $this->seed(ProvinsiSeeder::class);
        $this->actingAsAdmin();

        // Create
        $this->post(route('provinsi.store'), [
            'kode' => '99',
            'nama' => 'Luar Negeri',
        ])->assertRedirect(route('provinsi.index'));

        $item = Provinsi::where('kode', '99')->firstOrFail();
        $this->assertSame('LUAR NEGERI', $item->nama);

        // Update
        $this->put(route('provinsi.update', $item), [
            'kode' => '99',
            'nama' => 'Lain-Lain',
        ])->assertRedirect(route('provinsi.index'));

        $this->assertDatabaseHas('provinsis', [
            'id' => $item->id,
            'nama' => 'LAIN-LAIN',
        ]);

        // Delete
        $this->delete(route('provinsi.destroy', $item))
            ->assertRedirect(route('provinsi.index'));

        $this->assertDatabaseMissing('provinsis', ['id' => $item->id]);
    }
}
