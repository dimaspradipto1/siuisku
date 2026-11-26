<?php

namespace Database\Seeders;

use App\Models\JenisSk;
use Illuminate\Database\Seeder;

class JenisSkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['kode' => '001', 'nama' => 'SK-MENRISTEK'],
            ['kode' => '002', 'nama' => 'SK-YAPISTA'],
            ['kode' => '003', 'nama' => 'SK-STT'],
            ['kode' => '004', 'nama' => 'SK DOSEN'],
            ['kode' => '005', 'nama' => 'SK UIS'],
        ];

        foreach ($items as $item) {
            JenisSk::updateOrCreate(
                ['kode' => $item['kode']],
                ['nama' => $item['nama']],
            );
        }
    }
}
