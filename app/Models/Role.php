<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['kode', 'nama', 'scope', 'deskripsi'])]
class Role extends Model
{
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    public function userRoles(): HasMany
    {
        return $this->hasMany(UserRole::class);
    }

    public function scopeLabel(): string
    {
        return match ($this->scope) {
            'universitas' => 'Seluruh Universitas',
            'unit' => 'Unit Kerja & Sub-Unit (OTK)',
            default => 'Data Pribadi (Self)',
        };
    }
}
