<?php

namespace Database\Seeders;

use App\Models\RumpunBidangIlmu;
use Illuminate\Database\Seeder;

class RumpunBidangIlmuSeeder extends Seeder
{
    public function run(): void
    {
        // Rumpun Bidang Ilmu disesuaikan dengan Fakultas & Prodi Universitas Ibnu Sina:
        // FEB (Manajemen, S2 MM, Akuntansi), FST (Teknik Industri, Teknik Informatika, Sistem Informasi,
        // Teknik Logistik & Perkapalan), FIKES (K3, Kesehatan Lingkungan), serta MIPA & MKWU penunjang.
        $items = [
            // 1. Ilmu Kesehatan (FIKES)
            ['kode' => '350', 'nama' => 'Ilmu Kesehatan', 'parent' => null, 'level' => 0],
            ['kode' => '370', 'nama' => 'Ilmu Kesehatan Umum', 'parent' => '350', 'level' => 1],
            ['kode' => '371', 'nama' => 'Kesehatan Masyarakat', 'parent' => '370', 'level' => 2],
            ['kode' => '372', 'nama' => 'Keselamatan dan Kesehatan Kerja (K3)', 'parent' => '370', 'level' => 2],
            ['kode' => '373', 'nama' => 'Kesehatan Lingkungan', 'parent' => '370', 'level' => 2],
            ['kode' => '374', 'nama' => 'Epidemiologi', 'parent' => '370', 'level' => 2],
            ['kode' => '375', 'nama' => 'Gizi dan Kesehatan', 'parent' => '370', 'level' => 2],
            ['kode' => '376', 'nama' => 'Administrasi dan Kebijakan Kesehatan', 'parent' => '370', 'level' => 2],
            ['kode' => '377', 'nama' => 'Promosi Kesehatan', 'parent' => '370', 'level' => 2],

            // 2. Ilmu Teknik (FST)
            ['kode' => '400', 'nama' => 'Ilmu Teknik', 'parent' => null, 'level' => 0],
            ['kode' => '440', 'nama' => 'Teknik Industri', 'parent' => '400', 'level' => 1],
            ['kode' => '441', 'nama' => 'Teknik Industri', 'parent' => '440', 'level' => 2],
            ['kode' => '442', 'nama' => 'Manajemen Industri dan Rekayasa Sistem', 'parent' => '440', 'level' => 2],
            ['kode' => '443', 'nama' => 'Teknik Logistik', 'parent' => '440', 'level' => 2],
            ['kode' => '444', 'nama' => 'Teknik Perkapalan', 'parent' => '440', 'level' => 2],
            ['kode' => '445', 'nama' => 'Ergonomi dan Keselamatan Kerja Industri', 'parent' => '440', 'level' => 2],
            ['kode' => '446', 'nama' => 'Bidang Keteknikan Industri Lain Yang Belum Tercantum', 'parent' => '440', 'level' => 2],

            ['kode' => '450', 'nama' => 'Teknik Elektro dan Informatika', 'parent' => '400', 'level' => 1],
            ['kode' => '457', 'nama' => 'Teknik Komputer', 'parent' => '450', 'level' => 2],
            ['kode' => '458', 'nama' => 'Teknik Informatika', 'parent' => '450', 'level' => 2],
            ['kode' => '459', 'nama' => 'Ilmu Komputer', 'parent' => '450', 'level' => 2],
            ['kode' => '461', 'nama' => 'Sistem Informasi', 'parent' => '450', 'level' => 2],
            ['kode' => '462', 'nama' => 'Teknologi Informasi', 'parent' => '450', 'level' => 2],
            ['kode' => '463', 'nama' => 'Teknik Perangkat Lunak', 'parent' => '450', 'level' => 2],
            ['kode' => '465', 'nama' => 'Bidang Teknik Elektro dan Informatika Lain Yang Belum Tercantum', 'parent' => '450', 'level' => 2],

            // 3. Ilmu Ekonomi (FEB)
            ['kode' => '560', 'nama' => 'Ilmu Ekonomi', 'parent' => null, 'level' => 0],
            ['kode' => '561', 'nama' => 'Ilmu Ekonomi dan Studi Pembangunan', 'parent' => '560', 'level' => 1],
            ['kode' => '562', 'nama' => 'Ekonomi Pembangunan', 'parent' => '561', 'level' => 2],
            ['kode' => '563', 'nama' => 'Ekonomi Syariah', 'parent' => '561', 'level' => 2],

            ['kode' => '570', 'nama' => 'Manajemen', 'parent' => '560', 'level' => 1],
            ['kode' => '571', 'nama' => 'Manajemen', 'parent' => '570', 'level' => 2],
            ['kode' => '572', 'nama' => 'Manajemen Sumber Daya Manusia', 'parent' => '570', 'level' => 2],
            ['kode' => '573', 'nama' => 'Manajemen Keuangan', 'parent' => '570', 'level' => 2],
            ['kode' => '574', 'nama' => 'Manajemen Pemasaran', 'parent' => '570', 'level' => 2],
            ['kode' => '575', 'nama' => 'Manajemen Operasional dan Bisnis', 'parent' => '570', 'level' => 2],
            ['kode' => '576', 'nama' => 'Kewirausahaan', 'parent' => '570', 'level' => 2],

            ['kode' => '580', 'nama' => 'Akuntansi', 'parent' => '560', 'level' => 1],
            ['kode' => '581', 'nama' => 'Akuntansi', 'parent' => '580', 'level' => 2],
            ['kode' => '582', 'nama' => 'Akuntansi Keuangan', 'parent' => '580', 'level' => 2],
            ['kode' => '583', 'nama' => 'Akuntansi Manajemen', 'parent' => '580', 'level' => 2],
            ['kode' => '584', 'nama' => 'Auditing dan Sistem Informasi Akuntansi', 'parent' => '580', 'level' => 2],
            ['kode' => '585', 'nama' => 'Perpajakan', 'parent' => '580', 'level' => 2],

            // 4. MIPA & MKWU Penunjang UIS
            ['kode' => '100', 'nama' => 'Matematika dan Ilmu Pengetahuan Alam (MIPA)', 'parent' => null, 'level' => 0],
            ['kode' => '120', 'nama' => 'Matematika', 'parent' => '100', 'level' => 1],
            ['kode' => '121', 'nama' => 'Matematika', 'parent' => '120', 'level' => 2],
            ['kode' => '122', 'nama' => 'Statistika', 'parent' => '120', 'level' => 2],

            ['kode' => '610', 'nama' => 'Ilmu Agama dan Mata Kuliah Umum', 'parent' => null, 'level' => 0],
            ['kode' => '611', 'nama' => 'Ilmu Agama Islam', 'parent' => '610', 'level' => 1],
            ['kode' => '612', 'nama' => 'Pendidikan Agama Islam', 'parent' => '611', 'level' => 2],
            ['kode' => '620', 'nama' => 'Bahasa dan Kewarganegaraan', 'parent' => '610', 'level' => 1],
            ['kode' => '621', 'nama' => 'Bahasa Indonesia', 'parent' => '620', 'level' => 2],
            ['kode' => '622', 'nama' => 'Bahasa Inggris', 'parent' => '620', 'level' => 2],
            ['kode' => '623', 'nama' => 'Pendidikan Pancasila dan Kewarganegaraan', 'parent' => '620', 'level' => 2],
        ];

        $savedByKode = [];
        foreach ($items as $index => $item) {
            $parentId = $item['parent'] ? ($savedByKode[$item['parent']]?->id ?? null) : null;

            $savedByKode[$item['kode']] = RumpunBidangIlmu::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'parent_id' => $parentId,
                    'level' => $item['level'],
                    'urutan' => $index + 1,
                ],
            );
        }
    }
}
