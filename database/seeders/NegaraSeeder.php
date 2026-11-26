<?php

namespace Database\Seeders;

use App\Models\Negara;
use Illuminate\Database\Seeder;

class NegaraSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar negara disesuaikan dengan kebutuhan Universitas Ibnu Sina (Batam - Indonesia,
        // negara tetangga ASEAN, serta negara tujuan studi lanjut & kerjasama akademik).
        $items = [
            ['kode' => 'IDN', 'nama' => 'Indonesia', 'kode_emis' => 'ID'],
            ['kode' => 'MYS', 'nama' => 'Malaysia', 'kode_emis' => 'MY'],
            ['kode' => 'SGP', 'nama' => 'Singapura', 'kode_emis' => 'SG'],
            ['kode' => 'BRN', 'nama' => 'Brunei Darussalam', 'kode_emis' => 'BN'],
            ['kode' => 'THA', 'nama' => 'Thailand', 'kode_emis' => 'TH'],
            ['kode' => 'PHL', 'nama' => 'Filipina', 'kode_emis' => 'PH'],
            ['kode' => 'VNM', 'nama' => 'Vietnam', 'kode_emis' => 'VN'],
            ['kode' => 'KHM', 'nama' => 'Kamboja', 'kode_emis' => 'KH'],
            ['kode' => 'LAO', 'nama' => 'Laos', 'kode_emis' => 'LA'],
            ['kode' => 'MMR', 'nama' => 'Myanmar', 'kode_emis' => 'MM'],
            ['kode' => 'TLS', 'nama' => 'Timor-Leste', 'kode_emis' => 'TL'],
            ['kode' => 'SAU', 'nama' => 'Arab Saudi', 'kode_emis' => 'SA'],
            ['kode' => 'EGY', 'nama' => 'Mesir', 'kode_emis' => 'EG'],
            ['kode' => 'TUR', 'nama' => 'Turki', 'kode_emis' => 'TR'],
            ['kode' => 'JPN', 'nama' => 'Jepang', 'kode_emis' => 'JP'],
            ['kode' => 'KOR', 'nama' => 'Korea Selatan', 'kode_emis' => 'KR'],
            ['kode' => 'CHN', 'nama' => 'Tiongkok', 'kode_emis' => 'CN'],
            ['kode' => 'TWN', 'nama' => 'Taiwan', 'kode_emis' => 'TW'],
            ['kode' => 'IND', 'nama' => 'India', 'kode_emis' => 'IN'],
            ['kode' => 'AUS', 'nama' => 'Australia', 'kode_emis' => 'AU'],
            ['kode' => 'USA', 'nama' => 'Amerika Serikat', 'kode_emis' => 'US'],
            ['kode' => 'GBR', 'nama' => 'Inggris', 'kode_emis' => 'GB'],
            ['kode' => 'NLD', 'nama' => 'Belanda', 'kode_emis' => 'NL'],
            ['kode' => 'DEU', 'nama' => 'Jerman', 'kode_emis' => 'DE'],
            ['kode' => 'FRA', 'nama' => 'Prancis', 'kode_emis' => 'FR'],
        ];

        foreach ($items as $item) {
            Negara::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'kode_emis' => $item['kode_emis'],
                ],
            );
        }
    }
}
