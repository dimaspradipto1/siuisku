<?php

namespace Database\Seeders;

use App\Models\StatusAktif;
use Illuminate\Database\Seeder;

class StatusAktifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['kode' => 'AA', 'nama' => 'Aktif', 'status_keluar' => false],
            ['kode' => 'CL', 'nama' => 'Cuti Luar Tanggungan', 'status_keluar' => false],
            ['kode' => 'KH', 'nama' => 'Kontrak Habis', 'status_keluar' => true],
            ['kode' => 'M', 'nama' => 'Meninggal Dunia', 'status_keluar' => true],
            ['kode' => 'M5', 'nama' => 'Mangkir 5 Kali Berturut-turut', 'status_keluar' => true],
            ['kode' => 'MD', 'nama' => 'Mengundurkan diri', 'status_keluar' => true],
            ['kode' => 'PD', 'nama' => 'Pensiun Dini', 'status_keluar' => true],
            ['kode' => 'PH', 'nama' => 'PHK', 'status_keluar' => true],
            ['kode' => 'PL', 'nama' => 'Pelanggaran', 'status_keluar' => true],
            ['kode' => 'PN', 'nama' => 'Pensiun Normal', 'status_keluar' => true],
            ['kode' => 'PS', 'nama' => 'Pernikahan Sesama Karyawan', 'status_keluar' => true],
            ['kode' => 'SB', 'nama' => 'Kesalahan Berat', 'status_keluar' => true],
            ['kode' => 'SP', 'nama' => 'Sakit Berkepanjangan', 'status_keluar' => true],
            ['kode' => 'TA', 'nama' => 'Tidak Aktif', 'status_keluar' => true],
            ['kode' => 'TB', 'nama' => 'Tugas Belajar', 'status_keluar' => true],
            ['kode' => 'TW', 'nama' => 'Ditahan Pihak Berwajib', 'status_keluar' => true],
        ];

        foreach ($items as $item) {
            StatusAktif::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'status_keluar' => $item['status_keluar'],
                ],
            );
        }
    }
}
