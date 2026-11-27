<?php

namespace Tests\Feature;

use App\Models\GolonganDarah;
use App\Models\User;
use Database\Seeders\GolonganDarahSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GolonganDarahTest extends TestCase
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

    public function test_golongan_darah_index_and_datatable_render(): void
    {
        $this->seed(GolonganDarahSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('golongan-darah.index'))
            ->assertOk()
            ->assertSee('Daftar Golongan Darah')
            ->assertSee('LaravelDataTables["golongan-darah-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('golongan-darah.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 4);
    }

    public function test_can_store_update_and_delete_golongan_darah(): void
    {
        $this->seed(GolonganDarahSeeder::class);
        $this->actingAsAdmin();

        // Create
        $this->post(route('golongan-darah.store'), [
            'nama' => 'a+',
        ])->assertRedirect(route('golongan-darah.index'));

        $item = GolonganDarah::where('nama', 'A+')->firstOrFail();

        // Update
        $this->put(route('golongan-darah.update', $item), [
            'nama' => 'A-',
        ])->assertRedirect(route('golongan-darah.index'));

        $this->assertDatabaseHas('golongan_darahs', [
            'id' => $item->id,
            'nama' => 'A-',
        ]);

        // Delete
        $this->delete(route('golongan-darah.destroy', $item))
            ->assertRedirect(route('golongan-darah.index'));

        $this->assertDatabaseMissing('golongan_darahs', ['id' => $item->id]);
    }
}
