<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailAlumno extends Model
{
    use HasFactory;
    
    protected $connection = "mysql";
    protected $table = 'actmail';
    protected $primaryKey = 'cui';

    public function alumno(){
        return $this->hasOne(Alumno::class,'cui','cui');
    }
}
