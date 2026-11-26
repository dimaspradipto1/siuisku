<?php

namespace Database\Seeders;

use App\Models\JenisHari;
use Illuminate\Database\Seeder;

class JenisHariSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['kode' => 0, 'nama' => 'Minggu', 'jenis_hari' => 'Non Efektif'],
            ['kode' => 1, 'nama' => 'Senin', 'jenis_hari' => 'Efektif'],
            ['kode' => 2, 'nama' => 'Selasa', 'jenis_hari' => 'Efektif'],
            ['kode' => 3, 'nama' => 'Rabu', 'jenis_hari' => 'Efektif'],
            ['kode' => 4, 'nama' => 'Kamis', 'jenis_hari' => 'Efektif'],
            ['kode' => 5, 'nama' => 'Jumat', 'jenis_hari' => 'Efektif'],
            ['kode' => 6, 'nama' => 'Sabtu', 'jenis_hari' => 'Non Efektif'],
        ];

        foreach ($items as $item) {
            JenisHari::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'jenis_hari' => $item['jenis_hari'],
                ],
            );
        }
    }
}
