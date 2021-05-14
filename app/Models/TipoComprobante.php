<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoComprobante extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tipo_comprobante';

    protected $fillable = ['nombre'];

    public function comprobantes()
    {
        return $this->hasMany(Comprobante::class);
    }
    public function asignaciones()
    {
        return $this->hasMany(Asignaciones::class);
    }
    public function numerosOperacion()
    {
        return $this->hasMany(NumeroOperacion::class, 'tipo_comprobante_id', 'id');
    }
}
