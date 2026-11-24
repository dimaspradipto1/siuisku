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
        $items = [
            // Pimpinan Universitas
            ['kode' => 'R', 'nama' => 'Rektor'],
            ['kode' => 'WR1', 'nama' => 'Wakil Rektor I'],
            ['kode' => 'WR2', 'nama' => 'Wakil Rektor II'],
            ['kode' => 'WR3', 'nama' => 'Wakil Rektor III'],

            // Rektorat UIS - LPTI (Lembaga Pengembangan Teknologi Informasi)
            ['kode' => 'KLPTI', 'nama' => 'Kepala LPTI'],
            ['kode' => 'DPSI', 'nama' => 'Divisi Pengembangan Sistem Informasi dan Aplikasi - LPTI BAUK'],
            ['kode' => 'DIJT', 'nama' => 'Divisi Infrastruktur, Jaringan, dan Layanan Troubleshooting'],

            // Rektorat UIS - Wakil Rektor III (Unsur Pelaksana Administrasi)
            ['kode' => 'KBKAPP', 'nama' => 'Ka. Biro Kemahasiswaan, Alumni, Kerjasama, Perencanaan dan Pengembangan'],
            ['kode' => 'KBHP', 'nama' => 'Kabid. Humas dan Publikasi'],
            ['kode' => 'STDOK', 'nama' => 'Staff Dokumentasi'],
            ['kode' => 'STHUM', 'nama' => 'Staff Humas'],
            ['kode' => 'STWEB', 'nama' => 'Staff Website'],
            ['kode' => 'KBKJS', 'nama' => 'Kabid. Kerjasama'],
            ['kode' => 'STKJSI', 'nama' => 'Staff Kerjasama - Internasional'],
            ['kode' => 'STKJSN', 'nama' => 'Staff Kerjasama - Nasional'],
            ['kode' => 'KBKMH', 'nama' => 'Kabid. Kemahasiswaan'],
            ['kode' => 'STKMHKIP', 'nama' => 'Staff Kemahasiswaan - KIP'],
            ['kode' => 'STKMHOR', 'nama' => 'Staff Kemahasiswaan - Prestasi Olahraga'],
            ['kode' => 'STKMHSN', 'nama' => 'Staff Kemahasiswaan - Prestasi Seni'],
            ['kode' => 'KBPKAK', 'nama' => 'Kabid. Pusat Karir, Alumni dan Kewirausahaan'],
            ['kode' => 'STPKAK', 'nama' => 'Staff Pusat Karir, Alumni dan Kewirausahaan'],
            ['kode' => 'KBPP', 'nama' => 'Kabid. Perencanaan dan Pengembangan'],
            ['kode' => 'STPP', 'nama' => 'Staff Perencanaan dan Pengembangan'],

            // Rektorat UIS - LPPM
            ['kode' => 'KLPPM', 'nama' => 'Ka. LPPM'],
            ['kode' => 'KBPEN', 'nama' => 'Kabid. Penelitian'],
            ['kode' => 'KBPKM', 'nama' => 'Kabid. Pengabdian Kepada Masyarakat'],
            ['kode' => 'STLPPM', 'nama' => 'Staff Administrasi Penelitian dan Pengabdian Kepada Masyarakat'],
            ['kode' => 'KBHKI', 'nama' => 'Kabid. HAKI dan Publikasi'],

            // Rektorat UIS - LPMI
            ['kode' => 'KLPMI', 'nama' => 'Ka. LPMI'],
            ['kode' => 'KBSPMI', 'nama' => 'Kabid. Pengembangan SPMI dan SDM SPMI'],
            ['kode' => 'KBSOSPM', 'nama' => 'Kabid. Sosialisasi SPMI dan Kerjasama SPMI'],
            ['kode' => 'KBAKDOK', 'nama' => 'Kabid. Akreditasi dan Dokumentasi'],
            ['kode' => 'KBEAM', 'nama' => 'Kabid. Evaluasi dan Audit Mutu'],

            // Fakultas Ekonomi dan Bisnis
            ['kode' => 'DFB', 'nama' => 'Dekan Fakultas Ekonomi dan Bisnis'],
            ['kode' => 'WFB1', 'nama' => 'Wakil Dekan I Fakultas Ekonomi dan Bisnis'],
            ['kode' => 'WFB2', 'nama' => 'Wakil Dekan II Fakultas Ekonomi dan Bisnis'],
            ['kode' => 'KPMNJ', 'nama' => 'Ketua Program Studi S1 Manajemen'],
            ['kode' => 'SPMNJ', 'nama' => 'Sekretaris Prodi S1 Manajemen'],
            ['kode' => 'KPAKT', 'nama' => 'Ketua Program Studi S1 Akuntansi'],
            ['kode' => 'SPAKT', 'nama' => 'Sekretaris Prodi S1 Akuntansi'],
            ['kode' => 'KPMM', 'nama' => 'Ketua Program Studi Pascasarjana Magister Manajemen'],
            ['kode' => 'SPMM', 'nama' => 'Sekretaris Prodi Pascasarjana Magister Manajemen'],
            ['kode' => 'UPMIFB', 'nama' => 'UPMI Fakultas Ekonomi dan Bisnis'],
            ['kode' => 'UPPMFB', 'nama' => 'UPPM Fakultas Ekonomi dan Bisnis'],
            ['kode' => 'TUFB1', 'nama' => 'Tata Usaha Sarjana Fakultas Ekonomi dan Bisnis'],
            ['kode' => 'TUFB2', 'nama' => 'Tata Usaha Pascasarjana Fakultas Ekonomi dan Bisnis'],
            ['kode' => 'HUPFB', 'nama' => 'Humas dan Publikasi Web Fakultas Ekonomi dan Bisnis'],

            // Fakultas Teknik
            ['kode' => 'DFT', 'nama' => 'Dekan Fakultas Teknik'],
            ['kode' => 'WFT1', 'nama' => 'Wakil Dekan I Fakultas Teknik'],
            ['kode' => 'WFT2', 'nama' => 'Wakil Dekan II Fakultas Teknik'],
            ['kode' => 'KPTI', 'nama' => 'Ketua Program Studi Teknik Industri'],
            ['kode' => 'SPTI', 'nama' => 'Sekretaris Program Studi Teknik Industri'],
            ['kode' => 'KPTISI', 'nama' => 'Ketua Program Studi Teknik Informatika dan Prodi Sistem Informasi'],
            ['kode' => 'SPTISI', 'nama' => 'Sekretaris Program Studi Teknik Informatika dan Prodi Sistem Informasi'],
            ['kode' => 'KPTLP', 'nama' => 'Ketua Program Studi Teknik Logistik dan Perkapalan'],
            ['kode' => 'SPTLP', 'nama' => 'Sekretaris Program Studi Teknik Logistik dan Perkapalan'],
            ['kode' => 'TUFT', 'nama' => 'Tata Usaha Fakultas Teknik'],
            ['kode' => 'UPMIFT', 'nama' => 'UPMI Fakultas Teknik'],
            ['kode' => 'KUPPMFT', 'nama' => 'Ka. UPPM Fakultas Teknik'],
            ['kode' => 'SUPPMFT', 'nama' => 'Staff UPPM Fakultas Teknik'],
            ['kode' => 'KLABFT', 'nama' => 'Ka. Laboratorium Fakultas Teknik'],
            ['kode' => 'SLABTI', 'nama' => 'Staff Labor Teknik Industri'],
            ['kode' => 'SLABTK', 'nama' => 'Staff Labor Teknik Komputer'],
            ['kode' => 'SLABPP', 'nama' => 'Staff Labor Teknik Proses Produksi'],
            ['kode' => 'KHUPFT', 'nama' => 'Ka. Humas dan Publikasi FT'],
            ['kode' => 'SHUPFT', 'nama' => 'Staff Humas dan Publikasi FT'],

            // Fakultas Ilmu Kesehatan
            ['kode' => 'DFK', 'nama' => 'Dekan Fakultas Ilmu Kesehatan'],
            ['kode' => 'WFK1', 'nama' => 'Wakil Dekan I Fakultas Ilmu Kesehatan'],
            ['kode' => 'WFK2', 'nama' => 'Wakil Dekan II Fakultas Ilmu Kesehatan'],
            ['kode' => 'KPK3', 'nama' => 'Ketua Program Studi K3'],
            ['kode' => 'SPK3', 'nama' => 'Sekretaris Prodi K3'],
            ['kode' => 'KPKESL', 'nama' => 'Ketua Program Studi Kesling'],
            ['kode' => 'SPKESL', 'nama' => 'Sekretaris Prodi Kesling'],
            ['kode' => 'UPMIFK', 'nama' => 'UPMI Fakultas Ilmu Kesehatan'],
            ['kode' => 'GKMFK', 'nama' => 'GKM Fakultas Ilmu Kesehatan'],
            ['kode' => 'UPPMFK', 'nama' => 'UPPM Fakultas Ilmu Kesehatan'],
            ['kode' => 'LABFK', 'nama' => 'Laboran Fakultas Ilmu Kesehatan'],
            ['kode' => 'TUFK', 'nama' => 'Tata Usaha Fakultas Ilmu Kesehatan'],
            ['kode' => 'HUPFK', 'nama' => 'Humas dan Publikasi Web Fakultas Ilmu Kesehatan'],
        ];

        foreach ($items as $item) {
            JenisJabatanStruktural::updateOrCreate(
                ['kode' => $item['kode']],
                ['nama' => $item['nama']],
            );
        }
    }
}
