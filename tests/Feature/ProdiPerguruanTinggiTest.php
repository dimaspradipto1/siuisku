<?php

namespace Tests\Feature;

use App\Models\JenjangPendidikan;
use App\Models\PerguruanTinggi;
use App\Models\ProdiPerguruanTinggi;
use App\Models\User;
use Database\Seeders\JenjangPendidikanSeeder;
use Database\Seeders\PerguruanTinggiSeeder;
use Database\Seeders\ProdiPerguruanTinggiSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProdiPerguruanTinggiTest extends TestCase
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

    public function test_prodi_perguruan_tinggi_index_datatable_and_filter(): void
    {
        $this->seed([JenjangPendidikanSeeder::class, PerguruanTinggiSeeder::class, ProdiPerguruanTinggiSeeder::class]);
        $this->actingAsAdmin();

        $uis = PerguruanTinggi::where('kode', '101031')->firstOrFail();
        $amikom = PerguruanTinggi::where('kode', '051024')->firstOrFail();

        $this->get(route('prodi-perguruan-tinggi.index'))
            ->assertOk()
            ->assertSee('Daftar Prodi Perguruan Tinggi')
            ->assertSee('LaravelDataTables["prodi-perguruan-tinggi-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('prodi-perguruan-tinggi.index', ['draw' => 1, 'perguruan_tinggi_id' => $uis->id]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 9);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('prodi-perguruan-tinggi.index', ['draw' => 2, 'perguruan_tinggi_id' => $amikom->id]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 7);
    }

    public function test_can_store_update_and_delete_prodi_perguruan_tinggi(): void
    {
        $this->seed([JenjangPendidikanSeeder::class, PerguruanTinggiSeeder::class, ProdiPerguruanTinggiSeeder::class]);
        $this->actingAsAdmin();

        $uis = PerguruanTinggi::where('kode', '101031')->firstOrFail();
        $s1 = JenjangPendidikan::where('kode', 'S1')->firstOrFail();

        // Create
        $this->post(route('prodi-perguruan-tinggi.store'), [
            'perguruan_tinggi_id' => $uis->id,
            'jenjang_pendidikan_id' => $s1->id,
            'kode' => '99901',
            'nama' => 'Bisnis Digital',
            'alamat' => 'Batam',
            'no_telp' => '0778-425391',
        ])->assertRedirect(route('prodi-perguruan-tinggi.index', ['perguruan_tinggi_id' => $uis->id]));

        $item = ProdiPerguruanTinggi::where('perguruan_tinggi_id', $uis->id)->where('kode', '99901')->firstOrFail();

        // Update
        $this->put(route('prodi-perguruan-tinggi.update', $item), [
            'perguruan_tinggi_id' => $uis->id,
            'jenjang_pendidikan_id' => $s1->id,
            'kode' => '99901',
            'nama' => 'Bisnis Digital & Inovasi',
            'alamat' => 'Batam',
            'no_telp' => '0778-425391',
        ])->assertRedirect(route('prodi-perguruan-tinggi.index', ['perguruan_tinggi_id' => $uis->id]));

        $this->assertDatabaseHas('prodi_perguruan_tinggis', [
            'id' => $item->id,
            'nama' => 'Bisnis Digital & Inovasi',
        ]);

        // Delete
        $this->delete(route('prodi-perguruan-tinggi.destroy', $item))
            ->assertRedirect(route('prodi-perguruan-tinggi.index', ['perguruan_tinggi_id' => $uis->id]));

        $this->assertDatabaseMissing('prodi_perguruan_tinggis', ['id' => $item->id]);
    }
}
