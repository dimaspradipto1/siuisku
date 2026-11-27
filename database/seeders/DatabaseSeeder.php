<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            UserSeeder::class,
            ModulSeeder::class,
            JenisJabatanStrukturalSeeder::class,
            UnitKerjaSeeder::class,
            HakAksesSeeder::class,
            JenisSkSeeder::class,
            JabatanStrukturalSeeder::class,
            StatusAktifSeeder::class,
            HubunganKerjaSeeder::class,
            PangkatSeeder::class,
            KlasifikasiSdmSeeder::class,
            JabatanFungsionalSeeder::class,
            RumpunBidangIlmuSeeder::class,
            GelarAkademikSeeder::class,
            JenisSertifikasiSeeder::class,
            JenisTesSeeder::class,
            JenisLuaranSeeder::class,
            NegaraSeeder::class,
            ProvinsiSeeder::class,
            KabupatenKotaSeeder::class,
            KecamatanSeeder::class,
            JenisKehadiranSeeder::class,
            JenisCutiSeeder::class,
            JenisIzinSeeder::class,
            JenisHariSeeder::class,
            HariLiburSeeder::class,
            JamKerjaSeeder::class,
            AgamaSeeder::class,
            BahasaSeeder::class,
            StatusPernikahanSeeder::class,
            JenjangPendidikanSeeder::class,
            PerguruanTinggiSeeder::class,
            ProdiPerguruanTinggiSeeder::class,
            BankSeeder::class,
            SukuSeeder::class,
            GolonganDarahSeeder::class,
            JenisValidasiSeeder::class,
            MonitoringValidasiSeeder::class,
            KeluargaPegawaiSeeder::class,
            RiwayatHomebaseSeeder::class,
            RiwayatPangkatSeeder::class,
            RiwayatKlasifikasiSdmSeeder::class,
            RiwayatJabatanFungsionalSeeder::class,
            RiwayatJabatanStrukturalSeeder::class,
            RiwayatHubunganKerjaSeeder::class,
            RiwayatPendidikanFormalSeeder::class,
            RiwayatDiklatSeeder::class,
        ]);
    }
}
