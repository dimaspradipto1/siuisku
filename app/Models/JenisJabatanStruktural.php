<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['kode', 'nama', 'urutan'])]
class JenisJabatanStruktural extends Model
{
    protected $table = 'jenis_jabatan_strukturals';

    protected function casts(): array
    {
        return [
            'urutan' => 'integer',
        ];
    }
}
