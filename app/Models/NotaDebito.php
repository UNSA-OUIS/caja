<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaDebito extends Model
{
    use HasFactory;

    protected $table = 'nota_debito';

    protected $fillable = ['codigo', 'nues', 'serie', 'correlativo', 'codigo_motivo', 'descripcion_motivo', 'total', 'estado'];

    public function detalles()
    {
        return $this->hasMany(DetallesNotaDebito::class);
    }
}
