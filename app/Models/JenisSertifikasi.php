<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['kode', 'nama', 'kategori', 'is_system'])]
class JenisSertifikasi extends Model
{
    protected $table = 'jenis_sertifikasis';

    public const KATEGORI_OPTIONS = [
        'Sertifikasi Dosen',
        'Sertifikasi Profesi',
        'Sertifikasi Kompetensi',
    ];

    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
        ];
    }
}
