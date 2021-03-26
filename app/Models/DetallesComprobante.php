<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallesComprobante extends Model
{
    use HasFactory;

    protected $table = 'detalles_comprobante';

    protected $fillable = ['cantidad', 'valor_unitario', 'descuento', 'estado', 'concepto_id', 'comprobante_id'];

    public function comprobante()
    {
        return $this->belongsTo(Comprobante::class);
    }
}
