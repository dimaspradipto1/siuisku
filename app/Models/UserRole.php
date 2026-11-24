<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'modul_id', 'role_id', 'unit_kerja_id', 'jenis_jabatan_struktural_id'])]
class UserRole extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function modul(): BelongsTo
    {
        return $this->belongsTo(Modul::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class);
    }

    public function jenisJabatanStruktural(): BelongsTo
    {
        return $this->belongsTo(JenisJabatanStruktural::class);
    }

    public function displayTitle(): string
    {
        return $this->jenisJabatanStruktural?->nama
            ?? $this->role?->nama
            ?? 'Staff';
    }

    public function displayUnit(): string
    {
        return $this->unitKerja?->nama ?? 'Universitas Ibnu Sina';
    }
}
