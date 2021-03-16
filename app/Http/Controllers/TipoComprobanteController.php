<?php

namespace App\Http\Controllers;

use App\Http\Requests\TipoComprobanteStoreRequest;
use App\Http\Requests\TipoComprobanteUpdateRequest;
use App\Models\TipoComprobante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class TipoComprobanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = TipoComprobante::where('nombre', 'like', '%' . $request->filter . '%');

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
        $tipoComprobante = new TipoComprobante();
        $tipoComprobante->nombre = "";
        return Inertia::render('Tipo_Comprobante/NuevoMostrar', compact('tipoComprobante'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TipoComprobanteStoreRequest $request)
    {
        $tipoComprobante = new TipoComprobante();
        $tipoComprobante->nombre = $request->nombre;

        if ($tipoComprobante->save()) {
            $result = ['successMessage' => 'Tipo de Comprobante registrado con éxito', 'error' => false];
        } else {
            $result = ['errorMessage' => 'No se pudo registrar el tipo de comprobante', 'error' => true];
        }

        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TipoComprobante $tipoComprobante)
    {
        return Inertia::render('Tipo_Comprobante/NuevoMostrar', compact('tipoComprobante'));
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
    public function update(TipoComprobanteUpdateRequest $request, TipoComprobante $tipoComprobante)
    {
        $tipoComprobante->nombre = $request->nombre;

        if ($tipoComprobante->update()) {
            $result = ['successMessage' => 'Tipo de comprobante actualizado con éxito', 'error' => false];
        } else {
            $result = ['errorMessage' => 'No se pudo actualizar el tipo de comprobante', 'error' => true];
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoComprobante $tipoComprobante)
    {
        if ($tipoComprobante->delete()) {
            $result = ['successMessage' => 'Tipo de comprobante eliminado con éxito', 'error' => false];
        } else {
            $result = ['errorMessage' => 'No se pudo eliminar el tipo de comprobante', 'error' => true];
        }

        return $result;
    }

    public function restore($tipo_comprobante_id)
    {
        $tipo_comprobante_id = TipoComprobante::withTrashed()->findOrFail($tipo_comprobante_id);

        try {
            $tipo_comprobante_id->restore();
            $result = ['successMessage' => 'Tipo de comprobante restaurada con éxito', 'error' => false];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo restaurar el tipo de comprobante', 'error' => true];
            Log::warning('TiposConceptoController@restore, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return $result;
    }
}
