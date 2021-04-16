<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $connection = "mysql";
    protected $table = 'acdidal';

    public function escuela()
    {        
        return $this->hasOne(Escuela::class, 'nues', 'nues');
    }
}
