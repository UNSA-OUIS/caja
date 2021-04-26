<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CuentaCorriente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cuentas_corrientes';

    protected $fillable = ['numeroCuenta', 'descripcion', 'moneda'];
}
