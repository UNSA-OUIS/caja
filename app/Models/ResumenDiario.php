<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumenDiario extends Model
{
    use HasFactory;

    protected $table = 'resumen_diario';

    protected $fillable = ['fecha_envio', 'fecha_emision', 'serie', 'correlativo', 'ticket', 'estado'];
}
