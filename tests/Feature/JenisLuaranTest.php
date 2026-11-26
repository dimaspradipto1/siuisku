<?php

namespace Tests\Feature;

use App\Models\JenisLuaran;
use App\Models\User;
use Database\Seeders\JenisLuaranSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JenisLuaranTest extends TestCase
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

    public function test_jenis_luaran_index_and_datatable_render(): void
    {
        $this->seed(JenisLuaranSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('jenis-luaran.index'))
            ->assertOk()
            ->assertSee('Daftar Jenis Luaran')
            ->assertSee('LaravelDataTables["jenis-luaran-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('jenis-luaran.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 7);
    }

    public function test_can_store_update_and_delete_jenis_luaran(): void
    {
        $this->seed(JenisLuaranSeeder::class);
        $this->actingAsAdmin();

        // Create
        $this->post(route('jenis-luaran.store'), [
            'kode' => '8',
            'nama' => 'Paten Sederhana',
        ])->assertRedirect(route('jenis-luaran.index'));

        $item = JenisLuaran::where('kode', '8')->firstOrFail();
        $this->assertSame('Paten Sederhana', $item->nama);

        // Update
        $this->put(route('jenis-luaran.update', $item), [
            'kode' => '8',
            'nama' => 'Paten Internasional',
        ])->assertRedirect(route('jenis-luaran.index'));

        $this->assertDatabaseHas('jenis_luarans', [
            'id' => $item->id,
            'nama' => 'Paten Internasional',
        ]);

        // Delete
        $this->delete(route('jenis-luaran.destroy', $item))
            ->assertRedirect(route('jenis-luaran.index'));

        $this->assertDatabaseMissing('jenis_luarans', ['id' => $item->id]);
    }
}
