<?php

namespace Database\Seeders;

use App\Models\JenisSertifikasi;
use Illuminate\Database\Seeder;

class JenisSertifikasiSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['kode' => 'S1', 'nama' => 'Sertifikasi Dosen', 'kategori' => 'Sertifikasi Dosen', 'is_system' => true],
            ['kode' => 'S4', 'nama' => 'Sertifikasi Profesi', 'kategori' => 'Sertifikasi Profesi', 'is_system' => false],
            ['kode' => 'S5', 'nama' => 'Sertifikasi Kompetensi', 'kategori' => 'Sertifikasi Kompetensi', 'is_system' => false],
        ];

        foreach ($items as $item) {
            JenisSertifikasi::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'kategori' => $item['kategori'],
                    'is_system' => $item['is_system'],
                ],
            );
        }
    }
}
