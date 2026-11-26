<?php

namespace Database\Seeders;

use App\Models\HariLibur;
use Illuminate\Database\Seeder;

class HariLiburSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['nama' => 'Tahun Baru 2026 Masehi', 'tanggal_mulai' => '2026-01-01', 'tanggal_selesai' => '2026-01-01'],
            ['nama' => 'Isra Mikraj Nabi Muhammad SAW', 'tanggal_mulai' => '2026-01-16', 'tanggal_selesai' => '2026-01-16'],
            ['nama' => 'Tahun Baru Imlek', 'tanggal_mulai' => '2026-02-17', 'tanggal_selesai' => '2026-02-17'],
            ['nama' => 'Hari Suci Nyepi', 'tanggal_mulai' => '2026-03-19', 'tanggal_selesai' => '2026-03-19'],
            ['nama' => 'Libur Hari Raya Idul Fitri', 'tanggal_mulai' => '2026-03-20', 'tanggal_selesai' => '2026-03-29'],
            ['nama' => 'Wafat Yesus Kristus', 'tanggal_mulai' => '2026-04-03', 'tanggal_selesai' => '2026-04-03'],
            ['nama' => 'Hari Buruh Internasional', 'tanggal_mulai' => '2026-05-01', 'tanggal_selesai' => '2026-05-01'],
            ['nama' => 'Kenaikan Yesus Kristus', 'tanggal_mulai' => '2026-05-14', 'tanggal_selesai' => '2026-05-14'],
            ['nama' => 'Hari Raya Idul Adha', 'tanggal_mulai' => '2026-05-27', 'tanggal_selesai' => '2026-05-27'],
            ['nama' => 'Hari Raya Waisak', 'tanggal_mulai' => '2026-05-31', 'tanggal_selesai' => '2026-05-31'],
            ['nama' => 'Hari Lahir Pancasila', 'tanggal_mulai' => '2026-06-01', 'tanggal_selesai' => '2026-06-01'],
            ['nama' => 'Tahun Baru Islam 1448 H', 'tanggal_mulai' => '2026-06-16', 'tanggal_selesai' => '2026-06-16'],
            ['nama' => 'Hari Kemerdekaan Republik Indonesia', 'tanggal_mulai' => '2026-08-17', 'tanggal_selesai' => '2026-08-17'],
            ['nama' => 'Maulid Nabi Muhammad SAW', 'tanggal_mulai' => '2026-08-25', 'tanggal_selesai' => '2026-08-25'],
            ['nama' => 'Hari Raya Natal', 'tanggal_mulai' => '2026-12-25', 'tanggal_selesai' => '2026-12-25'],
        ];

        foreach ($items as $item) {
            HariLibur::updateOrCreate(
                [
                    'nama' => $item['nama'],
                    'tanggal_mulai' => $item['tanggal_mulai'],
                ],
                [
                    'tanggal_selesai' => $item['tanggal_selesai'],
                ],
            );
        }
    }
}
