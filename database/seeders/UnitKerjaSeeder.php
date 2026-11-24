<?php

namespace Database\Seeders;

use App\Models\UnitKerja;
use Illuminate\Database\Seeder;

class UnitKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $uis = UnitKerja::updateOrCreate(
            ['kode' => 'UIS'],
            ['nama' => 'Universitas Ibnu Sina', 'jenis' => 'universitas', 'parent_id' => null, 'urutan' => 1]
        );

        // Level 2: Rektorat, Lembaga, Biro, Fakultas di bawah Universitas
        $level2 = [
            ['kode' => 'REK', 'nama' => 'Rektorat Universitas Ibnu Sina', 'jenis' => 'unit', 'urutan' => 2],
            ['kode' => 'LPTI', 'nama' => 'Lembaga Pengembangan Teknologi Informasi (LPTI)', 'jenis' => 'lembaga', 'urutan' => 3],
            ['kode' => 'LPPM', 'nama' => 'Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM)', 'jenis' => 'lembaga', 'urutan' => 4],
            ['kode' => 'LPMI', 'nama' => 'Lembaga Penjaminan Mutu Internal (LPMI)', 'jenis' => 'lembaga', 'urutan' => 5],
            ['kode' => 'BKAKP', 'nama' => 'Biro Kemahasiswaan, Alumni, Kerjasama, Perencanaan & Pengembangan', 'jenis' => 'biro', 'urutan' => 6],
            ['kode' => 'FEB', 'nama' => 'Fakultas Ekonomi dan Bisnis', 'jenis' => 'fakultas', 'urutan' => 7],
            ['kode' => 'FT', 'nama' => 'Fakultas Teknik', 'jenis' => 'fakultas', 'urutan' => 8],
            ['kode' => 'FIK', 'nama' => 'Fakultas Ilmu Kesehatan', 'jenis' => 'fakultas', 'urutan' => 9],
        ];

        $parents = [];
        foreach ($level2 as $unit) {
            $parents[$unit['kode']] = UnitKerja::updateOrCreate(
                ['kode' => $unit['kode']],
                [
                    'nama' => $unit['nama'],
                    'jenis' => $unit['jenis'],
                    'parent_id' => $uis->id,
                    'urutan' => $unit['urutan'],
                ]
            );
        }

        // Level 3: Program Studi di bawah masing-masing Fakultas
        $prodis = [
            ['kode' => 'S2-MM', 'nama' => 'Prodi Pascasarjana Magister Manajemen', 'parent' => 'FEB', 'urutan' => 10],
            ['kode' => 'S1-MNJ', 'nama' => 'Prodi S1 Manajemen', 'parent' => 'FEB', 'urutan' => 11],
            ['kode' => 'S1-AKT', 'nama' => 'Prodi S1 Akuntansi', 'parent' => 'FEB', 'urutan' => 12],
            ['kode' => 'S1-TI', 'nama' => 'Prodi Teknik Industri', 'parent' => 'FT', 'urutan' => 13],
            ['kode' => 'S1-TIF', 'nama' => 'Prodi Teknik Informatika', 'parent' => 'FT', 'urutan' => 14],
            ['kode' => 'S1-SI', 'nama' => 'Prodi Sistem Informasi', 'parent' => 'FT', 'urutan' => 15],
            ['kode' => 'S1-TLP', 'nama' => 'Prodi Teknik Logistik dan Perkapalan', 'parent' => 'FT', 'urutan' => 16],
            ['kode' => 'S1-K3', 'nama' => 'Prodi Keselamatan dan Kesehatan Kerja (K3)', 'parent' => 'FIK', 'urutan' => 17],
            ['kode' => 'S1-KESL', 'nama' => 'Prodi Kesehatan Lingkungan (Kesling)', 'parent' => 'FIK', 'urutan' => 18],
        ];

        foreach ($prodis as $prodi) {
            UnitKerja::updateOrCreate(
                ['kode' => $prodi['kode']],
                [
                    'nama' => $prodi['nama'],
                    'jenis' => 'prodi',
                    'parent_id' => $parents[$prodi['parent']]->id,
                    'urutan' => $prodi['urutan'],
                ]
            );
        }
    }
}
