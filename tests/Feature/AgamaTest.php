<?php

namespace Tests\Feature;

use App\Models\Agama;
use App\Models\User;
use Database\Seeders\AgamaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgamaTest extends TestCase
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

    public function test_agama_index_and_datatable_render(): void
    {
        $this->seed(AgamaSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('agama.index'))
            ->assertOk()
            ->assertSee('Daftar Agama')
            ->assertSee('LaravelDataTables["agama-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('agama.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 8);
    }

    public function test_can_store_update_and_delete_agama(): void
    {
        $this->seed(AgamaSeeder::class);
        $this->actingAsAdmin();

        // Create
        $this->post(route('agama.store'), [
            'kode' => '9',
            'nama' => 'Kepercayaan Lain',
        ])->assertRedirect(route('agama.index'));

        $item = Agama::where('kode', '9')->firstOrFail();
        $this->assertSame('Kepercayaan Lain', $item->nama);

        // Update
        $this->put(route('agama.update', $item), [
            'kode' => '9',
            'nama' => 'Kepercayaan Lainnya',
        ])->assertRedirect(route('agama.index'));

        $this->assertDatabaseHas('agamas', [
            'id' => $item->id,
            'nama' => 'Kepercayaan Lainnya',
        ]);

        // Delete
        $this->delete(route('agama.destroy', $item))
            ->assertRedirect(route('agama.index'));

        $this->assertDatabaseMissing('agamas', ['id' => $item->id]);
    }
}
