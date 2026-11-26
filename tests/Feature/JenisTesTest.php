<?php

namespace Tests\Feature;

use App\Models\JenisTes;
use App\Models\User;
use Database\Seeders\JenisTesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JenisTesTest extends TestCase
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

    public function test_jenis_tes_index_and_datatable_render(): void
    {
        $this->seed(JenisTesSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('jenis-tes.index'))
            ->assertOk()
            ->assertSee('Daftar Jenis Tes')
            ->assertSee('LaravelDataTables["jenis-tes-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('jenis-tes.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 7);
    }

    public function test_can_store_update_and_delete_jenis_tes(): void
    {
        $this->seed(JenisTesSeeder::class);
        $this->actingAsAdmin();

        // Create
        $this->post(route('jenis-tes.store'), [
            'kode' => 'tpa',
            'nama' => 'TPA BAPPENAS',
            'nilai_minimal' => 450,
            'nilai_maksimal' => 800,
        ])->assertRedirect(route('jenis-tes.index'));

        $item = JenisTes::where('kode', 'TPA')->firstOrFail();
        $this->assertSame('TPA BAPPENAS', $item->nama);

        // Update
        $this->put(route('jenis-tes.update', $item), [
            'kode' => 'TPA',
            'nama' => 'TPA OTO BAPPENAS',
            'nilai_minimal' => 500,
            'nilai_maksimal' => 800,
        ])->assertRedirect(route('jenis-tes.index'));

        $this->assertDatabaseHas('jenis_tes', [
            'id' => $item->id,
            'nama' => 'TPA OTO BAPPENAS',
            'nilai_minimal' => 500.00,
        ]);

        // Delete
        $this->delete(route('jenis-tes.destroy', $item))
            ->assertRedirect(route('jenis-tes.index'));

        $this->assertDatabaseMissing('jenis_tes', ['id' => $item->id]);
    }
}
