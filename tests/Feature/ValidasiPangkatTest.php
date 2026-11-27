<?php

namespace Tests\Feature;

use App\Models\RiwayatPangkat;
use App\Models\UnitKerja;
use App\Models\User;
use Database\Seeders\HubunganKerjaSeeder;
use Database\Seeders\JenisSkSeeder;
use Database\Seeders\KeluargaPegawaiSeeder;
use Database\Seeders\KlasifikasiSdmSeeder;
use Database\Seeders\MonitoringValidasiSeeder;
use Database\Seeders\PangkatSeeder;
use Database\Seeders\RiwayatPangkatSeeder;
use Database\Seeders\StatusAktifSeeder;
use Database\Seeders\UnitKerjaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidasiPangkatTest extends TestCase
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
        $this->seed(PangkatSeeder::class);
        $this->seed(JenisSkSeeder::class);
        $this->seed(MonitoringValidasiSeeder::class);
        $this->seed(KeluargaPegawaiSeeder::class);
        $this->seed(RiwayatPangkatSeeder::class);
    }

    public function test_validasi_pangkat_index_and_datatable_filters(): void
    {
        $this->seedDependencies();
        $this->actingAsAdmin();

        $this->get(route('validasi-pangkat.index'))
            ->assertOk()
            ->assertSee('Riwayat Pangkat')
            ->assertSee('LaravelDataTables["validasi-pangkat-table"]', false);

        // Default status: diajukan (3 seeded records)
        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('validasi-pangkat.index', [
                'draw' => 1,
                'status' => 'diajukan',
            ]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 3);

        // Filter by FST + diajukan (2 seeded records under 26201)
        $unitFst = UnitKerja::where('kode', 'FST')->firstOrFail();
        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('validasi-pangkat.index', [
                'draw' => 1,
                'unit_kerja_id' => $unitFst->id,
                'status' => 'diajukan',
            ]))
            ->assertOk()
            ->assertJsonPath('recordsTotal', 2);
    }

    public function test_can_show_approve_reject_and_defer_validasi_pangkat(): void
    {
        $this->seedDependencies();
        $admin = $this->actingAsAdmin();

        $item = RiwayatPangkat::where('status', RiwayatPangkat::STATUS_DIAJUKAN)->firstOrFail();

        $this->get(route('validasi-pangkat.show', $item))
            ->assertOk()
            ->assertSee($item->nomor_sk);

        // Approve
        $this->post(route('validasi-pangkat.bulk'), [
            'action' => RiwayatPangkat::STATUS_DISETUJUI,
            'ids' => [$item->id],
            'catatan_validasi' => 'SK Pangkat disetujui',
        ])->assertRedirect();

        $item->refresh();
        $this->assertSame(RiwayatPangkat::STATUS_DISETUJUI, $item->status);
        $this->assertSame($admin->id, $item->validated_by);
        $this->assertNotNull($item->validated_at);

        // Defer (Tangguhkan)
        $this->post(route('validasi-pangkat.bulk'), [
            'action' => RiwayatPangkat::STATUS_DITANGGUHKAN,
            'ids' => [$item->id],
            'catatan_validasi' => 'Menunggu SK asli',
        ])->assertRedirect();

        $item->refresh();
        $this->assertSame(RiwayatPangkat::STATUS_DITANGGUHKAN, $item->status);
        $this->assertSame('Menunggu SK asli', $item->catatan_validasi);
    }
}
