<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['kode', 'nama'])]
class StatusPernikahan extends Model
{
    protected $table = 'status_pernikahans';
}
