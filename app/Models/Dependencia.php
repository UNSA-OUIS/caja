<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dependencia extends Model
{
    use HasFactory;

    protected $connection = "mysql2";
    protected $table = 'depe';
    protected $primaryKey = 'codi_depe';
    protected $keyType = 'string';
    //protected $guarded = [];

    /**
     * Obtener todos los comprobantes de la dependencia.
     */
    public function comprobantes()
    {
        return $this->morphMany(Comprobante::class, 'comprobanteable');
    }

    public function conceptos()
    {
        return $this->setConnection('pgsql')->hasMany(Concepto::class, 'codi_depe', 'codi_depe');
    }
    public function docenctes()
    {
        return $this->setConnection('mysql2')->hasMany(Docente::class, 'sigl_depe', 'depend');
    }
}
