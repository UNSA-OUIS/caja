<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetallesComprobante extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'detalles_comprobante';

    protected $fillable = ['id', 'cantidad', 'valor_unitario', 'descuento', 'estado', 'concepto_id', 'comprobante_id'];
}
