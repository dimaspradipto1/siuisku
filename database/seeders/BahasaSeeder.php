<?php

namespace Database\Seeders;

use App\Models\Bahasa;
use Illuminate\Database\Seeder;

class BahasaSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['kode' => 'AR', 'nama' => 'Bahasa Arab'],
            ['kode' => 'BN', 'nama' => 'Bahasa Bengali'],
            ['kode' => 'DE', 'nama' => 'Bahasa Jerman'],
            ['kode' => 'EL', 'nama' => 'Bahasa Yunani'],
            ['kode' => 'EN', 'nama' => 'Bahasa Inggris'],
            ['kode' => 'ES', 'nama' => 'Bahasa Spanyol'],
            ['kode' => 'FA', 'nama' => 'Bahasa Persia'],
            ['kode' => 'FR', 'nama' => 'Bahasa Prancis'],
            ['kode' => 'HI', 'nama' => 'Bahasa Hindi'],
            ['kode' => 'ID', 'nama' => 'Bahasa Indonesia'],
            ['kode' => 'JA', 'nama' => 'Bahasa Jepang'],
            ['kode' => 'JV', 'nama' => 'Bahasa Jawa'],
            ['kode' => 'KO', 'nama' => 'Bahasa Korea'],
            ['kode' => 'MS', 'nama' => 'Bahasa Melayu'],
            ['kode' => 'NL', 'nama' => 'Bahasa Belanda'],
            ['kode' => 'PT', 'nama' => 'Bahasa Portugis'],
            ['kode' => 'RU', 'nama' => 'Bahasa Rusia'],
            ['kode' => 'TH', 'nama' => 'Bahasa Thailand'],
            ['kode' => 'ZH', 'nama' => 'Bahasa Tionghoa/Mandarin'],
        ];

        foreach ($items as $item) {
            Bahasa::updateOrCreate(
                ['kode' => $item['kode']],
                ['nama' => $item['nama']],
            );
        }
    }
}
