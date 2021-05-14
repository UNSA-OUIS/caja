<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class NumeroOperacion extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'numeros_operacion';
    protected $fillable = [
        'serie',
        'correlativo',
        'tipo_comprobante_id',
        'punto_venta_id'
    ];

    public function puntoVenta()
    {
        return $this->belongsTo(PuntosVenta::class, 'punto_venta_id', 'id');
    }

    public function tipoComprobante()
    {
        return $this->belongsTo(TipoComprobante::class, 'tipo_comprobante_id', 'id');
    }
}


