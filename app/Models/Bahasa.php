<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['kode', 'nama'])]
class Bahasa extends Model
{
    protected $table = 'bahasas';
}
