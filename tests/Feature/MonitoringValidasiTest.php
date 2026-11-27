<?php

namespace Tests\Feature;

use App\Models\JenisRiwayat;
use App\Models\Pegawai;
use App\Models\RiwayatPegawai;
use App\Models\UnitKerja;
use App\Models\User;
use Database\Seeders\HubunganKerjaSeeder;
use Database\Seeders\MonitoringValidasiSeeder;
use Database\Seeders\StatusAktifSeeder;
use Database\Seeders\UnitKerjaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MonitoringValidasiTest extends TestCase
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

    public function test_monitoring_validasi_index_and_filters_render(): void
    {
        $this->seed(UnitKerjaSeeder::class);
        $this->seed(StatusAktifSeeder::class);
        $this->seed(HubunganKerjaSeeder::class);
        $this->seed(MonitoringValidasiSeeder::class);
        $this->actingAsAdmin();

        $unitFt = UnitKerja::where('kode', 'FST')->firstOrFail();
        $pegawai = Pegawai::where('nip', '1018116701')->firstOrFail();

        $this->get(route('monitoring-validasi.index'))
            ->assertOk()
            ->assertSee('Data Riwayat Pegawai')
            ->assertSee('Keluarga')
            ->assertSee('Pelaksanaan Penelitian')
            ->assertSee('Pasangan');

        // Filter by parent unit FT (includes S1-TI, S1-TIF, S1-SI)
        $this->get(route('monitoring-validasi.index', ['unit_kerja_id' => $unitFt->id]))
            ->assertOk()
            ->assertSee($pegawai->nama);

        // Filter by specific pegawai
        $this->get(route('monitoring-validasi.index', [
            'unit_kerja_id' => $unitFt->id,
            'pegawai_id' => $pegawai->id,
        ]))->assertOk();
    }

    public function test_monitoring_show_and_bulk_validation_actions(): void
    {
        $this->seed(UnitKerjaSeeder::class);
        $this->seed(StatusAktifSeeder::class);
        $this->seed(HubunganKerjaSeeder::class);
        $this->seed(MonitoringValidasiSeeder::class);
        $admin = $this->actingAsAdmin();

        $jenisPendidikan = JenisRiwayat::where('kode', 'pendidikan')->firstOrFail();
        $pending = RiwayatPegawai::where('jenis_riwayat_id', $jenisPendidikan->id)
            ->where('status', RiwayatPegawai::STATUS_DIAJUKAN)
            ->firstOrFail();

        // Show detail page
        $this->get(route('monitoring-validasi.show', $jenisPendidikan))
            ->assertOk()
            ->assertSee($pending->judul);

        // Bulk approve from index by jenis_riwayat_ids
        $this->post(route('monitoring-validasi.bulk'), [
            'action' => RiwayatPegawai::STATUS_DISETUJUI,
            'jenis_riwayat_ids' => [$jenisPendidikan->id],
        ])->assertRedirect();

        $pending->refresh();
        $this->assertSame(RiwayatPegawai::STATUS_DISETUJUI, $pending->status);
        $this->assertSame($admin->id, $pending->validated_by);
        $this->assertNotNull($pending->validated_at);

        // Bulk defer from detail by riwayat_ids
        $this->post(route('monitoring-validasi.bulk'), [
            'action' => RiwayatPegawai::STATUS_DITANGGUHKAN,
            'riwayat_ids' => [$pending->id],
            'catatan_validasi' => 'Lengkapi berkas ijazah',
        ])->assertRedirect();

        $pending->refresh();
        $this->assertSame(RiwayatPegawai::STATUS_DITANGGUHKAN, $pending->status);
        $this->assertSame('Lengkapi berkas ijazah', $pending->catatan_validasi);
    }
}
