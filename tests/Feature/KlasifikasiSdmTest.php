<?php

namespace Tests\Feature;

use App\Models\KlasifikasiSdm;
use App\Models\User;
use Database\Seeders\KlasifikasiSdmSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KlasifikasiSdmTest extends TestCase
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

    public function test_klasifikasi_sdm_index_datatable_and_filter(): void
    {
        $this->seed(KlasifikasiSdmSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('klasifikasi-sdm.index'))
            ->assertOk()
            ->assertSee('Daftar Jabatan Akademik')
            ->assertSee('LaravelDataTables["klasifikasi-sdm-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('klasifikasi-sdm.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 48);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('klasifikasi-sdm.index', ['draw' => 2, 'jenis_jabatan' => 'Dosen Praktisi/Industri']))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 4);
    }

    public function test_can_store_update_and_delete_klasifikasi_sdm(): void
    {
        $this->seed(KlasifikasiSdmSeeder::class);
        $this->actingAsAdmin();

        // Create
        $this->post(route('klasifikasi-sdm.store'), [
            'kode' => 'k18',
            'nama' => 'Staf Akademik',
            'jenis_jabatan' => 'Tenaga Kependidikan',
        ])->assertRedirect(route('klasifikasi-sdm.index'));

        $item = KlasifikasiSdm::where('kode', 'K18')->firstOrFail();
        $this->assertSame('Staf Akademik', $item->nama);
        $this->assertSame('Tenaga Kependidikan', $item->jenis_jabatan);

        // Update
        $this->put(route('klasifikasi-sdm.update', $item), [
            'kode' => 'K18',
            'nama' => 'Staf Administrasi Akademik',
            'jenis_jabatan' => 'Dosen',
        ])->assertRedirect(route('klasifikasi-sdm.index'));

        $this->assertDatabaseHas('klasifikasi_sdms', [
            'id' => $item->id,
            'kode' => 'K18',
            'nama' => 'Staf Administrasi Akademik',
            'jenis_jabatan' => 'Dosen',
        ]);

        // Delete
        $this->delete(route('klasifikasi-sdm.destroy', $item))
            ->assertRedirect(route('klasifikasi-sdm.index'));

        $this->assertDatabaseMissing('klasifikasi_sdms', ['id' => $item->id]);
    }
}
