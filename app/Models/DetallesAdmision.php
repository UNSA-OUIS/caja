<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallesAdmision extends Model
{
    use HasFactory;

    protected $table = 'detalles_admision';

    protected $fillable = ['concepto_id', 'admision_id'];

    public function admision()
    {
        return $this->belongsTo(Admision::class);
    }

    public function concepto()
    {
        return $this->belongsTo(Concepto::class);
    }
}
