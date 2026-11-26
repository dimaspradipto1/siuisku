<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['kode', 'nama', 'standar_cuti', 'format_nomor_surat', 'keterangan', 'is_system'])]
class JenisCuti extends Model
{
    protected $table = 'jenis_cutis';

    protected function casts(): array
    {
        return [
            'standar_cuti' => 'integer',
            'is_system' => 'boolean',
        ];
    }
}
