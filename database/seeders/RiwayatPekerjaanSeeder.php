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
                'nama_instansi' => 'PT Batamindo Industrial Park',
                'jabatan' => 'Industrial Engineer',
                'jenis_pekerjaan' => 'Karyawan Swasta',
                'area_pekerjaan' => 'Dalam Negeri',
                'tanggal_mulai' => '2016-01-10',
                'tanggal_selesai' => '2019-08-31',
                'file_pendukung' => 'paklaring_1018116701.pdf',
                'keterangan' => 'Pengalaman kerja industri manufaktur sebelum menjadi dosen tetap',
                'tanggal_diajukan' => '2026-03-10',
                'status' => RiwayatPekerjaan::STATUS_DIAJUKAN,
            ],
            [
                'nip' => '8842540017',
                'nama_instansi' => 'SMA Negeri 1 Batam',
                'jabatan' => 'Guru Matematika',
                'jenis_pekerjaan' => 'Tenaga Pendidik',
                'area_pekerjaan' => 'Dalam Negeri',
                'tanggal_mulai' => '2015-07-01',
                'tanggal_selesai' => '2020-06-30',
                'file_pendukung' => 'sk_pengalaman_8842540017.pdf',
                'keterangan' => 'Pengajuan validasi riwayat pekerjaan pendidik',
                'tanggal_diajukan' => '2026-03-12',
                'status' => RiwayatPekerjaan::STATUS_DIAJUKAN,
            ],
            [
                'nip' => '5851777678230192',
                'nama_instansi' => 'PT Telkom Indonesia (Persero) Tbk - Witel Kepri',
                'jabatan' => 'Software Engineer',
                'jenis_pekerjaan' => 'Pegawai BUMN',
                'area_pekerjaan' => 'Dalam Negeri',
                'tanggal_mulai' => '2017-03-01',
                'tanggal_selesai' => '2021-12-31',
                'file_pendukung' => 'paklaring_5851777678230192.pdf',
                'keterangan' => 'Pengalaman pengembangan sistem informasi',
                'tanggal_diajukan' => '2026-02-14',
                'status' => RiwayatPekerjaan::STATUS_DISETUJUI,
            ],
            [
                'nip' => '3332509122',
                'nama_instansi' => 'PT Digital Solusi Batam',
                'jabatan' => 'IT Support & Administrator',
                'jenis_pekerjaan' => 'Karyawan Swasta',
                'area_pekerjaan' => 'Dalam Negeri',
                'tanggal_mulai' => '2022-02-01',
                'tanggal_selesai' => '2024-06-30',
                'file_pendukung' => 'surat_kerja_3332509122.pdf',
                'keterangan' => 'Menunggu verifikasi surat keterangan kerja',
                'tanggal_diajukan' => '2026-03-05',
                'status' => RiwayatPekerjaan::STATUS_DITANGGUHKAN,
            ],
            [
                'nip' => '1000111010',
                'nama_instansi' => 'Keppel Offshore & Marine Singapore',
                'jabatan' => 'Quality Assurance Engineer',
                'jenis_pekerjaan' => 'Tenaga Profesional',
                'area_pekerjaan' => 'Luar Negeri',
                'tanggal_mulai' => '2016-05-01',
                'tanggal_selesai' => '2018-11-30',
                'file_pendukung' => null,
                'keterangan' => 'Lampiran surat keterangan kerja belum diunggah',
                'tanggal_diajukan' => '2026-02-20',
                'status' => RiwayatPekerjaan::STATUS_DITOLAK,
            ],
            [
                'nip' => '1019038701',
                'nama_instansi' => 'RSUD Embung Fatimah Kota Batam',
                'jabatan' => 'Koordinator K3 Rumah Sakit',
                'jenis_pekerjaan' => 'Tenaga Kesehatan',
                'area_pekerjaan' => 'Dalam Negeri',
                'tanggal_mulai' => '2018-01-15',
                'tanggal_selesai' => '2023-12-31',
                'file_pendukung' => 'sk_rsud_1019038701.pdf',
                'keterangan' => 'Pengajuan validasi pengalaman praktisi K3',
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
                    'tanggal_mulai' => $row['tanggal_mulai'],
                ],
                [
                    'jabatan' => $row['jabatan'],
                    'jenis_pekerjaan' => $row['jenis_pekerjaan'],
                    'area_pekerjaan' => $row['area_pekerjaan'],
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
