<?php

namespace Tests\Feature;

use App\Models\HubunganKerja;
use App\Models\User;
use Database\Seeders\HubunganKerjaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HubunganKerjaTest extends TestCase
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

    public function test_hubungan_kerja_index_and_datatable_render(): void
    {
        $this->seed(HubunganKerjaSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('hubungan-kerja.index'))
            ->assertOk()
            ->assertSee('Daftar Hubungan Kerja')
            ->assertSee('LaravelDataTables["hubungan-kerja-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('hubungan-kerja.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 6);
    }

    public function test_can_store_update_and_delete_hubungan_kerja(): void
    {
        $this->seed(HubunganKerjaSeeder::class);
        $this->actingAsAdmin();

        // Create
        $this->post(route('hubungan-kerja.store'), [
            'kode' => 'h7',
            'nama' => 'Magang',
            'status_aktif' => true,
            'pegawai_tetap' => false,
            'jenis_asn' => 'Non ASN',
        ])->assertRedirect(route('hubungan-kerja.index'));

        $item = HubunganKerja::where('kode', 'H7')->firstOrFail();
        $this->assertTrue($item->status_aktif);
        $this->assertFalse($item->pegawai_tetap);
        $this->assertSame('Non ASN', $item->jenis_asn);

        // Update
        $this->put(route('hubungan-kerja.update', $item), [
            'kode' => 'H7',
            'nama' => 'PPPK Paruh Waktu',
            'status_aktif' => true,
            'pegawai_tetap' => true,
            'jenis_asn' => 'PPPK',
        ])->assertRedirect(route('hubungan-kerja.index'));

        $this->assertDatabaseHas('hubungan_kerjas', [
            'id' => $item->id,
            'nama' => 'PPPK Paruh Waktu',
            'status_aktif' => true,
            'pegawai_tetap' => true,
            'jenis_asn' => 'PPPK',
        ]);

        // Delete
        $this->delete(route('hubungan-kerja.destroy', $item))
            ->assertRedirect(route('hubungan-kerja.index'));

        $this->assertDatabaseMissing('hubungan_kerjas', ['id' => $item->id]);
    }
}
