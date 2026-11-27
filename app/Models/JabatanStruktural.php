<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'kode',
    'nama',
    'jenis_jabatan_struktural_id',
    'singkatan',
    'parent_id',
    'unit_kerja_id',
    'email',
    'eselon',
    'pangkat_min',
    'pangkat_max',
    'beban_sks',
    'is_pimpinan',
    'is_active',
    'keterangan',
    'urutan',
])]
class JabatanStruktural extends Model
{
    protected $table = 'jabatan_strukturals';

    protected function casts(): array
    {
        return [
            'beban_sks' => 'decimal:2',
            'is_pimpinan' => 'boolean',
            'is_active' => 'boolean',
            'urutan' => 'integer',
        ];
    }

    public function jenisJabatanStruktural(): BelongsTo
    {
        return $this->belongsTo(JenisJabatanStruktural::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class);
    }

    public function riwayatJabatanStrukturals(): HasMany
    {
        return $this->hasMany(RiwayatJabatanStruktural::class);
    }
}
