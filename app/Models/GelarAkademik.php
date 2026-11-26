<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['gelar', 'nama', 'urutan'])]
class GelarAkademik extends Model
{
    protected $table = 'gelar_akademiks';

    protected function casts(): array
    {
        return [
            'urutan' => 'integer',
        ];
    }
}
