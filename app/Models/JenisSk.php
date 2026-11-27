<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['kode', 'nama'])]
class JenisSk extends Model
{
    protected $table = 'jenis_sks';

    public function riwayatPangkats(): HasMany
    {
        return $this->hasMany(RiwayatPangkat::class);
    }
}
