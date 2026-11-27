<?php

namespace Tests\Feature;

use App\Models\JabatanStruktural;
use App\Models\JenisJabatanStruktural;
use App\Models\UnitKerja;
use App\Models\User;
use Database\Seeders\JabatanStrukturalSeeder;
use Database\Seeders\JenisJabatanStrukturalSeeder;
use Database\Seeders\UnitKerjaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JabatanStrukturalTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): User
    {
        $this->seed([
            UnitKerjaSeeder::class,
            JenisJabatanStrukturalSeeder::class,
            JabatanStrukturalSeeder::class,
        ]);

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

    public function test_index_datatable_create_and_edit_render(): void
    {
        $this->actingAsAdmin();

        $this->get(route('jabatan-struktural.index'))
            ->assertOk()
            ->assertSee('Daftar Jabatan Struktural')
            ->assertSee('LaravelDataTables["jabatan-struktural-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('jabatan-struktural.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 11);

        $this->get(route('jabatan-struktural.create'))
            ->assertOk()
            ->assertSee('Tambah Jabatan Struktural');

        $rektor = JabatanStruktural::where('kode', 'R')->firstOrFail();

        $this->get(route('jabatan-struktural.edit', $rektor))
            ->assertOk()
            ->assertSee('Edit Jabatan Struktural');
    }

    public function test_can_store_update_and_delete_jabatan_struktural(): void
    {
        $this->actingAsAdmin();

        $rektor = JabatanStruktural::where('kode', 'R')->firstOrFail();
        $unitFt = UnitKerja::where('kode', 'FST')->firstOrFail();
        $jenis = JenisJabatanStruktural::where('kode', 'KPTI')->firstOrFail();

        $this->post(route('jabatan-struktural.store'), [
            'kode' => 'kpti',
            'nama' => 'Ketua Program Studi Teknik Industri',
            'jenis_jabatan_struktural_id' => $jenis->id,
            'singkatan' => 'Kaprodi TI',
            'parent_id' => $rektor->id,
            'unit_kerja_id' => $unitFt->id,
            'email' => 'ti@uis.ac.id',
            'eselon' => 'Eselon III',
            'beban_sks' => 6,
            'is_pimpinan' => true,
            'is_active' => true,
            'urutan' => 20,
        ])->assertRedirect(route('jabatan-struktural.index'));

        $item = JabatanStruktural::where('kode', 'KPTI')->firstOrFail();
        $this->assertTrue($item->is_pimpinan);
        $this->assertTrue($item->is_active);

        $this->put(route('jabatan-struktural.update', $item), [
            'kode' => 'KPTI',
            'nama' => 'Kaprodi Teknik Industri',
            'jenis_jabatan_struktural_id' => $jenis->id,
            'singkatan' => 'Kaprodi TI',
            'parent_id' => $rektor->id,
            'unit_kerja_id' => $unitFt->id,
            'is_pimpinan' => true,
            'is_active' => false,
            'urutan' => 21,
        ])->assertRedirect(route('jabatan-struktural.index'));

        $this->assertDatabaseHas('jabatan_strukturals', [
            'id' => $item->id,
            'nama' => 'Kaprodi Teknik Industri',
            'is_active' => false,
        ]);

        $this->delete(route('jabatan-struktural.destroy', $item))
            ->assertRedirect(route('jabatan-struktural.index'));

        $this->assertDatabaseMissing('jabatan_strukturals', ['id' => $item->id]);
    }
}
