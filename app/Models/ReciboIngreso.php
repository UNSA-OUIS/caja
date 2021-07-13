<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ReciboIngreso extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'recibos_ingreso';
    protected $fillable = [
        'correlativo',
        'fecha_registro',
        'cajero_id',
        'cuenta_corriente_id'
    ];
}
