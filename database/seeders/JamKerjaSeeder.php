<?php

namespace Database\Seeders;

use App\Models\JamKerja;
use Illuminate\Database\Seeder;

class JamKerjaSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['nama' => 'Sarpras', 'is_normal' => false, 'jam_datang' => '08:00', 'jam_pulang' => '20:00'],
            ['nama' => 'Jam Kerja Normal', 'is_normal' => true, 'jam_datang' => '14:00', 'jam_pulang' => '20:00'],
        ];

        foreach ($items as $item) {
            JamKerja::updateOrCreate(
                ['nama' => $item['nama']],
                [
                    'is_normal' => $item['is_normal'],
                    'jam_datang' => $item['jam_datang'],
                    'jam_pulang' => $item['jam_pulang'],
                ],
            );
        }
    }
}
