<?php

namespace Database\Seeders;

use App\Models\GelarAkademik;
use Illuminate\Database\Seeder;

class GelarAkademikSeeder extends Seeder
{
    public function run(): void
    {
        // Gelar Akademik disesuaikan dengan Fakultas, Prodi, dan SDM Universitas Ibnu Sina
        $items = [
            // Diploma
            ['gelar' => 'A.Md.', 'nama' => 'Ahli Madya'],
            ['gelar' => 'A.Md.Ak.', 'nama' => 'Ahli Madya Akuntansi'],
            ['gelar' => 'A.Md.Kom.', 'nama' => 'Ahli Madya Komputer'],
            ['gelar' => 'A.Md.Kes.', 'nama' => 'Ahli Madya Kesehatan'],
            ['gelar' => 'S.Tr.T.', 'nama' => 'Sarjana Terapan Teknik'],
            ['gelar' => 'S.Tr.Kom.', 'nama' => 'Sarjana Terapan Komputer'],

            // Sarjana (S1)
            ['gelar' => 'S.E.', 'nama' => 'Sarjana Ekonomi'],
            ['gelar' => 'S.M.', 'nama' => 'Sarjana Manajemen'],
            ['gelar' => 'S.Ak.', 'nama' => 'Sarjana Akuntansi'],
            ['gelar' => 'S.T.', 'nama' => 'Sarjana Teknik'],
            ['gelar' => 'S.Kom.', 'nama' => 'Sarjana Komputer'],
            ['gelar' => 'S.Si.', 'nama' => 'Sarjana Sains'],
            ['gelar' => 'S.K.M.', 'nama' => 'Sarjana Kesehatan Masyarakat'],
            ['gelar' => 'S.K.L.', 'nama' => 'Sarjana Kesehatan Lingkungan'],
            ['gelar' => 'S.K.K.K.', 'nama' => 'Sarjana Keselamatan dan Kesehatan Kerja'],
            ['gelar' => 'S.Pd.', 'nama' => 'Sarjana Pendidikan'],
            ['gelar' => 'S.Pd.I.', 'nama' => 'Sarjana Pendidikan Islam'],
            ['gelar' => 'S.Ag.', 'nama' => 'Sarjana Agama'],
            ['gelar' => 'S.H.', 'nama' => 'Sarjana Hukum'],
            ['gelar' => 'S.Sos.', 'nama' => 'Sarjana Sosial'],
            ['gelar' => 'S.S.', 'nama' => 'Sarjana Sastra'],

            // Magister (S2)
            ['gelar' => 'M.M.', 'nama' => 'Magister Manajemen'],
            ['gelar' => 'M.Ak.', 'nama' => 'Magister Akuntansi'],
            ['gelar' => 'M.E.', 'nama' => 'Magister Ekonomi'],
            ['gelar' => 'M.Si.', 'nama' => 'Magister Sains'],
            ['gelar' => 'M.T.', 'nama' => 'Magister Teknik'],
            ['gelar' => 'M.Kom.', 'nama' => 'Magister Komputer'],
            ['gelar' => 'M.TI.', 'nama' => 'Magister Teknologi Informasi'],
            ['gelar' => 'M.Cs.', 'nama' => 'Master of Computer Science'],
            ['gelar' => 'M.K.M.', 'nama' => 'Magister Kesehatan Masyarakat'],
            ['gelar' => 'M.K.K.K.', 'nama' => 'Magister Keselamatan dan Kesehatan Kerja'],
            ['gelar' => 'M.Kes.', 'nama' => 'Magister Kesehatan'],
            ['gelar' => 'M.Ling.', 'nama' => 'Magister Ilmu Lingkungan'],
            ['gelar' => 'M.Pd.', 'nama' => 'Magister Pendidikan'],
            ['gelar' => 'M.Pd.I.', 'nama' => 'Magister Pendidikan Islam'],
            ['gelar' => 'M.Ag.', 'nama' => 'Magister Agama'],
            ['gelar' => 'M.H.', 'nama' => 'Magister Hukum'],
            ['gelar' => 'M.Hum.', 'nama' => 'Magister Humaniora'],
            ['gelar' => 'M.B.A.', 'nama' => 'Master of Business Administration'],

            // Doktor (S3) & Guru Besar
            ['gelar' => 'Dr.', 'nama' => 'Doktor'],
            ['gelar' => 'Ph.D.', 'nama' => 'Doctor of Philosophy'],
            ['gelar' => 'Prof.', 'nama' => 'Profesor'],

            // Profesi / Sertifikasi Akademik
            ['gelar' => 'Ir.', 'nama' => 'Insinyur'],
            ['gelar' => 'Ak.', 'nama' => 'Akuntan'],
            ['gelar' => 'CA.', 'nama' => 'Chartered Accountant'],
        ];

        foreach ($items as $index => $item) {
            GelarAkademik::updateOrCreate(
                ['gelar' => $item['gelar']],
                [
                    'nama' => $item['nama'],
                    'urutan' => $index + 1,
                ],
            );
        }
    }
}
