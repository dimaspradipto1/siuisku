<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['pangkat', 'nama_golongan', 'urutan'])]
class Pangkat extends Model
{
    protected $table = 'pangkats';

    protected function casts(): array
    {
        return [
            'urutan' => 'integer',
        ];
    }
}
