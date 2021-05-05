<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;
    protected $table = 'personas';
    protected $fillable = ['codigo', 'nombre'];

    public function persona()
    {
        $persona = Persona::where('user_id', $this->id)->first();
        return $persona;
    }

}
