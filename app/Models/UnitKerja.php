<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['kode', 'nama', 'jenis', 'parent_id', 'urutan'])]
class UnitKerja extends Model
{
    protected function casts(): array
    {
        return [
            'urutan' => 'integer',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function pegawais(): HasMany
    {
        return $this->hasMany(Pegawai::class);
    }

    /**
     * Get this unit's ID along with all descendant unit IDs in the OTK hierarchy.
     *
     * @return array<int, int>
     */
    public function descendantIds(): array
    {
        $ids = [$this->id];

        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->descendantIds());
        }

        return $ids;
    }
}
