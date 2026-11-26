<?php

namespace Database\Seeders;

use App\Models\HubunganKerja;
use Illuminate\Database\Seeder;

class HubunganKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['kode' => 'H1', 'nama' => 'Tetap Yayasan', 'status_aktif' => true, 'pegawai_tetap' => true, 'jenis_asn' => 'Non ASN'],
            ['kode' => 'H2', 'nama' => 'Partime', 'status_aktif' => true, 'pegawai_tetap' => false, 'jenis_asn' => 'Non ASN'],
            ['kode' => 'H3', 'nama' => 'PNS/DPK', 'status_aktif' => true, 'pegawai_tetap' => false, 'jenis_asn' => 'PNS'],
            ['kode' => 'H4', 'nama' => 'Fulltime', 'status_aktif' => true, 'pegawai_tetap' => false, 'jenis_asn' => 'Non ASN'],
            ['kode' => 'H5', 'nama' => 'Kontrak', 'status_aktif' => true, 'pegawai_tetap' => false, 'jenis_asn' => 'Non ASN'],
            ['kode' => 'H6', 'nama' => 'Honorer', 'status_aktif' => true, 'pegawai_tetap' => false, 'jenis_asn' => 'Non ASN'],
        ];

        foreach ($items as $item) {
            HubunganKerja::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'status_aktif' => $item['status_aktif'],
                    'pegawai_tetap' => $item['pegawai_tetap'],
                    'jenis_asn' => $item['jenis_asn'],
                ],
            );
        }
    }
}
