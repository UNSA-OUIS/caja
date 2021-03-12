<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clasificador extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clasificadores';

    protected $fillable = ['nombre'];
}
