<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Escuela extends Model
{
     use HasFactory;

    protected $connection = "mysql";
    protected $table = 'actescu';

    public function matricula()
    {        
        return $this->belongsTo(Matricula::class, 'nues', 'nues');
    }
}
