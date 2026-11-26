<?php

namespace Tests\Feature;

use App\Models\RumpunBidangIlmu;
use App\Models\User;
use Database\Seeders\RumpunBidangIlmuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RumpunBidangIlmuTest extends TestCase
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

    public function test_rumpun_bidang_ilmu_index_and_datatable_render(): void
    {
        $this->seed(RumpunBidangIlmuSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('rumpun-bidang-ilmu.index'))
            ->assertOk()
            ->assertSee('Daftar Rumpun Bidang Ilmu')
            ->assertSee('LaravelDataTables["rumpun-bidang-ilmu-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('rumpun-bidang-ilmu.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 53);
    }

    public function test_can_store_update_delete_and_protect_parent_with_children(): void
    {
        $this->seed(RumpunBidangIlmuSeeder::class);
        $this->actingAsAdmin();

        $parent = RumpunBidangIlmu::where('kode', '450')->firstOrFail();

        // Parent with children cannot be deleted
        $this->delete(route('rumpun-bidang-ilmu.destroy', $parent))
            ->assertRedirect(route('rumpun-bidang-ilmu.index'));
        $this->assertDatabaseHas('rumpun_bidang_ilmus', ['id' => $parent->id]);

        // Create child
        $this->post(route('rumpun-bidang-ilmu.store'), [
            'kode' => '466',
            'nama' => 'Keamanan Siber',
            'parent_id' => $parent->id,
        ])->assertRedirect(route('rumpun-bidang-ilmu.index'));

        $item = RumpunBidangIlmu::where('kode', '466')->firstOrFail();
        $this->assertSame($parent->id, $item->parent_id);
        $this->assertSame(2, $item->level);

        // Update
        $this->put(route('rumpun-bidang-ilmu.update', $item), [
            'kode' => '466',
            'nama' => 'Keamanan Siber & Jaringan',
            'parent_id' => $parent->id,
        ])->assertRedirect(route('rumpun-bidang-ilmu.index'));

        $this->assertDatabaseHas('rumpun_bidang_ilmus', [
            'id' => $item->id,
            'nama' => 'Keamanan Siber & Jaringan',
        ]);

        // Delete leaf item
        $this->delete(route('rumpun-bidang-ilmu.destroy', $item))
            ->assertRedirect(route('rumpun-bidang-ilmu.index'));

        $this->assertDatabaseMissing('rumpun_bidang_ilmus', ['id' => $item->id]);
    }
}
