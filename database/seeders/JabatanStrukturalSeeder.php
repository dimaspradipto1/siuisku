<?php

namespace Database\Seeders;

use App\Models\JabatanStruktural;
use App\Models\JenisJabatanStruktural;
use App\Models\UnitKerja;
use Illuminate\Database\Seeder;

class JabatanStrukturalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitUis = UnitKerja::whereIn('kode', ['171033', 'UIS'])->first();
        $unitLpti = UnitKerja::where('kode', 'LPTI')->first();
        $unitLppm = UnitKerja::where('kode', 'LPPM')->first();
        $unitLpmi = UnitKerja::where('kode', 'LPMI')->first();
        $unitBiro = UnitKerja::where('kode', 'BKAKP')->first();
        $unitFeb = UnitKerja::where('kode', 'FEB')->first();
        $unitFt = UnitKerja::whereIn('kode', ['FST', 'FT'])->first();
        $unitFik = UnitKerja::whereIn('kode', ['FIKES', 'FIK'])->first();

        $jenisMap = JenisJabatanStruktural::pluck('id', 'kode');

        $rektor = JabatanStruktural::updateOrCreate(
            ['kode' => 'R'],
            [
                'nama' => 'Rektor',
                'singkatan' => 'Rektor',
                'jenis_jabatan_struktural_id' => $jenisMap['R'] ?? null,
                'parent_id' => null,
                'unit_kerja_id' => $unitUis?->id,
                'is_pimpinan' => true,
                'is_active' => true,
                'urutan' => 1,
            ]
        );

        $wr1 = JabatanStruktural::updateOrCreate(
            ['kode' => 'WR1'],
            [
                'nama' => 'Wakil Rektor I',
                'singkatan' => 'WR I',
                'jenis_jabatan_struktural_id' => $jenisMap['WR1'] ?? null,
                'parent_id' => $rektor->id,
                'unit_kerja_id' => $unitUis?->id,
                'is_pimpinan' => true,
                'is_active' => true,
                'urutan' => 2,
            ]
        );

        $wr2 = JabatanStruktural::updateOrCreate(
            ['kode' => 'WR2'],
            [
                'nama' => 'Wakil Rektor II',
                'singkatan' => 'WR II',
                'jenis_jabatan_struktural_id' => $jenisMap['WR2'] ?? null,
                'parent_id' => $rektor->id,
                'unit_kerja_id' => $unitUis?->id,
                'is_pimpinan' => true,
                'is_active' => true,
                'urutan' => 3,
            ]
        );

        $wr3 = JabatanStruktural::updateOrCreate(
            ['kode' => 'WR3'],
            [
                'nama' => 'Wakil Rektor III',
                'singkatan' => 'WR III',
                'jenis_jabatan_struktural_id' => $jenisMap['WR3'] ?? null,
                'parent_id' => $rektor->id,
                'unit_kerja_id' => $unitUis?->id,
                'is_pimpinan' => true,
                'is_active' => true,
                'urutan' => 4,
            ]
        );

        $items = [
            ['kode' => 'DFB', 'nama' => 'Dekan Fakultas Ekonomi dan Bisnis', 'singkatan' => 'Dekan FEB', 'jenis' => 'DFB', 'parent' => $rektor->id, 'unit' => $unitFeb?->id, 'pimpinan' => true, 'urutan' => 5],
            ['kode' => 'DFT', 'nama' => 'Dekan Fakultas Teknik', 'singkatan' => 'Dekan FT', 'jenis' => 'DFT', 'parent' => $rektor->id, 'unit' => $unitFt?->id, 'pimpinan' => true, 'urutan' => 6],
            ['kode' => 'DFK', 'nama' => 'Dekan Fakultas Ilmu Kesehatan', 'singkatan' => 'Dekan FIKES', 'jenis' => 'DFK', 'parent' => $rektor->id, 'unit' => $unitFik?->id, 'pimpinan' => true, 'urutan' => 7],
            ['kode' => 'KLPTI', 'nama' => 'Kepala LPTI', 'singkatan' => 'Ka. LPTI', 'jenis' => 'KLPTI', 'parent' => $wr2->id, 'unit' => $unitLpti?->id, 'pimpinan' => true, 'urutan' => 8],
            ['kode' => 'KLPPM', 'nama' => 'Ka. LPPM', 'singkatan' => 'Ka. LPPM', 'jenis' => 'KLPPM', 'parent' => $wr1->id, 'unit' => $unitLppm?->id, 'pimpinan' => true, 'urutan' => 9],
            ['kode' => 'KLPMI', 'nama' => 'Ka. LPMI', 'singkatan' => 'Ka. LPMI', 'jenis' => 'KLPMI', 'parent' => $rektor->id, 'unit' => $unitLpmi?->id, 'pimpinan' => true, 'urutan' => 10],
            ['kode' => 'KBKAPP', 'nama' => 'Ka. Biro Kemahasiswaan, Alumni, Kerjasama, Perencanaan dan Pengembangan', 'singkatan' => 'Ka. Biro', 'jenis' => 'KBKAPP', 'parent' => $wr3->id, 'unit' => $unitBiro?->id, 'pimpinan' => true, 'urutan' => 11],
        ];

        foreach ($items as $item) {
            JabatanStruktural::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'singkatan' => $item['singkatan'],
                    'jenis_jabatan_struktural_id' => $jenisMap[$item['jenis']] ?? null,
                    'parent_id' => $item['parent'],
                    'unit_kerja_id' => $item['unit'],
                    'is_pimpinan' => $item['pimpinan'],
                    'is_active' => true,
                    'urutan' => $item['urutan'],
                ]
            );
        }
    }
}
