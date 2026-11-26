<?php

namespace Tests\Feature;

use App\Models\JenisSk;
use App\Models\User;
use Database\Seeders\JenisSkSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JenisSkTest extends TestCase
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

    public function test_jenis_sk_index_and_datatable_render(): void
    {
        $this->seed(JenisSkSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('jenis-sk.index'))
            ->assertOk()
            ->assertSee('Daftar Jenis SK')
            ->assertSee('LaravelDataTables["jenis-sk-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('jenis-sk.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 5);

        $this->get(route('jenis-sk.create'))
            ->assertRedirect(route('jenis-sk.index', ['create' => 1]));
    }

    public function test_can_store_update_and_delete_jenis_sk(): void
    {
        $this->actingAsAdmin();

        $this->post(route('jenis-sk.store'), [
            'kode' => '006',
            'nama' => 'SK REKTOR',
        ])->assertRedirect(route('jenis-sk.index'));

        $item = JenisSk::where('kode', '006')->firstOrFail();

        $this->put(route('jenis-sk.update', $item), [
            'kode' => '006',
            'nama' => 'SK REKTOR UIS',
        ])->assertRedirect(route('jenis-sk.index'));

        $this->assertDatabaseHas('jenis_sks', [
            'id' => $item->id,
            'nama' => 'SK REKTOR UIS',
        ]);

        $this->delete(route('jenis-sk.destroy', $item))
            ->assertRedirect(route('jenis-sk.index'));

        $this->assertDatabaseMissing('jenis_sks', ['id' => $item->id]);
    }
}
