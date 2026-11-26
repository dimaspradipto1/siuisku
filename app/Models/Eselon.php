<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['kode', 'nama', 'aktif'])]
class Eselon extends Model
{
    protected $table = 'eselons';

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }
}
