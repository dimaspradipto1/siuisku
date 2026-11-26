<?php

namespace Database\Seeders;

use App\Models\JenisKehadiran;
use Illuminate\Database\Seeder;

class JenisKehadiranSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['kode' => 'A', 'nama' => 'Alpha', 'warna' => '#DC3545'],
            ['kode' => 'B', 'nama' => 'Data belum dimasukkan', 'warna' => '#6C757D'],
            ['kode' => 'C', 'nama' => 'Cuti', 'warna' => '#0DCAF0'],
            ['kode' => 'D', 'nama' => 'Dinas', 'warna' => '#0D6EFD'],
            ['kode' => 'DK', 'nama' => 'Tidak Absen Datang', 'warna' => '#FD7E14'],
            ['kode' => 'H', 'nama' => 'Hadir', 'warna' => '#198754'],
            ['kode' => 'HL', 'nama' => 'Hadir Libur', 'warna' => '#20C997'],
            ['kode' => 'I', 'nama' => 'Izin Tidak Masuk', 'warna' => '#6F42C1'],
            ['kode' => 'L', 'nama' => 'Libur', 'warna' => '#64748B'],
            ['kode' => 'MP', 'nama' => 'Menunggu Persetujuan', 'warna' => '#F5BD24'],
            ['kode' => 'P', 'nama' => 'Perubahan Hari Kerja', 'warna' => '#6610F2'],
            ['kode' => 'PD', 'nama' => 'Pulang Awal', 'warna' => '#EAB308'],
            ['kode' => 'PK', 'nama' => 'Tidak Absen Pulang', 'warna' => '#EA580C'],
            ['kode' => 'S', 'nama' => 'Sakit', 'warna' => '#0891B2'],
            ['kode' => 'T', 'nama' => 'Terlambat', 'warna' => '#D63384'],
        ];

        foreach ($items as $item) {
            JenisKehadiran::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'warna' => $item['warna'],
                ],
            );
        }
    }
}
