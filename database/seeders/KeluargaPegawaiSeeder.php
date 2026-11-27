<?php

namespace Database\Seeders;

use App\Models\KeluargaPegawai;
use App\Models\KlasifikasiSdm;
use App\Models\Pegawai;
use Illuminate\Database\Seeder;

class KeluargaPegawaiSeeder extends Seeder
{
    public function run(): void
    {
        // Hubungkan pegawai sampel ke Klasifikasi SDM (Jabatan Akademik) jika belum terisi
        $dosenTetap = KlasifikasiSdm::where('nama', 'like', '%Dosen Tetap%')->first()
            ?? KlasifikasiSdm::first();
        $tendik = KlasifikasiSdm::where('jenis_jabatan', 'Tenaga Kependidikan')->first()
            ?? $dosenTetap;

        $pegawais = Pegawai::all()->keyBy('nip');

        foreach ($pegawais as $pegawai) {
            if (! $pegawai->klasifikasi_sdm_id) {
                $pegawai->update([
                    'klasifikasi_sdm_id' => $pegawai->nidn ? $dosenTetap?->id : $tendik?->id,
                ]);
            }
        }

        $items = [
            // Pasangan
            [
                'nip' => '1018116701',
                'hubungan' => KeluargaPegawai::HUBUNGAN_PASANGAN,
                'nama' => 'Siti Aminah, S.Pd.',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Batam',
                'tanggal_lahir' => '1988-05-14',
                'kartu_nikah' => 'KN-2171-0458/2014',
                'status_hubungan' => 'Istri',
                'pekerjaan' => 'Guru',
                'tanggal_diajukan' => '2026-03-10',
                'status' => KeluargaPegawai::STATUS_DIAJUKAN,
            ],
            [
                'nip' => '8842540017',
                'hubungan' => KeluargaPegawai::HUBUNGAN_PASANGAN,
                'nama' => 'Dewi Sartika, S.E.',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Tanjungpinang',
                'tanggal_lahir' => '1990-08-21',
                'kartu_nikah' => 'KN-2172-0912/2016',
                'status_hubungan' => 'Istri',
                'pekerjaan' => 'Karyawan Swasta',
                'tanggal_diajukan' => '2026-03-12',
                'status' => KeluargaPegawai::STATUS_DIAJUKAN,
            ],
            [
                'nip' => '5851777678230192',
                'hubungan' => KeluargaPegawai::HUBUNGAN_PASANGAN,
                'nama' => 'Rizky Pratama, S.T.',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Medan',
                'tanggal_lahir' => '1987-11-03',
                'kartu_nikah' => 'KN-1271-0231/2015',
                'status_hubungan' => 'Suami',
                'pekerjaan' => 'Wiraswasta',
                'tanggal_diajukan' => '2026-02-20',
                'status' => KeluargaPegawai::STATUS_DISETUJUI,
            ],
            // Anak
            [
                'nip' => '1018116701',
                'hubungan' => KeluargaPegawai::HUBUNGAN_ANAK,
                'nama' => 'Muhammad Fajar',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Batam',
                'tanggal_lahir' => '2016-04-12',
                'kartu_nikah' => 'AKTA-2171-2016-0082',
                'status_hubungan' => 'Anak Kandung',
                'pekerjaan' => 'Pelajar',
                'tanggal_diajukan' => '2026-03-11',
                'status' => KeluargaPegawai::STATUS_DIAJUKAN,
            ],
            [
                'nip' => '1000111010',
                'hubungan' => KeluargaPegawai::HUBUNGAN_ANAK,
                'nama' => 'Aisyah Putri',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Batam',
                'tanggal_lahir' => '2019-09-25',
                'kartu_nikah' => 'AKTA-2171-2019-0411',
                'status_hubungan' => 'Anak Kandung',
                'pekerjaan' => 'Pelajar',
                'tanggal_diajukan' => '2026-03-05',
                'status' => KeluargaPegawai::STATUS_DRAFT,
            ],
            // Orang Tua
            [
                'nip' => '1018116701',
                'hubungan' => KeluargaPegawai::HUBUNGAN_ORANG_TUA,
                'nama' => 'H. Muhidin',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Pekanbaru',
                'tanggal_lahir' => '1958-02-10',
                'kartu_nikah' => null,
                'status_hubungan' => 'Ayah',
                'pekerjaan' => 'Pensiunan',
                'tanggal_diajukan' => '2026-03-13',
                'status' => KeluargaPegawai::STATUS_DIAJUKAN,
            ],
            [
                'nip' => '1020087901',
                'hubungan' => KeluargaPegawai::HUBUNGAN_ORANG_TUA,
                'nama' => 'Hj. Rosmawati',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Padang',
                'tanggal_lahir' => '1961-07-19',
                'kartu_nikah' => null,
                'status_hubungan' => 'Ibu',
                'pekerjaan' => 'Ibu Rumah Tangga',
                'tanggal_diajukan' => '2026-02-14',
                'status' => KeluargaPegawai::STATUS_DISETUJUI,
            ],
        ];

        foreach ($items as $row) {
            $pegawai = $pegawais->get($row['nip']);
            if (! $pegawai) {
                continue;
            }

            KeluargaPegawai::updateOrCreate(
                [
                    'pegawai_id' => $pegawai->id,
                    'hubungan' => $row['hubungan'],
                    'nama' => $row['nama'],
                ],
                [
                    'jenis_kelamin' => $row['jenis_kelamin'],
                    'tempat_lahir' => $row['tempat_lahir'],
                    'tanggal_lahir' => $row['tanggal_lahir'],
                    'kartu_nikah' => $row['kartu_nikah'],
                    'status_hubungan' => $row['status_hubungan'],
                    'pekerjaan' => $row['pekerjaan'],
                    'tanggal_diajukan' => $row['tanggal_diajukan'],
                    'status' => $row['status'],
                ],
            );
        }
    }
}
