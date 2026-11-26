<?php

namespace Tests\Feature;

use App\Models\Negara;
use App\Models\User;
use Database\Seeders\NegaraSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NegaraTest extends TestCase
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

    public function test_negara_index_and_datatable_render(): void
    {
        $this->seed(NegaraSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('negara.index'))
            ->assertOk()
            ->assertSee('Daftar Negara')
            ->assertSee('LaravelDataTables["negara-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('negara.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 25);
    }

    public function test_can_store_update_and_delete_negara(): void
    {
        $this->seed(NegaraSeeder::class);
        $this->actingAsAdmin();

        // Create
        $this->post(route('negara.store'), [
            'kode' => 'can',
            'nama' => 'Kanada',
            'kode_emis' => 'ca',
        ])->assertRedirect(route('negara.index'));

        $item = Negara::where('kode', 'CAN')->firstOrFail();
        $this->assertSame('Kanada', $item->nama);
        $this->assertSame('CA', $item->kode_emis);

        // Update
        $this->put(route('negara.update', $item), [
            'kode' => 'CAN',
            'nama' => 'Canada',
            'kode_emis' => 'CAN',
        ])->assertRedirect(route('negara.index'));

        $this->assertDatabaseHas('negaras', [
            'id' => $item->id,
            'nama' => 'Canada',
            'kode_emis' => 'CAN',
        ]);

        // Delete
        $this->delete(route('negara.destroy', $item))
            ->assertRedirect(route('negara.index'));

        $this->assertDatabaseMissing('negaras', ['id' => $item->id]);
    }
}
