<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['kode', 'nama'])]
class OutputPenelitian extends Model
{
    protected $table = 'output_penelitians';
}
