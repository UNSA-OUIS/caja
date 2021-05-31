<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model
{
    use HasFactory;

    protected $table = 'comprobantes';

    protected $fillable = ['codi_usuario', 'nues_depe', 'serie', 'correlativo', 'total', 'estado'];

    public function tipo_comprobante()
    {
        return $this->belongsTo(TipoComprobante::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetallesComprobante::class);
    }

    /**
     * Get the parent comprobanteable model (alumno or docente or dependencia or particular or empresa).
     */
    public function comprobanteable()
    {
        return $this->morphTo(__FUNCTION__, 'tipo_usuario', 'codi_usuario');
    }

    public function comprobante_afectado()
    {
        return $this->hasOne(Comprobante::class);
    }
}
