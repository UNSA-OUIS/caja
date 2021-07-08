<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CuentaCorriente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cuentas_corrientes';

    protected $fillable = ['numeroCuenta', 'banco', 'moneda'];

    public function puntos_venta()
    {
        return $this->belongsToMany(PuntosVenta::class, 'cuenta_corriente_punto_venta', 'cuenta_corriente_id', 'punto_venta_id');
    }
}
