<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'kode',
    'nama',
    'singkatan',
    'icon',
    'warna',
    'warna_bg',
    'url',
    'is_active',
    'urutan',
])]
class Modul extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'urutan' => 'integer',
        ];
    }

    public function resolvedUrl(): string
    {
        if (blank($this->url)) {
            return route('dashboard');
        }

        if (str_starts_with($this->url, 'http://') || str_starts_with($this->url, 'https://')) {
            return $this->url;
        }

        return url($this->url);
    }
}
