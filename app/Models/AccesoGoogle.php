<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccesoGoogle extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'accesos_google';

    protected $fillable = [
        'correo',
        'nombre',
        'cargo',                
    ];
}
