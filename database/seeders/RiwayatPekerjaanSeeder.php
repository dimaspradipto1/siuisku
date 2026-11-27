<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\RiwayatPekerjaan;
use Illuminate\Database\Seeder;

class RiwayatPekerjaanSeeder extends Seeder
{
    public function run(): void
    {
        $pegawais = Pegawai::all()->keyBy('nip');

        $items = [
            [
                'nip' => '1018116701',
                'nama_instansi' => 'PT Batamindo Investment Cakrawala',
                'jenis_pekerjaan' => 'Konsultan Teknik Industri',
                'jabatan' => 'Industrial Engineering Specialist',
                'area_pekerjaan' => 'Dalam Negeri',
                'tanggal_mulai' => '2018-01-10',
                'tanggal_selesai' => '2021-08-31',
                'file_pendukung' => 'paklaring_1018116701.pdf',
                'keterangan' => 'Pengalaman kerja industri manufaktur di Batamindo',
                'tanggal_diajukan' => '2026-03-10',
                'status' => RiwayatPekerjaan::STATUS_DIAJUKAN,
            ],
            [
                'nip' => '8842540017',
                'nama_instansi' => 'SMK Negeri 1 Batam',
                'jenis_pekerjaan' => 'Tenaga Pendidik',
                'jabatan' => 'Guru Produktif',
                'area_pekerjaan' => 'Dalam Negeri',
                'tanggal_mulai' => '2017-07-01',
                'tanggal_selesai' => '2022-06-30',
                'file_pendukung' => 'sk_mengajar_8842540017.pdf',
                'keterangan' => 'Pengalaman mengajar sebelum bergabung di UIS',
                'tanggal_diajukan' => '2026-03-11',
                'status' => RiwayatPekerjaan::STATUS_DIAJUKAN,
            ],
            [
                'nip' => '5851777678230192',
                'nama_instansi' => 'Nongsa Digital Park Batam',
                'jenis_pekerjaan' => 'Software Engineer',
                'jabatan' => 'Senior Backend Developer',
                'area_pekerjaan' => 'Dalam Negeri',
                'tanggal_mulai' => '2019-03-01',
                'tanggal_selesai' => '2023-07-31',
                'file_pendukung' => 'paklaring_5851777678230192.pdf',
                'keterangan' => 'Pengembangan aplikasi enterprise',
                'tanggal_diajukan' => '2026-02-14',
                'status' => RiwayatPekerjaan::STATUS_DISETUJUI,
            ],
            [
                'nip' => '3332509122',
                'nama_instansi' => 'PT Bank Riau Kepri Syariah',
                'jenis_pekerjaan' => 'Administrasi & IT Support',
                'jabatan' => 'Staf IT Operasional',
                'area_pekerjaan' => 'Dalam Negeri',
                'tanggal_mulai' => '2022-10-01',
                'tanggal_selesai' => '2024-12-31',
                'file_pendukung' => 'paklaring_3332509122.pdf',
                'keterangan' => 'Menunggu verifikasi surat keterangan kerja',
                'tanggal_diajukan' => '2026-03-05',
                'status' => RiwayatPekerjaan::STATUS_DITANGGUHKAN,
            ],
            [
                'nip' => '1000111010',
                'nama_instansi' => 'Keppel Shipyard Singapore',
                'jenis_pekerjaan' => 'Engineer',
                'jabatan' => 'Production Planning Engineer',
                'area_pekerjaan' => 'Luar Negeri',
                'tanggal_mulai' => '2016-05-01',
                'tanggal_selesai' => '2020-04-30',
                'file_pendukung' => null,
                'keterangan' => 'Lampiran surat pengalaman kerja belum diunggah',
                'tanggal_diajukan' => '2026-02-21',
                'status' => RiwayatPekerjaan::STATUS_DITOLAK,
            ],
            [
                'nip' => '1019038701',
                'nama_instansi' => 'RS Awal Bros Batam',
                'jenis_pekerjaan' => 'Tenaga Kesehatan',
                'jabatan' => 'Koordinator K3 Rumah Sakit',
                'area_pekerjaan' => 'Dalam Negeri',
                'tanggal_mulai' => '2020-02-01',
                'tanggal_selesai' => '2025-08-31',
                'file_pendukung' => 'paklaring_1019038701.pdf',
                'keterangan' => 'Pengajuan validasi riwayat pekerjaan K3RS',
                'tanggal_diajukan' => '2026-03-14',
                'status' => RiwayatPekerjaan::STATUS_DIAJUKAN,
            ],
        ];

        foreach ($items as $row) {
            $pegawai = $pegawais->get($row['nip']);
            if (! $pegawai) {
                continue;
            }

            RiwayatPekerjaan::updateOrCreate(
                [
                    'pegawai_id' => $pegawai->id,
                    'nama_instansi' => $row['nama_instansi'],
                    'jenis_pekerjaan' => $row['jenis_pekerjaan'],
                ],
                [
                    'jabatan' => $row['jabatan'],
                    'area_pekerjaan' => $row['area_pekerjaan'],
                    'tanggal_mulai' => $row['tanggal_mulai'],
                    'tanggal_selesai' => $row['tanggal_selesai'],
                    'file_pendukung' => $row['file_pendukung'],
                    'keterangan' => $row['keterangan'],
                    'tanggal_diajukan' => $row['tanggal_diajukan'],
                    'status' => $row['status'],
                ],
            );
        }
    }
}
