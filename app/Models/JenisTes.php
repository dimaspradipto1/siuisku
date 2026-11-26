<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['kode', 'nama', 'nilai_minimal', 'nilai_maksimal'])]
class JenisTes extends Model
{
    protected $table = 'jenis_tes';

    protected function casts(): array
    {
        return [
            'nilai_minimal' => 'decimal:2',
            'nilai_maksimal' => 'decimal:2',
        ];
    }
}
