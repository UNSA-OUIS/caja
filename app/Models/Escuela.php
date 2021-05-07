<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Escuela extends Model
{
     use HasFactory;

    protected $connection = "mysql";
    protected $table = 'actescu';

    protected $hidden = [
        'area', 'cegre', 'ctes', 'escu', 'muj', 'hom', 'total', 'facu',
        'ffin', 'fin', 'flc', 'fln', 'napr', 'ncua', 'ninv', 'nive',
        'nnor', 'nseg', 'nter'
    ];

    public function matricula()
    {        
        return $this->belongsTo(Matricula::class, 'nues', 'nues');
    }
}
