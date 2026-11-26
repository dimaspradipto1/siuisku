<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['kode', 'nama'])]
class JenisSk extends Model
{
    protected $table = 'jenis_sks';
}
