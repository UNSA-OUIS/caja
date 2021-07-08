<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PuntosVenta extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'puntos_venta';
    protected $fillable = [
        'ip',
        'nombre',
        'direccion',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function numerosComprobante()
    {
        return $this->hasMany(NumeroComprobante::class, 'punto_venta_id', 'id');
    }

    public function conceptos()
    {
        return $this->belongsToMany(Concepto::class, 'concepto_punto_venta', 'punto_venta_id', 'concepto_id');
    }

    public function cuentasCorrientes()
    {
        return $this->belongsToMany(CuentaCorriente::class, 'cuenta_corriente_punto_venta', 'punto_venta_id', 'cuenta_corriente_id');
    }
}
