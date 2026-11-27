<?php

namespace Tests\Feature;

use App\Models\PerguruanTinggi;
use App\Models\User;
use Database\Seeders\PerguruanTinggiSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerguruanTinggiTest extends TestCase
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

    public function test_perguruan_tinggi_index_and_datatable_render(): void
    {
        $this->seed(PerguruanTinggiSeeder::class);
        $this->actingAsAdmin();

        $this->assertDatabaseHas('perguruan_tinggis', [
            'kode' => '051024',
            'nama' => 'Universitas Amikom Yogyakarta',
        ]);

        $this->get(route('perguruan-tinggi.index'))
            ->assertOk()
            ->assertSee('Daftar Perguruan Tinggi')
            ->assertSee('LaravelDataTables["perguruan-tinggi-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('perguruan-tinggi.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 38);
    }

    public function test_can_store_update_and_delete_perguruan_tinggi(): void
    {
        $this->seed(PerguruanTinggiSeeder::class);
        $this->actingAsAdmin();

        // Create
        $this->post(route('perguruan-tinggi.store'), [
            'kode' => '999001',
            'nama' => 'Universitas Contoh',
            'alamat' => 'Jl. Pendidikan No. 1',
            'no_telp' => '0778-123456',
        ])->assertRedirect(route('perguruan-tinggi.index'));

        $item = PerguruanTinggi::where('kode', '999001')->firstOrFail();
        $this->assertSame('Universitas Contoh', $item->nama);

        // Update
        $this->put(route('perguruan-tinggi.update', $item), [
            'kode' => '999001',
            'nama' => 'Universitas Contoh Nusantara',
            'alamat' => 'Jl. Pendidikan No. 2',
            'no_telp' => '0778-654321',
        ])->assertRedirect(route('perguruan-tinggi.index'));

        $this->assertDatabaseHas('perguruan_tinggis', [
            'id' => $item->id,
            'nama' => 'Universitas Contoh Nusantara',
        ]);

        // Delete
        $this->delete(route('perguruan-tinggi.destroy', $item))
            ->assertRedirect(route('perguruan-tinggi.index'));

        $this->assertDatabaseMissing('perguruan_tinggis', ['id' => $item->id]);
    }
}
