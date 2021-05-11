<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Matricula;
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    public function buscarCuiAlumno($cui)
    {
        $alumno = Alumno::with('matriculas.escuela')
                    ->where('cui', $cui)->select('cui', 'dic', 'apn')->first();

        return $alumno;        
    }

    public function buscarApnAlumno(Request $request)
    {        
        $alumnos = Alumno::with('matriculas.escuela')
                            ->where('apn', 'like', $request->apn . '%')->select('cui', 'dic', 'apn')
                            ->take(10)->get();

        return $alumnos;      
    }    
}
