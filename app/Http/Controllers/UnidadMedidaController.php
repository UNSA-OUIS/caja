<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;
use App\Http\Requests\UnidadMedidaStoreRequest;
use App\Http\Requests\UnidadMedidaUpdateRequest;

class UnidadMedidaController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("viewAny", UnidadMedida::class);        

        $query = UnidadMedida::where('nombre', 'like', '%' . $request->filter . '%')
                    ->orderBy('id', 'desc');

        $sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {
            $sortdirection = $request->sortdesc == "true" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
        } else {
            $query = $query->withTrashed();
        }

        return $query->paginate($request->size);
    }

    public function create()
    {   
        $this->authorize('create', UnidadMedida::class);
        // $nextid = UnidadMedida::all()->last()->id;
        $unidadMedida = new UnidadMedida();
        // $unidadMedida->id = $nextid+1;k
        $unidadMedida->nombre = "";
        $unidadMedida->codigo = "";

        return Inertia::render('Unidades_Medida/NuevoMostrar', compact('unidadMedida'));
    }

    public function store(UnidadMedidaStoreRequest $request)
    {
        $this->authorize('create', UnidadMedida::class);
        
        try {
            $unidadMedida = new UnidadMedida();
            $unidadMedida->nombre = $request->nombre;
            $unidadMedida->codigo = $request->codigo;
            $unidadMedida->save();
            $result = ['successMessage' => 'Unidad de medida registrada con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo registrar la unidad de medida'];
            \Log::error('UnidadMedidaController@store, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->route('unidades-medida.iniciar')->with($result);
    }

    public function show(UnidadMedida $unidadMedida)
    {
        $this->authorize('view', $unidadMedida);

        return Inertia::render('Unidades_Medida/NuevoMostrar', compact('unidadMedida'));
    }

    public function edit($id)
    {
        //
    }

    public function update(UnidadMedidaUpdateRequest $request, UnidadMedida $unidadMedida)
    {
        $this->authorize('update', $unidadMedida);

        try {
            $unidadMedida->nombre = $request->nombre;
            $unidadMedida->codigo = $request->codigo;
            $unidadMedida->update();
            $result = ['successMessage' => 'Unidad de medida actualizada con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo actualizar la unidad de medida'];
            \Log::error('UnidadMedidaController@update, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->route('unidades-medida.iniciar')->with($result);
    }

    public function destroy(UnidadMedida $unidadMedida)
    {
        $this->authorize('delete', $unidadMedida);

        try {
            $unidadMedida->delete();
            $result = ['successMessage' => 'Unidad de medida eliminada con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo eliminar la unidad de medida'];
            \Log::error('UnidadMedidaController@destroy, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->back()->with($result);
    }

    public function restore($unidad_medida_id)
    {
        $unidadMedida = UnidadMedida::withTrashed()->findOrFail($unidad_medida_id);
        $this->authorize('restore', $unidadMedida);

        try {
            
            $unidadMedida->restore();
            $result = ['successMessage' => 'Unidad de medida restaurada con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo restaurar la unidad de medida'];
            \Log::error('UnidadMedidaController@restore, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->back()->with($result);
    }
}
