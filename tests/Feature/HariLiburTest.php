<?php

namespace Tests\Feature;

use App\Models\HariLibur;
use App\Models\User;
use Database\Seeders\HariLiburSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HariLiburTest extends TestCase
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

    public function test_hari_libur_index_datatable_and_year_filter(): void
    {
        $this->seed(HariLiburSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('hari-libur.index'))
            ->assertOk()
            ->assertSee('Daftar Hari Libur')
            ->assertSee('LaravelDataTables["hari-libur-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('hari-libur.index', ['draw' => 1, 'tahun' => 2026]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 15);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('hari-libur.index', ['draw' => 2, 'tahun' => 2025]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 0);
    }

    public function test_can_store_update_and_delete_hari_libur(): void
    {
        $this->seed(HariLiburSeeder::class);
        $this->actingAsAdmin();

        // Create
        $this->post(route('hari-libur.store'), [
            'nama' => 'Cuti Bersama Natal',
            'tanggal_mulai' => '2026-12-24',
            'tanggal_selesai' => '2026-12-24',
            'keterangan' => 'SKB 3 Menteri',
        ])->assertRedirect(route('hari-libur.index', ['tahun' => 2026]));

        $item = HariLibur::where('nama', 'Cuti Bersama Natal')->firstOrFail();

        // Update
        $this->put(route('hari-libur.update', $item), [
            'nama' => 'Cuti Bersama Akhir Tahun',
            'tanggal_mulai' => '2026-12-24',
            'tanggal_selesai' => '2026-12-26',
            'keterangan' => 'Diperbarui',
        ])->assertRedirect(route('hari-libur.index', ['tahun' => 2026]));

        $this->assertDatabaseHas('hari_liburs', [
            'id' => $item->id,
            'nama' => 'Cuti Bersama Akhir Tahun',
        ]);

        // Delete
        $this->delete(route('hari-libur.destroy', $item))
            ->assertRedirect(route('hari-libur.index', ['tahun' => 2026]));

        $this->assertDatabaseMissing('hari_liburs', ['id' => $item->id]);
    }
}
