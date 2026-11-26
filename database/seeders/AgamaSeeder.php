<?php

namespace Database\Seeders;

use App\Models\Agama;
use Illuminate\Database\Seeder;

class AgamaSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['kode' => '1', 'nama' => 'Islam'],
            ['kode' => '2', 'nama' => 'Kristen'],
            ['kode' => '3', 'nama' => 'Katolik'],
            ['kode' => '4', 'nama' => 'Hindu'],
            ['kode' => '5', 'nama' => 'Budha'],
            ['kode' => '6', 'nama' => 'Konghucu'],
            ['kode' => '7', 'nama' => 'Penghayat Kepercayaan TTYME'],
            ['kode' => '8', 'nama' => 'Lain-lain'],
        ];

        foreach ($items as $item) {
            Agama::updateOrCreate(
                ['kode' => $item['kode']],
                ['nama' => $item['nama']],
            );
        }
    }
}
