<?php

namespace App\Http\Controllers;

use App\Models\Dependencia;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DependenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$this->authorize("viewAny", Dependencia::class);
        error_log("index method");

        $query = Dependencia::where('nomb_depe', 'like', '%' . $request->filter . '%');
        $sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {
            $sortdirection = $request->sortdesc == "true" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
            error_log("the answer was sorted");

        }
        return $query->paginate($request->size);
    }

    public function buscarCodigoDependencia($codigo)
    {
        $dependencia = Dependencia::where('codi_depe', $codigo)->first();

        return json_encode($dependencia);
    }

    public function buscarDependencia($dependencia)
    {
        $dependencias = Dependencia::where('nomb_depe', 'like', $dependencia . '%')
                            ->take(20)
                            ->get();

        return $dependencias;
    }
}
