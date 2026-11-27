<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['kode', 'nama', 'status_aktif', 'pegawai_tetap', 'jenis_asn'])]
class HubunganKerja extends Model
{
    protected $table = 'hubungan_kerjas';

    protected function casts(): array
    {
        return [
            'status_aktif' => 'boolean',
            'pegawai_tetap' => 'boolean',
        ];
    }

    public function riwayatHubunganKerjas(): HasMany
    {
        return $this->hasMany(RiwayatHubunganKerja::class);
    }
}
