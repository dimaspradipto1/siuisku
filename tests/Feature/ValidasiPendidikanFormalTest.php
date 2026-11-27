<?php

namespace Tests\Feature;

use App\Models\RiwayatPendidikanFormal;
use App\Models\UnitKerja;
use App\Models\User;
use Database\Seeders\GelarAkademikSeeder;
use Database\Seeders\HubunganKerjaSeeder;
use Database\Seeders\JenjangPendidikanSeeder;
use Database\Seeders\KeluargaPegawaiSeeder;
use Database\Seeders\KlasifikasiSdmSeeder;
use Database\Seeders\MonitoringValidasiSeeder;
use Database\Seeders\PerguruanTinggiSeeder;
use Database\Seeders\RiwayatPendidikanFormalSeeder;
use Database\Seeders\StatusAktifSeeder;
use Database\Seeders\UnitKerjaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidasiPendidikanFormalTest extends TestCase
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
        $this->seed(JenjangPendidikanSeeder::class);
        $this->seed(PerguruanTinggiSeeder::class);
        $this->seed(GelarAkademikSeeder::class);
        $this->seed(MonitoringValidasiSeeder::class);
        $this->seed(KeluargaPegawaiSeeder::class);
        $this->seed(RiwayatPendidikanFormalSeeder::class);
    }

    public function test_validasi_pendidikan_formal_index_and_datatable_filters(): void
    {
        $this->seedDependencies();
        $this->actingAsAdmin();

        $this->get(route('validasi-pendidikan-formal.index'))
            ->assertOk()
            ->assertSee('Pendidikan Formal Pegawai')
            ->assertSee('LaravelDataTables["validasi-pendidikan-formal-table"]', false);

        // Default status: diajukan (3 seeded records)
        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('validasi-pendidikan-formal.index', [
                'draw' => 1,
                'status' => 'diajukan',
            ]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 3);

        // Filter by FST + diajukan (2 seeded records under 26201)
        $unitFst = UnitKerja::where('kode', 'FST')->firstOrFail();
        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('validasi-pendidikan-formal.index', [
                'draw' => 1,
                'unit_kerja_id' => $unitFst->id,
                'status' => 'diajukan',
            ]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 2);
    }

    public function test_can_show_approve_reject_and_defer_validasi_pendidikan_formal(): void
    {
        $this->seedDependencies();
        $admin = $this->actingAsAdmin();

        $item = RiwayatPendidikanFormal::where('status', RiwayatPendidikanFormal::STATUS_DIAJUKAN)->firstOrFail();

        $this->get(route('validasi-pendidikan-formal.show', $item))
            ->assertOk()
            ->assertSee($item->no_ijazah);

        // Approve
        $this->post(route('validasi-pendidikan-formal.bulk'), [
            'action' => RiwayatPendidikanFormal::STATUS_DISETUJUI,
            'ids' => [$item->id],
            'catatan_validasi' => 'Ijazah terverifikasi',
        ])->assertRedirect();

        $item->refresh();
        $this->assertSame(RiwayatPendidikanFormal::STATUS_DISETUJUI, $item->status);
        $this->assertSame($admin->id, $item->validated_by);
        $this->assertNotNull($item->validated_at);

        // Defer (Tangguhkan)
        $this->post(route('validasi-pendidikan-formal.bulk'), [
            'action' => RiwayatPendidikanFormal::STATUS_DITANGGUHKAN,
            'ids' => [$item->id],
            'catatan_validasi' => 'Lengkapi legalisir transkrip',
        ])->assertRedirect();

        $item->refresh();
        $this->assertSame(RiwayatPendidikanFormal::STATUS_DITANGGUHKAN, $item->status);
        $this->assertSame('Lengkapi legalisir transkrip', $item->catatan_validasi);
    }
}
