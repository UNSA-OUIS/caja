<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use Illuminate\Http\Request;

class DocenteController extends Controller
{
    public function buscarCodigoDocente($codigo)
    {
        $docente = Docente::where('codper', $codigo)->first();

        return json_encode($docente);
    }

    public function buscarApnDocente(Request $request)
    {
        $docentes = Docente::where('apn', 'like', $request->apn . '%')->take(10)->get();

        return $docentes;
    }
}
