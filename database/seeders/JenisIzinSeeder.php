<?php

namespace Database\Seeders;

use App\Models\JenisIzin;
use App\Models\JenisKehadiran;
use Illuminate\Database\Seeder;

class JenisIzinSeeder extends Seeder
{
    public function run(): void
    {
        $statusMap = JenisKehadiran::pluck('id', 'kode');

        $items = [
            ['kode' => '001', 'nama' => 'Sakit', 'status' => 'S', 'maksimal_hari' => null, 'potong_cuti' => false],
            ['kode' => '002', 'nama' => 'Izin Menikah', 'status' => 'I', 'maksimal_hari' => 3, 'potong_cuti' => false],
            ['kode' => '003', 'nama' => 'Izin Menikahkan Anak', 'status' => 'I', 'maksimal_hari' => 2, 'potong_cuti' => false],
            ['kode' => '004', 'nama' => 'Izin Mengkhitankan Anak', 'status' => 'I', 'maksimal_hari' => 2, 'potong_cuti' => false],
            ['kode' => '005', 'nama' => 'Izin Melahirkan', 'status' => 'I', 'maksimal_hari' => 40, 'potong_cuti' => false],
            ['kode' => '006', 'nama' => 'Izin Istri Melahirkan', 'status' => 'I', 'maksimal_hari' => 3, 'potong_cuti' => false],
            ['kode' => '007', 'nama' => 'Izin Anak Sakit', 'status' => 'I', 'maksimal_hari' => 2, 'potong_cuti' => false],
            ['kode' => '008', 'nama' => 'Izin Duka', 'status' => 'I', 'maksimal_hari' => 2, 'potong_cuti' => false],
            ['kode' => '009', 'nama' => 'Izin Ibadah Haji', 'status' => 'I', 'maksimal_hari' => 40, 'potong_cuti' => false],
            ['kode' => '999', 'nama' => 'Izin Lain-lain', 'status' => 'I', 'maksimal_hari' => 3, 'potong_cuti' => true],
        ];

        foreach ($items as $item) {
            JenisIzin::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'jenis_kehadiran_id' => $statusMap[$item['status']] ?? null,
                    'maksimal_hari' => $item['maksimal_hari'],
                    'potong_cuti' => $item['potong_cuti'],
                ],
            );
        }
    }
}
