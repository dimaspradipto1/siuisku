<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['kode', 'nama', 'jenis_hari'])]
class JenisHari extends Model
{
    protected $table = 'jenis_haris';

    public const JENIS_OPTIONS = [
        'Efektif',
        'Non Efektif',
    ];

    protected function casts(): array
    {
        return [
            'kode' => 'integer',
        ];
    }
}
