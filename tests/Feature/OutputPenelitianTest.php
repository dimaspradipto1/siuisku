<?php

namespace Tests\Feature;

use App\Models\OutputPenelitian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OutputPenelitianTest extends TestCase
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

    public function test_output_penelitian_index_and_datatable_render(): void
    {
        $this->actingAsAdmin();

        $this->get(route('output-penelitian.index'))
            ->assertOk()
            ->assertSee('Daftar Output Penelitian')
            ->assertSee('LaravelDataTables["output-penelitian-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('output-penelitian.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 0);
    }

    public function test_can_store_update_and_delete_output_penelitian(): void
    {
        $this->actingAsAdmin();

        // Create
        $this->post(route('output-penelitian.store'), [
            'kode' => 'op1',
            'nama' => 'Jurnal Nasional Terakreditasi',
        ])->assertRedirect(route('output-penelitian.index'));

        $item = OutputPenelitian::where('kode', 'OP1')->firstOrFail();
        $this->assertSame('Jurnal Nasional Terakreditasi', $item->nama);

        // Update
        $this->put(route('output-penelitian.update', $item), [
            'kode' => 'OP1',
            'nama' => 'Jurnal Internasional Bereputasi',
        ])->assertRedirect(route('output-penelitian.index'));

        $this->assertDatabaseHas('output_penelitians', [
            'id' => $item->id,
            'nama' => 'Jurnal Internasional Bereputasi',
        ]);

        // Delete
        $this->delete(route('output-penelitian.destroy', $item))
            ->assertRedirect(route('output-penelitian.index'));

        $this->assertDatabaseMissing('output_penelitians', ['id' => $item->id]);
    }
}
