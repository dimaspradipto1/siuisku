<?php

namespace Tests\Feature;

use App\Models\RiwayatJabatanFungsional;
use App\Models\UnitKerja;
use App\Models\User;
use Database\Seeders\HubunganKerjaSeeder;
use Database\Seeders\JabatanFungsionalSeeder;
use Database\Seeders\KeluargaPegawaiSeeder;
use Database\Seeders\KlasifikasiSdmSeeder;
use Database\Seeders\MonitoringValidasiSeeder;
use Database\Seeders\PangkatSeeder;
use Database\Seeders\RiwayatJabatanFungsionalSeeder;
use Database\Seeders\StatusAktifSeeder;
use Database\Seeders\UnitKerjaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidasiJabatanFungsionalTest extends TestCase
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
        $this->seed(PangkatSeeder::class);
        $this->seed(KlasifikasiSdmSeeder::class);
        $this->seed(JabatanFungsionalSeeder::class);
        $this->seed(MonitoringValidasiSeeder::class);
        $this->seed(KeluargaPegawaiSeeder::class);
        $this->seed(RiwayatJabatanFungsionalSeeder::class);
    }

    public function test_validasi_jabatan_fungsional_index_and_datatable_filters(): void
    {
        $this->seedDependencies();
        $this->actingAsAdmin();

        $this->get(route('validasi-jabatan-fungsional.index'))
            ->assertOk()
            ->assertSee('Riwayat Jabatan Fungsional')
            ->assertSee('LaravelDataTables["validasi-jafung-table"]', false);

        // Default status: diajukan (3 seeded records)
        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('validasi-jabatan-fungsional.index', [
                'draw' => 1,
                'status' => 'diajukan',
            ]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 3);

        // Filter by FST + diajukan (2 seeded records under 26201)
        $unitFst = UnitKerja::where('kode', 'FST')->firstOrFail();
        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('validasi-jabatan-fungsional.index', [
                'draw' => 1,
                'unit_kerja_id' => $unitFst->id,
                'status' => 'diajukan',
            ]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 2);
    }

    public function test_can_show_approve_and_reject_validasi_jabatan_fungsional(): void
    {
        $this->seedDependencies();
        $admin = $this->actingAsAdmin();

        $item = RiwayatJabatanFungsional::where('status', RiwayatJabatanFungsional::STATUS_DIAJUKAN)->firstOrFail();

        $this->get(route('validasi-jabatan-fungsional.show', $item))
            ->assertOk()
            ->assertSee($item->nomor_sk);

        // Approve
        $this->post(route('validasi-jabatan-fungsional.bulk'), [
            'action' => RiwayatJabatanFungsional::STATUS_DISETUJUI,
            'ids' => [$item->id],
            'catatan_validasi' => 'SK Jafung sesuai',
        ])->assertRedirect();

        $item->refresh();
        $this->assertSame(RiwayatJabatanFungsional::STATUS_DISETUJUI, $item->status);
        $this->assertSame($admin->id, $item->validated_by);
        $this->assertNotNull($item->validated_at);

        // Reject
        $this->post(route('validasi-jabatan-fungsional.bulk'), [
            'action' => RiwayatJabatanFungsional::STATUS_DITOLAK,
            'ids' => [$item->id],
            'catatan_validasi' => 'Lampiran PAK belum diunggah',
        ])->assertRedirect();

        $item->refresh();
        $this->assertSame(RiwayatJabatanFungsional::STATUS_DITOLAK, $item->status);
        $this->assertSame('Lampiran PAK belum diunggah', $item->catatan_validasi);
    }
}
