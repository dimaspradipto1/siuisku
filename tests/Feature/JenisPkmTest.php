<?php

namespace Tests\Feature;

use App\Models\JenisPkm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JenisPkmTest extends TestCase
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

    public function test_jenis_pkm_index_and_datatable_render(): void
    {
        $this->actingAsAdmin();

        $this->get(route('jenis-pkm.index'))
            ->assertOk()
            ->assertSee('Daftar Jenis PKM')
            ->assertSee('LaravelDataTables["jenis-pkm-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('jenis-pkm.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 0);
    }

    public function test_can_store_update_and_delete_jenis_pkm(): void
    {
        $this->actingAsAdmin();

        // Create
        $this->post(route('jenis-pkm.store'), [
            'kode' => 'pkm1',
            'nama' => 'Pengabdian Masyarakat Mandiri',
        ])->assertRedirect(route('jenis-pkm.index'));

        $item = JenisPkm::where('kode', 'PKM1')->firstOrFail();
        $this->assertSame('Pengabdian Masyarakat Mandiri', $item->nama);

        // Update
        $this->put(route('jenis-pkm.update', $item), [
            'kode' => 'PKM1',
            'nama' => 'Pengabdian Masyarakat Hibah',
        ])->assertRedirect(route('jenis-pkm.index'));

        $this->assertDatabaseHas('jenis_pkms', [
            'id' => $item->id,
            'nama' => 'Pengabdian Masyarakat Hibah',
        ]);

        // Delete
        $this->delete(route('jenis-pkm.destroy', $item))
            ->assertRedirect(route('jenis-pkm.index'));

        $this->assertDatabaseMissing('jenis_pkms', ['id' => $item->id]);
    }
}
