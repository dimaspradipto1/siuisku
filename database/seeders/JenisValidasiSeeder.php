<?php

namespace Database\Seeders;

use App\Models\JenisValidasi;
use App\Models\Role;
use App\Models\SettingValidasiStep;
use Illuminate\Database\Seeder;

class JenisValidasiSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['kode' => 'C', 'nama' => 'Pengajuan Cuti'],
            ['kode' => 'I', 'nama' => 'Pengajuan Izin'],
            ['kode' => 'KGH', 'nama' => 'Kegiatan Harian'],
        ];

        $validasiMap = [];
        foreach ($items as $item) {
            $validasiMap[$item['kode']] = JenisValidasi::updateOrCreate(
                ['kode' => $item['kode']],
                ['nama' => $item['nama']],
            );
        }

        $roleKepalaUnit = Role::where('kode', 'kepala_lembaga')->first();
        $roleAdminKepegawaian = Role::where('kode', 'admin_kepegawaian')->first();

        $defaultSteps = [
            'C' => [
                ['urutan' => 1, 'nama' => 'Validasi Atasan Langsung / Kepala Unit', 'role_id' => $roleKepalaUnit?->id],
                ['urutan' => 2, 'nama' => 'Validasi Admin Kepegawaian', 'role_id' => $roleAdminKepegawaian?->id],
            ],
            'I' => [
                ['urutan' => 1, 'nama' => 'Validasi Atasan Langsung / Kepala Unit', 'role_id' => $roleKepalaUnit?->id],
                ['urutan' => 2, 'nama' => 'Validasi Admin Kepegawaian', 'role_id' => $roleAdminKepegawaian?->id],
            ],
            'KGH' => [
                ['urutan' => 1, 'nama' => 'Validasi Atasan Langsung / Kepala Unit', 'role_id' => $roleKepalaUnit?->id],
            ],
        ];

        foreach ($defaultSteps as $kode => $steps) {
            $jenis = $validasiMap[$kode] ?? null;
            if (! $jenis) {
                continue;
            }

            foreach ($steps as $step) {
                SettingValidasiStep::updateOrCreate(
                    [
                        'jenis_validasi_id' => $jenis->id,
                        'urutan' => $step['urutan'],
                    ],
                    [
                        'nama' => $step['nama'],
                        'role_id' => $step['role_id'],
                    ],
                );
            }
        }
    }
}
