<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    protected $connection = "mysql";
    protected $table = 'actdepa';
    
    public function docentes()
    {
        return $this->setConnection('mysql2')->hasMany(Docente::class, 'depa', 'depend');
    }
}
