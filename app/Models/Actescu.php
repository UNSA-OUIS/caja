<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actescu extends Model
{
    use HasFactory;

    protected $connection = "mysql";
    protected $table = 'actescu';   

    protected $hidden = [
        'area', 'cegre', 'ctes', 'escu', 'muj', 'hom', 'total', 'facu',
        'ffin', 'fin', 'flc', 'fln', 'napr', 'ncua', 'ninv', 'nive',
        'nnor', 'nseg', 'nter', 'nues', 'espe',
    ];

    public function acdidal()
    {        
        return $this->belongsTo(Acdidal::class, 'nues', 'nues');
    }
}
