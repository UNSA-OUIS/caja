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
        /*$alumnos = Alumno::with('matriculas.escuela')
                            ->where('apn', 'like', $request->apn . '%')->select('cui', 'dic', 'apn')
                            ->take(10)->get();

        return $alumnos;*/        

        $query = Alumno::with('matriculas.escuela')
                    ->whereRaw("REPLACE(apn, '/', ' ') like ?", [$request->filter . '%'])
                    ->select('cui', 'dic', 'apn')
                    ->orderBy('apn', 'asc');
        
        return $query->paginate($request->size);
    }

    public function buscarAlumno(Request $request) 
    {             
        if ($request->opcion_busqueda == 'CUI') {
            $query = Alumno::with('matriculas.escuela')
                    ->where('cui', $request->filtro)->select('cui', 'dic', 'apn')
                    ->orderBy('apn', 'asc');
        }
        else if ($request->opcion_busqueda == 'APN') {
            $query = Alumno::with('matriculas.escuela')
                    ->whereRaw("REPLACE(apn, '/', ' ') like ?", [$request->filtro . '%'])
                    ->select('cui', 'dic', 'apn')
                    ->orderBy('apn', 'asc');
        }

        return $query->paginate($request->size);
    }
}
