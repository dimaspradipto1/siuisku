<?php

namespace Tests\Feature;

use App\Models\JamKerja;
use App\Models\User;
use Database\Seeders\JamKerjaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JamKerjaTest extends TestCase
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

    public function test_jam_kerja_index_and_datatable_render(): void
    {
        $this->seed(JamKerjaSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('jam-kerja.index'))
            ->assertOk()
            ->assertSee('Daftar Jam Kerja')
            ->assertSee('LaravelDataTables["jam-kerja-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('jam-kerja.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 2);
    }

    public function test_can_store_update_and_delete_jam_kerja(): void
    {
        $this->seed(JamKerjaSeeder::class);
        $this->actingAsAdmin();

        // Create
        $this->post(route('jam-kerja.store'), [
            'nama' => 'Shift Pagi',
            'is_normal' => false,
            'jam_datang' => '08:00',
            'jam_pulang' => '16:00',
        ])->assertRedirect(route('jam-kerja.index'));

        $item = JamKerja::where('nama', 'Shift Pagi')->firstOrFail();
        $this->assertFalse($item->is_normal);
        $this->assertSame('08:00', $item->jam_datang);

        // Update
        $this->put(route('jam-kerja.update', $item), [
            'nama' => 'Shift Pagi Reguler',
            'is_normal' => true,
            'jam_datang' => '07:30',
            'jam_pulang' => '16:30',
        ])->assertRedirect(route('jam-kerja.index'));

        $this->assertDatabaseHas('jam_kerjas', [
            'id' => $item->id,
            'nama' => 'Shift Pagi Reguler',
            'is_normal' => true,
            'jam_datang' => '07:30',
            'jam_pulang' => '16:30',
        ]);

        // Delete
        $this->delete(route('jam-kerja.destroy', $item))
            ->assertRedirect(route('jam-kerja.index'));

        $this->assertDatabaseMissing('jam_kerjas', ['id' => $item->id]);
    }
}
