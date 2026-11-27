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
        $uis = $this->upsertUnit('171033', 'UIS', [
            'nama' => 'Universitas Ibnu Sina',
            'jenis' => 'universitas',
            'parent_id' => null,
            'urutan' => 1,
        ]);

        // Level 2: Rektorat, Lembaga, Biro, Fakultas di bawah Universitas
        $level2 = [
            ['kode' => 'REK', 'old' => null, 'nama' => 'Rektorat Universitas Ibnu Sina', 'jenis' => 'unit', 'urutan' => 2],
            ['kode' => 'LPTI', 'old' => null, 'nama' => 'Lembaga Pengembangan Teknologi Informasi (LPTI)', 'jenis' => 'lembaga', 'urutan' => 3],
            ['kode' => 'LPPM', 'old' => null, 'nama' => 'Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM)', 'jenis' => 'lembaga', 'urutan' => 4],
            ['kode' => 'LPMI', 'old' => null, 'nama' => 'Lembaga Penjaminan Mutu Internal (LPMI)', 'jenis' => 'lembaga', 'urutan' => 5],
            ['kode' => 'BKAKP', 'old' => null, 'nama' => 'Biro Kemahasiswaan, Alumni, Kerjasama, Perencanaan & Pengembangan', 'jenis' => 'biro', 'urutan' => 6],
            ['kode' => 'FEB', 'old' => null, 'nama' => 'Fakultas Ekonomi dan Bisnis', 'jenis' => 'fakultas', 'urutan' => 7],
            ['kode' => 'FST', 'old' => 'FT', 'nama' => 'Fakultas Sains dan Teknologi', 'jenis' => 'fakultas', 'urutan' => 8],
            ['kode' => 'FIK', 'old' => null, 'nama' => 'Fakultas Ilmu Kesehatan', 'jenis' => 'fakultas', 'urutan' => 9],
        ];

        $parents = [];
        foreach ($level2 as $unit) {
            $parents[$unit['kode']] = $this->upsertUnit($unit['kode'], $unit['old'], [
                'nama' => $unit['nama'],
                'jenis' => $unit['jenis'],
                'parent_id' => $uis->id,
                'urutan' => $unit['urutan'],
            ]);
        }

        // Level 3: Program Studi dengan kode resmi PDDikti / SEVIMA
        $prodis = [
            ['kode' => '61101', 'old' => 'S2-MM', 'nama' => 'S2 - Magister Manajemen', 'parent' => 'FEB', 'urutan' => 10],
            ['kode' => '61201', 'old' => 'S1-MNJ', 'nama' => 'S1 - Manajemen', 'parent' => 'FEB', 'urutan' => 11],
            ['kode' => '62201', 'old' => 'S1-AKT', 'nama' => 'S1 - Akuntansi', 'parent' => 'FEB', 'urutan' => 12],
            ['kode' => '26201', 'old' => 'S1-TI', 'nama' => 'S1 - Teknik Industri', 'parent' => 'FST', 'urutan' => 13],
            ['kode' => '55201', 'old' => 'S1-TIF', 'nama' => 'S1 - Teknik Informatika', 'parent' => 'FST', 'urutan' => 14],
            ['kode' => '57201', 'old' => 'S1-SI', 'nama' => 'S1 - Sistem Informasi', 'parent' => 'FST', 'urutan' => 15],
            ['kode' => '36201', 'old' => 'S1-TLP', 'nama' => 'S1 - Teknik Perkapalan', 'parent' => 'FST', 'urutan' => 16],
            ['kode' => '63315', 'old' => null, 'nama' => 'S1 - Teknik Logistik', 'parent' => 'FST', 'urutan' => 17],
            ['kode' => '13261', 'old' => 'S1-K3', 'nama' => 'S1 - Keselamatan dan Kesehatan Kerja', 'parent' => 'FIK', 'urutan' => 18],
            ['kode' => '13251', 'old' => 'S1-KESL', 'nama' => 'S1 - Kesehatan Lingkungan', 'parent' => 'FIK', 'urutan' => 19],
        ];

        foreach ($prodis as $prodi) {
            $this->upsertUnit($prodi['kode'], $prodi['old'], [
                'nama' => $prodi['nama'],
                'jenis' => 'prodi',
                'parent_id' => $parents[$prodi['parent']]->id,
                'urutan' => $prodi['urutan'],
            ]);
        }
    }

    /**
     * Update existing record in-place (preserving ID and foreign keys) when migrating legacy codes to PDDikti codes.
     */
    private function upsertUnit(string $kode, ?string $legacyKode, array $attributes): UnitKerja
    {
        $unit = UnitKerja::where('kode', $kode)->first();

        if (! $unit && $legacyKode) {
            $unit = UnitKerja::where('kode', $legacyKode)->first();
        }

        if ($unit) {
            $unit->update(array_merge(['kode' => $kode], $attributes));

            return $unit;
        }

        return UnitKerja::create(array_merge(['kode' => $kode], $attributes));
    }
}
