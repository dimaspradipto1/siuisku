<?php

namespace Database\Seeders;

use App\Models\JenisCuti;
use Illuminate\Database\Seeder;

class JenisCutiSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'kode' => 'LHR',
                'nama' => 'Melahirkan',
                'standar_cuti' => 40,
                'format_nomor_surat' => '{{kode}}/{{urutan}}/{{tahun}}',
                'keterangan' => null,
                'is_system' => false,
            ],
            [
                'kode' => 'MNK',
                'nama' => 'Menikah',
                'standar_cuti' => 3,
                'format_nomor_surat' => '{{kode}}/{{urutan}}/{{tahun}}',
                'keterangan' => null,
                'is_system' => false,
            ],
            [
                'kode' => 'THN',
                'nama' => 'Tahunan',
                'standar_cuti' => 12,
                'format_nomor_surat' => '{{kode}}/{{urutan}}/{{tahun}}',
                'keterangan' => null,
                'is_system' => true,
            ],
        ];

        foreach ($items as $item) {
            JenisCuti::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'standar_cuti' => $item['standar_cuti'],
                    'format_nomor_surat' => $item['format_nomor_surat'],
                    'keterangan' => $item['keterangan'],
                    'is_system' => $item['is_system'],
                ],
            );
        }
    }
}
