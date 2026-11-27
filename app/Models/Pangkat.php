<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function riwayatPangkats(): HasMany
    {
        return $this->hasMany(RiwayatPangkat::class);
    }
}
