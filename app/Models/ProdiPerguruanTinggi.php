<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'perguruan_tinggi_id',
    'jenjang_pendidikan_id',
    'kode',
    'nama',
    'alamat',
    'no_telp',
])]
class ProdiPerguruanTinggi extends Model
{
    protected $table = 'prodi_perguruan_tinggis';

    public function perguruanTinggi(): BelongsTo
    {
        return $this->belongsTo(PerguruanTinggi::class, 'perguruan_tinggi_id');
    }

    public function jenjangPendidikan(): BelongsTo
    {
        return $this->belongsTo(JenjangPendidikan::class, 'jenjang_pendidikan_id');
    }
}
