<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TiposConcepto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tipos_concepto';

    protected $fillable = ['nombre'];

    public function conceptos()
    {
        return $this->hasMany(Concepto::class);
    }
}
