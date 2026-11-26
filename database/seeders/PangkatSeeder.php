<?php

namespace Database\Seeders;

use App\Models\Pangkat;
use Illuminate\Database\Seeder;

class PangkatSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['pangkat' => 'I A', 'nama_golongan' => 'Juru Muda'],
            ['pangkat' => 'I B', 'nama_golongan' => 'Juru Muda Tingkat I'],
            ['pangkat' => 'I C', 'nama_golongan' => 'Juru'],
            ['pangkat' => 'I D', 'nama_golongan' => 'Juru Tingkat I'],
            ['pangkat' => 'II A', 'nama_golongan' => 'Pengatur Muda'],
            ['pangkat' => 'II B', 'nama_golongan' => 'Pengatur Muda Tingkat I'],
            ['pangkat' => 'II C', 'nama_golongan' => 'Pengatur'],
            ['pangkat' => 'II D', 'nama_golongan' => 'Pengatur Tingkat I'],
            ['pangkat' => 'III A', 'nama_golongan' => 'Penata Muda'],
            ['pangkat' => 'III B', 'nama_golongan' => 'Penata Muda Tingkat I'],
            ['pangkat' => 'III C', 'nama_golongan' => 'Penata'],
            ['pangkat' => 'III D', 'nama_golongan' => 'Penata Tingkat I'],
            ['pangkat' => 'IV A', 'nama_golongan' => 'Pembina'],
            ['pangkat' => 'IV B', 'nama_golongan' => 'Pembina Tingkat I'],
            ['pangkat' => 'IV C', 'nama_golongan' => 'Pembina Utama Muda'],
            ['pangkat' => 'IV D', 'nama_golongan' => 'Pembina Utama Madya'],
            ['pangkat' => 'IV E', 'nama_golongan' => 'Pembina Utama'],
        ];

        foreach ($items as $index => $item) {
            Pangkat::updateOrCreate(
                ['pangkat' => $item['pangkat']],
                [
                    'nama_golongan' => $item['nama_golongan'],
                    'urutan' => $index + 1,
                ],
            );
        }
    }
}
