<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['kode', 'nama', 'nama_en', 'urutan', 'is_perguruan_tinggi', 'is_pasca_sarjana'])]
class JenjangPendidikan extends Model
{
    protected $table = 'jenjang_pendidikans';

    protected function casts(): array
    {
        return [
            'urutan' => 'integer',
            'is_perguruan_tinggi' => 'boolean',
            'is_pasca_sarjana' => 'boolean',
        ];
    }
}
