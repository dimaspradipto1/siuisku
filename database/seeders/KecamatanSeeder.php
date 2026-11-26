<?php

namespace Database\Seeders;

use App\Models\KabupatenKota;
use App\Models\Kecamatan;
use Illuminate\Database\Seeder;

class KecamatanSeeder extends Seeder
{
    public function run(): void
    {
        $kabKotas = KabupatenKota::pluck('id', 'kode');

        $items = [
            // Kota Batam (lengkap 12 Kecamatan)
            ['kab' => '2171', 'kode' => '217101', 'nama' => 'BELAKANG PADANG'],
            ['kab' => '2171', 'kode' => '217102', 'nama' => 'BATU AMPAR'],
            ['kab' => '2171', 'kode' => '217103', 'nama' => 'SEKUPANG'],
            ['kab' => '2171', 'kode' => '217104', 'nama' => 'NONGSA'],
            ['kab' => '2171', 'kode' => '217105', 'nama' => 'BULANG'],
            ['kab' => '2171', 'kode' => '217106', 'nama' => 'LUBUK BAJA'],
            ['kab' => '2171', 'kode' => '217107', 'nama' => 'SEI BEDUK'],
            ['kab' => '2171', 'kode' => '217108', 'nama' => 'GALANG'],
            ['kab' => '2171', 'kode' => '217109', 'nama' => 'BENGKONG'],
            ['kab' => '2171', 'kode' => '217110', 'nama' => 'BATAM KOTA'],
            ['kab' => '2171', 'kode' => '217111', 'nama' => 'SAGULUNG'],
            ['kab' => '2171', 'kode' => '217112', 'nama' => 'BATU AJI'],

            // Kota Tanjungpinang (lengkap 4 Kecamatan)
            ['kab' => '2172', 'kode' => '217201', 'nama' => 'TANJUNGPINANG BARAT'],
            ['kab' => '2172', 'kode' => '217202', 'nama' => 'TANJUNGPINANG TIMUR'],
            ['kab' => '2172', 'kode' => '217203', 'nama' => 'TANJUNGPINANG KOTA'],
            ['kab' => '2172', 'kode' => '217204', 'nama' => 'BUKIT BESTARI'],

            // Kab. Bintan & Kab. Karimun
            ['kab' => '2101', 'kode' => '210104', 'nama' => 'GUNUNG KIJANG'],
            ['kab' => '2101', 'kode' => '210106', 'nama' => 'BINTAN TIMUR'],
            ['kab' => '2101', 'kode' => '210107', 'nama' => 'BINTAN UTARA'],
            ['kab' => '2101', 'kode' => '210108', 'nama' => 'TELUK BINTAN'],
            ['kab' => '2102', 'kode' => '210203', 'nama' => 'KARIMUN'],
            ['kab' => '2102', 'kode' => '210204', 'nama' => 'MERAL'],
            ['kab' => '2102', 'kode' => '210205', 'nama' => 'TEBING'],
        ];

        foreach ($items as $item) {
            $kabId = $kabKotas[$item['kab']] ?? null;
            if (! $kabId) {
                continue;
            }

            Kecamatan::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'kabupaten_kota_id' => $kabId,
                    'nama' => $item['nama'],
                ],
            );
        }
    }
}
