<?php

namespace Tests\Feature;

use App\Models\JenisCuti;
use App\Models\User;
use Database\Seeders\JenisCutiSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JenisCutiTest extends TestCase
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

    public function test_jenis_cuti_index_and_datatable_render(): void
    {
        $this->seed(JenisCutiSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('jenis-cuti.index'))
            ->assertOk()
            ->assertSee('Daftar Cuti')
            ->assertSee('LaravelDataTables["jenis-cuti-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('jenis-cuti.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 3);
    }

    public function test_can_store_update_delete_and_protect_system_cuti(): void
    {
        $this->seed(JenisCutiSeeder::class);
        $this->actingAsAdmin();

        // System THN cannot be deleted
        $tahunan = JenisCuti::where('kode', 'THN')->firstOrFail();
        $this->delete(route('jenis-cuti.destroy', $tahunan))
            ->assertRedirect(route('jenis-cuti.index'));
        $this->assertDatabaseHas('jenis_cutis', ['id' => $tahunan->id]);

        // Create
        $this->post(route('jenis-cuti.store'), [
            'kode' => 'bsr',
            'nama' => 'Cuti Besar',
            'standar_cuti' => 30,
            'format_nomor_surat' => '{{kode}}/{{urutan}}/{{tahun}}',
            'keterangan' => 'Setelah 5 tahun kerja',
        ])->assertRedirect(route('jenis-cuti.index'));

        $item = JenisCuti::where('kode', 'BSR')->firstOrFail();
        $this->assertSame(30, $item->standar_cuti);

        // Update
        $this->put(route('jenis-cuti.update', $item), [
            'kode' => 'BSR',
            'nama' => 'Cuti Besar Pegawai',
            'standar_cuti' => 45,
            'format_nomor_surat' => '{{kode}}/{{urutan}}/{{tahun}}',
            'keterangan' => 'Diperbarui',
        ])->assertRedirect(route('jenis-cuti.index'));

        $this->assertDatabaseHas('jenis_cutis', [
            'id' => $item->id,
            'nama' => 'Cuti Besar Pegawai',
            'standar_cuti' => 45,
        ]);

        // Delete
        $this->delete(route('jenis-cuti.destroy', $item))
            ->assertRedirect(route('jenis-cuti.index'));

        $this->assertDatabaseMissing('jenis_cutis', ['id' => $item->id]);
    }
}
