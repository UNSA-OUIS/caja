<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallesNotaDebito extends Model
{
    use HasFactory;

    protected $table = 'detalles_nota_debito';

    protected $fillable = ['cantidad', 'valor_unitario', 'descuento', 'estado', 'concepto_id', 'comprobante_id', 'nota_credito_id'];

    public function comprobante()
    {
        return $this->belongsTo(NotaDebito::class);
    }
}
