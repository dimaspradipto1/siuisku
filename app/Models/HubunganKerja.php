<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['kode', 'nama', 'status_aktif', 'pegawai_tetap', 'jenis_asn'])]
class HubunganKerja extends Model
{
    protected $table = 'hubungan_kerjas';

    protected function casts(): array
    {
        return [
            'status_aktif' => 'boolean',
            'pegawai_tetap' => 'boolean',
        ];
    }
}
