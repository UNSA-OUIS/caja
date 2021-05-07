<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;
    protected $table = 'personas';
    protected $fillable = [
        'codigo',
        'nombre',
        'email_personal',
        'celular',
        'direccion',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
