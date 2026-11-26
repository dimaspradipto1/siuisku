<?php

namespace Tests\Feature;

use App\Models\JenisIzin;
use App\Models\JenisKehadiran;
use App\Models\User;
use Database\Seeders\JenisIzinSeeder;
use Database\Seeders\JenisKehadiranSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JenisIzinTest extends TestCase
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

    public function test_jenis_izin_index_and_datatable_render(): void
    {
        $this->seed([JenisKehadiranSeeder::class, JenisIzinSeeder::class]);
        $this->actingAsAdmin();

        $this->get(route('jenis-izin.index'))
            ->assertOk()
            ->assertSee('Daftar Jenis Izin')
            ->assertSee('LaravelDataTables["jenis-izin-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('jenis-izin.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 10);
    }

    public function test_can_store_update_and_delete_jenis_izin(): void
    {
        $this->seed([JenisKehadiranSeeder::class, JenisIzinSeeder::class]);
        $this->actingAsAdmin();

        $statusIzin = JenisKehadiran::where('kode', 'I')->firstOrFail();

        // Create
        $this->post(route('jenis-izin.store'), [
            'kode' => '010',
            'nama' => 'Izin Umrah',
            'jenis_kehadiran_id' => $statusIzin->id,
            'maksimal_hari' => 14,
            'potong_cuti' => false,
        ])->assertRedirect(route('jenis-izin.index'));

        $item = JenisIzin::where('kode', '010')->firstOrFail();
        $this->assertSame(14, $item->maksimal_hari);
        $this->assertFalse($item->potong_cuti);

        // Update
        $this->put(route('jenis-izin.update', $item), [
            'kode' => '010',
            'nama' => 'Izin Ibadah Umrah',
            'jenis_kehadiran_id' => $statusIzin->id,
            'maksimal_hari' => 12,
            'potong_cuti' => true,
        ])->assertRedirect(route('jenis-izin.index'));

        $this->assertDatabaseHas('jenis_izins', [
            'id' => $item->id,
            'nama' => 'Izin Ibadah Umrah',
            'maksimal_hari' => 12,
            'potong_cuti' => true,
        ]);

        // Delete
        $this->delete(route('jenis-izin.destroy', $item))
            ->assertRedirect(route('jenis-izin.index'));

        $this->assertDatabaseMissing('jenis_izins', ['id' => $item->id]);
    }
}
