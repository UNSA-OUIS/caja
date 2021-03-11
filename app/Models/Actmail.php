<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actmail extends Model
{
    use HasFactory;

    protected $connection = "mysql";
    protected $table = 'actmail';
    protected $primaryKey = 'mail';   

}
