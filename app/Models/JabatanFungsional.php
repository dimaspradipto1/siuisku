<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'klasifikasi_sdm_id',
    'kode',
    'nama',
    'pangkat_id',
    'angka_kredit',
    'usia_pensiun',
    'referensi_sister',
    'keterangan',
])]
class JabatanFungsional extends Model
{
    protected $table = 'jabatan_fungsionals';

    public const REFERENSI_SISTER_OPTIONS = [
        'Tenaga Pengajar',
        'Asisten Ahli 100',
        'Asisten Ahli 150',
        'Lektor 200',
        'Lektor 300',
        'Lektor Kepala 400',
        'Lektor Kepala 550',
        'Lektor Kepala 700',
        'Profesor 850',
        'Profesor 1050',
    ];

    protected function casts(): array
    {
        return [
            'angka_kredit' => 'integer',
            'usia_pensiun' => 'integer',
        ];
    }

    public function klasifikasiSdm(): BelongsTo
    {
        return $this->belongsTo(KlasifikasiSdm::class, 'klasifikasi_sdm_id');
    }

    public function pangkat(): BelongsTo
    {
        return $this->belongsTo(Pangkat::class, 'pangkat_id');
    }

    public function riwayatJabatanFungsionals(): HasMany
    {
        return $this->hasMany(RiwayatJabatanFungsional::class);
    }
}
