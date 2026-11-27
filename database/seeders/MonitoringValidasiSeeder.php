<?php

namespace Database\Seeders;

use App\Models\HubunganKerja;
use App\Models\JenisRiwayat;
use App\Models\Pegawai;
use App\Models\RiwayatPegawai;
use App\Models\StatusAktif;
use App\Models\UnitKerja;
use Illuminate\Database\Seeder;

class MonitoringValidasiSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed 32 Jenis Riwayat across 9 Kategori sesuai SIMPEG Universitas Ibnu Sina
        $categories = [
            1 => [
                'kategori' => 'Keluarga',
                'items' => [
                    ['kode' => 'pasangan', 'nama' => 'Pasangan'],
                    ['kode' => 'anak', 'nama' => 'Anak'],
                    ['kode' => 'orang_tua', 'nama' => 'Orang Tua'],
                ],
            ],
            2 => [
                'kategori' => 'Kepegawaian',
                'items' => [
                    ['kode' => 'homebase', 'nama' => 'Homebase'],
                    ['kode' => 'pangkat', 'nama' => 'Pangkat'],
                    ['kode' => 'jabatan_akademik', 'nama' => 'Jabatan Akademik'],
                    ['kode' => 'jabatan_fungsional', 'nama' => 'Jabatan Fungsional'],
                    ['kode' => 'jabatan_struktural', 'nama' => 'Jabatan Struktural'],
                    ['kode' => 'hubungan_kerja', 'nama' => 'Hubungan Kerja'],
                ],
            ],
            3 => [
                'kategori' => 'Kualifikasi',
                'items' => [
                    ['kode' => 'pendidikan', 'nama' => 'Pendidikan'],
                    ['kode' => 'diklat', 'nama' => 'Diklat'],
                    ['kode' => 'pekerjaan', 'nama' => 'Pekerjaan'],
                ],
            ],
            4 => [
                'kategori' => 'Kompetensi',
                'items' => [
                    ['kode' => 'sertifikasi', 'nama' => 'Sertifikasi'],
                    ['kode' => 'tes', 'nama' => 'Tes'],
                ],
            ],
            5 => [
                'kategori' => 'Pelaksanaan Pendidikan',
                'items' => [
                    ['kode' => 'visiting_scientist', 'nama' => 'Visiting Scientist'],
                    ['kode' => 'detasering', 'nama' => 'Detasering'],
                    ['kode' => 'bahan_ajar', 'nama' => 'Bahan Ajar'],
                    ['kode' => 'orasi_ilmiah', 'nama' => 'Orasi Ilmiah'],
                    ['kode' => 'pembimbing_dosen', 'nama' => 'Pembimbing Dosen'],
                    ['kode' => 'tugas_tambahan', 'nama' => 'Tugas Tambahan'],
                ],
            ],
            6 => [
                'kategori' => 'Pelaksanaan Penelitian',
                'items' => [
                    ['kode' => 'penelitian', 'nama' => 'Penelitian'],
                    ['kode' => 'publikasi', 'nama' => 'Publikasi'],
                    ['kode' => 'paten', 'nama' => 'Paten'],
                ],
            ],
            7 => [
                'kategori' => 'Pelaksanaan Pengabdian',
                'items' => [
                    ['kode' => 'pengabdian', 'nama' => 'Pengabdian'],
                    ['kode' => 'pengelola_jurnal', 'nama' => 'Pengelola Jurnal'],
                    ['kode' => 'pembicara', 'nama' => 'Pembicara'],
                    ['kode' => 'jabatan_tugas', 'nama' => 'Jabatan Tugas'],
                ],
            ],
            8 => [
                'kategori' => 'Penunjang',
                'items' => [
                    ['kode' => 'anggota_profesi', 'nama' => 'Anggota Profesi'],
                    ['kode' => 'penghargaan', 'nama' => 'Penghargaan'],
                    ['kode' => 'penunjang_lain', 'nama' => 'Penunjang Lain'],
                ],
            ],
            9 => [
                'kategori' => 'Pengembangan',
                'items' => [
                    ['kode' => 'organisasi', 'nama' => 'Organisasi'],
                    ['kode' => 'kemampuan_bahasa', 'nama' => 'Kemampuan Bahasa'],
                ],
            ],
        ];

        $jenisMap = [];
        $urutan = 1;
        foreach ($categories as $urutanKat => $group) {
            foreach ($group['items'] as $item) {
                $jenisMap[$item['kode']] = JenisRiwayat::updateOrCreate(
                    ['kode' => $item['kode']],
                    [
                        'kategori' => $group['kategori'],
                        'nama' => $item['nama'],
                        'urutan_kategori' => $urutanKat,
                        'urutan' => $urutan++,
                    ],
                );
            }
        }

        // 2. Seed sample Pegawai UIS terhubung ke UnitKerja
        $units = UnitKerja::pluck('id', 'kode');
        $statusAktifId = StatusAktif::where('kode', 'AA')->value('id') ?? StatusAktif::value('id');
        $hubunganKerjaId = HubunganKerja::where('kode', 'PT')->value('id') ?? HubunganKerja::value('id');

        $pegawaiData = [
            ['nip' => '1018116701', 'nidn' => '1018116701', 'nama' => 'ABD. RAZAK MUHIDIN, S.Pd., M.Pd.I', 'unit' => '26201'],
            ['nip' => '8842540017', 'nidn' => '8842540017', 'nama' => 'ADRIANTO, S.Pd., M.Pd.', 'unit' => '26201'],
            ['nip' => '1000111010', 'nidn' => '1000111010', 'nama' => 'Adri Wislawawan, S.T., M.T.', 'unit' => '26201'],
            ['nip' => '5851777678230192', 'nidn' => '1015088801', 'nama' => 'AMELIA RACHMI NASUTION, S.T., M.T.', 'unit' => '55201'],
            ['nip' => '1020087901', 'nidn' => '1020087901', 'nama' => 'ANDI HEPY SUSANTI, S.E., M.M.', 'unit' => '61201'],
            ['nip' => '3332509122', 'nidn' => null, 'nama' => 'Anita Cahyati, S.Kom.', 'unit' => '57201'],
            ['nip' => '1014059002', 'nidn' => '1014059002', 'nama' => 'Ropianto, M.Kom.', 'unit' => 'LPTI'],
            ['nip' => '1019038701', 'nidn' => '1019038701', 'nama' => 'dr. Nurul Hidayah, M.K.M.', 'unit' => '13261'],
        ];

        $pegawaiMap = [];
        foreach ($pegawaiData as $row) {
            $pegawaiMap[$row['nip']] = Pegawai::updateOrCreate(
                ['nip' => $row['nip']],
                [
                    'nidn' => $row['nidn'],
                    'nama' => $row['nama'],
                    'unit_kerja_id' => $units[$row['unit']] ?? $units['171033'] ?? $units['UIS'] ?? null,
                    'status_aktif_id' => $statusAktifId,
                    'hubungan_kerja_id' => $hubunganKerjaId,
                ],
            );
        }

        // 3. Seed sample RiwayatPegawai (idempotent by pegawai_id + jenis_riwayat_id + judul)
        $riwayats = [
            ['nip' => '1018116701', 'jenis' => 'pasangan', 'judul' => 'Data Pasangan - Siti Aminah', 'status' => RiwayatPegawai::STATUS_DISETUJUI, 'tgl' => '2026-01-15'],
            ['nip' => '1018116701', 'jenis' => 'anak', 'judul' => 'Data Anak Pertama - Muhammad Fajar', 'status' => RiwayatPegawai::STATUS_DRAFT, 'tgl' => '2026-02-10'],
            ['nip' => '1018116701', 'jenis' => 'homebase', 'judul' => 'Homebase Prodi S1 Teknik Industri', 'status' => RiwayatPegawai::STATUS_DISETUJUI, 'tgl' => '2026-01-05'],
            ['nip' => '1018116701', 'jenis' => 'pendidikan', 'judul' => 'Magister Pendidikan Islam (S2)', 'status' => RiwayatPegawai::STATUS_DIAJUKAN, 'tgl' => '2026-03-01'],
            ['nip' => '8842540017', 'jenis' => 'jabatan_fungsional', 'judul' => 'Lektor 300', 'status' => RiwayatPegawai::STATUS_DIAJUKAN, 'tgl' => '2026-03-04'],
            ['nip' => '8842540017', 'jenis' => 'publikasi', 'judul' => 'Jurnal Nasional Terakreditasi SINTA 3', 'status' => RiwayatPegawai::STATUS_DISETUJUI, 'tgl' => '2026-02-20'],
            ['nip' => '1000111010', 'jenis' => 'pangkat', 'judul' => 'Penata Muda Tk. I (III/b)', 'status' => RiwayatPegawai::STATUS_DITOLAK, 'tgl' => '2026-02-18'],
            ['nip' => '1000111010', 'jenis' => 'penelitian', 'judul' => 'Optimasi Rantai Pasok Industri Batam', 'status' => RiwayatPegawai::STATUS_DIAJUKAN, 'tgl' => '2026-03-10'],
            ['nip' => '5851777678230192', 'jenis' => 'homebase', 'judul' => 'Homebase Prodi S1 Teknik Informatika', 'status' => RiwayatPegawai::STATUS_DISETUJUI, 'tgl' => '2026-01-08'],
            ['nip' => '5851777678230192', 'jenis' => 'sertifikasi', 'judul' => 'Sertifikasi Pendidik (Serdos)', 'status' => RiwayatPegawai::STATUS_DIAJUKAN, 'tgl' => '2026-03-12'],
            ['nip' => '5851777678230192', 'jenis' => 'publikasi', 'judul' => 'Implementasi Machine Learning untuk Deteksi Anomali', 'status' => RiwayatPegawai::STATUS_DITANGGUHKAN, 'tgl' => '2026-03-05'],
            ['nip' => '1020087901', 'jenis' => 'pengabdian', 'judul' => 'Pendampingan UMKM Digital Kota Batam', 'status' => RiwayatPegawai::STATUS_DISETUJUI, 'tgl' => '2026-02-25'],
            ['nip' => '3332509122', 'jenis' => 'diklat', 'judul' => 'Pelatihan Administrasi Akademik Berbasis Web', 'status' => RiwayatPegawai::STATUS_DRAFT, 'tgl' => '2026-03-02'],
            ['nip' => '1014059002', 'jenis' => 'jabatan_struktural', 'judul' => 'Kepala LPTI Universitas Ibnu Sina', 'status' => RiwayatPegawai::STATUS_DISETUJUI, 'tgl' => '2026-01-02'],
            ['nip' => '1019038701', 'jenis' => 'penghargaan', 'judul' => 'Dosen Berprestasi Bidang Kesehatan', 'status' => RiwayatPegawai::STATUS_DIAJUKAN, 'tgl' => '2026-03-14'],
        ];

        foreach ($riwayats as $row) {
            $pegawai = $pegawaiMap[$row['nip']] ?? null;
            $jenis = $jenisMap[$row['jenis']] ?? null;
            if (! $pegawai || ! $jenis) {
                continue;
            }

            RiwayatPegawai::updateOrCreate(
                [
                    'pegawai_id' => $pegawai->id,
                    'jenis_riwayat_id' => $jenis->id,
                    'judul' => $row['judul'],
                ],
                [
                    'status' => $row['status'],
                    'tanggal_pengajuan' => $row['tgl'],
                ],
            );
        }
    }
}
