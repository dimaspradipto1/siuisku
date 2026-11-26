<?php

namespace Tests\Feature;

use App\Models\Pangkat;
use App\Models\User;
use Database\Seeders\PangkatSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PangkatTest extends TestCase
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

    public function test_pangkat_index_and_datatable_render(): void
    {
        $this->seed(PangkatSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('pangkat.index'))
            ->assertOk()
            ->assertSee('Daftar Pangkat/Golongan')
            ->assertSee('LaravelDataTables["pangkat-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('pangkat.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 17);
    }

    public function test_can_store_update_and_delete_pangkat(): void
    {
        $this->seed(PangkatSeeder::class);
        $this->actingAsAdmin();

        // Create
        $this->post(route('pangkat.store'), [
            'pangkat' => 'v a',
            'nama_golongan' => 'Pembina Khusus',
        ])->assertRedirect(route('pangkat.index'));

        $item = Pangkat::where('pangkat', 'V A')->firstOrFail();
        $this->assertSame('Pembina Khusus', $item->nama_golongan);

        // Update
        $this->put(route('pangkat.update', $item), [
            'pangkat' => 'V A',
            'nama_golongan' => 'Pembina Khusus Utama',
        ])->assertRedirect(route('pangkat.index'));

        $this->assertDatabaseHas('pangkats', [
            'id' => $item->id,
            'pangkat' => 'V A',
            'nama_golongan' => 'Pembina Khusus Utama',
        ]);

        // Delete
        $this->delete(route('pangkat.destroy', $item))
            ->assertRedirect(route('pangkat.index'));

        $this->assertDatabaseMissing('pangkats', ['id' => $item->id]);
    }
}
