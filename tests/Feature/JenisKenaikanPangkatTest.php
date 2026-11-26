<?php

namespace Tests\Feature;

use App\Models\JenisKenaikanPangkat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JenisKenaikanPangkatTest extends TestCase
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

    public function test_jenis_kenaikan_pangkat_index_and_datatable_render(): void
    {
        $this->actingAsAdmin();

        $this->get(route('jenis-kenaikan-pangkat.index'))
            ->assertOk()
            ->assertSee('Daftar Jenis Kenaikan Pangkat')
            ->assertSee('LaravelDataTables["jenis-kenaikan-pangkat-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('jenis-kenaikan-pangkat.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 0);
    }

    public function test_can_store_update_and_delete_jenis_kenaikan_pangkat(): void
    {
        $this->actingAsAdmin();

        // Create
        $this->post(route('jenis-kenaikan-pangkat.store'), [
            'kode' => 'reg',
            'nama' => 'Reguler',
        ])->assertRedirect(route('jenis-kenaikan-pangkat.index'));

        $item = JenisKenaikanPangkat::where('kode', 'REG')->firstOrFail();
        $this->assertSame('Reguler', $item->nama);

        // Update
        $this->put(route('jenis-kenaikan-pangkat.update', $item), [
            'kode' => 'REG',
            'nama' => 'Kenaikan Pangkat Reguler',
        ])->assertRedirect(route('jenis-kenaikan-pangkat.index'));

        $this->assertDatabaseHas('jenis_kenaikan_pangkats', [
            'id' => $item->id,
            'kode' => 'REG',
            'nama' => 'Kenaikan Pangkat Reguler',
        ]);

        // Delete
        $this->delete(route('jenis-kenaikan-pangkat.destroy', $item))
            ->assertRedirect(route('jenis-kenaikan-pangkat.index'));

        $this->assertDatabaseMissing('jenis_kenaikan_pangkats', ['id' => $item->id]);
    }
}
