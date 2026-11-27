<?php

namespace Tests\Feature;

use App\Models\Bank;
use App\Models\User;
use Database\Seeders\BankSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BankTest extends TestCase
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

    public function test_bank_index_and_datatable_render(): void
    {
        $this->seed(BankSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('bank.index'))
            ->assertOk()
            ->assertSee('Daftar Bank')
            ->assertSee('LaravelDataTables["bank-table"]', false);

        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('bank.index', ['draw' => 1]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 7);
    }

    public function test_can_store_update_and_delete_bank(): void
    {
        $this->seed(BankSeeder::class);
        $this->actingAsAdmin();

        // Create
        $this->post(route('bank.store'), [
            'kode' => 'b08',
            'nama' => 'BSI',
        ])->assertRedirect(route('bank.index'));

        $item = Bank::where('kode', 'B08')->firstOrFail();
        $this->assertSame('BSI', $item->nama);

        // Update
        $this->put(route('bank.update', $item), [
            'kode' => 'B08',
            'nama' => 'BANK SYARIAH INDONESIA',
        ])->assertRedirect(route('bank.index'));

        $this->assertDatabaseHas('banks', [
            'id' => $item->id,
            'nama' => 'BANK SYARIAH INDONESIA',
        ]);

        // Delete
        $this->delete(route('bank.destroy', $item))
            ->assertRedirect(route('bank.index'));

        $this->assertDatabaseMissing('banks', ['id' => $item->id]);
    }
}
