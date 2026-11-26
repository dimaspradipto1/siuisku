<?php

namespace Database\Seeders;

use App\Models\JenjangPendidikan;
use Illuminate\Database\Seeder;

class JenjangPendidikanSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['kode' => 'SD', 'nama' => 'Sekolah Dasar', 'nama_en' => 'Elementary School', 'urutan' => 1, 'pt' => false, 'pasca' => false],
            ['kode' => 'SMP', 'nama' => 'SMP/ Sederajat', 'nama_en' => 'Junior High School', 'urutan' => 2, 'pt' => false, 'pasca' => false],
            ['kode' => 'SMA', 'nama' => 'SMA/SMK Sederajat', 'nama_en' => 'Senior High School', 'urutan' => 3, 'pt' => false, 'pasca' => false],
            ['kode' => 'D1', 'nama' => 'Diploma 1', 'nama_en' => 'Diploma 1', 'urutan' => 4, 'pt' => true, 'pasca' => false],
            ['kode' => 'D2', 'nama' => 'Diploma 2', 'nama_en' => 'Diploma 2', 'urutan' => 5, 'pt' => true, 'pasca' => false],
            ['kode' => 'D3', 'nama' => 'Diploma 3', 'nama_en' => 'Diploma 3', 'urutan' => 6, 'pt' => true, 'pasca' => false],
            ['kode' => 'D4', 'nama' => 'Diploma 4', 'nama_en' => 'Applied Bachelor', 'urutan' => 7, 'pt' => true, 'pasca' => false],
            ['kode' => 'S1', 'nama' => 'Strata 1', 'nama_en' => 'Bachelor Degree', 'urutan' => 8, 'pt' => true, 'pasca' => false],
            ['kode' => 'Prof', 'nama' => 'Profesi', 'nama_en' => 'Professional Degree', 'urutan' => 9, 'pt' => true, 'pasca' => true],
            ['kode' => 'S2', 'nama' => 'Strata 2', 'nama_en' => 'Master Degree', 'urutan' => 10, 'pt' => true, 'pasca' => true],
            ['kode' => 'MTr', 'nama' => 'S2 Terapan', 'nama_en' => 'Applied Master', 'urutan' => 11, 'pt' => true, 'pasca' => true],
            ['kode' => 'Sp-1', 'nama' => 'Spesialis 1', 'nama_en' => 'Specialist 1', 'urutan' => 12, 'pt' => true, 'pasca' => true],
            ['kode' => 'S3', 'nama' => 'Strata 3', 'nama_en' => 'Doctoral Degree', 'urutan' => 13, 'pt' => true, 'pasca' => true],
            ['kode' => 'DTr', 'nama' => 'S3 Terapan', 'nama_en' => 'Applied Doctorate', 'urutan' => 14, 'pt' => true, 'pasca' => true],
            ['kode' => 'Sp-2', 'nama' => 'Spesialis 2', 'nama_en' => 'Specialist 2', 'urutan' => 15, 'pt' => true, 'pasca' => true],
            ['kode' => 'PAUD', 'nama' => 'PAUD', 'nama_en' => null, 'urutan' => 16, 'pt' => false, 'pasca' => false],
            ['kode' => 'TK', 'nama' => 'TK', 'nama_en' => 'Kindergarten', 'urutan' => 17, 'pt' => false, 'pasca' => false],
            ['kode' => 'PSD', 'nama' => 'PUTUS SD', 'nama_en' => null, 'urutan' => 18, 'pt' => false, 'pasca' => false],
            ['kode' => 'PK-A', 'nama' => 'PAKET A', 'nama_en' => null, 'urutan' => 19, 'pt' => false, 'pasca' => false],
            ['kode' => 'PK-B', 'nama' => 'PAKET B', 'nama_en' => null, 'urutan' => 20, 'pt' => false, 'pasca' => false],
            ['kode' => 'PK-C', 'nama' => 'PAKET C', 'nama_en' => null, 'urutan' => 21, 'pt' => false, 'pasca' => false],
            ['kode' => 'NON F', 'nama' => 'NON FORMAL', 'nama_en' => null, 'urutan' => 22, 'pt' => false, 'pasca' => false],
            ['kode' => 'IF', 'nama' => 'INFORMAL', 'nama_en' => null, 'urutan' => 23, 'pt' => false, 'pasca' => false],
            ['kode' => 'L', 'nama' => 'LAINNYA', 'nama_en' => null, 'urutan' => 24, 'pt' => false, 'pasca' => false],
            ['kode' => 'TS', 'nama' => 'TIDAK SEKOLAH', 'nama_en' => null, 'urutan' => 25, 'pt' => false, 'pasca' => false],
        ];

        foreach ($items as $item) {
            JenjangPendidikan::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'nama_en' => $item['nama_en'],
                    'urutan' => $item['urutan'],
                    'is_perguruan_tinggi' => $item['pt'],
                    'is_pasca_sarjana' => $item['pasca'],
                ],
            );
        }
    }
}
