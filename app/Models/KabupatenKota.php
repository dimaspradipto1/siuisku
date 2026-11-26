<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['provinsi_id', 'kode', 'nama'])]
class KabupatenKota extends Model
{
    protected $table = 'kabupaten_kotas';

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }
}
