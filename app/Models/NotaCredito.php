<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaCredito extends Model
{
    use HasFactory;

    protected $table = 'nota_credito';

    protected $fillable = ['codigo', 'nues', 'serie', 'correlativo', 'codigo_motivo', 'descripcion_motivo', 'total', 'estado'];

    public function detalles()
    {
        return $this->hasMany(DetallesNotaCredito::class);
    }
}
