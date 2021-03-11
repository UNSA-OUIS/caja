<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Acdiden;
use App\Models\Acdidal;
use Inertia\Inertia;

class EstudianteController extends Controller
{
    public function getInfo() 
    {
        $cui = \Auth::user()->cui;

        $alumno = Acdiden::select(\DB::raw('(SUBSTRING(acdiden.dic, 2)) AS dni'),
                                  \DB::raw('(SUBSTRING_INDEX(REPLACE(acdiden.apn, "/", " "), ",", 1)) AS apellidos'),
                                  \DB::raw('(SUBSTRING_INDEX(SUBSTRING_INDEX(REPLACE(acdiden.apn, "/", " "), ",", 2), ",", -1)) AS nombres')
                                )
                            ->where('cui', $cui)
                            ->first();  
        
        $matriculas = Acdidal::with('escuela')
                        ->where('cui', $cui)
                        ->whereHas('escuela', function($query) {
                            $query->where('nive', 'Z');
                        })
                        ->get();       

        return Inertia::render('Pago', compact('alumno', 'matriculas'));
    }
}
