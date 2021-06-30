<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admision extends Model
{
    use HasFactory;

    protected $table = 'admision';

    protected $fillable = ['proceso_id', 'monto_total', 'tipo_colegio'];

    public function detalles()
    {
        return $this->hasMany(DetallesAdmision::class);
    }
}
