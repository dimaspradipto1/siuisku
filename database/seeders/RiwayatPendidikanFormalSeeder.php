<?php

namespace Database\Seeders;

use App\Models\GelarAkademik;
use App\Models\JenjangPendidikan;
use App\Models\Pegawai;
use App\Models\PerguruanTinggi;
use App\Models\RiwayatPendidikanFormal;
use Illuminate\Database\Seeder;

class RiwayatPendidikanFormalSeeder extends Seeder
{
    public function run(): void
    {
        $pegawais = Pegawai::all()->keyBy('nip');
        $jenjangs = JenjangPendidikan::all()->keyBy('kode');
        $pts = PerguruanTinggi::all()->keyBy('kode');
        $gelars = GelarAkademik::all()->keyBy('gelar');

        $items = [
            [
                'nip' => '1018116701',
                'jenjang' => 'S2',
                'pt' => '101031',
                'nama_institusi' => 'Universitas Ibnu Sina',
                'program_studi' => 'Magister Manajemen',
                'gelar' => 'M.Pd.I.',
                'tahun_masuk' => 2017,
                'tahun_lulus' => 2019,
                'no_ijazah' => 'IJZ/S2/2019/00412',
                'tanggal_ijazah' => '2019-09-20',
                'file_ijazah' => 'ijazah_s2_1018116701.pdf',
                'ipk' => 3.78,
                'keterangan' => 'Ijazah Strata 2 terverifikasi PDDikti',
                'tanggal_diajukan' => '2026-03-10',
                'status' => RiwayatPendidikanFormal::STATUS_DIAJUKAN,
            ],
            [
                'nip' => '8842540017',
                'jenjang' => 'S2',
                'pt' => '051024',
                'nama_institusi' => 'Universitas Amikom Yogyakarta',
                'program_studi' => 'Magister Teknik Informatika',
                'gelar' => 'M.Pd.',
                'tahun_masuk' => 2018,
                'tahun_lulus' => 2020,
                'no_ijazah' => 'IJZ/S2/2020/01189',
                'tanggal_ijazah' => '2020-08-15',
                'file_ijazah' => 'ijazah_s2_8842540017.pdf',
                'ipk' => 3.85,
                'keterangan' => 'Pengajuan validasi ijazah S2',
                'tanggal_diajukan' => '2026-03-11',
                'status' => RiwayatPendidikanFormal::STATUS_DIAJUKAN,
            ],
            [
                'nip' => '5851777678230192',
                'jenjang' => 'S2',
                'pt' => '051024',
                'nama_institusi' => 'Universitas Amikom Yogyakarta',
                'program_studi' => 'Magister Teknik Informatika',
                'gelar' => 'M.T.',
                'tahun_masuk' => 2016,
                'tahun_lulus' => 2018,
                'no_ijazah' => 'IJZ/S2/2018/00754',
                'tanggal_ijazah' => '2018-10-12',
                'file_ijazah' => 'ijazah_s2_5851777678230192.pdf',
                'ipk' => 3.82,
                'keterangan' => 'Lulusan Magister Teknik Informatika',
                'tanggal_diajukan' => '2026-02-12',
                'status' => RiwayatPendidikanFormal::STATUS_DISETUJUI,
            ],
            [
                'nip' => '3332509122',
                'jenjang' => 'S1',
                'pt' => '101031',
                'nama_institusi' => 'Universitas Ibnu Sina',
                'program_studi' => 'Sistem Informasi',
                'gelar' => 'S.Kom.',
                'tahun_masuk' => 2018,
                'tahun_lulus' => 2022,
                'no_ijazah' => 'IJZ/S1/2022/03105',
                'tanggal_ijazah' => '2022-09-10',
                'file_ijazah' => 'ijazah_s1_3332509122.pdf',
                'ipk' => 3.64,
                'keterangan' => 'Menunggu unggah transkrip nilai asli',
                'tanggal_diajukan' => '2026-03-04',
                'status' => RiwayatPendidikanFormal::STATUS_DITANGGUHKAN,
            ],
            [
                'nip' => '1000111010',
                'jenjang' => 'S2',
                'pt' => '101031',
                'nama_institusi' => 'Universitas Ibnu Sina',
                'program_studi' => 'Teknik Industri',
                'gelar' => 'M.T.',
                'tahun_masuk' => 2017,
                'tahun_lulus' => 2019,
                'no_ijazah' => 'IJZ/S2/2019/00991',
                'tanggal_ijazah' => '2019-11-05',
                'file_ijazah' => null,
                'ipk' => 3.70,
                'keterangan' => 'Scan ijazah belum dilampirkan',
                'tanggal_diajukan' => '2026-02-20',
                'status' => RiwayatPendidikanFormal::STATUS_DITOLAK,
            ],
            [
                'nip' => '1019038701',
                'jenjang' => 'S2',
                'pt' => '101031',
                'nama_institusi' => 'Universitas Ibnu Sina',
                'program_studi' => 'Kesehatan Masyarakat',
                'gelar' => 'M.K.M.',
                'tahun_masuk' => 2019,
                'tahun_lulus' => 2021,
                'no_ijazah' => 'IJZ/S2/2021/00523',
                'tanggal_ijazah' => '2021-08-25',
                'file_ijazah' => 'ijazah_s2_1019038701.pdf',
                'ipk' => 3.90,
                'keterangan' => 'Pengajuan validasi ijazah Magister Kesehatan Masyarakat',
                'tanggal_diajukan' => '2026-03-14',
                'status' => RiwayatPendidikanFormal::STATUS_DIAJUKAN,
            ],
        ];

        foreach ($items as $row) {
            $pegawai = $pegawais->get($row['nip']);
            $jenjang = $jenjangs->get($row['jenjang']) ?? JenjangPendidikan::first();
            if (! $pegawai || ! $jenjang) {
                continue;
            }

            $pt = $pts->get($row['pt']);
            $gelar = $gelars->get($row['gelar']);

            RiwayatPendidikanFormal::updateOrCreate(
                [
                    'pegawai_id' => $pegawai->id,
                    'jenjang_pendidikan_id' => $jenjang->id,
                    'no_ijazah' => $row['no_ijazah'],
                ],
                [
                    'perguruan_tinggi_id' => $pt?->id,
                    'gelar_akademik_id' => $gelar?->id,
                    'nama_institusi' => $pt?->nama ?? $row['nama_institusi'],
                    'program_studi' => $row['program_studi'],
                    'tahun_masuk' => $row['tahun_masuk'],
                    'tahun_lulus' => $row['tahun_lulus'],
                    'tanggal_ijazah' => $row['tanggal_ijazah'],
                    'file_ijazah' => $row['file_ijazah'],
                    'ipk' => $row['ipk'],
                    'keterangan' => $row['keterangan'],
                    'tanggal_diajukan' => $row['tanggal_diajukan'],
                    'status' => $row['status'],
                ],
            );
        }
    }
}
