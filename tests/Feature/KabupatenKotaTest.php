<?php

namespace Tests\Feature;

use App\Models\KabupatenKota;
use App\Models\Provinsi;
use App\Models\User;
use Database\Seeders\KabupatenKotaSeeder;
use Database\Seeders\ProvinsiSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KabupatenKotaTest extends TestCase
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

    public function test_kabupaten_kota_index_datatable_and_filter(): void
    {
        $this->seed([ProvinsiSeeder::class, KabupatenKotaSeeder::class]);
        $this->actingAsAdmin();

        $kepri = Provinsi::where('kode', '21')->firstOrFail();

        $this->get(route('kabupaten-kota.index'))
            ->assertOk()
            ->assertSee('Daftar Kabupaten / Kota')
            ->assertSee('LaravelDataTables["kabupaten-kota-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('kabupaten-kota.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 34);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('kabupaten-kota.index', ['draw' => 2, 'provinsi_id' => $kepri->id]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 7);
    }

    public function test_can_store_update_and_delete_kabupaten_kota(): void
    {
        $this->seed([ProvinsiSeeder::class, KabupatenKotaSeeder::class]);
        $this->actingAsAdmin();

        $kepri = Provinsi::where('kode', '21')->firstOrFail();

        // Create
        $this->post(route('kabupaten-kota.store'), [
            'provinsi_id' => $kepri->id,
            'kode' => '2199',
            'nama' => 'Kota Baru',
        ])->assertRedirect(route('kabupaten-kota.index', ['provinsi_id' => $kepri->id]));

        $item = KabupatenKota::where('kode', '2199')->firstOrFail();
        $this->assertSame('KOTA BARU', $item->nama);

        // Update
        $this->put(route('kabupaten-kota.update', $item), [
            'provinsi_id' => $kepri->id,
            'kode' => '2199',
            'nama' => 'Kota Baru Kepri',
        ])->assertRedirect(route('kabupaten-kota.index', ['provinsi_id' => $kepri->id]));

        $this->assertDatabaseHas('kabupaten_kotas', [
            'id' => $item->id,
            'nama' => 'KOTA BARU KEPRI',
        ]);

        // Delete
        $this->delete(route('kabupaten-kota.destroy', $item))
            ->assertRedirect(route('kabupaten-kota.index', ['provinsi_id' => $kepri->id]));

        $this->assertDatabaseMissing('kabupaten_kotas', ['id' => $item->id]);
    }
}
