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
            ['nama' => 'Universitas Ibnu Sina', 'jenis' => 'universitas', 'parent_id' => null]
        );

        // Level 2: Lembaga, Biro, Fakultas di bawah Universitas
        $level2 = [
            ['kode' => 'REK', 'nama' => 'Rektorat Universitas Ibnu Sina', 'jenis' => 'unit'],
            ['kode' => 'LPTI', 'nama' => 'Lembaga Pengembangan Teknologi Informasi (LPTI)', 'jenis' => 'lembaga'],
            ['kode' => 'LPPM', 'nama' => 'Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM)', 'jenis' => 'lembaga'],
            ['kode' => 'LPMI', 'nama' => 'Lembaga Penjaminan Mutu Internal (LPMI)', 'jenis' => 'lembaga'],
            ['kode' => 'BKAKP', 'nama' => 'Biro Kemahasiswaan, Alumni, Kerjasama, Perencanaan & Pengembangan', 'jenis' => 'biro'],
            ['kode' => 'FEB', 'nama' => 'Fakultas Ekonomi dan Bisnis', 'jenis' => 'fakultas'],
            ['kode' => 'FT', 'nama' => 'Fakultas Teknik', 'jenis' => 'fakultas'],
            ['kode' => 'FIK', 'nama' => 'Fakultas Ilmu Kesehatan', 'jenis' => 'fakultas'],
        ];

        $parents = [];
        foreach ($level2 as $unit) {
            $parents[$unit['kode']] = UnitKerja::updateOrCreate(
                ['kode' => $unit['kode']],
                [
                    'nama' => $unit['nama'],
                    'jenis' => $unit['jenis'],
                    'parent_id' => $uis->id,
                ]
            );
        }

        // Level 3: Program Studi di bawah masing-masing Fakultas
        $prodis = [
            ['kode' => 'S1-MNJ', 'nama' => 'Prodi S1 Manajemen', 'parent' => 'FEB'],
            ['kode' => 'S1-AKT', 'nama' => 'Prodi S1 Akuntansi', 'parent' => 'FEB'],
            ['kode' => 'S2-MM', 'nama' => 'Prodi Pascasarjana Magister Manajemen', 'parent' => 'FEB'],
            ['kode' => 'S1-TI', 'nama' => 'Prodi Teknik Industri', 'parent' => 'FT'],
            ['kode' => 'S1-TIF', 'nama' => 'Prodi Teknik Informatika', 'parent' => 'FT'],
            ['kode' => 'S1-SI', 'nama' => 'Prodi Sistem Informasi', 'parent' => 'FT'],
            ['kode' => 'S1-TLP', 'nama' => 'Prodi Teknik Logistik dan Perkapalan', 'parent' => 'FT'],
            ['kode' => 'S1-K3', 'nama' => 'Prodi Keselamatan dan Kesehatan Kerja (K3)', 'parent' => 'FIK'],
            ['kode' => 'S1-KESL', 'nama' => 'Prodi Kesehatan Lingkungan (Kesling)', 'parent' => 'FIK'],
        ];

        foreach ($prodis as $prodi) {
            UnitKerja::updateOrCreate(
                ['kode' => $prodi['kode']],
                [
                    'nama' => $prodi['nama'],
                    'jenis' => 'prodi',
                    'parent_id' => $parents[$prodi['parent']]->id,
                ]
            );
        }
    }
}
