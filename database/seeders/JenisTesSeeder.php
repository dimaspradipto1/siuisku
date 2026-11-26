<?php

namespace Database\Seeders;

use App\Models\JenisTes;
use Illuminate\Database\Seeder;

class JenisTesSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['kode' => '11', 'nama' => 'IELTS', 'nilai_minimal' => null, 'nilai_maksimal' => 9.00],
            ['kode' => '12', 'nama' => 'TOEFL iBT', 'nilai_minimal' => null, 'nilai_maksimal' => 120.00],
            ['kode' => '13', 'nama' => 'TOEFL ITP', 'nilai_minimal' => null, 'nilai_maksimal' => 677.00],
            ['kode' => '15', 'nama' => 'TOEP-TEFLIN', 'nilai_minimal' => null, 'nilai_maksimal' => 100.00],
            ['kode' => '29', 'nama' => 'TOEFL CBT', 'nilai_minimal' => null, 'nilai_maksimal' => 300.00],
            ['kode' => 'TKDA', 'nama' => 'TKDA', 'nilai_minimal' => 508.00, 'nilai_maksimal' => 687.00],
            ['kode' => 'TOEP', 'nama' => 'TOEP', 'nilai_minimal' => 46.00, 'nilai_maksimal' => 76.00],
        ];

        foreach ($items as $item) {
            JenisTes::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'nilai_minimal' => $item['nilai_minimal'],
                    'nilai_maksimal' => $item['nilai_maksimal'],
                ],
            );
        }
    }
}
