<?php

namespace Database\Seeders;

use App\Models\StatusPernikahan;
use Illuminate\Database\Seeder;

class StatusPernikahanSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['kode' => 'D', 'nama' => 'Duda'],
            ['kode' => 'J', 'nama' => 'Janda'],
            ['kode' => 'M', 'nama' => 'Menikah'],
            ['kode' => 'S', 'nama' => 'Belum Pernah Menikah'],
        ];

        foreach ($items as $item) {
            StatusPernikahan::updateOrCreate(
                ['kode' => $item['kode']],
                ['nama' => $item['nama']],
            );
        }
    }
}
