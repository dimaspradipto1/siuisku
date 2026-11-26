<?php

namespace Tests\Feature;

use App\Models\JenisKehadiran;
use App\Models\User;
use Database\Seeders\JenisKehadiranSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JenisKehadiranTest extends TestCase
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

    public function test_jenis_kehadiran_index_and_datatable_render(): void
    {
        $this->seed(JenisKehadiranSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('jenis-kehadiran.index'))
            ->assertOk()
            ->assertSee('Daftar Jenis Kehadiran')
            ->assertSee('LaravelDataTables["jenis-kehadiran-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('jenis-kehadiran.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 15);
    }

    public function test_can_store_update_and_delete_jenis_kehadiran(): void
    {
        $this->seed(JenisKehadiranSeeder::class);
        $this->actingAsAdmin();

        // Create
        $this->post(route('jenis-kehadiran.store'), [
            'kode' => 'wfh',
            'nama' => 'Work From Home',
            'warna' => '#0ea5e9',
        ])->assertRedirect(route('jenis-kehadiran.index'));

        $item = JenisKehadiran::where('kode', 'WFH')->firstOrFail();
        $this->assertSame('#0EA5E9', $item->warna);

        // Update
        $this->put(route('jenis-kehadiran.update', $item), [
            'kode' => 'WFH',
            'nama' => 'Kerja Dari Rumah',
            'warna' => '#f5bd24',
        ])->assertRedirect(route('jenis-kehadiran.index'));

        $this->assertDatabaseHas('jenis_kehadirans', [
            'id' => $item->id,
            'nama' => 'Kerja Dari Rumah',
            'warna' => '#F5BD24',
        ]);

        // Delete
        $this->delete(route('jenis-kehadiran.destroy', $item))
            ->assertRedirect(route('jenis-kehadiran.index'));

        $this->assertDatabaseMissing('jenis_kehadirans', ['id' => $item->id]);
    }
}
