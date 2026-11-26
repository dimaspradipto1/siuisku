<?php

namespace Tests\Feature;

use App\Models\KabupatenKota;
use App\Models\Kecamatan;
use App\Models\User;
use Database\Seeders\KabupatenKotaSeeder;
use Database\Seeders\KecamatanSeeder;
use Database\Seeders\ProvinsiSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KecamatanTest extends TestCase
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

    public function test_kecamatan_index_datatable_and_filter(): void
    {
        $this->seed([ProvinsiSeeder::class, KabupatenKotaSeeder::class, KecamatanSeeder::class]);
        $this->actingAsAdmin();

        $batam = KabupatenKota::where('kode', '2171')->firstOrFail();

        $this->get(route('kecamatan.index'))
            ->assertOk()
            ->assertSee('Daftar Kecamatan')
            ->assertSee('LaravelDataTables["kecamatan-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('kecamatan.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 23);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('kecamatan.index', ['draw' => 2, 'kabupaten_kota_id' => $batam->id]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 12);
    }

    public function test_can_store_update_and_delete_kecamatan(): void
    {
        $this->seed([ProvinsiSeeder::class, KabupatenKotaSeeder::class, KecamatanSeeder::class]);
        $this->actingAsAdmin();

        $batam = KabupatenKota::where('kode', '2171')->firstOrFail();

        // Create
        $this->post(route('kecamatan.store'), [
            'kabupaten_kota_id' => $batam->id,
            'kode' => '217199',
            'nama' => 'Kecamatan Baru',
        ])->assertRedirect(route('kecamatan.index', ['kabupaten_kota_id' => $batam->id]));

        $item = Kecamatan::where('kode', '217199')->firstOrFail();
        $this->assertSame('KECAMATAN BARU', $item->nama);

        // Update
        $this->put(route('kecamatan.update', $item), [
            'kabupaten_kota_id' => $batam->id,
            'kode' => '217199',
            'nama' => 'Kecamatan Baru Batam',
        ])->assertRedirect(route('kecamatan.index', ['kabupaten_kota_id' => $batam->id]));

        $this->assertDatabaseHas('kecamatans', [
            'id' => $item->id,
            'nama' => 'KECAMATAN BARU BATAM',
        ]);

        // Delete
        $this->delete(route('kecamatan.destroy', $item))
            ->assertRedirect(route('kecamatan.index', ['kabupaten_kota_id' => $batam->id]));

        $this->assertDatabaseMissing('kecamatans', ['id' => $item->id]);
    }
}
