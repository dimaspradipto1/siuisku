<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['kode', 'nama', 'status_keluar'])]
class StatusAktif extends Model
{
    protected $table = 'status_aktifs';

    protected function casts(): array
    {
        return [
            'status_keluar' => 'boolean',
        ];
    }

    public function isSystemStatus(): bool
    {
        return in_array(strtoupper((string) $this->kode), ['AA', 'TA'], true);
    }
}
