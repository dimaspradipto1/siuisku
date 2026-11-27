<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisRiwayat extends Model
{
    protected $fillable = [
        'kode',
        'kategori',
        'nama',
        'urutan_kategori',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'urutan_kategori' => 'integer',
            'urutan' => 'integer',
        ];
    }

    public function riwayats(): HasMany
    {
        return $this->hasMany(RiwayatPegawai::class);
    }
}
