<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['kode', 'nama', 'jenis_kehadiran_id', 'maksimal_hari', 'potong_cuti'])]
class JenisIzin extends Model
{
    protected $table = 'jenis_izins';

    protected function casts(): array
    {
        return [
            'maksimal_hari' => 'integer',
            'potong_cuti' => 'boolean',
        ];
    }

    public function jenisKehadiran(): BelongsTo
    {
        return $this->belongsTo(JenisKehadiran::class, 'jenis_kehadiran_id');
    }
}
