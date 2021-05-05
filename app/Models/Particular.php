<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Particular extends Model
{
    use HasFactory, SoftDeletes;   

    protected $table = 'particulares';
    protected $primaryKey = 'dni';
    protected $keyType = 'string';

    protected $fillable = ['dni', 'apn', 'email'];

    /**
     * Obtener todos los comprobantes del particular.
     */
    public function comprobantes()
    {
        return $this->morphMany(Comprobante::class, 'comprobanteable');
    }
}
