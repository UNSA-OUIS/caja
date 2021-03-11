<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acdidal extends Model
{
    use HasFactory;

    protected $connection = "mysql";
    protected $table = 'acdidal';       
    
    protected $hidden = [
        'cod0', 'cona', 'fcon', 'flc', 'fnc', 'cond', 'deta', 'digi', 'muj', 'fdig', 'sede',
        'nmat', 'fln', 
    ];

    public function escuela()
    {        
        return $this->hasOne(Actescu::class, 'nues', 'nues');
    }
}
