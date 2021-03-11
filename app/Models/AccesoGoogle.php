<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccesoGoogle extends Model
{
    use HasFactory;
    protected $table = 'google_accesos';
    protected $primaryKey = 'correo';
}
