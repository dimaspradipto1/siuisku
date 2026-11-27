<?php

namespace Database\Seeders;

use App\Models\KlasifikasiSdm;
use App\Models\Pegawai;
use App\Models\RiwayatKlasifikasiSdm;
use Illuminate\Database\Seeder;

class RiwayatKlasifikasiSdmSeeder extends Seeder
{
    public function run(): void
    {
        $pegawais = Pegawai::all()->keyBy('nip');
        $dosenTetap = KlasifikasiSdm::where('nama', 'like', '%Dosen Tetap%')->first() ?? KlasifikasiSdm::first();
        $asistenDosen = KlasifikasiSdm::where('nama', 'like', '%Asisten%')->first() ?? $dosenTetap;
        $tendik = KlasifikasiSdm::where('jenis_jabatan', 'Tenaga Kependidikan')->first() ?? $dosenTetap;

        $items = [
            [
                'nip' => '1018116701',
                'sdm' => $dosenTetap,
                'tmt_jabatan' => '2025-09-01',
                'tanggal_sk' => '2025-08-20',
                'nomor_sk' => 'SK/UIS/SDM/2025/018',
                'file_jabatan' => 'sk_jabatan_akademik_1018116701.pdf',
                'keterangan' => 'Penetapan Jabatan Akademik Dosen Tetap Yayasan',
                'tanggal_diajukan' => '2026-03-10',
                'status' => RiwayatKlasifikasiSdm::STATUS_DIAJUKAN,
            ],
            [
                'nip' => '8842540017',
                'sdm' => $dosenTetap,
                'tmt_jabatan' => '2025-09-01',
                'tanggal_sk' => '2025-08-22',
                'nomor_sk' => 'SK/UIS/SDM/2025/019',
                'file_jabatan' => 'sk_jabatan_akademik_8842540017.pdf',
                'keterangan' => 'Penetapan Klasifikasi SDM Dosen Tetap Yayasan',
                'tanggal_diajukan' => '2026-03-11',
                'status' => RiwayatKlasifikasiSdm::STATUS_DIAJUKAN,
            ],
            [
                'nip' => '5851777678230192',
                'sdm' => $dosenTetap,
                'tmt_jabatan' => '2024-02-01',
                'tanggal_sk' => '2024-01-18',
                'nomor_sk' => 'SK/UIS/SDM/2024/005',
                'file_jabatan' => 'sk_jabatan_akademik_5851777678230192.pdf',
                'keterangan' => 'Dosen Tetap Yayasan Prodi Teknik Informatika',
                'tanggal_diajukan' => '2026-02-12',
                'status' => RiwayatKlasifikasiSdm::STATUS_DISETUJUI,
            ],
            [
                'nip' => '3332509122',
                'sdm' => $tendik,
                'tmt_jabatan' => '2025-03-01',
                'tanggal_sk' => '2025-02-20',
                'nomor_sk' => 'SK/UIS/SDM/2025/009',
                'file_jabatan' => 'sk_tendik_3332509122.pdf',
                'keterangan' => 'Penempatan Tenaga Kependidikan',
                'tanggal_diajukan' => '2026-03-06',
                'status' => RiwayatKlasifikasiSdm::STATUS_DITANGGUHKAN,
            ],
            [
                'nip' => '1000111010',
                'sdm' => $asistenDosen,
                'tmt_jabatan' => '2025-01-01',
                'tanggal_sk' => '2024-12-19',
                'nomor_sk' => 'SK/UIS/SDM/2025/002',
                'file_jabatan' => null,
                'keterangan' => 'File SK belum dilampirkan',
                'tanggal_diajukan' => '2026-02-21',
                'status' => RiwayatKlasifikasiSdm::STATUS_DITOLAK,
            ],
            [
                'nip' => '1019038701',
                'sdm' => $dosenTetap,
                'tmt_jabatan' => '2026-01-01',
                'tanggal_sk' => '2025-12-22',
                'nomor_sk' => 'SK/UIS/SDM/2026/004',
                'file_jabatan' => 'sk_jabatan_akademik_1019038701.pdf',
                'keterangan' => 'Pengajuan Klasifikasi SDM Dosen Tetap',
                'tanggal_diajukan' => '2026-03-14',
                'status' => RiwayatKlasifikasiSdm::STATUS_DIAJUKAN,
            ],
        ];

        foreach ($items as $row) {
            $pegawai = $pegawais->get($row['nip']);
            $sdm = $row['sdm'];
            if (! $pegawai || ! $sdm) {
                continue;
            }

            RiwayatKlasifikasiSdm::updateOrCreate(
                [
                    'pegawai_id' => $pegawai->id,
                    'klasifikasi_sdm_id' => $sdm->id,
                    'tmt_jabatan' => $row['tmt_jabatan'],
                ],
                [
                    'tanggal_sk' => $row['tanggal_sk'],
                    'nomor_sk' => $row['nomor_sk'],
                    'file_jabatan' => $row['file_jabatan'],
                    'keterangan' => $row['keterangan'],
                    'tanggal_diajukan' => $row['tanggal_diajukan'],
                    'status' => $row['status'],
                ],
            );
        }
    }
}
