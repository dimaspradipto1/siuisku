<?php

namespace Tests\Feature;

use App\Models\RiwayatPekerjaan;
use App\Models\UnitKerja;
use App\Models\User;
use Database\Seeders\HubunganKerjaSeeder;
use Database\Seeders\KeluargaPegawaiSeeder;
use Database\Seeders\KlasifikasiSdmSeeder;
use Database\Seeders\MonitoringValidasiSeeder;
use Database\Seeders\RiwayatPekerjaanSeeder;
use Database\Seeders\StatusAktifSeeder;
use Database\Seeders\UnitKerjaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidasiRiwayatPekerjaanTest extends TestCase
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
        $this->seed(RiwayatPekerjaanSeeder::class);
    }

    public function test_validasi_riwayat_pekerjaan_index_and_datatable_filters(): void
    {
        $this->seedDependencies();
        $this->actingAsAdmin();

        $this->get(route('validasi-riwayat-pekerjaan.index'))
            ->assertOk()
            ->assertSee('Riwayat Pekerjaan Pegawai')
            ->assertSee('LaravelDataTables["validasi-riwayat-pekerjaan-table"]', false);

        // Default status: diajukan (3 seeded records)
        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('validasi-riwayat-pekerjaan.index', [
                'draw' => 1,
                'status' => 'diajukan',
            ]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 3);

        // Filter by FST + diajukan (2 seeded records under 26201)
        $unitFst = UnitKerja::where('kode', 'FST')->firstOrFail();
        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('validasi-riwayat-pekerjaan.index', [
                'draw' => 1,
                'unit_kerja_id' => $unitFst->id,
                'status' => 'diajukan',
            ]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 2);
    }

    public function test_can_show_approve_reject_and_defer_validasi_riwayat_pekerjaan(): void
    {
        $this->seedDependencies();
        $admin = $this->actingAsAdmin();

        $item = RiwayatPekerjaan::where('status', RiwayatPekerjaan::STATUS_DIAJUKAN)->firstOrFail();

        $this->get(route('validasi-riwayat-pekerjaan.show', $item))
            ->assertOk()
            ->assertSee($item->nama_instansi);

        // Approve
        $this->post(route('validasi-riwayat-pekerjaan.bulk'), [
            'action' => RiwayatPekerjaan::STATUS_DISETUJUI,
            'ids' => [$item->id],
            'catatan_validasi' => 'Surat pengalaman kerja valid',
        ])->assertRedirect();

        $item->refresh();
        $this->assertSame(RiwayatPekerjaan::STATUS_DISETUJUI, $item->status);
        $this->assertSame($admin->id, $item->validated_by);
        $this->assertNotNull($item->validated_at);

        // Defer (Tangguhkan)
        $this->post(route('validasi-riwayat-pekerjaan.bulk'), [
            'action' => RiwayatPekerjaan::STATUS_DITANGGUHKAN,
            'ids' => [$item->id],
            'catatan_validasi' => 'Lengkapi stempel instansi',
        ])->assertRedirect();

        $item->refresh();
        $this->assertSame(RiwayatPekerjaan::STATUS_DITANGGUHKAN, $item->status);
        $this->assertSame('Lengkapi stempel instansi', $item->catatan_validasi);
    }
}
