<?php

namespace Tests\Feature;

use App\Models\RiwayatHubunganKerja;
use App\Models\UnitKerja;
use App\Models\User;
use Database\Seeders\HubunganKerjaSeeder;
use Database\Seeders\KeluargaPegawaiSeeder;
use Database\Seeders\KlasifikasiSdmSeeder;
use Database\Seeders\MonitoringValidasiSeeder;
use Database\Seeders\RiwayatHubunganKerjaSeeder;
use Database\Seeders\StatusAktifSeeder;
use Database\Seeders\UnitKerjaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidasiHubunganKerjaTest extends TestCase
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
        $this->seed(StatusAktifSeeder::class);
        $this->seed(HubunganKerjaSeeder::class);
        $this->seed(KlasifikasiSdmSeeder::class);
        $this->seed(MonitoringValidasiSeeder::class);
        $this->seed(KeluargaPegawaiSeeder::class);
        $this->seed(RiwayatHubunganKerjaSeeder::class);
    }

    public function test_validasi_hubungan_kerja_index_and_datatable_filters(): void
    {
        $this->seedDependencies();
        $this->actingAsAdmin();

        $this->get(route('validasi-hubungan-kerja.index'))
            ->assertOk()
            ->assertSee('Riwayat Hubungan Kerja Pegawai')
            ->assertSee('LaravelDataTables["validasi-hubungan-kerja-table"]', false);

        // Default status: diajukan (3 seeded records)
        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('validasi-hubungan-kerja.index', [
                'draw' => 1,
                'status' => 'diajukan',
            ]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 3);

        // Filter by FST + diajukan (2 seeded records under 26201)
        $unitFst = UnitKerja::where('kode', 'FST')->firstOrFail();
        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('validasi-hubungan-kerja.index', [
                'draw' => 1,
                'unit_kerja_id' => $unitFst->id,
                'status' => 'diajukan',
            ]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 2);
    }

    public function test_can_show_approve_and_reject_validasi_hubungan_kerja(): void
    {
        $this->seedDependencies();
        $admin = $this->actingAsAdmin();

        $item = RiwayatHubunganKerja::where('status', RiwayatHubunganKerja::STATUS_DIAJUKAN)->firstOrFail();

        $this->get(route('validasi-hubungan-kerja.show', $item))
            ->assertOk()
            ->assertSee($item->nomor_sk);

        // Approve
        $this->post(route('validasi-hubungan-kerja.bulk'), [
            'action' => RiwayatHubunganKerja::STATUS_DISETUJUI,
            'ids' => [$item->id],
            'catatan_validasi' => 'SK Hubungan Kerja valid',
        ])->assertRedirect();

        $item->refresh();
        $this->assertSame(RiwayatHubunganKerja::STATUS_DISETUJUI, $item->status);
        $this->assertSame($admin->id, $item->validated_by);
        $this->assertNotNull($item->validated_at);

        // Reject
        $this->post(route('validasi-hubungan-kerja.bulk'), [
            'action' => RiwayatHubunganKerja::STATUS_DITOLAK,
            'ids' => [$item->id],
            'catatan_validasi' => 'SK belum distempel',
        ])->assertRedirect();

        $item->refresh();
        $this->assertSame(RiwayatHubunganKerja::STATUS_DITOLAK, $item->status);
        $this->assertSame('SK belum distempel', $item->catatan_validasi);
    }
}
