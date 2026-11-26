<?php

namespace Tests\Feature;

use App\Models\JabatanFungsional;
use App\Models\KlasifikasiSdm;
use App\Models\Pangkat;
use App\Models\User;
use Database\Seeders\JabatanFungsionalSeeder;
use Database\Seeders\KlasifikasiSdmSeeder;
use Database\Seeders\PangkatSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JabatanFungsionalTest extends TestCase
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

    public function test_jabatan_fungsional_index_datatable_and_create_page_render(): void
    {
        $this->seed([KlasifikasiSdmSeeder::class, PangkatSeeder::class, JabatanFungsionalSeeder::class]);
        $this->actingAsAdmin();

        $this->get(route('jabatan-fungsional.index'))
            ->assertOk()
            ->assertSee('Daftar Jabatan Fungsional')
            ->assertSee('LaravelDataTables["jabatan-fungsional-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('jabatan-fungsional.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 10);

        $this->get(route('jabatan-fungsional.create'))
            ->assertOk()
            ->assertSee('Tambah Jabatan Fungsional');
    }

    public function test_can_store_edit_update_and_delete_jabatan_fungsional(): void
    {
        $this->seed([KlasifikasiSdmSeeder::class, PangkatSeeder::class, JabatanFungsionalSeeder::class]);
        $this->actingAsAdmin();

        $klasifikasi = KlasifikasiSdm::where('kode', '2')->firstOrFail();
        $pangkat = Pangkat::where('pangkat', 'III B')->firstOrFail();

        // Create
        $this->post(route('jabatan-fungsional.store'), [
            'klasifikasi_sdm_id' => $klasifikasi->id,
            'kode' => '31',
            'nama' => 'Asisten Ahli (150)',
            'pangkat_id' => $pangkat->id,
            'angka_kredit' => 150,
            'usia_pensiun' => 65,
            'referensi_sister' => 'Asisten Ahli 150',
            'keterangan' => 'Dosen DPK',
        ])->assertRedirect(route('jabatan-fungsional.index'));

        $item = JabatanFungsional::where('klasifikasi_sdm_id', $klasifikasi->id)
            ->where('kode', '31')
            ->firstOrFail();

        // Edit page
        $this->get(route('jabatan-fungsional.edit', $item))
            ->assertOk()
            ->assertSee('Edit Jabatan Fungsional');

        // Update
        $this->put(route('jabatan-fungsional.update', $item), [
            'klasifikasi_sdm_id' => $klasifikasi->id,
            'kode' => '31',
            'nama' => 'Asisten Ahli (150) Updated',
            'pangkat_id' => $pangkat->id,
            'angka_kredit' => 200,
            'usia_pensiun' => 70,
            'referensi_sister' => 'Lektor 200',
            'keterangan' => 'Diperbarui',
        ])->assertRedirect(route('jabatan-fungsional.index'));

        $this->assertDatabaseHas('jabatan_fungsionals', [
            'id' => $item->id,
            'nama' => 'Asisten Ahli (150) Updated',
            'angka_kredit' => 200,
            'usia_pensiun' => 70,
        ]);

        // Delete
        $this->delete(route('jabatan-fungsional.destroy', $item))
            ->assertRedirect(route('jabatan-fungsional.index'));

        $this->assertDatabaseMissing('jabatan_fungsionals', ['id' => $item->id]);
    }
}
