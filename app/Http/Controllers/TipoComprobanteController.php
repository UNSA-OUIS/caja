<?php

namespace App\Http\Controllers;

use App\Http\Requests\TipoComprobanteStoreRequest;
use App\Http\Requests\TipoComprobanteUpdateRequest;
use App\Models\TipoComprobante;
use App\Models\TiposConcepto;
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
        try {           
            $tipoComprobante = new TipoComprobante();
            $tipoComprobante->nombre = $request->nombre;
            $tipoComprobante->save();
            $result = ['successMessage' => 'Comprobante registrada con éxito'];            
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo registrar el comprobante'];
            \Log::error('TipoComprobanteController@store, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }                      
        
        return redirect()->route('tipo-comprobante.iniciar')->with($result);
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
        try {           
            $tipoComprobante = $request->nombre;
            $tipoComprobante->update();
            $result = ['successMessage' => 'Comprobante actualizado con éxito'];            
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo actualizar el comprobante'];
            \Log::error('TipoComprobanteController@store, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }                      
        
        return redirect()->route('tipo-comprobante.iniciar')->with($result);
  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoComprobante $tipoComprobante)
    {
        try {                       
            $tipoComprobante->delete();            
            $result = ['successMessage' => 'Tipo de comprobante eliminado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo eliminar el comprobante'];
            \Log::error('TipoComprobanteController@destroy, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }                      
        
        return redirect()->back()->with($result);   
    }

    public function restore($tipo_comprobante_id)
    {
        try {                       
            $tipoComprobante = TipoComprobante::withTrashed()->findOrFail($tipo_comprobante_id);  
            $tipoComprobante->restore();
            $result = ['successMessage' => 'Comprobante restaurado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo restaurar el comprobante'];
            \Log::error('TipoComprobanteController@restore, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }                      
        
        return redirect()->back()->with($result);
    }
}
