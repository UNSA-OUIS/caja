<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Concepto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'conceptos';

    protected $fillable = [
        'codigo',
        'descripcion',
        'descripcion_imp',
        'precio',
        'tipo_afectacion',
        'tipo_concepto_id',
        'clasificador_id',
        'unidad_medida_id',
        'semestre',
        'codi_depe',
    ];

    public function tipo_concepto()
    {
        return $this->belongsTo(TiposConcepto::class);
    }

    public function clasificador()
    {
        return $this->belongsTo(Clasificador::class);
    }

    public function unidad_medida()
    {
        return $this->belongsTo(UnidadMedida::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetallesComprobante::class);
    }

    public function dependencia()
    {
        return $this->setConnection('mysql2')->belongsTo(Dependencia::class, 'codi_depe', 'codi_depe');
    }

    public function detalles_comprobante()
    {
        return $this->setConnection('pgsql')->hasMany(DetallesComprobante::class);
    }

    public function puntos_venta()
    {
        return $this->belongsToMany(PuntosVenta::class, 'concepto_punto_venta', 'concepto_id', 'punto_venta_id');
    }
}
