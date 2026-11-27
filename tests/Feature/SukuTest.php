<?php

namespace Tests\Feature;

use App\Models\Suku;
use App\Models\User;
use Database\Seeders\SukuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SukuTest extends TestCase
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

    public function test_suku_index_and_datatable_render(): void
    {
        $this->seed(SukuSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('suku.index'))
            ->assertOk()
            ->assertSee('Daftar Suku')
            ->assertSee('LaravelDataTables["suku-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('suku.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 83);
    }

    public function test_can_store_update_and_delete_suku(): void
    {
        $this->seed(SukuSeeder::class);
        $this->actingAsAdmin();

        // Create
        $this->post(route('suku.store'), [
            'nama' => 'Melayu Kepulauan Riau',
        ])->assertRedirect(route('suku.index'));

        $item = Suku::where('nama', 'Melayu Kepulauan Riau')->firstOrFail();

        // Update
        $this->put(route('suku.update', $item), [
            'nama' => 'Melayu Batam',
        ])->assertRedirect(route('suku.index'));

        $this->assertDatabaseHas('sukus', [
            'id' => $item->id,
            'nama' => 'Melayu Batam',
        ]);

        // Delete
        $this->delete(route('suku.destroy', $item))
            ->assertRedirect(route('suku.index'));

        $this->assertDatabaseMissing('sukus', ['id' => $item->id]);
    }
}
