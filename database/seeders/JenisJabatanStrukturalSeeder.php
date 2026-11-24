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
            ['kode' => 'DFB', 'nama' => 'Dekan Fakultas Ekonomi dan Bisnis'],
            ['kode' => 'DFK', 'nama' => 'Dekan Fakultas Ilmu Kesehatan'],
            ['kode' => 'DFT', 'nama' => 'Dekan Fakultas Teknik'],
            ['kode' => 'R', 'nama' => 'Rektor'],
            ['kode' => 'WFB1', 'nama' => 'Wakil Dekan I Fakultas Ekonomi dan Bisnis'],
            ['kode' => 'WFB2', 'nama' => 'Wakil Dekan II Fakultas Ekonomi dan Bisnis'],
            ['kode' => 'WFK1', 'nama' => 'Wakil Dekan I Fakultas Ilmu Kesehatan'],
            ['kode' => 'WFK2', 'nama' => 'Wakil Dekan II Fakultas Ilmu Kesehatan'],
            ['kode' => 'WFT1', 'nama' => 'Wakil Dekan I Fakultas Teknik'],
            ['kode' => 'WFT2', 'nama' => 'Wakil Dekan II Fakultas Teknik'],
            ['kode' => 'WR1', 'nama' => 'Wakil Rektor I'],
            ['kode' => 'WR2', 'nama' => 'Wakil Rektor II'],
            ['kode' => 'WR3', 'nama' => 'Wakil Rektor III'],
        ];

        foreach ($items as $item) {
            JenisJabatanStruktural::updateOrCreate(
                ['kode' => $item['kode']],
                ['nama' => $item['nama']],
            );
        }
    }
}
