<?php

namespace Tests\Feature;

use App\Models\StatusPernikahan;
use App\Models\User;
use Database\Seeders\StatusPernikahanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatusPernikahanTest extends TestCase
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

    public function test_status_pernikahan_index_and_datatable_render(): void
    {
        $this->seed(StatusPernikahanSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('status-pernikahan.index'))
            ->assertOk()
            ->assertSee('Daftar Status Pernikahan')
            ->assertSee('LaravelDataTables["status-pernikahan-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('status-pernikahan.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 4);
    }

    public function test_can_update_status_pernikahan(): void
    {
        $this->seed(StatusPernikahanSeeder::class);
        $this->actingAsAdmin();

        $item = StatusPernikahan::where('kode', 'S')->firstOrFail();

        $this->put(route('status-pernikahan.update', $item), [
            'nama' => 'Belum Menikah',
        ])->assertRedirect(route('status-pernikahan.index'));

        $this->assertDatabaseHas('status_pernikahans', [
            'id' => $item->id,
            'nama' => 'Belum Menikah',
        ]);
    }
}
