<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['kode', 'nama'])]
class JenisJabatanStruktural extends Model
{
    protected $table = 'jenis_jabatan_strukturals';
}
