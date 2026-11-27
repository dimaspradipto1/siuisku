<?php

namespace Database\Seeders;

use App\Models\JenisSk;
use App\Models\Pangkat;
use App\Models\Pegawai;
use App\Models\RiwayatPangkat;
use Illuminate\Database\Seeder;

class RiwayatPangkatSeeder extends Seeder
{
    public function run(): void
    {
        $pegawais = Pegawai::all()->keyBy('nip');
        $pangkats = Pangkat::all()->keyBy('pangkat');
        $jenisSkDefault = JenisSk::first();
        $jenisSkAll = JenisSk::all()->values();

        $items = [
            [
                'nip' => '1018116701',
                'pangkat' => 'III C',
                'sk_idx' => 0,
                'tmt_pangkat' => '2025-10-01',
                'nomor_sk' => 'SK/UIS/PKT/2025/011',
                'tanggal_sk' => '2025-09-20',
                'file_pangkat' => 'sk_pangkat_1018116701_iiic.pdf',
                'mk_tahun' => 8,
                'mk_bulan' => 4,
                'keterangan' => 'Kenaikan pangkat reguler Penata (III/c)',
                'tanggal_diajukan' => '2026-03-10',
                'status' => RiwayatPangkat::STATUS_DIAJUKAN,
            ],
            [
                'nip' => '8842540017',
                'pangkat' => 'III B',
                'sk_idx' => 1,
                'tmt_pangkat' => '2025-04-01',
                'nomor_sk' => 'SK/UIS/PKT/2025/004',
                'tanggal_sk' => '2025-03-18',
                'file_pangkat' => 'sk_pangkat_8842540017_iiib.pdf',
                'mk_tahun' => 5,
                'mk_bulan' => 0,
                'keterangan' => 'Penyetaraan pangkat Penata Muda Tk. I (III/b)',
                'tanggal_diajukan' => '2026-03-12',
                'status' => RiwayatPangkat::STATUS_DIAJUKAN,
            ],
            [
                'nip' => '5851777678230192',
                'pangkat' => 'III C',
                'sk_idx' => 0,
                'tmt_pangkat' => '2024-10-01',
                'nomor_sk' => 'SK/UIS/PKT/2024/029',
                'tanggal_sk' => '2024-09-25',
                'file_pangkat' => 'sk_pangkat_5851777678230192_iiic.pdf',
                'mk_tahun' => 7,
                'mk_bulan' => 6,
                'keterangan' => 'Kenaikan pangkat Penata (III/c)',
                'tanggal_diajukan' => '2026-02-10',
                'status' => RiwayatPangkat::STATUS_DISETUJUI,
            ],
            [
                'nip' => '1000111010',
                'pangkat' => 'III B',
                'sk_idx' => 0,
                'tmt_pangkat' => '2025-01-01',
                'nomor_sk' => 'SK/UIS/PKT/2025/001',
                'tanggal_sk' => '2024-12-20',
                'file_pangkat' => 'sk_pangkat_1000111010_iiib.pdf',
                'mk_tahun' => 4,
                'mk_bulan' => 2,
                'keterangan' => 'Berkas lampiran SK perlu diperjelas',
                'tanggal_diajukan' => '2026-02-18',
                'status' => RiwayatPangkat::STATUS_DITOLAK,
            ],
            [
                'nip' => '1020087901',
                'pangkat' => 'III D',
                'sk_idx' => 0,
                'tmt_pangkat' => '2026-01-01',
                'nomor_sk' => 'SK/UIS/PKT/2026/003',
                'tanggal_sk' => '2025-12-28',
                'file_pangkat' => 'sk_pangkat_1020087901_iiid.pdf',
                'mk_tahun' => 11,
                'mk_bulan' => 3,
                'keterangan' => 'Menunggu verifikasi SK Inpassing',
                'tanggal_diajukan' => '2026-03-14',
                'status' => RiwayatPangkat::STATUS_DITANGGUHKAN,
            ],
            [
                'nip' => '1019038701',
                'pangkat' => 'III B',
                'sk_idx' => 0,
                'tmt_pangkat' => '2026-02-01',
                'nomor_sk' => 'SK/UIS/PKT/2026/007',
                'tanggal_sk' => '2026-01-20',
                'file_pangkat' => 'sk_pangkat_1019038701_iiib.pdf',
                'mk_tahun' => 6,
                'mk_bulan' => 0,
                'keterangan' => 'Pengajuan kenaikan pangkat Penata Muda Tk. I (III/b)',
                'tanggal_diajukan' => '2026-03-15',
                'status' => RiwayatPangkat::STATUS_DIAJUKAN,
            ],
        ];

        foreach ($items as $row) {
            $pegawai = $pegawais->get($row['nip']);
            $pangkat = $pangkats->get($row['pangkat']) ?? Pangkat::first();
            if (! $pegawai || ! $pangkat) {
                continue;
            }

            $jenisSk = $jenisSkAll->get($row['sk_idx']) ?? $jenisSkDefault;

            RiwayatPangkat::updateOrCreate(
                [
                    'pegawai_id' => $pegawai->id,
                    'pangkat_id' => $pangkat->id,
                    'tmt_pangkat' => $row['tmt_pangkat'],
                ],
                [
                    'jenis_sk_id' => $jenisSk?->id,
                    'nomor_sk' => $row['nomor_sk'],
                    'tanggal_sk' => $row['tanggal_sk'],
                    'file_pangkat' => $row['file_pangkat'],
                    'masa_kerja_tahun' => $row['mk_tahun'],
                    'masa_kerja_bulan' => $row['mk_bulan'],
                    'keterangan' => $row['keterangan'],
                    'tanggal_diajukan' => $row['tanggal_diajukan'],
                    'status' => $row['status'],
                ],
            );
        }
    }
}
