<?php

namespace Tests\Feature;

use App\Models\JenjangPendidikan;
use App\Models\User;
use Database\Seeders\JenjangPendidikanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JenjangPendidikanTest extends TestCase
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

    public function test_jenjang_pendidikan_index_and_datatable_render(): void
    {
        $this->seed(JenjangPendidikanSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('jenjang-pendidikan.index'))
            ->assertOk()
            ->assertSee('Daftar Jenjang Pendidikan')
            ->assertSee('LaravelDataTables["jenjang-pendidikan-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('jenjang-pendidikan.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 25);
    }

    public function test_can_store_update_and_delete_jenjang_pendidikan(): void
    {
        $this->seed(JenjangPendidikanSeeder::class);
        $this->actingAsAdmin();

        // Create
        $this->post(route('jenjang-pendidikan.store'), [
            'kode' => 'PostDoc',
            'nama' => 'Post Doctoral',
            'nama_en' => 'Post Doctoral',
            'urutan' => 26,
            'is_perguruan_tinggi' => true,
            'is_pasca_sarjana' => true,
        ])->assertRedirect(route('jenjang-pendidikan.index'));

        $item = JenjangPendidikan::where('kode', 'PostDoc')->firstOrFail();
        $this->assertTrue($item->is_perguruan_tinggi);
        $this->assertTrue($item->is_pasca_sarjana);

        // Update
        $this->put(route('jenjang-pendidikan.update', $item), [
            'kode' => 'PostDoc',
            'nama' => 'Post Doctoral Fellowship',
            'nama_en' => 'Post Doctoral Fellowship',
            'urutan' => 26,
            'is_perguruan_tinggi' => true,
            'is_pasca_sarjana' => false,
        ])->assertRedirect(route('jenjang-pendidikan.index'));

        $this->assertDatabaseHas('jenjang_pendidikans', [
            'id' => $item->id,
            'nama' => 'Post Doctoral Fellowship',
            'is_pasca_sarjana' => false,
        ]);

        // Delete
        $this->delete(route('jenjang-pendidikan.destroy', $item))
            ->assertRedirect(route('jenjang-pendidikan.index'));

        $this->assertDatabaseMissing('jenjang_pendidikans', ['id' => $item->id]);
    }
}
