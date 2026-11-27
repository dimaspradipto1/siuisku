<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisValidasi extends Model
{
    protected $fillable = [
        'kode',
        'nama',
    ];

    public function steps(): HasMany
    {
        return $this->hasMany(SettingValidasiStep::class)->orderBy('urutan')->orderBy('id');
    }
}
