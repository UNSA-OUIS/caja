<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    protected $connection = "mysql";
    protected $table = 'acdiden';
    protected $primaryKey = 'cui';
    protected $keyType = 'string';

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'cui', 'cui');
    }

    /**
     * Obtener todos los comprobantes del alumno.
     */
    public function comprobantes()
    {
        return $this->morphMany(Comprobante::class, 'comprobanteable');
    }
}
