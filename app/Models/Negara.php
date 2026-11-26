<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['kode', 'nama', 'kode_emis'])]
class Negara extends Model
{
    protected $table = 'negaras';
}
