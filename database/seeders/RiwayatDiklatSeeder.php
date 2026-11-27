<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\RiwayatDiklat;
use Illuminate\Database\Seeder;

class RiwayatDiklatSeeder extends Seeder
{
    public function run(): void
    {
        $pegawais = Pegawai::all()->keyBy('nip');

        $items = [
            [
                'nip' => '1018116701',
                'nama_diklat' => 'Pelatihan Peningkatan Keterampilan Dasar Teknik Instruksional (PEKERTI)',
                'jenis_diklat' => 'Pekerti',
                'penyelenggara' => 'Pusat Pengembangan Pembelajaran, Lembaga Penjaminan Mutu dan Pengembangan Pembelajaran (LPMPP)',
                'tahun' => 2024,
                'jumlah_jam' => 64,
                'nomor_sertifikat' => 'SERT/PEKERTI/2024/0182',
                'tanggal_sertifikat' => '2024-09-20',
                'file_sertifikat' => 'sertifikat_pekerti_1018116701.pdf',
                'keterangan' => 'Pelatihan PEKERTI Dosen Universitas Ibnu Sina',
                'tanggal_diajukan' => '2026-03-10',
                'status' => RiwayatDiklat::STATUS_DIAJUKAN,
            ],
            [
                'nip' => '8842540017',
                'nama_diklat' => 'Pelatihan Applied Approach (AA) Bagi Dosen Perguruan Tinggi',
                'jenis_diklat' => 'Applied Approach',
                'penyelenggara' => 'LLDIKTI Wilayah XVII Riau dan Kepulauan Riau',
                'tahun' => 2025,
                'jumlah_jam' => 48,
                'nomor_sertifikat' => 'SERT/AA/2025/0049',
                'tanggal_sertifikat' => '2025-07-15',
                'file_sertifikat' => 'sertifikat_aa_8842540017.pdf',
                'keterangan' => 'Pengajuan validasi sertifikat AA',
                'tanggal_diajukan' => '2026-03-12',
                'status' => RiwayatDiklat::STATUS_DIAJUKAN,
            ],
            [
                'nip' => '5851777678230192',
                'nama_diklat' => 'Bimbingan Teknis Penyusunan Kurikulum Outcome Based Education (OBE)',
                'jenis_diklat' => 'Workshop / Bimtek',
                'penyelenggara' => 'Lembaga Penjaminan Mutu Internal (LPMI) Universitas Ibnu Sina',
                'tahun' => 2025,
                'jumlah_jam' => 32,
                'nomor_sertifikat' => 'SERT/LPMI/OBE/2025/014',
                'tanggal_sertifikat' => '2025-05-10',
                'file_sertifikat' => 'sertifikat_obe_5851777678230192.pdf',
                'keterangan' => 'Implementasi Kurikulum OBE Prodi Teknik Informatika',
                'tanggal_diajukan' => '2026-02-14',
                'status' => RiwayatDiklat::STATUS_DISETUJUI,
            ],
            [
                'nip' => '3332509122',
                'nama_diklat' => 'Pelatihan Pengelolaan Administrasi Akademik dan PDDIKTI',
                'jenis_diklat' => 'Pelatihan Teknis',
                'penyelenggara' => 'LLDIKTI Wilayah XVII',
                'tahun' => 2025,
                'jumlah_jam' => 24,
                'nomor_sertifikat' => 'SERT/PDDIKTI/2025/091',
                'tanggal_sertifikat' => '2025-08-22',
                'file_sertifikat' => 'sertifikat_pddikti_3332509122.pdf',
                'keterangan' => 'Menunggu unggah lembar nilai/materi pelatihan',
                'tanggal_diajukan' => '2026-03-04',
                'status' => RiwayatDiklat::STATUS_DITANGGUHKAN,
            ],
            [
                'nip' => '1000111010',
                'nama_diklat' => 'Pelatihan Audit Mutu Internal (AMI) Perguruan Tinggi',
                'jenis_diklat' => 'Pelatihan Manajemen',
                'penyelenggara' => 'LPMI Universitas Ibnu Sina',
                'tahun' => 2024,
                'jumlah_jam' => 36,
                'nomor_sertifikat' => 'SERT/AMI/2024/031',
                'tanggal_sertifikat' => '2024-11-18',
                'file_sertifikat' => null,
                'keterangan' => 'Lampiran sertifikat belum diunggah',
                'tanggal_diajukan' => '2026-02-20',
                'status' => RiwayatDiklat::STATUS_DITOLAK,
            ],
            [
                'nip' => '1019038701',
                'nama_diklat' => 'Pelatihan Ahli Keselamatan dan Kesehatan Kerja (AK3) Umum',
                'jenis_diklat' => 'Pelatihan Teknis',
                'penyelenggara' => 'Kementerian Ketenagakerjaan Republik Indonesia',
                'tahun' => 2025,
                'jumlah_jam' => 120,
                'nomor_sertifikat' => 'SERT/AK3U/2025/4421',
                'tanggal_sertifikat' => '2025-11-30',
                'file_sertifikat' => 'sertifikat_ak3u_1019038701.pdf',
                'keterangan' => 'Pengajuan validasi diklat K3',
                'tanggal_diajukan' => '2026-03-14',
                'status' => RiwayatDiklat::STATUS_DIAJUKAN,
            ],
        ];

        foreach ($items as $row) {
            $pegawai = $pegawais->get($row['nip']);
            if (! $pegawai) {
                continue;
            }

            RiwayatDiklat::updateOrCreate(
                [
                    'pegawai_id' => $pegawai->id,
                    'nama_diklat' => $row['nama_diklat'],
                    'tahun' => $row['tahun'],
                ],
                [
                    'jenis_diklat' => $row['jenis_diklat'],
                    'penyelenggara' => $row['penyelenggara'],
                    'jumlah_jam' => $row['jumlah_jam'],
                    'nomor_sertifikat' => $row['nomor_sertifikat'],
                    'tanggal_sertifikat' => $row['tanggal_sertifikat'],
                    'file_sertifikat' => $row['file_sertifikat'],
                    'keterangan' => $row['keterangan'],
                    'tanggal_diajukan' => $row['tanggal_diajukan'],
                    'status' => $row['status'],
                ],
            );
        }
    }
}
