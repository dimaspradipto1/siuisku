<?php

namespace Database\Seeders;

use App\Models\JenisJabatanStruktural;
use Illuminate\Database\Seeder;

class JenisJabatanStrukturalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Urut dari jabatan tertinggi (Rektor) ke bawah sesuai hierarki OTK UIS
        $items = [
            // 1. Pimpinan Universitas
            ['kode' => 'R', 'nama' => 'Rektor'],
            ['kode' => 'WR1', 'nama' => 'Wakil Rektor I'],
            ['kode' => 'WR2', 'nama' => 'Wakil Rektor II'],
            ['kode' => 'WR3', 'nama' => 'Wakil Rektor III'],

            // 2. Dekan Fakultas
            ['kode' => 'DFB', 'nama' => 'Dekan Fakultas Ekonomi dan Bisnis'],
            ['kode' => 'DFT', 'nama' => 'Dekan Fakultas Teknik'],
            ['kode' => 'DFK', 'nama' => 'Dekan Fakultas Ilmu Kesehatan'],

            // 3. Kepala Lembaga & Biro
            ['kode' => 'KLPTI', 'nama' => 'Kepala LPTI'],
            ['kode' => 'KLPPM', 'nama' => 'Ka. LPPM'],
            ['kode' => 'KLPMI', 'nama' => 'Ka. LPMI'],
            ['kode' => 'KBKAPP', 'nama' => 'Ka. Biro Kemahasiswaan, Alumni, Kerjasama, Perencanaan dan Pengembangan'],

            // 4. Wakil Dekan I & II
            ['kode' => 'WFB1', 'nama' => 'Wakil Dekan I Fakultas Ekonomi dan Bisnis'],
            ['kode' => 'WFB2', 'nama' => 'Wakil Dekan II Fakultas Ekonomi dan Bisnis'],
            ['kode' => 'WFT1', 'nama' => 'Wakil Dekan I Fakultas Teknik'],
            ['kode' => 'WFT2', 'nama' => 'Wakil Dekan II Fakultas Teknik'],
            ['kode' => 'WFK1', 'nama' => 'Wakil Dekan I Fakultas Ilmu Kesehatan'],
            ['kode' => 'WFK2', 'nama' => 'Wakil Dekan II Fakultas Ilmu Kesehatan'],

            // 5. Ketua Program Studi
            ['kode' => 'KPMM', 'nama' => 'Ketua Program Studi Pascasarjana Magister Manajemen'],
            ['kode' => 'KPMNJ', 'nama' => 'Ketua Program Studi S1 Manajemen'],
            ['kode' => 'KPAKT', 'nama' => 'Ketua Program Studi S1 Akuntansi'],
            ['kode' => 'KPTI', 'nama' => 'Ketua Program Studi Teknik Industri'],
            ['kode' => 'KPTISI', 'nama' => 'Ketua Program Studi Teknik Informatika dan Prodi Sistem Informasi'],
            ['kode' => 'KPTLP', 'nama' => 'Ketua Program Studi Teknik Logistik dan Perkapalan'],
            ['kode' => 'KPK3', 'nama' => 'Ketua Program Studi K3'],
            ['kode' => 'KPKESL', 'nama' => 'Ketua Program Studi Kesling'],

            // 6. Sekretaris Program Studi
            ['kode' => 'SPMM', 'nama' => 'Sekretaris Prodi Pascasarjana Magister Manajemen'],
            ['kode' => 'SPMNJ', 'nama' => 'Sekretaris Prodi S1 Manajemen'],
            ['kode' => 'SPAKT', 'nama' => 'Sekretaris Prodi S1 Akuntansi'],
            ['kode' => 'SPTI', 'nama' => 'Sekretaris Program Studi Teknik Industri'],
            ['kode' => 'SPTISI', 'nama' => 'Sekretaris Program Studi Teknik Informatika dan Prodi Sistem Informasi'],
            ['kode' => 'SPTLP', 'nama' => 'Sekretaris Program Studi Teknik Logistik dan Perkapalan'],
            ['kode' => 'SPK3', 'nama' => 'Sekretaris Prodi K3'],
            ['kode' => 'SPKESL', 'nama' => 'Sekretaris Prodi Kesling'],

            // 7. Kepala Bidang, Divisi & Unit Penunjang
            ['kode' => 'KBHP', 'nama' => 'Kabid. Humas dan Publikasi'],
            ['kode' => 'KBKJS', 'nama' => 'Kabid. Kerjasama'],
            ['kode' => 'KBKMH', 'nama' => 'Kabid. Kemahasiswaan'],
            ['kode' => 'KBPKAK', 'nama' => 'Kabid. Pusat Karir, Alumni dan Kewirausahaan'],
            ['kode' => 'KBPP', 'nama' => 'Kabid. Perencanaan dan Pengembangan'],
            ['kode' => 'KBPEN', 'nama' => 'Kabid. Penelitian'],
            ['kode' => 'KBPKM', 'nama' => 'Kabid. Pengabdian Kepada Masyarakat'],
            ['kode' => 'KBHKI', 'nama' => 'Kabid. HAKI dan Publikasi'],
            ['kode' => 'KBSPMI', 'nama' => 'Kabid. Pengembangan SPMI dan SDM SPMI'],
            ['kode' => 'KBSOSPM', 'nama' => 'Kabid. Sosialisasi SPMI dan Kerjasama SPMI'],
            ['kode' => 'KBAKDOK', 'nama' => 'Kabid. Akreditasi dan Dokumentasi'],
            ['kode' => 'KBEAM', 'nama' => 'Kabid. Evaluasi dan Audit Mutu'],
            ['kode' => 'DPSI', 'nama' => 'Divisi Pengembangan Sistem Informasi dan Aplikasi - LPTI BAUK'],
            ['kode' => 'DIJT', 'nama' => 'Divisi Infrastruktur, Jaringan, dan Layanan Troubleshooting'],
            ['kode' => 'KUPPMFT', 'nama' => 'Ka. UPPM Fakultas Teknik'],
            ['kode' => 'KLABFT', 'nama' => 'Ka. Laboratorium Fakultas Teknik'],
            ['kode' => 'KHUPFT', 'nama' => 'Ka. Humas dan Publikasi FT'],

            // 8. UPMI, UPPM, GKM
            ['kode' => 'UPMIFB', 'nama' => 'UPMI Fakultas Ekonomi dan Bisnis'],
            ['kode' => 'UPPMFB', 'nama' => 'UPPM Fakultas Ekonomi dan Bisnis'],
            ['kode' => 'UPMIFT', 'nama' => 'UPMI Fakultas Teknik'],
            ['kode' => 'UPMIFK', 'nama' => 'UPMI Fakultas Ilmu Kesehatan'],
            ['kode' => 'GKMFK', 'nama' => 'GKM Fakultas Ilmu Kesehatan'],
            ['kode' => 'UPPMFK', 'nama' => 'UPPM Fakultas Ilmu Kesehatan'],

            // 9. Tata Usaha, Laboran & Staff
            ['kode' => 'TUFB1', 'nama' => 'Tata Usaha Sarjana Fakultas Ekonomi dan Bisnis'],
            ['kode' => 'TUFB2', 'nama' => 'Tata Usaha Pascasarjana Fakultas Ekonomi dan Bisnis'],
            ['kode' => 'TUFT', 'nama' => 'Tata Usaha Fakultas Teknik'],
            ['kode' => 'TUFK', 'nama' => 'Tata Usaha Fakultas Ilmu Kesehatan'],
            ['kode' => 'HUPFB', 'nama' => 'Humas dan Publikasi Web Fakultas Ekonomi dan Bisnis'],
            ['kode' => 'HUPFK', 'nama' => 'Humas dan Publikasi Web Fakultas Ilmu Kesehatan'],
            ['kode' => 'LABFK', 'nama' => 'Laboran Fakultas Ilmu Kesehatan'],
            ['kode' => 'STDOK', 'nama' => 'Staff Dokumentasi'],
            ['kode' => 'STHUM', 'nama' => 'Staff Humas'],
            ['kode' => 'STWEB', 'nama' => 'Staff Website'],
            ['kode' => 'STKJSI', 'nama' => 'Staff Kerjasama - Internasional'],
            ['kode' => 'STKJSN', 'nama' => 'Staff Kerjasama - Nasional'],
            ['kode' => 'STKMHKIP', 'nama' => 'Staff Kemahasiswaan - KIP'],
            ['kode' => 'STKMHOR', 'nama' => 'Staff Kemahasiswaan - Prestasi Olahraga'],
            ['kode' => 'STKMHSN', 'nama' => 'Staff Kemahasiswaan - Prestasi Seni'],
            ['kode' => 'STPKAK', 'nama' => 'Staff Pusat Karir, Alumni dan Kewirausahaan'],
            ['kode' => 'STPP', 'nama' => 'Staff Perencanaan dan Pengembangan'],
            ['kode' => 'STLPPM', 'nama' => 'Staff Administrasi Penelitian dan Pengabdian Kepada Masyarakat'],
            ['kode' => 'SUPPMFT', 'nama' => 'Staff UPPM Fakultas Teknik'],
            ['kode' => 'SLABTI', 'nama' => 'Staff Labor Teknik Industri'],
            ['kode' => 'SLABTK', 'nama' => 'Staff Labor Teknik Komputer'],
            ['kode' => 'SLABPP', 'nama' => 'Staff Labor Teknik Proses Produksi'],
            ['kode' => 'SHUPFT', 'nama' => 'Staff Humas dan Publikasi FT'],
        ];

        foreach ($items as $index => $item) {
            JenisJabatanStruktural::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'urutan' => $index + 1,
                ],
            );
        }
    }
}
