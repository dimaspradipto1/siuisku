<?php

namespace Database\Seeders;

use App\Models\KlasifikasiSdm;
use Illuminate\Database\Seeder;

class KlasifikasiSdmSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['kode' => '1', 'nama' => 'Dosen Tetap Yayasan', 'jenis_jabatan' => 'Dosen'],
            ['kode' => '2', 'nama' => 'Dosen Tetap DPK', 'jenis_jabatan' => 'Dosen'],
            ['kode' => '3', 'nama' => 'Dosen Luar Biasa', 'jenis_jabatan' => 'Dosen'],
            ['kode' => '4', 'nama' => 'Dosen Tamu', 'jenis_jabatan' => 'Dosen Praktisi/Industri'],
            ['kode' => '5', 'nama' => 'Asisten Dosen', 'jenis_jabatan' => 'Dosen'],
            ['kode' => '6', 'nama' => 'Asisten Laboratorium', 'jenis_jabatan' => 'Dosen'],
            ['kode' => '7', 'nama' => 'Dosen Tidak Tetap', 'jenis_jabatan' => 'Dosen'],
            ['kode' => 'DP', 'nama' => 'Dosen Praktisi/Industri', 'jenis_jabatan' => 'Dosen Praktisi/Industri'],
            ['kode' => 'GP', 'nama' => 'Guru Pamong', 'jenis_jabatan' => 'Dosen Praktisi/Industri'],
            ['kode' => 'K01', 'nama' => 'Rektor', 'jenis_jabatan' => 'Tenaga Kependidikan'],
            ['kode' => 'K02', 'nama' => 'Wakil Rektor', 'jenis_jabatan' => 'Tenaga Kependidikan'],
            ['kode' => 'K03', 'nama' => 'Biro Administrasi Akademik Kemahasiswaan', 'jenis_jabatan' => 'Tenaga Kependidikan'],
            ['kode' => 'K04', 'nama' => 'Biro Administrasi Umum dan Keuangan', 'jenis_jabatan' => 'Tenaga Kependidikan'],
            ['kode' => 'K05', 'nama' => 'Biro Kemahasiswaan, Alumni dan Kerjasama', 'jenis_jabatan' => 'Tenaga Kependidikan'],
            ['kode' => 'K06', 'nama' => 'LPTI', 'jenis_jabatan' => 'Tenaga Kependidikan'],
            ['kode' => 'K07', 'nama' => 'LPPM', 'jenis_jabatan' => 'Tenaga Kependidikan'],
            ['kode' => 'K08', 'nama' => 'LPMI', 'jenis_jabatan' => 'Tenaga Kependidikan'],
            ['kode' => 'K09', 'nama' => 'Dekan', 'jenis_jabatan' => 'Tenaga Kependidikan'],
            ['kode' => 'K10', 'nama' => 'Wakil Dekan', 'jenis_jabatan' => 'Dosen'],
            ['kode' => 'K11', 'nama' => 'Tata Usaha', 'jenis_jabatan' => 'Tenaga Kependidikan'],
            ['kode' => 'K12', 'nama' => 'Laboran', 'jenis_jabatan' => 'Tenaga Kependidikan'],
            ['kode' => 'K13', 'nama' => 'Pustakawan', 'jenis_jabatan' => 'Tenaga Kependidikan'],
            ['kode' => 'K14', 'nama' => 'CS', 'jenis_jabatan' => 'Tenaga Kependidikan'],
            ['kode' => 'K15', 'nama' => 'Keamanan', 'jenis_jabatan' => 'Tenaga Kependidikan'],
            ['kode' => 'K16', 'nama' => 'GKM Fakultas Teknik', 'jenis_jabatan' => 'Tenaga Kependidikan'],
            ['kode' => 'K17', 'nama' => 'GKM Fakultas Ilmu Kesehatan', 'jenis_jabatan' => 'Tenaga Kependidikan'],
            ['kode' => 'N', 'nama' => 'Kosong', 'jenis_jabatan' => 'Dosen'],
            ['kode' => 'P1', 'nama' => 'Dosen', 'jenis_jabatan' => 'Dosen'],
            ['kode' => 'P10', 'nama' => 'Kepala Lab. Industri', 'jenis_jabatan' => 'Dosen'],
            ['kode' => 'P11', 'nama' => 'Kepala Lab. Informatika', 'jenis_jabatan' => 'Dosen'],
            ['kode' => 'P12', 'nama' => 'Asisten Lab. Software', 'jenis_jabatan' => 'Dosen'],
            ['kode' => 'P13', 'nama' => 'Kaprodi Manajemen', 'jenis_jabatan' => 'Dosen'],
            ['kode' => 'P14', 'nama' => 'Kaprodi Akuntansi', 'jenis_jabatan' => 'Dosen'],
            ['kode' => 'P15', 'nama' => 'Kaprodi Akuntansi D3', 'jenis_jabatan' => 'Dosen'],
            ['kode' => 'P16', 'nama' => 'Kaprodi K3', 'jenis_jabatan' => 'Dosen'],
            ['kode' => 'P17', 'nama' => 'Kaprodi Kesling', 'jenis_jabatan' => 'Dosen'],
            ['kode' => 'P18', 'nama' => 'Sekretaris Prodi K3', 'jenis_jabatan' => 'Dosen'],
            ['kode' => 'P19', 'nama' => 'Sekretaris Prodi Kesling', 'jenis_jabatan' => 'Dosen'],
            ['kode' => 'P2', 'nama' => 'Dosen Luar Biasa', 'jenis_jabatan' => 'Dosen'],
            ['kode' => 'P20', 'nama' => 'Ka. Prodi S2 Manajemen', 'jenis_jabatan' => 'Dosen'],
            ['kode' => 'P3', 'nama' => 'Arsiparis', 'jenis_jabatan' => 'Dosen'],
            ['kode' => 'P4', 'nama' => 'Peneliti', 'jenis_jabatan' => 'Dosen'],
            ['kode' => 'P5', 'nama' => 'Dokter', 'jenis_jabatan' => 'Dosen'],
            ['kode' => 'P6', 'nama' => 'Kaprodi TF', 'jenis_jabatan' => 'Dosen'],
            ['kode' => 'P7', 'nama' => 'Kaprodi TI', 'jenis_jabatan' => 'Dosen'],
            ['kode' => 'P8', 'nama' => 'Sekretaris Prodi TF', 'jenis_jabatan' => 'Dosen'],
            ['kode' => 'P9', 'nama' => 'Sekretaris Prodi TI', 'jenis_jabatan' => 'Dosen'],
            ['kode' => 'TA', 'nama' => 'Tenaga Ahli', 'jenis_jabatan' => 'Dosen Praktisi/Industri'],
        ];

        foreach ($items as $item) {
            KlasifikasiSdm::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'jenis_jabatan' => $item['jenis_jabatan'],
                ],
            );
        }
    }
}
