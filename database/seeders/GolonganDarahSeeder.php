<?php

namespace Database\Seeders;

use App\Models\GolonganDarah;
use Illuminate\Database\Seeder;

class GolonganDarahSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'A',
            'B',
            'AB',
            'O',
        ];

        foreach ($items as $nama) {
            GolonganDarah::firstOrCreate(['nama' => $nama]);
        }
    }
}
