<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClasificadorStoreRequest;
use App\Http\Requests\ClasificadorUpdateRequest;
use App\Models\Clasificador;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ClasificadorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize("viewAny", Clasificador::class);

        $query = Clasificador::where('nombre', 'like', '%' . $request->filter . '%');
        $sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {
            $sortdirection = $request->sortdesc == "true" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
        } else {
            $query = $query->withTrashed();
        }

        return $query->paginate($request->size);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clasificador = new Clasificador();
        $clasificador->nombre = "";

        return Inertia::render('Clasificadores/NuevoMostrar', compact('clasificador'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClasificadorStoreRequest $request)
    {
        $clasificador = new Clasificador();
        $clasificador->nombre = $request->nombre;

        if ($clasificador->save()) {
            $result = ['successMessage' => 'Clasificador registrado con éxito', 'error' => false];
        } else {
            $result = ['errorMessage' => 'No se pudo registrar el clasificador', 'error' => true];
        }

        return $result;
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Clasificador $clasificador)
    {
        return Inertia::render('Clasificadores/NuevoMostrar', compact('clasificador'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ClasificadorUpdateRequest $request, Clasificador $clasificador)
    {
        $clasificador->nombre = $request->nombre;

        if ($clasificador->update()) {
            $result = ['successMessage' => 'Clasificador actualizado con éxito', 'error' => false];
        } else {
            $result = ['errorMessage' => 'No se pudo actualizar el clasificador', 'error' => true];
        }

        return $result;
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Clasificador $clasificador)
    {
        if ($clasificador->delete()) {
            $result = ['successMessage' => 'Clasificador eliminado con éxito', 'error' => false];
        } else {
            $result = ['errorMessage' => 'No se pudo eliminar el clasificador', 'error' => true];
        }

        return $result;
    }

    public function restore($clasificador_id)
    {
        $clasificador = Clasificador::withTrashed()->findOrFail($clasificador_id);

        try {
            $clasificador->restore();
            $result = ['successMessage' => 'Clasificador restaurado con éxito', 'error' => false];
            
        } catch (\Throwable $e) {
            $result = ['errorMessage' => 'No se pudo restaurar el clasificador', 'error' => true];
            \Log::warning('ClasificadorController@restore, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }

        return $result;
    }
}
