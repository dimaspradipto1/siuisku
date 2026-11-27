<?php

namespace Database\Seeders;

use App\Models\JabatanFungsional;
use App\Models\Pegawai;
use App\Models\RiwayatJabatanFungsional;
use Illuminate\Database\Seeder;

class RiwayatJabatanFungsionalSeeder extends Seeder
{
    public function run(): void
    {
        $pegawais = Pegawai::all()->keyBy('nip');
        $lektor300 = JabatanFungsional::where('nama', 'like', '%Lektor 300%')->first() ?? JabatanFungsional::first();
        $lektor200 = JabatanFungsional::where('nama', 'like', '%Lektor 200%')->first() ?? $lektor300;
        $asistenAhli = JabatanFungsional::where('nama', 'like', '%Asisten Ahli%')->first() ?? $lektor300;

        $items = [
            [
                'nip' => '1018116701',
                'jafung' => $lektor300,
                'tmt_jabatan' => '2025-10-01',
                'tanggal_sk' => '2025-09-15',
                'nomor_sk' => 'SK/LLDIKTI-X/JAFUNG/2025/041',
                'file_jabatan' => 'sk_jafung_1018116701_lektor300.pdf',
                'angka_kredit' => 300.00,
                'keterangan' => 'Kenaikan Jabatan Fungsional Lektor 300',
                'tanggal_diajukan' => '2026-03-10',
                'status' => RiwayatJabatanFungsional::STATUS_DIAJUKAN,
            ],
            [
                'nip' => '8842540017',
                'jafung' => $lektor200,
                'tmt_jabatan' => '2025-05-01',
                'tanggal_sk' => '2025-04-20',
                'nomor_sk' => 'SK/LLDIKTI-X/JAFUNG/2025/018',
                'file_jabatan' => 'sk_jafung_8842540017_lektor200.pdf',
                'angka_kredit' => 200.00,
                'keterangan' => 'Pengajuan Jabatan Fungsional Lektor 200',
                'tanggal_diajukan' => '2026-03-11',
                'status' => RiwayatJabatanFungsional::STATUS_DIAJUKAN,
            ],
            [
                'nip' => '5851777678230192',
                'jafung' => $lektor200,
                'tmt_jabatan' => '2024-08-01',
                'tanggal_sk' => '2024-07-22',
                'nomor_sk' => 'SK/LLDIKTI-X/JAFUNG/2024/089',
                'file_jabatan' => 'sk_jafung_5851777678230192_lektor200.pdf',
                'angka_kredit' => 200.00,
                'keterangan' => 'Jabatan Fungsional Lektor 200',
                'tanggal_diajukan' => '2026-02-14',
                'status' => RiwayatJabatanFungsional::STATUS_DISETUJUI,
            ],
            [
                'nip' => '1000111010',
                'jafung' => $asistenAhli,
                'tmt_jabatan' => '2025-01-01',
                'tanggal_sk' => '2024-12-15',
                'nomor_sk' => 'SK/LLDIKTI-X/JAFUNG/2024/112',
                'file_jabatan' => null,
                'angka_kredit' => 150.00,
                'keterangan' => 'Lampiran PAK belum lengkap',
                'tanggal_diajukan' => '2026-02-20',
                'status' => RiwayatJabatanFungsional::STATUS_DITOLAK,
            ],
            [
                'nip' => '1020087901',
                'jafung' => $lektor300,
                'tmt_jabatan' => '2025-07-01',
                'tanggal_sk' => '2025-06-19',
                'nomor_sk' => 'SK/LLDIKTI-X/JAFUNG/2025/027',
                'file_jabatan' => 'sk_jafung_1020087901_lektor300.pdf',
                'angka_kredit' => 300.00,
                'keterangan' => 'Jabatan Fungsional Lektor 300',
                'tanggal_diajukan' => '2026-03-01',
                'status' => RiwayatJabatanFungsional::STATUS_DRAFT,
            ],
            [
                'nip' => '1019038701',
                'jafung' => $asistenAhli,
                'tmt_jabatan' => '2026-01-01',
                'tanggal_sk' => '2025-12-18',
                'nomor_sk' => 'SK/LLDIKTI-X/JAFUNG/2025/145',
                'file_jabatan' => 'sk_jafung_1019038701_aa150.pdf',
                'angka_kredit' => 150.00,
                'keterangan' => 'Pengajuan Jabatan Fungsional Asisten Ahli 150',
                'tanggal_diajukan' => '2026-03-14',
                'status' => RiwayatJabatanFungsional::STATUS_DIAJUKAN,
            ],
        ];

        foreach ($items as $row) {
            $pegawai = $pegawais->get($row['nip']);
            $jafung = $row['jafung'];
            if (! $pegawai || ! $jafung) {
                continue;
            }

            RiwayatJabatanFungsional::updateOrCreate(
                [
                    'pegawai_id' => $pegawai->id,
                    'jabatan_fungsional_id' => $jafung->id,
                    'tmt_jabatan' => $row['tmt_jabatan'],
                ],
                [
                    'tanggal_sk' => $row['tanggal_sk'],
                    'nomor_sk' => $row['nomor_sk'],
                    'file_jabatan' => $row['file_jabatan'],
                    'angka_kredit' => $row['angka_kredit'],
                    'keterangan' => $row['keterangan'],
                    'tanggal_diajukan' => $row['tanggal_diajukan'],
                    'status' => $row['status'],
                ],
            );
        }
    }
}
