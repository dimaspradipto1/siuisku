<?php

namespace Tests\Feature;

use App\Models\JenisJabatanStruktural;
use App\Models\User;
use Database\Seeders\JenisJabatanStrukturalSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JenisJabatanStrukturalTest extends TestCase
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

    public function test_index_datatable_create_and_edit_pages_render(): void
    {
        $this->seed(JenisJabatanStrukturalSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('jenis-jabatan-struktural.index'))
            ->assertOk()
            ->assertSee('Jenis Jabatan Struktural')
            ->assertDontSee('Jenis Jabatan Struktural Sister')
            ->assertSee('LaravelDataTables["jenis-jabatan-struktural-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('jenis-jabatan-struktural.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 79);

        $this->get(route('jenis-jabatan-struktural.create'))
            ->assertOk()
            ->assertSee('Tambah Jenis Jabatan Struktural');

        $item = JenisJabatanStruktural::where('kode', 'DFB')->firstOrFail();

        $this->get(route('jenis-jabatan-struktural.edit', $item))
            ->assertOk()
            ->assertSee('Dekan Fakultas Ekonomi dan Bisnis');
    }

    public function test_can_store_update_and_delete_jenis_jabatan_struktural(): void
    {
        $this->actingAsAdmin();

        $this->post(route('jenis-jabatan-struktural.store'), [
            'kode' => 'kp1',
            'nama' => 'Ketua Program Studi Teknik Informatika',
        ])->assertRedirect(route('jenis-jabatan-struktural.index'));

        $item = JenisJabatanStruktural::where('kode', 'KP1')->firstOrFail();

        $this->put(route('jenis-jabatan-struktural.update', $item), [
            'kode' => 'KP1',
            'nama' => 'Ketua Prodi Teknik Informatika',
        ])->assertRedirect(route('jenis-jabatan-struktural.index'));

        $this->assertDatabaseHas('jenis_jabatan_strukturals', [
            'id' => $item->id,
            'nama' => 'Ketua Prodi Teknik Informatika',
        ]);

        $this->delete(route('jenis-jabatan-struktural.destroy', $item))
            ->assertRedirect(route('jenis-jabatan-struktural.index'));

        $this->assertDatabaseMissing('jenis_jabatan_strukturals', ['id' => $item->id]);
    }
}
