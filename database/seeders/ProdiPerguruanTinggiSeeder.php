<?php

namespace Database\Seeders;

use App\Models\JenjangPendidikan;
use App\Models\PerguruanTinggi;
use App\Models\ProdiPerguruanTinggi;
use Illuminate\Database\Seeder;

class ProdiPerguruanTinggiSeeder extends Seeder
{
    public function run(): void
    {
        $pts = PerguruanTinggi::pluck('id', 'kode');
        $jenjangs = JenjangPendidikan::pluck('id', 'kode');

        $items = [
            // Universitas Ibnu Sina (101031)
            ['pt' => '101031', 'kode' => '61101', 'nama' => 'Magister Manajemen', 'jenjang' => 'S2', 'alamat' => 'Jl. Teuku Umar, Lubuk Baja, Batam', 'no_telp' => '0778-425391'],
            ['pt' => '101031', 'kode' => '61201', 'nama' => 'Manajemen', 'jenjang' => 'S1', 'alamat' => 'Jl. Teuku Umar, Lubuk Baja, Batam', 'no_telp' => '0778-425391'],
            ['pt' => '101031', 'kode' => '62201', 'nama' => 'Akuntansi', 'jenjang' => 'S1', 'alamat' => 'Jl. Teuku Umar, Lubuk Baja, Batam', 'no_telp' => '0778-425391'],
            ['pt' => '101031', 'kode' => '26201', 'nama' => 'Teknik Industri', 'jenjang' => 'S1', 'alamat' => 'Jl. Teuku Umar, Lubuk Baja, Batam', 'no_telp' => '0778-425391'],
            ['pt' => '101031', 'kode' => '55201', 'nama' => 'Teknik Informatika', 'jenjang' => 'S1', 'alamat' => 'Jl. Teuku Umar, Lubuk Baja, Batam', 'no_telp' => '0778-425391'],
            ['pt' => '101031', 'kode' => '57201', 'nama' => 'Sistem Informasi', 'jenjang' => 'S1', 'alamat' => 'Jl. Teuku Umar, Lubuk Baja, Batam', 'no_telp' => '0778-425391'],
            ['pt' => '101031', 'kode' => '36201', 'nama' => 'Teknik Logistik dan Perkapalan', 'jenjang' => 'S1', 'alamat' => 'Jl. Teuku Umar, Lubuk Baja, Batam', 'no_telp' => '0778-425391'],
            ['pt' => '101031', 'kode' => '13261', 'nama' => 'Keselamatan dan Kesehatan Kerja', 'jenjang' => 'S1', 'alamat' => 'Jl. Teuku Umar, Lubuk Baja, Batam', 'no_telp' => '0778-425391'],
            ['pt' => '101031', 'kode' => '13251', 'nama' => 'Kesehatan Lingkungan', 'jenjang' => 'S1', 'alamat' => 'Jl. Teuku Umar, Lubuk Baja, Batam', 'no_telp' => '0778-425391'],

            // Universitas Amikom Yogyakarta (051024)
            ['pt' => '051024', 'kode' => '55101', 'nama' => 'Magister Teknik Informatika', 'jenjang' => 'S2', 'alamat' => 'Jl. Ring Road Utara, Condongcatur, Sleman', 'no_telp' => '0274-884201'],
            ['pt' => '051024', 'kode' => '55201', 'nama' => 'Informatika', 'jenjang' => 'S1', 'alamat' => 'Jl. Ring Road Utara, Condongcatur, Sleman', 'no_telp' => '0274-884201'],
            ['pt' => '051024', 'kode' => '57201', 'nama' => 'Sistem Informasi', 'jenjang' => 'S1', 'alamat' => 'Jl. Ring Road Utara, Condongcatur, Sleman', 'no_telp' => '0274-884201'],
            ['pt' => '051024', 'kode' => '56201', 'nama' => 'Teknik Komputer', 'jenjang' => 'S1', 'alamat' => 'Jl. Ring Road Utara, Condongcatur, Sleman', 'no_telp' => '0274-884201'],
            ['pt' => '051024', 'kode' => '59201', 'nama' => 'Teknologi Informasi', 'jenjang' => 'S1', 'alamat' => 'Jl. Ring Road Utara, Condongcatur, Sleman', 'no_telp' => '0274-884201'],
            ['pt' => '051024', 'kode' => '57401', 'nama' => 'Manajemen Informatika', 'jenjang' => 'D3', 'alamat' => 'Jl. Ring Road Utara, Condongcatur, Sleman', 'no_telp' => '0274-884201'],
            ['pt' => '051024', 'kode' => '56401', 'nama' => 'Teknik Informatika', 'jenjang' => 'D3', 'alamat' => 'Jl. Ring Road Utara, Condongcatur, Sleman', 'no_telp' => '0274-884201'],
        ];

        foreach ($items as $item) {
            $ptId = $pts[$item['pt']] ?? null;
            if (! $ptId) {
                continue;
            }

            ProdiPerguruanTinggi::updateOrCreate(
                [
                    'perguruan_tinggi_id' => $ptId,
                    'kode' => $item['kode'],
                ],
                [
                    'jenjang_pendidikan_id' => $jenjangs[$item['jenjang']] ?? null,
                    'nama' => $item['nama'],
                    'alamat' => $item['alamat'],
                    'no_telp' => $item['no_telp'],
                ],
            );
        }
    }
}
