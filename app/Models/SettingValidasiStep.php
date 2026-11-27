<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SettingValidasiStep extends Model
{
    protected $fillable = [
        'jenis_validasi_id',
        'urutan',
        'nama',
        'role_id',
    ];

    protected function casts(): array
    {
        return [
            'urutan' => 'integer',
        ];
    }

    public function jenisValidasi(): BelongsTo
    {
        return $this->belongsTo(JenisValidasi::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
