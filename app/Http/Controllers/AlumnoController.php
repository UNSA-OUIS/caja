<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Matricula;
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    public function buscarCuiAlumno($cui)
    {
        $alumno = Alumno::where('cui', $cui)->select('cui', 'dic', 'apn')->first();
        $matriculas = Matricula::with('escuela')->where('cui', $cui)->get();

        return [
            'alumno' => $alumno,
            'matriculas' => $matriculas
        ];
    }

    public function buscarApnAlumno(Request $request)
    {
        $alumnos = Alumno::where('apn', 'like', $request->ap_paterno . '%')
            //->orWhere('apn', 'like', '%' . $request->ap_materno)
            //->orWhere('apn', 'like', $request->nombres)
            ->take(10)
            ->get();

        return $alumnos;
    }
}
