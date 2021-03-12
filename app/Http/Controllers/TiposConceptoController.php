<?php

namespace App\Http\Controllers;

use App\Http\Requests\TipoConceptoStoreRequest;
use App\Http\Requests\TipoConceptoUpdateRequest;
use App\Models\TiposConcepto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class TiposConceptoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = TiposConcepto::where('nombre', 'like', '%' . $request->filter . '%');

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
        $tiposConcepto = new TiposConcepto();
        $tiposConcepto->nombre = "";
        return Inertia::render('Tipos_Concepto/NuevoMostrar', compact('tiposConcepto'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TipoConceptoStoreRequest $request)
    {
        $tiposConcepto = new TiposConcepto();
        $tiposConcepto->nombre = $request->nombre;

        if ($tiposConcepto->save()) {
            $result = ['successMessage' => 'Tipo de Concepto registrado con éxito', 'error' => false];
        } else {
            $result = ['errorMessage' => 'No se pudo registrar el tipo de concepto', 'error' => true];
        }

        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TiposConcepto $tiposConcepto)
    {
        return Inertia::render('Tipos_Concepto/NuevoMostrar', compact('tiposConcepto'));
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
    public function update(TipoConceptoUpdateRequest $request, TiposConcepto $tiposConcepto)
    {
        $tiposConcepto->nombre = $request->nombre;

        if ($tiposConcepto->update()) {
            $result = ['successMessage' => 'Tipo de concepto actualizado con éxito', 'error' => false];
        } else {
            $result = ['errorMessage' => 'No se pudo actualizar el tipo de concepto', 'error' => true];
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TiposConcepto $tiposConcepto)
    {
        if ($tiposConcepto->delete()) {
            $result = ['successMessage' => 'Tipo de concepto eliminado con éxito', 'error' => false];
        } else {
            $result = ['errorMessage' => 'No se pudo eliminar el tipo de concepto', 'error' => true];
        }

        return $result;
    }

    public function restore($tipos_concepto_id)
    {
        $tipos_concepto_id = TiposConcepto::withTrashed()->findOrFail($tipos_concepto_id);

        try {
            $tipos_concepto_id->restore();
            $result = ['successMessage' => 'Tipo de concepto restaurada con éxito', 'error' => false];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo restaurar el tipo de concepto', 'error' => true];
            Log::warning('TiposConceptoController@restore, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return $result;
    }
}
