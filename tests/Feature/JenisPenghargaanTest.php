<?php

namespace Tests\Feature;

use App\Models\JenisPenghargaan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JenisPenghargaanTest extends TestCase
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

    public function test_jenis_penghargaan_index_and_datatable_render(): void
    {
        $this->actingAsAdmin();

        $this->get(route('jenis-penghargaan.index'))
            ->assertOk()
            ->assertSee('Daftar Jenis Penghargaan')
            ->assertSee('LaravelDataTables["jenis-penghargaan-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('jenis-penghargaan.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 0);
    }

    public function test_can_store_update_and_delete_jenis_penghargaan(): void
    {
        $this->actingAsAdmin();

        // Create
        $this->post(route('jenis-penghargaan.store'), [
            'kode' => 'ph1',
            'nama' => 'Satyalancana Karya Satya',
        ])->assertRedirect(route('jenis-penghargaan.index'));

        $item = JenisPenghargaan::where('kode', 'PH1')->firstOrFail();
        $this->assertSame('Satyalancana Karya Satya', $item->nama);

        // Update
        $this->put(route('jenis-penghargaan.update', $item), [
            'kode' => 'PH1',
            'nama' => 'Dosen Berprestasi',
        ])->assertRedirect(route('jenis-penghargaan.index'));

        $this->assertDatabaseHas('jenis_penghargaans', [
            'id' => $item->id,
            'nama' => 'Dosen Berprestasi',
        ]);

        // Delete
        $this->delete(route('jenis-penghargaan.destroy', $item))
            ->assertRedirect(route('jenis-penghargaan.index'));

        $this->assertDatabaseMissing('jenis_penghargaans', ['id' => $item->id]);
    }
}
