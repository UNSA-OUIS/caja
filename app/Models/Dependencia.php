<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Dependencia extends Model
{
    use HasFactory;

    protected $connection = "mysql2";
    protected $table = 'depe';
    protected $primaryKey = 'codi_depe';
    protected $keyType = 'string';
    protected $appends = ['detalles_sum'];
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

    public function conceptosMontos()
    {
        return $this->setConnection('pgsql')->hasMany(Concepto::class, 'codi_depe', 'codi_depe')
                        ->select(DB::raw('SUM(detalles_comprobante.valor_unitario) as monto'))
                        ->leftJoin('detalles_comprobante', 'detalles_comprobante.concepto_id', '=', 'conceptos.id');
    }

    public function getDetallesSumAttribute()
    {
        return $this->conceptos()->leftJoin('detalles_comprobante', 'detalles_comprobante.concepto_id', '=', 'conceptos.id')
                                ->sum('detalles_comprobante.valor_unitario');
    }

}
