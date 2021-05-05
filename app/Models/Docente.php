<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;
    
    protected $connection = "mysql";
    protected $table = 'siac_doc';
    protected $primaryKey = 'codper';

    /**
     * Obtener todos los comprobantes del docente.
     */
    public function comprobantes()
    {
        return $this->morphMany(Comprobante::class, 'comprobanteable');
    }
}
