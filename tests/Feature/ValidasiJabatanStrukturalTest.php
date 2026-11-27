<?php

namespace Tests\Feature;

use App\Models\RiwayatJabatanStruktural;
use App\Models\UnitKerja;
use App\Models\User;
use Database\Seeders\HubunganKerjaSeeder;
use Database\Seeders\JabatanStrukturalSeeder;
use Database\Seeders\JenisJabatanStrukturalSeeder;
use Database\Seeders\MonitoringValidasiSeeder;
use Database\Seeders\RiwayatJabatanStrukturalSeeder;
use Database\Seeders\StatusAktifSeeder;
use Database\Seeders\UnitKerjaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidasiJabatanStrukturalTest extends TestCase
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

    private function seedDependencies(): void
    {
        $this->seed(UnitKerjaSeeder::class);
        $this->seed(JenisJabatanStrukturalSeeder::class);
        $this->seed(JabatanStrukturalSeeder::class);
        $this->seed(StatusAktifSeeder::class);
        $this->seed(HubunganKerjaSeeder::class);
        $this->seed(MonitoringValidasiSeeder::class);
        $this->seed(RiwayatJabatanStrukturalSeeder::class);
    }

    public function test_validasi_jabatan_struktural_index_and_datatable_filters(): void
    {
        $this->seedDependencies();
        $this->actingAsAdmin();

        $this->get(route('validasi-jabatan-struktural.index'))
            ->assertOk()
            ->assertSee('Riwayat Jabatan Struktural')
            ->assertSee('LaravelDataTables["validasi-struktural-table"]', false);

        // Default status: diajukan (3 seeded records)
        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('validasi-jabatan-struktural.index', [
                'draw' => 1,
                'status' => 'diajukan',
            ]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 3);

        // Filter by FST + diajukan (2 seeded records under 26201)
        $unitFst = UnitKerja::where('kode', 'FST')->firstOrFail();
        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('validasi-jabatan-struktural.index', [
                'draw' => 1,
                'unit_kerja_id' => $unitFst->id,
                'status' => 'diajukan',
            ]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 2);
    }

    public function test_can_show_approve_and_reject_validasi_jabatan_struktural(): void
    {
        $this->seedDependencies();
        $admin = $this->actingAsAdmin();

        $item = RiwayatJabatanStruktural::where('status', RiwayatJabatanStruktural::STATUS_DIAJUKAN)->firstOrFail();

        $this->get(route('validasi-jabatan-struktural.show', $item))
            ->assertOk()
            ->assertSee($item->nomor_sk);

        // Approve
        $this->post(route('validasi-jabatan-struktural.bulk'), [
            'action' => RiwayatJabatanStruktural::STATUS_DISETUJUI,
            'ids' => [$item->id],
            'catatan_validasi' => 'SK Struktural sesuai',
        ])->assertRedirect();

        $item->refresh();
        $this->assertSame(RiwayatJabatanStruktural::STATUS_DISETUJUI, $item->status);
        $this->assertSame($admin->id, $item->validated_by);
        $this->assertNotNull($item->validated_at);

        // Reject
        $this->post(route('validasi-jabatan-struktural.bulk'), [
            'action' => RiwayatJabatanStruktural::STATUS_DITOLAK,
            'ids' => [$item->id],
            'catatan_validasi' => 'Tanggal selesai belum sesuai SK',
        ])->assertRedirect();

        $item->refresh();
        $this->assertSame(RiwayatJabatanStruktural::STATUS_DITOLAK, $item->status);
        $this->assertSame('Tanggal selesai belum sesuai SK', $item->catatan_validasi);
    }
}
