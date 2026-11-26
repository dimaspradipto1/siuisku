<?php

namespace Tests\Feature;

use App\Models\Eselon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EselonTest extends TestCase
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

    public function test_eselon_index_and_datatable_render(): void
    {
        $this->actingAsAdmin();

        $this->get(route('eselon.index'))
            ->assertOk()
            ->assertSee('Daftar Eselon')
            ->assertSee('LaravelDataTables["eselon-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('eselon.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 0);
    }

    public function test_can_store_update_and_delete_eselon(): void
    {
        $this->actingAsAdmin();

        // Create
        $this->post(route('eselon.store'), [
            'kode' => 'iia',
            'nama' => 'Eselon II.A',
            'aktif' => true,
        ])->assertRedirect(route('eselon.index'));

        $item = Eselon::where('kode', 'IIA')->firstOrFail();
        $this->assertTrue($item->aktif);

        // Update
        $this->put(route('eselon.update', $item), [
            'kode' => 'IIA',
            'nama' => 'Eselon II.A Nonaktif',
            'aktif' => false,
        ])->assertRedirect(route('eselon.index'));

        $this->assertDatabaseHas('eselons', [
            'id' => $item->id,
            'nama' => 'Eselon II.A Nonaktif',
            'aktif' => false,
        ]);

        // Delete
        $this->delete(route('eselon.destroy', $item))
            ->assertRedirect(route('eselon.index'));

        $this->assertDatabaseMissing('eselons', ['id' => $item->id]);
    }
}
