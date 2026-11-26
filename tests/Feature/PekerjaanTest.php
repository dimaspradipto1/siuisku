<?php

namespace Tests\Feature;

use App\Models\Pekerjaan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PekerjaanTest extends TestCase
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

    public function test_pekerjaan_index_and_datatable_render(): void
    {
        $this->actingAsAdmin();

        $this->get(route('pekerjaan.index'))
            ->assertOk()
            ->assertSee('Daftar Nama Pekerjaan')
            ->assertSee('LaravelDataTables["pekerjaan-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('pekerjaan.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 0);
    }

    public function test_can_store_update_and_delete_pekerjaan(): void
    {
        $this->actingAsAdmin();

        // Create
        $this->post(route('pekerjaan.store'), [
            'kode' => 'pns',
            'nama' => 'Pegawai Negeri Sipil',
        ])->assertRedirect(route('pekerjaan.index'));

        $item = Pekerjaan::where('kode', 'PNS')->firstOrFail();
        $this->assertSame('Pegawai Negeri Sipil', $item->nama);

        // Update
        $this->put(route('pekerjaan.update', $item), [
            'kode' => 'PNS',
            'nama' => 'Aparatur Sipil Negara (ASN)',
        ])->assertRedirect(route('pekerjaan.index'));

        $this->assertDatabaseHas('pekerjaans', [
            'id' => $item->id,
            'nama' => 'Aparatur Sipil Negara (ASN)',
        ]);

        // Delete
        $this->delete(route('pekerjaan.destroy', $item))
            ->assertRedirect(route('pekerjaan.index'));

        $this->assertDatabaseMissing('pekerjaans', ['id' => $item->id]);
    }
}
