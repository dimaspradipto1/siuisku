<?php

namespace Tests\Feature;

use App\Models\StatusAktif;
use App\Models\User;
use Database\Seeders\StatusAktifSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatusAktifTest extends TestCase
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

    public function test_status_aktif_index_and_datatable_render(): void
    {
        $this->seed(StatusAktifSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('status-aktif.index'))
            ->assertOk()
            ->assertSee('Daftar Status Aktif')
            ->assertSee('LaravelDataTables["status-aktif-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('status-aktif.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 16);
    }

    public function test_can_store_update_delete_and_protect_system_statuses(): void
    {
        $this->seed(StatusAktifSeeder::class);
        $this->actingAsAdmin();

        // System status AA cannot be deleted
        $aktif = StatusAktif::where('kode', 'AA')->firstOrFail();
        $this->delete(route('status-aktif.destroy', $aktif))
            ->assertRedirect(route('status-aktif.index'));
        $this->assertDatabaseHas('status_aktifs', ['id' => $aktif->id]);

        // Create new status
        $this->post(route('status-aktif.store'), [
            'kode' => 'ib',
            'nama' => 'Izin Belajar',
            'status_keluar' => false,
        ])->assertRedirect(route('status-aktif.index'));

        $item = StatusAktif::where('kode', 'IB')->firstOrFail();
        $this->assertFalse($item->status_keluar);

        // Update status
        $this->put(route('status-aktif.update', $item), [
            'kode' => 'IB',
            'nama' => 'Izin Belajar Luar Negeri',
            'status_keluar' => true,
        ])->assertRedirect(route('status-aktif.index'));

        $this->assertDatabaseHas('status_aktifs', [
            'id' => $item->id,
            'nama' => 'Izin Belajar Luar Negeri',
            'status_keluar' => true,
        ]);

        // Delete status
        $this->delete(route('status-aktif.destroy', $item))
            ->assertRedirect(route('status-aktif.index'));

        $this->assertDatabaseMissing('status_aktifs', ['id' => $item->id]);
    }
}
