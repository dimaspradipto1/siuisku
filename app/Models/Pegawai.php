<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pegawai extends Model
{
    protected $fillable = [
        'user_id',
        'nip',
        'nidn',
        'nama',
        'unit_kerja_id',
        'status_aktif_id',
        'hubungan_kerja_id',
        'jabatan_fungsional_id',
        'klasifikasi_sdm_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class);
    }

    public function statusAktif(): BelongsTo
    {
        return $this->belongsTo(StatusAktif::class);
    }

    public function hubunganKerja(): BelongsTo
    {
        return $this->belongsTo(HubunganKerja::class);
    }

    public function jabatanFungsional(): BelongsTo
    {
        return $this->belongsTo(JabatanFungsional::class);
    }

    public function klasifikasiSdm(): BelongsTo
    {
        return $this->belongsTo(KlasifikasiSdm::class);
    }

    public function riwayats(): HasMany
    {
        return $this->hasMany(RiwayatPegawai::class);
    }

    public function keluargas(): HasMany
    {
        return $this->hasMany(KeluargaPegawai::class);
    }

    public function getLabelAttribute(): string
    {
        return "{$this->nip} - {$this->nama}";
    }
}
