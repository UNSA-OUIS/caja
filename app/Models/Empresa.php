<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use HasFactory, SoftDeletes;   

    protected $table = 'empresas';
    protected $primaryKey = 'ruc';
    protected $keyType = 'string';

    //protected $fillable = ['ruc', 'razon_social', 'direccion', 'email'];

    /**
     * Obtener todos los comprobantes de la empresa.
     */
    public function comprobantes()
    {
        return $this->morphMany(Comprobante::class, 'comprobanteable');
    }
}
