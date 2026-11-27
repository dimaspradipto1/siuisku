<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\RiwayatHomebase;
use App\Models\UnitKerja;
use Illuminate\Database\Seeder;

class RiwayatHomebaseSeeder extends Seeder
{
    public function run(): void
    {
        $pegawais = Pegawai::all()->keyBy('nip');
        $units = UnitKerja::pluck('id', 'kode');

        $items = [
            [
                'nip' => '1018116701',
                'homebase' => '26201',
                'tmt_tugas' => '2025-09-01',
                'nomor_sk' => 'SK/UIS/HB/2025/014',
                'tanggal_sk' => '2025-08-25',
                'file_sk' => 'sk_homebase_1018116701.pdf',
                'keterangan' => 'Penetapan Homebase Prodi S1 Teknik Industri',
                'tanggal_diajukan' => '2026-03-10',
                'status' => RiwayatHomebase::STATUS_DIAJUKAN,
            ],
            [
                'nip' => '8842540017',
                'homebase' => '26201',
                'tmt_tugas' => '2025-09-01',
                'nomor_sk' => 'SK/UIS/HB/2025/015',
                'tanggal_sk' => '2025-08-25',
                'file_sk' => 'sk_homebase_8842540017.pdf',
                'keterangan' => 'Penetapan Homebase Prodi S1 Teknik Industri',
                'tanggal_diajukan' => '2026-03-11',
                'status' => RiwayatHomebase::STATUS_DIAJUKAN,
            ],
            [
                'nip' => '5851777678230192',
                'homebase' => '55201',
                'tmt_tugas' => '2024-02-01',
                'nomor_sk' => 'SK/UIS/HB/2024/008',
                'tanggal_sk' => '2024-01-20',
                'file_sk' => 'sk_homebase_5851777678230192.pdf',
                'keterangan' => 'Homebase Prodi S1 Teknik Informatika',
                'tanggal_diajukan' => '2026-02-15',
                'status' => RiwayatHomebase::STATUS_DISETUJUI,
            ],
            [
                'nip' => '3332509122',
                'homebase' => '57201',
                'tmt_tugas' => '2025-03-01',
                'nomor_sk' => 'SK/UIS/HB/2025/021',
                'tanggal_sk' => '2025-02-24',
                'file_sk' => null,
                'keterangan' => 'Pengajuan Homebase Prodi S1 Sistem Informasi',
                'tanggal_diajukan' => '2026-03-05',
                'status' => RiwayatHomebase::STATUS_DRAFT,
            ],
            [
                'nip' => '1020087901',
                'homebase' => '61201',
                'tmt_tugas' => '2024-09-01',
                'nomor_sk' => 'SK/UIS/HB/2024/031',
                'tanggal_sk' => '2024-08-28',
                'file_sk' => 'sk_homebase_1020087901.pdf',
                'keterangan' => 'Homebase Prodi S1 Manajemen',
                'tanggal_diajukan' => '2026-02-18',
                'status' => RiwayatHomebase::STATUS_DISETUJUI,
            ],
            [
                'nip' => '1019038701',
                'homebase' => '13261',
                'tmt_tugas' => '2026-01-01',
                'nomor_sk' => 'SK/UIS/HB/2026/002',
                'tanggal_sk' => '2025-12-20',
                'file_sk' => 'sk_homebase_1019038701.pdf',
                'keterangan' => 'Homebase Prodi S1 Keselamatan dan Kesehatan Kerja',
                'tanggal_diajukan' => '2026-03-14',
                'status' => RiwayatHomebase::STATUS_DIAJUKAN,
            ],
        ];

        foreach ($items as $row) {
            $pegawai = $pegawais->get($row['nip']);
            $homebaseUnitId = $units[$row['homebase']] ?? null;
            if (! $pegawai || ! $homebaseUnitId) {
                continue;
            }

            RiwayatHomebase::updateOrCreate(
                [
                    'pegawai_id' => $pegawai->id,
                    'homebase_unit_id' => $homebaseUnitId,
                    'tmt_tugas' => $row['tmt_tugas'],
                ],
                [
                    'nomor_sk' => $row['nomor_sk'],
                    'tanggal_sk' => $row['tanggal_sk'],
                    'file_sk' => $row['file_sk'],
                    'keterangan' => $row['keterangan'],
                    'tanggal_diajukan' => $row['tanggal_diajukan'],
                    'status' => $row['status'],
                ],
            );
        }
    }
}
