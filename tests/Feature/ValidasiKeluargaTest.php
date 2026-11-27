<?php

namespace Tests\Feature;

use App\Models\KeluargaPegawai;
use App\Models\UnitKerja;
use App\Models\User;
use Database\Seeders\HubunganKerjaSeeder;
use Database\Seeders\KeluargaPegawaiSeeder;
use Database\Seeders\KlasifikasiSdmSeeder;
use Database\Seeders\MonitoringValidasiSeeder;
use Database\Seeders\StatusAktifSeeder;
use Database\Seeders\UnitKerjaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidasiKeluargaTest extends TestCase
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

    public function test_validasi_keluarga_index_and_datatable_filters(): void
    {
        $this->seed(UnitKerjaSeeder::class);
        $this->seed(StatusAktifSeeder::class);
        $this->seed(HubunganKerjaSeeder::class);
        $this->seed(KlasifikasiSdmSeeder::class);
        $this->seed(MonitoringValidasiSeeder::class);
        $this->seed(KeluargaPegawaiSeeder::class);
        $this->actingAsAdmin();

        $this->get(route('validasi-keluarga.index'))
            ->assertOk()
            ->assertSee('Data Pasangan')
            ->assertSee('LaravelDataTables["validasi-keluarga-table"]', false);

        // Default filter: pasangan + diajukan (2 seeded records)
        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('validasi-keluarga.index', [
                'draw' => 1,
                'hubungan' => 'pasangan',
                'status' => 'diajukan',
            ]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 2);

        // Filter by FST unit + anak + diajukan (1 seeded record)
        $unitFst = UnitKerja::where('kode', 'FST')->firstOrFail();
        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('validasi-keluarga.index', [
                'draw' => 1,
                'unit_kerja_id' => $unitFst->id,
                'hubungan' => 'anak',
                'status' => 'diajukan',
            ]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 1);
    }

    public function test_can_show_approve_and_reject_validasi_keluarga(): void
    {
        $this->seed(UnitKerjaSeeder::class);
        $this->seed(StatusAktifSeeder::class);
        $this->seed(HubunganKerjaSeeder::class);
        $this->seed(KlasifikasiSdmSeeder::class);
        $this->seed(MonitoringValidasiSeeder::class);
        $this->seed(KeluargaPegawaiSeeder::class);
        $admin = $this->actingAsAdmin();

        $item = KeluargaPegawai::where('hubungan', KeluargaPegawai::HUBUNGAN_PASANGAN)
            ->where('status', KeluargaPegawai::STATUS_DIAJUKAN)
            ->firstOrFail();

        // Detail view
        $this->get(route('validasi-keluarga.show', $item))
            ->assertOk()
            ->assertSee($item->nama);

        // Approve
        $this->post(route('validasi-keluarga.bulk'), [
            'action' => KeluargaPegawai::STATUS_DISETUJUI,
            'ids' => [$item->id],
            'catatan_validasi' => 'Berkas kartu nikah valid',
        ])->assertRedirect();

        $item->refresh();
        $this->assertSame(KeluargaPegawai::STATUS_DISETUJUI, $item->status);
        $this->assertSame('Berkas kartu nikah valid', $item->catatan_validasi);
        $this->assertSame($admin->id, $item->validated_by);
        $this->assertNotNull($item->validated_at);

        // Reject
        $this->post(route('validasi-keluarga.bulk'), [
            'action' => KeluargaPegawai::STATUS_DITOLAK,
            'ids' => [$item->id],
            'catatan_validasi' => 'Perlu scan ulang',
        ])->assertRedirect();

        $item->refresh();
        $this->assertSame(KeluargaPegawai::STATUS_DITOLAK, $item->status);
    }
}
