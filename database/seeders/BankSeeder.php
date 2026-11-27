<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['kode' => 'B01', 'nama' => 'BNI'],
            ['kode' => 'B02', 'nama' => 'BNI SYARIAH'],
            ['kode' => 'B03', 'nama' => 'BUKOPIN'],
            ['kode' => 'B04', 'nama' => 'BCA'],
            ['kode' => 'B05', 'nama' => 'MANDIRI'],
            ['kode' => 'B06', 'nama' => 'BRI'],
            ['kode' => 'B07', 'nama' => 'RIAU KEPRI'],
        ];

        foreach ($items as $item) {
            Bank::updateOrCreate(
                ['kode' => $item['kode']],
                ['nama' => $item['nama']],
            );
        }
    }
}
