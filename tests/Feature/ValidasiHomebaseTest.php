<?php

namespace Tests\Feature;

use App\Models\RiwayatHomebase;
use App\Models\UnitKerja;
use App\Models\User;
use Database\Seeders\HubunganKerjaSeeder;
use Database\Seeders\KeluargaPegawaiSeeder;
use Database\Seeders\KlasifikasiSdmSeeder;
use Database\Seeders\MonitoringValidasiSeeder;
use Database\Seeders\RiwayatHomebaseSeeder;
use Database\Seeders\StatusAktifSeeder;
use Database\Seeders\UnitKerjaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidasiHomebaseTest extends TestCase
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

    public function test_validasi_homebase_index_and_datatable_filters(): void
    {
        $this->seed(UnitKerjaSeeder::class);
        $this->seed(StatusAktifSeeder::class);
        $this->seed(HubunganKerjaSeeder::class);
        $this->seed(KlasifikasiSdmSeeder::class);
        $this->seed(MonitoringValidasiSeeder::class);
        $this->seed(KeluargaPegawaiSeeder::class);
        $this->seed(RiwayatHomebaseSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('validasi-homebase.index'))
            ->assertOk()
            ->assertSee('Riwayat Homebase Pegawai')
            ->assertSee('LaravelDataTables["validasi-homebase-table"]', false);

        // Default status: diajukan (3 seeded records)
        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('validasi-homebase.index', [
                'draw' => 1,
                'status' => 'diajukan',
            ]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 3);

        // Filter by FST + diajukan (2 seeded records in 26201 under FST)
        $unitFst = UnitKerja::where('kode', 'FST')->firstOrFail();
        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('validasi-homebase.index', [
                'draw' => 1,
                'unit_kerja_id' => $unitFst->id,
                'status' => 'diajukan',
            ]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 2);
    }

    public function test_can_show_approve_and_reject_validasi_homebase(): void
    {
        $this->seed(UnitKerjaSeeder::class);
        $this->seed(StatusAktifSeeder::class);
        $this->seed(HubunganKerjaSeeder::class);
        $this->seed(KlasifikasiSdmSeeder::class);
        $this->seed(MonitoringValidasiSeeder::class);
        $this->seed(KeluargaPegawaiSeeder::class);
        $this->seed(RiwayatHomebaseSeeder::class);
        $admin = $this->actingAsAdmin();

        $item = RiwayatHomebase::where('status', RiwayatHomebase::STATUS_DIAJUKAN)->firstOrFail();

        $this->get(route('validasi-homebase.show', $item))
            ->assertOk()
            ->assertSee($item->nomor_sk);

        // Approve
        $this->post(route('validasi-homebase.bulk'), [
            'action' => RiwayatHomebase::STATUS_DISETUJUI,
            'ids' => [$item->id],
            'catatan_validasi' => 'SK Homebase sesuai',
        ])->assertRedirect();

        $item->refresh();
        $this->assertSame(RiwayatHomebase::STATUS_DISETUJUI, $item->status);
        $this->assertSame('SK Homebase sesuai', $item->catatan_validasi);
        $this->assertSame($admin->id, $item->validated_by);
        $this->assertNotNull($item->validated_at);

        // Reject
        $this->post(route('validasi-homebase.bulk'), [
            'action' => RiwayatHomebase::STATUS_DITOLAK,
            'ids' => [$item->id],
            'catatan_validasi' => 'Lampiran SK kurang jelas',
        ])->assertRedirect();

        $item->refresh();
        $this->assertSame(RiwayatHomebase::STATUS_DITOLAK, $item->status);
    }
}
