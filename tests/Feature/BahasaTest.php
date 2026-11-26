<?php

namespace Tests\Feature;

use App\Models\Bahasa;
use App\Models\User;
use Database\Seeders\BahasaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BahasaTest extends TestCase
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

    public function test_bahasa_index_and_datatable_render(): void
    {
        $this->seed(BahasaSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('bahasa.index'))
            ->assertOk()
            ->assertSee('Daftar Bahasa')
            ->assertSee('LaravelDataTables["bahasa-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('bahasa.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 19);
    }

    public function test_can_store_update_and_delete_bahasa(): void
    {
        $this->seed(BahasaSeeder::class);
        $this->actingAsAdmin();

        // Create
        $this->post(route('bahasa.store'), [
            'kode' => 'tr',
            'nama' => 'Bahasa Turki',
        ])->assertRedirect(route('bahasa.index'));

        $item = Bahasa::where('kode', 'TR')->firstOrFail();
        $this->assertSame('Bahasa Turki', $item->nama);

        // Update
        $this->put(route('bahasa.update', $item), [
            'kode' => 'TR',
            'nama' => 'Bahasa Turki Modern',
        ])->assertRedirect(route('bahasa.index'));

        $this->assertDatabaseHas('bahasas', [
            'id' => $item->id,
            'nama' => 'Bahasa Turki Modern',
        ]);

        // Delete
        $this->delete(route('bahasa.destroy', $item))
            ->assertRedirect(route('bahasa.index'));

        $this->assertDatabaseMissing('bahasas', ['id' => $item->id]);
    }
}
