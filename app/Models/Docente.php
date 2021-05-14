<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;
    
    protected $connection = "mysql";
    protected $table = 'SIAC_DOC';
    protected $primaryKey = 'codper';
    protected $keyType = 'string';

    /**
     * Obtener todos los comprobantes del docente.
     */
    public function comprobantes()
    {
        return $this->morphMany(Comprobante::class, 'comprobanteable');
    }
    public function dependencia()
    {
        return $this->setConnection('mysql2')->belongsTo(Dependencia::class,'depend' ,'sigl_depe');
    }
}
