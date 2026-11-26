<?php

namespace Tests\Feature;

use App\Models\JenisSertifikasi;
use App\Models\User;
use Database\Seeders\JenisSertifikasiSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JenisSertifikasiTest extends TestCase
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

    public function test_jenis_sertifikasi_index_and_datatable_render(): void
    {
        $this->seed(JenisSertifikasiSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('jenis-sertifikasi.index'))
            ->assertOk()
            ->assertSee('Daftar Jenis Sertifikasi')
            ->assertSee('LaravelDataTables["jenis-sertifikasi-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('jenis-sertifikasi.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 3);
    }

    public function test_can_store_update_delete_and_protect_system_sertifikasi(): void
    {
        $this->seed(JenisSertifikasiSeeder::class);
        $this->actingAsAdmin();

        // System S1 cannot be deleted
        $serdos = JenisSertifikasi::where('kode', 'S1')->firstOrFail();
        $this->delete(route('jenis-sertifikasi.destroy', $serdos))
            ->assertRedirect(route('jenis-sertifikasi.index'));
        $this->assertDatabaseHas('jenis_sertifikasis', ['id' => $serdos->id]);

        // Create
        $this->post(route('jenis-sertifikasi.store'), [
            'kode' => 's6',
            'nama' => 'Sertifikasi BNSP',
            'kategori' => 'Sertifikasi Kompetensi',
        ])->assertRedirect(route('jenis-sertifikasi.index'));

        $item = JenisSertifikasi::where('kode', 'S6')->firstOrFail();
        $this->assertSame('Sertifikasi BNSP', $item->nama);

        // Update
        $this->put(route('jenis-sertifikasi.update', $item), [
            'kode' => 'S6',
            'nama' => 'Sertifikasi Profesi BNSP',
            'kategori' => 'Sertifikasi Profesi',
        ])->assertRedirect(route('jenis-sertifikasi.index'));

        $this->assertDatabaseHas('jenis_sertifikasis', [
            'id' => $item->id,
            'nama' => 'Sertifikasi Profesi BNSP',
            'kategori' => 'Sertifikasi Profesi',
        ]);

        // Delete
        $this->delete(route('jenis-sertifikasi.destroy', $item))
            ->assertRedirect(route('jenis-sertifikasi.index'));

        $this->assertDatabaseMissing('jenis_sertifikasis', ['id' => $item->id]);
    }
}
