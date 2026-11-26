<?php

namespace Database\Seeders;

use App\Models\JenisLuaran;
use Illuminate\Database\Seeder;

class JenisLuaranSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['kode' => '5', 'nama' => 'Buku'],
            ['kode' => '4', 'nama' => 'HKI'],
            ['kode' => '2', 'nama' => 'Jenis Luaran Lainnya'],
            ['kode' => '6', 'nama' => 'Pembicara'],
            ['kode' => '1', 'nama' => 'Produk Teknologi Tepat Guna'],
            ['kode' => '3', 'nama' => 'Publikasi'],
            ['kode' => '7', 'nama' => 'Visiting Scientist'],
        ];

        foreach ($items as $item) {
            JenisLuaran::updateOrCreate(
                ['kode' => $item['kode']],
                ['nama' => $item['nama']],
            );
        }
    }
}
