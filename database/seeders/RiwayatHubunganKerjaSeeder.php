<?php

namespace Database\Seeders;

use App\Models\HubunganKerja;
use App\Models\Pegawai;
use App\Models\RiwayatHubunganKerja;
use Illuminate\Database\Seeder;

class RiwayatHubunganKerjaSeeder extends Seeder
{
    public function run(): void
    {
        $pegawais = Pegawai::all()->keyBy('nip');
        $hubunganAll = HubunganKerja::orderBy('id')->get()->values();

        if ($hubunganAll->isEmpty()) {
            return;
        }

        $items = [
            [
                'nip' => '1018116701',
                'hub_idx' => 0,
                'tanggal_mulai' => '2024-04-22',
                'tanggal_selesai' => null,
                'nomor_sk' => 'SK/UIS/HK/2024/019',
                'tanggal_sk' => '2024-04-15',
                'file_hubungan_kerja' => 'SK_Hubungan_Kerja_1018116701.pdf',
                'keterangan' => 'Pengangkatan Pegawai Tetap Yayasan',
                'tanggal_diajukan' => '2026-03-10',
                'status' => RiwayatHubunganKerja::STATUS_DIAJUKAN,
            ],
            [
                'nip' => '8842540017',
                'hub_idx' => 1,
                'tanggal_mulai' => '2025-01-02',
                'tanggal_selesai' => '2026-12-31',
                'nomor_sk' => 'SK/UIS/HK/2025/004',
                'tanggal_sk' => '2024-12-28',
                'file_hubungan_kerja' => 'SK_Hubungan_Kerja_8842540017.pdf',
                'keterangan' => 'Perpanjangan Kontrak Kerja 2 Tahun',
                'tanggal_diajukan' => '2026-03-12',
                'status' => RiwayatHubunganKerja::STATUS_DIAJUKAN,
            ],
            [
                'nip' => '5851777678230192',
                'hub_idx' => 0,
                'tanggal_mulai' => '2023-08-01',
                'tanggal_selesai' => null,
                'nomor_sk' => 'SK/UIS/HK/2023/022',
                'tanggal_sk' => '2023-07-25',
                'file_hubungan_kerja' => 'SK_Hubungan_Kerja_5851777678230192.pdf',
                'keterangan' => 'Pegawai Tetap Yayasan',
                'tanggal_diajukan' => '2026-02-11',
                'status' => RiwayatHubunganKerja::STATUS_DISETUJUI,
            ],
            [
                'nip' => '3332509122',
                'hub_idx' => 1,
                'tanggal_mulai' => '2025-03-01',
                'tanggal_selesai' => '2026-02-28',
                'nomor_sk' => 'SK/UIS/HK/2025/011',
                'tanggal_sk' => '2025-02-20',
                'file_hubungan_kerja' => null,
                'keterangan' => 'Berkas kontrak belum ditandatangani',
                'tanggal_diajukan' => '2026-02-19',
                'status' => RiwayatHubunganKerja::STATUS_DITOLAK,
            ],
            [
                'nip' => '1020087901',
                'hub_idx' => 0,
                'tanggal_mulai' => '2024-09-01',
                'tanggal_selesai' => null,
                'nomor_sk' => 'SK/UIS/HK/2024/031',
                'tanggal_sk' => '2024-08-26',
                'file_hubungan_kerja' => 'SK_Hubungan_Kerja_1020087901.pdf',
                'keterangan' => 'Draft pembaruan data hubungan kerja',
                'tanggal_diajukan' => '2026-03-01',
                'status' => RiwayatHubunganKerja::STATUS_DRAFT,
            ],
            [
                'nip' => '1019038701',
                'hub_idx' => 0,
                'tanggal_mulai' => '2026-01-01',
                'tanggal_selesai' => null,
                'nomor_sk' => 'SK/UIS/HK/2026/001',
                'tanggal_sk' => '2025-12-20',
                'file_hubungan_kerja' => 'SK_Hubungan_Kerja_1019038701.pdf',
                'keterangan' => 'Pengajuan SK Hubungan Kerja Tetap',
                'tanggal_diajukan' => '2026-03-14',
                'status' => RiwayatHubunganKerja::STATUS_DIAJUKAN,
            ],
        ];

        foreach ($items as $row) {
            $pegawai = $pegawais->get($row['nip']);
            $hubungan = $hubunganAll->get($row['hub_idx'] % $hubunganAll->count());
            if (! $pegawai || ! $hubungan) {
                continue;
            }

            RiwayatHubunganKerja::updateOrCreate(
                [
                    'pegawai_id' => $pegawai->id,
                    'hubungan_kerja_id' => $hubungan->id,
                    'tanggal_mulai' => $row['tanggal_mulai'],
                ],
                [
                    'tanggal_selesai' => $row['tanggal_selesai'],
                    'nomor_sk' => $row['nomor_sk'],
                    'tanggal_sk' => $row['tanggal_sk'],
                    'file_hubungan_kerja' => $row['file_hubungan_kerja'],
                    'keterangan' => $row['keterangan'],
                    'tanggal_diajukan' => $row['tanggal_diajukan'],
                    'status' => $row['status'],
                ],
            );
        }
    }
}
