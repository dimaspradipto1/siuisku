<?php

namespace Database\Seeders;

use App\Models\KabupatenKota;
use App\Models\Provinsi;
use Illuminate\Database\Seeder;

class KabupatenKotaSeeder extends Seeder
{
    public function run(): void
    {
        $provinsis = Provinsi::pluck('id', 'kode');

        $items = [
            // Kepulauan Riau (lengkap 7 Kab/Kota)
            ['prov' => '21', 'kode' => '2101', 'nama' => 'KAB. BINTAN'],
            ['prov' => '21', 'kode' => '2102', 'nama' => 'KAB. KARIMUN'],
            ['prov' => '21', 'kode' => '2103', 'nama' => 'KAB. NATUNA'],
            ['prov' => '21', 'kode' => '2104', 'nama' => 'KAB. LINGGA'],
            ['prov' => '21', 'kode' => '2105', 'nama' => 'KAB. KEPULAUAN ANAMBAS'],
            ['prov' => '21', 'kode' => '2171', 'nama' => 'KOTA BATAM'],
            ['prov' => '21', 'kode' => '2172', 'nama' => 'KOTA TANJUNGPINANG'],

            // Riau
            ['prov' => '14', 'kode' => '1401', 'nama' => 'KAB. KAMPAR'],
            ['prov' => '14', 'kode' => '1403', 'nama' => 'KAB. BENGKALIS'],
            ['prov' => '14', 'kode' => '1404', 'nama' => 'KAB. INDRAGIRI HILIR'],
            ['prov' => '14', 'kode' => '1471', 'nama' => 'KOTA PEKANBARU'],
            ['prov' => '14', 'kode' => '1472', 'nama' => 'KOTA DUMAI'],

            // Sumatera Barat, Sumut, Jambi, Sumsel
            ['prov' => '13', 'kode' => '1371', 'nama' => 'KOTA PADANG'],
            ['prov' => '13', 'kode' => '1375', 'nama' => 'KOTA BUKITTINGGI'],
            ['prov' => '12', 'kode' => '1271', 'nama' => 'KOTA MEDAN'],
            ['prov' => '15', 'kode' => '1571', 'nama' => 'KOTA JAMBI'],
            ['prov' => '16', 'kode' => '1671', 'nama' => 'KOTA PALEMBANG'],

            // DKI Jakarta
            ['prov' => '31', 'kode' => '3171', 'nama' => 'KOTA ADM. JAKARTA PUSAT'],
            ['prov' => '31', 'kode' => '3172', 'nama' => 'KOTA ADM. JAKARTA UTARA'],
            ['prov' => '31', 'kode' => '3173', 'nama' => 'KOTA ADM. JAKARTA BARAT'],
            ['prov' => '31', 'kode' => '3174', 'nama' => 'KOTA ADM. JAKARTA SELATAN'],
            ['prov' => '31', 'kode' => '3175', 'nama' => 'KOTA ADM. JAKARTA TIMUR'],

            // Jawa Barat, DIY, Jawa Tengah, Jawa Timur, Banten
            ['prov' => '32', 'kode' => '3271', 'nama' => 'KOTA BOGOR'],
            ['prov' => '32', 'kode' => '3273', 'nama' => 'KOTA BANDUNG'],
            ['prov' => '32', 'kode' => '3275', 'nama' => 'KOTA BEKASI'],
            ['prov' => '32', 'kode' => '3276', 'nama' => 'KOTA DEPOK'],
            ['prov' => '34', 'kode' => '3404', 'nama' => 'KAB. SLEMAN'],
            ['prov' => '34', 'kode' => '3471', 'nama' => 'KOTA YOGYAKARTA'],
            ['prov' => '33', 'kode' => '3372', 'nama' => 'KOTA SURAKARTA'],
            ['prov' => '33', 'kode' => '3374', 'nama' => 'KOTA SEMARANG'],
            ['prov' => '35', 'kode' => '3573', 'nama' => 'KOTA MALANG'],
            ['prov' => '35', 'kode' => '3578', 'nama' => 'KOTA SURABAYA'],
            ['prov' => '36', 'kode' => '3671', 'nama' => 'KOTA TANGERANG'],
            ['prov' => '36', 'kode' => '3674', 'nama' => 'KOTA TANGERANG SELATAN'],
        ];

        foreach ($items as $item) {
            $provId = $provinsis[$item['prov']] ?? null;
            if (! $provId) {
                continue;
            }

            KabupatenKota::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'provinsi_id' => $provId,
                    'nama' => $item['nama'],
                ],
            );
        }
    }
}
