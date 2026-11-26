<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nama', 'is_normal', 'jam_datang', 'jam_pulang'])]
class JamKerja extends Model
{
    protected $table = 'jam_kerjas';

    protected function casts(): array
    {
        return [
            'is_normal' => 'boolean',
        ];
    }
}
