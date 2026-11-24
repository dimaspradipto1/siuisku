<?php

namespace Database\Seeders;

use App\Models\Modul;
use Illuminate\Database\Seeder;

class ModulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            [
                'kode' => 'simpeg',
                'nama' => 'SIM Kepegawaian',
                'singkatan' => 'SIMPEG',
                'icon' => 'bi-people-fill',
                'warna' => '#ea580c',
                'warna_bg' => '#fff7ed',
                'url' => '/dashboard',
                'is_active' => true,
                'urutan' => 1,
            ],
            [
                'kode' => 'siakad',
                'nama' => 'SIM Akademik',
                'singkatan' => 'SIAKAD',
                'icon' => 'bi-mortarboard-fill',
                'warna' => '#0284c7',
                'warna_bg' => '#f0f9ff',
                'url' => null,
                'is_active' => false,
                'urutan' => 2,
            ],
            [
                'kode' => 'akreditasi',
                'nama' => 'SIM Akreditasi',
                'singkatan' => 'Akreditasi',
                'icon' => 'bi-patch-check-fill',
                'warna' => '#dc2626',
                'warna_bg' => '#fef2f2',
                'url' => null,
                'is_active' => false,
                'urutan' => 3,
            ],
            [
                'kode' => 'elearning',
                'nama' => 'E-Learning',
                'singkatan' => 'LMS',
                'icon' => 'bi-book-half',
                'warna' => '#16a34a',
                'warna_bg' => '#f0fdf4',
                'url' => null,
                'is_active' => false,
                'urutan' => 4,
            ],
            [
                'kode' => 'pelaporan',
                'nama' => 'SIM Dashboard & Pelaporan',
                'singkatan' => 'Pelaporan',
                'icon' => 'bi-bar-chart-line-fill',
                'warna' => '#0d9488',
                'warna_bg' => '#f0fdfa',
                'url' => null,
                'is_active' => false,
                'urutan' => 5,
            ],
            [
                'kode' => 'helpdesk',
                'nama' => 'SIM Support & Helpdesk',
                'singkatan' => 'Helpdesk',
                'icon' => 'bi-headset',
                'warna' => '#7c3aed',
                'warna_bg' => '#f5f3ff',
                'url' => null,
                'is_active' => false,
                'urutan' => 6,
            ],
        ];

        foreach ($modules as $module) {
            Modul::updateOrCreate(
                ['kode' => $module['kode']],
                $module,
            );
        }
    }
}
