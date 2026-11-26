<?php

namespace Tests\Feature;

use App\Models\JenisPelanggaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JenisPelanggaranTest extends TestCase
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

    public function test_jenis_pelanggaran_index_and_datatable_render(): void
    {
        $this->actingAsAdmin();

        $this->get(route('jenis-pelanggaran.index'))
            ->assertOk()
            ->assertSee('Daftar Jenis Pelanggaran')
            ->assertSee('LaravelDataTables["jenis-pelanggaran-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('jenis-pelanggaran.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 0);
    }

    public function test_can_store_update_and_delete_jenis_pelanggaran(): void
    {
        $this->actingAsAdmin();

        // Create
        $this->post(route('jenis-pelanggaran.store'), [
            'kode' => 'pl1',
            'nama' => 'Teguran Lisan',
        ])->assertRedirect(route('jenis-pelanggaran.index'));

        $item = JenisPelanggaran::where('kode', 'PL1')->firstOrFail();
        $this->assertSame('Teguran Lisan', $item->nama);

        // Update
        $this->put(route('jenis-pelanggaran.update', $item), [
            'kode' => 'PL1',
            'nama' => 'Teguran Tertulis (SP 1)',
        ])->assertRedirect(route('jenis-pelanggaran.index'));

        $this->assertDatabaseHas('jenis_pelanggarans', [
            'id' => $item->id,
            'nama' => 'Teguran Tertulis (SP 1)',
        ]);

        // Delete
        $this->delete(route('jenis-pelanggaran.destroy', $item))
            ->assertRedirect(route('jenis-pelanggaran.index'));

        $this->assertDatabaseMissing('jenis_pelanggarans', ['id' => $item->id]);
    }
}
