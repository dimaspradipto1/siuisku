<?php

namespace Database\Seeders;

use App\Models\JabatanFungsional;
use App\Models\KlasifikasiSdm;
use Illuminate\Database\Seeder;

class JabatanFungsionalSeeder extends Seeder
{
    public function run(): void
    {
        $dosenTetap = KlasifikasiSdm::where('kode', '1')->first();

        if (! $dosenTetap) {
            return;
        }

        $items = [
            ['kode' => '00', 'nama' => 'Tenaga Pengajar', 'angka_kredit' => 0, 'usia_pensiun' => 65, 'referensi_sister' => 'Tenaga Pengajar'],
            ['kode' => '31', 'nama' => 'Asisten Ahli (100)', 'angka_kredit' => 100, 'usia_pensiun' => 65, 'referensi_sister' => 'Asisten Ahli 100'],
            ['kode' => '32', 'nama' => 'Asisten Ahli (150)', 'angka_kredit' => 150, 'usia_pensiun' => 65, 'referensi_sister' => 'Asisten Ahli 150'],
            ['kode' => '33', 'nama' => 'Lektor (200)', 'angka_kredit' => 200, 'usia_pensiun' => 65, 'referensi_sister' => 'Lektor 200'],
            ['kode' => '34', 'nama' => 'Lektor (300)', 'angka_kredit' => 300, 'usia_pensiun' => 65, 'referensi_sister' => 'Lektor 300'],
            ['kode' => '41', 'nama' => 'Lektor Kepala (400)', 'angka_kredit' => 400, 'usia_pensiun' => 65, 'referensi_sister' => 'Lektor Kepala 400'],
            ['kode' => '42', 'nama' => 'Lektor Kepala (550)', 'angka_kredit' => 550, 'usia_pensiun' => 65, 'referensi_sister' => 'Lektor Kepala 550'],
            ['kode' => '43', 'nama' => 'Lektor Kepala (700)', 'angka_kredit' => 700, 'usia_pensiun' => 65, 'referensi_sister' => 'Lektor Kepala 700'],
            ['kode' => '44', 'nama' => 'Guru Besar (850)', 'angka_kredit' => 850, 'usia_pensiun' => 65, 'referensi_sister' => 'Profesor 850'],
            ['kode' => '45', 'nama' => 'Guru Besar (1050)', 'angka_kredit' => 1050, 'usia_pensiun' => 65, 'referensi_sister' => 'Profesor 1050'],
        ];

        foreach ($items as $item) {
            JabatanFungsional::updateOrCreate(
                [
                    'klasifikasi_sdm_id' => $dosenTetap->id,
                    'kode' => $item['kode'],
                ],
                [
                    'nama' => $item['nama'],
                    'angka_kredit' => $item['angka_kredit'],
                    'usia_pensiun' => $item['usia_pensiun'],
                    'referensi_sister' => $item['referensi_sister'],
                ],
            );
        }
    }
}
