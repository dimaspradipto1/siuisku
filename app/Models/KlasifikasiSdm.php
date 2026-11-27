<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['kode', 'nama', 'jenis_jabatan'])]
class KlasifikasiSdm extends Model
{
    protected $table = 'klasifikasi_sdms';

    public const JENIS_JABATAN_OPTIONS = [
        'Dosen',
        'Dosen Praktisi/Industri',
        'Tenaga Kependidikan',
    ];

    public function pegawais(): HasMany
    {
        return $this->hasMany(Pegawai::class);
    }
}
