<?php

namespace Database\Seeders;

use App\Models\PerguruanTinggi;
use Illuminate\Database\Seeder;

class PerguruanTinggiSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // Universitas Ibnu Sina & Universitas Amikom Yogyakarta
            [
                'kode' => '101031',
                'nama' => 'Universitas Ibnu Sina',
                'alamat' => 'Jl. Teuku Umar, Lubuk Baja, Kota Batam, Kepulauan Riau',
                'no_telp' => '0778-425391',
            ],
            [
                'kode' => '051024',
                'nama' => 'Universitas Amikom Yogyakarta',
                'alamat' => 'Jl. Ring Road Utara, Condongcatur, Depok, Sleman, DI Yogyakarta',
                'no_telp' => '0274-884201',
            ],

            // Kepulauan Riau & Batam
            [
                'kode' => '001052',
                'nama' => 'Universitas Maritim Raja Ali Haji',
                'alamat' => 'Jl. Raya Dompak, Kota Tanjungpinang, Kepulauan Riau',
                'no_telp' => '0771-7001550',
            ],
            [
                'kode' => '005031',
                'nama' => 'Politeknik Negeri Batam',
                'alamat' => 'Jl. Ahmad Yani, Batam Centre, Kota Batam, Kepulauan Riau',
                'no_telp' => '0778-469856',
            ],
            [
                'kode' => '101014',
                'nama' => 'Universitas Internasional Batam',
                'alamat' => 'Jl. Gajah Mada, Baloi - Sei Ladi, Kota Batam, Kepulauan Riau',
                'no_telp' => '0778-7437111',
            ],
            [
                'kode' => '101018',
                'nama' => 'Universitas Batam',
                'alamat' => 'Jl. Abulyatama No. 5, Batam Centre, Kota Batam, Kepulauan Riau',
                'no_telp' => '0778-7485055',
            ],
            [
                'kode' => '101020',
                'nama' => 'Universitas Putera Batam',
                'alamat' => 'Jl. R. Soeprapto, Muka Kuning, Kota Batam, Kepulauan Riau',
                'no_telp' => '0778-364035',
            ],
            [
                'kode' => '101022',
                'nama' => 'Universitas Riau Kepulauan',
                'alamat' => 'Jl. Pahlawan No. 99, Bukit Tempayan, Batu Aji, Kota Batam',
                'no_telp' => '0778-392752',
            ],
            [
                'kode' => '101027',
                'nama' => 'Universitas Universal',
                'alamat' => 'Kompleks Maha Vihara Duta Maitreya, Sungai Panas, Kota Batam',
                'no_telp' => '0778-473399',
            ],

            // Sumatra
            [
                'kode' => '001017',
                'nama' => 'Universitas Riau',
                'alamat' => 'Kampus Bina Widya KM 12,5 Simpang Baru, Pekanbaru, Riau',
                'no_telp' => '0761-63266',
            ],
            [
                'kode' => '001006',
                'nama' => 'Universitas Andalas',
                'alamat' => 'Limau Manis, Pauh, Kota Padang, Sumatera Barat',
                'no_telp' => '0751-71181',
            ],
            [
                'kode' => '001032',
                'nama' => 'Universitas Negeri Padang',
                'alamat' => 'Jl. Prof. Dr. Hamka, Air Tawar Barat, Kota Padang, Sumatera Barat',
                'no_telp' => '0751-7051260',
            ],
            [
                'kode' => '001003',
                'nama' => 'Universitas Sumatera Utara',
                'alamat' => 'Jl. Dr. T. Mansur No. 9, Padang Bulan, Kota Medan, Sumatera Utara',
                'no_telp' => '061-8211633',
            ],
            [
                'kode' => '001009',
                'nama' => 'Universitas Sriwijaya',
                'alamat' => 'Jl. Palembang - Prabumulih KM 32, Indralaya, Sumatera Selatan',
                'no_telp' => '0711-580069',
            ],
            [
                'kode' => '001011',
                'nama' => 'Universitas Syiah Kuala',
                'alamat' => 'Jl. Teuku Nyak Arief No. 441, Darussalam, Banda Aceh',
                'no_telp' => '0651-7553205',
            ],
            [
                'kode' => '001020',
                'nama' => 'Universitas Jambi',
                'alamat' => 'Jl. Jambi - Muara Bulian KM 15, Mendalo Darat, Jambi',
                'no_telp' => '0741-583377',
            ],
            [
                'kode' => '101001',
                'nama' => 'Universitas Bung Hatta',
                'alamat' => 'Jl. Sumatera, Ulak Karang, Kota Padang, Sumatera Barat',
                'no_telp' => '0751-7051678',
            ],
            [
                'kode' => '101002',
                'nama' => 'Universitas Muhammadiyah Sumatera Barat',
                'alamat' => 'Jl. Pasir Jambak No. 4, Kota Padang, Sumatera Barat',
                'no_telp' => '0751-4851002',
            ],
            [
                'kode' => '101007',
                'nama' => 'Universitas Islam Riau',
                'alamat' => 'Jl. Kaharuddin Nasution No. 113, Marpoyan, Pekanbaru, Riau',
                'no_telp' => '0761-674674',
            ],
            [
                'kode' => '101009',
                'nama' => 'Universitas Lancang Kuning',
                'alamat' => 'Jl. Yos Sudarso KM 8, Rumbai, Pekanbaru, Riau',
                'no_telp' => '0761-52581',
            ],
            [
                'kode' => '101019',
                'nama' => 'Universitas Putra Indonesia YPTK Padang',
                'alamat' => 'Jl. Raya Lubuk Begalung, Kota Padang, Sumatera Barat',
                'no_telp' => '0751-776666',
            ],

            // Jawa & Nasional
            [
                'kode' => '001001',
                'nama' => 'Universitas Gadjah Mada',
                'alamat' => 'Bulaksumur, Caturtunggal, Depok, Sleman, DI Yogyakarta',
                'no_telp' => '0274-588688',
            ],
            [
                'kode' => '001002',
                'nama' => 'Universitas Indonesia',
                'alamat' => 'Kampus UI Depok, Jawa Barat',
                'no_telp' => '021-7867222',
            ],
            [
                'kode' => '001004',
                'nama' => 'Universitas Airlangga',
                'alamat' => 'Jl. Airlangga No. 4-6, Kota Surabaya, Jawa Timur',
                'no_telp' => '031-5914042',
            ],
            [
                'kode' => '001007',
                'nama' => 'Universitas Padjadjaran',
                'alamat' => 'Jl. Raya Bandung Sumedang KM 21, Jatinangor, Jawa Barat',
                'no_telp' => '022-84288888',
            ],
            [
                'kode' => '001008',
                'nama' => 'Universitas Diponegoro',
                'alamat' => 'Jl. Prof. Soedarto SH, Tembalang, Kota Semarang, Jawa Tengah',
                'no_telp' => '024-7465403',
            ],
            [
                'kode' => '001019',
                'nama' => 'Universitas Brawijaya',
                'alamat' => 'Jl. Veteran, Kota Malang, Jawa Timur',
                'no_telp' => '0341-551611',
            ],
            [
                'kode' => '001031',
                'nama' => 'Universitas Terbuka',
                'alamat' => 'Jl. Cabe Raya, Pondok Cabe, Pamulang, Tangerang Selatan',
                'no_telp' => '021-7490941',
            ],
            [
                'kode' => '001034',
                'nama' => 'Universitas Pendidikan Indonesia',
                'alamat' => 'Jl. Dr. Setiabudhi No. 229, Kota Bandung, Jawa Barat',
                'no_telp' => '022-2013163',
            ],
            [
                'kode' => '001038',
                'nama' => 'Universitas Negeri Yogyakarta',
                'alamat' => 'Jl. Colombo No. 1, Karangmalang, Sleman, DI Yogyakarta',
                'no_telp' => '0274-586168',
            ],
            [
                'kode' => '002001',
                'nama' => 'Institut Teknologi Bandung',
                'alamat' => 'Jl. Ganesa No. 10, Coblong, Kota Bandung, Jawa Barat',
                'no_telp' => '022-2500935',
            ],
            [
                'kode' => '002002',
                'nama' => 'Institut Teknologi Sepuluh Nopember',
                'alamat' => 'Kampus ITS Sukolilo, Kota Surabaya, Jawa Timur',
                'no_telp' => '031-5994251',
            ],
            [
                'kode' => '002003',
                'nama' => 'Institut Pertanian Bogor',
                'alamat' => 'Jl. Raya Dramaga, Kampus IPB Dramaga, Bogor, Jawa Barat',
                'no_telp' => '0251-8622642',
            ],
            [
                'kode' => '031038',
                'nama' => 'Universitas Bina Nusantara',
                'alamat' => 'Jl. K.H. Syahdan No. 9, Palmerah, Jakarta Barat',
                'no_telp' => '021-5345830',
            ],
            [
                'kode' => '041057',
                'nama' => 'Universitas Telkom',
                'alamat' => 'Jl. Telekomunikasi No. 1, Terusan Buah Batu, Bandung, Jawa Barat',
                'no_telp' => '022-7564108',
            ],
            [
                'kode' => '051001',
                'nama' => 'Universitas Islam Indonesia',
                'alamat' => 'Jl. Kaliurang KM 14,5, Sleman, DI Yogyakarta',
                'no_telp' => '0274-898444',
            ],
            [
                'kode' => '051007',
                'nama' => 'Universitas Muhammadiyah Yogyakarta',
                'alamat' => 'Jl. Brawijaya, Kasihan, Bantul, DI Yogyakarta',
                'no_telp' => '0274-387656',
            ],
            [
                'kode' => '051013',
                'nama' => 'Universitas Ahmad Dahlan',
                'alamat' => 'Jl. Kapas No. 9, Semaki, Kota Yogyakarta, DI Yogyakarta',
                'no_telp' => '0274-563515',
            ],
        ];

        foreach ($items as $item) {
            PerguruanTinggi::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'alamat' => $item['alamat'],
                    'no_telp' => $item['no_telp'],
                ],
            );
        }
    }
}
