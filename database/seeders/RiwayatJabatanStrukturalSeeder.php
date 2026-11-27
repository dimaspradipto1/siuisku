<?php

namespace Database\Seeders;

use App\Models\JabatanStruktural;
use App\Models\Pegawai;
use App\Models\RiwayatJabatanStruktural;
use Illuminate\Database\Seeder;

class RiwayatJabatanStrukturalSeeder extends Seeder
{
    public function run(): void
    {
        $pegawais = Pegawai::all()->keyBy('nip');
        $jabatans = JabatanStruktural::orderBy('urutan')->orderBy('id')->get()->values();

        if ($jabatans->isEmpty()) {
            return;
        }

        $items = [
            [
                'nip' => '1018116701',
                'jabatan_idx' => 0,
                'tanggal_mulai' => '2025-09-01',
                'tanggal_selesai' => '2029-08-31',
                'nomor_sk' => 'SK/UIS/STR/2025/012',
                'tanggal_sk' => '2025-08-25',
                'file_jabatan' => 'sk_struktural_1018116701.pdf',
                'keterangan' => 'Pengangkatan Jabatan Struktural Periode 2025-2029',
                'tanggal_diajukan' => '2026-03-10',
                'status' => RiwayatJabatanStruktural::STATUS_DIAJUKAN,
            ],
            [
                'nip' => '8842540017',
                'jabatan_idx' => 1,
                'tanggal_mulai' => '2025-09-01',
                'tanggal_selesai' => '2029-08-31',
                'nomor_sk' => 'SK/UIS/STR/2025/015',
                'tanggal_sk' => '2025-08-26',
                'file_jabatan' => 'sk_struktural_8842540017.pdf',
                'keterangan' => 'Pengajuan SK Jabatan Struktural',
                'tanggal_diajukan' => '2026-03-11',
                'status' => RiwayatJabatanStruktural::STATUS_DIAJUKAN,
            ],
            [
                'nip' => '1014059002',
                'jabatan_idx' => 2,
                'tanggal_mulai' => '2024-01-02',
                'tanggal_selesai' => '2028-01-01',
                'nomor_sk' => 'SK/UIS/STR/2024/003',
                'tanggal_sk' => '2023-12-28',
                'file_jabatan' => 'sk_struktural_1014059002.pdf',
                'keterangan' => 'Kepala LPTI Universitas Ibnu Sina',
                'tanggal_diajukan' => '2026-02-05',
                'status' => RiwayatJabatanStruktural::STATUS_DISETUJUI,
            ],
            [
                'nip' => '5851777678230192',
                'jabatan_idx' => 3,
                'tanggal_mulai' => '2025-02-01',
                'tanggal_selesai' => '2029-01-31',
                'nomor_sk' => 'SK/UIS/STR/2025/008',
                'tanggal_sk' => '2025-01-24',
                'file_jabatan' => null,
                'keterangan' => 'File SK belum diunggah',
                'tanggal_diajukan' => '2026-02-22',
                'status' => RiwayatJabatanStruktural::STATUS_DITOLAK,
            ],
            [
                'nip' => '1020087901',
                'jabatan_idx' => 4,
                'tanggal_mulai' => '2025-07-01',
                'tanggal_selesai' => '2029-06-30',
                'nomor_sk' => 'SK/UIS/STR/2025/021',
                'tanggal_sk' => '2025-06-20',
                'file_jabatan' => 'sk_struktural_1020087901.pdf',
                'keterangan' => 'Draft pengajuan jabatan struktural',
                'tanggal_diajukan' => '2026-03-01',
                'status' => RiwayatJabatanStruktural::STATUS_DRAFT,
            ],
            [
                'nip' => '1019038701',
                'jabatan_idx' => 5,
                'tanggal_mulai' => '2026-01-01',
                'tanggal_selesai' => '2030-01-01',
                'nomor_sk' => 'SK/UIS/STR/2026/002',
                'tanggal_sk' => '2025-12-22',
                'file_jabatan' => 'sk_struktural_1019038701.pdf',
                'keterangan' => 'Pengajuan SK Jabatan Struktural FIK',
                'tanggal_diajukan' => '2026-03-14',
                'status' => RiwayatJabatanStruktural::STATUS_DIAJUKAN,
            ],
        ];

        foreach ($items as $row) {
            $pegawai = $pegawais->get($row['nip']);
            $jabatan = $jabatans->get($row['jabatan_idx'] % $jabatans->count());
            if (! $pegawai || ! $jabatan) {
                continue;
            }

            RiwayatJabatanStruktural::updateOrCreate(
                [
                    'pegawai_id' => $pegawai->id,
                    'jabatan_struktural_id' => $jabatan->id,
                    'tanggal_mulai' => $row['tanggal_mulai'],
                ],
                [
                    'tanggal_selesai' => $row['tanggal_selesai'],
                    'nomor_sk' => $row['nomor_sk'],
                    'tanggal_sk' => $row['tanggal_sk'],
                    'file_jabatan' => $row['file_jabatan'],
                    'keterangan' => $row['keterangan'],
                    'tanggal_diajukan' => $row['tanggal_diajukan'],
                    'status' => $row['status'],
                ],
            );
        }
    }
}
