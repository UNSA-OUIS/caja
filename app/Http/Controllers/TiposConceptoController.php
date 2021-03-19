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
        $this->authorize("viewAny", TiposConcepto::class);

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
        try{
        $tiposConcepto = new TiposConcepto();
        $tiposConcepto->nombre = $request->nombre;
        $tiposConcepto->save();
        $result = ['successMessage' => 'Tipo de concepto registrado con exito'];
        }catch(\Exception $e){
            $result = ['errorMessage' => 'No se pudo registrar el tipo de concepto'];
            \Log::error('TiposConceptoController@store, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }

        return redirect()->route('tipos-concepto.iniciar')->with($result);
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
        try {                       
            $tiposConcepto->nombre = $request->nombre;
            $tiposConcepto->update();
            $result = ['successMessage' => 'Tipo de concepto actualizado con éxito'];            
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo actualizar el tipo de concepto'];
            \Log::error('TiposConceptoController@update, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }                      
        
        return redirect()->route('tipos-concepto.iniciar')->with($result);       
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TiposConcepto $tiposConcepto)
    {
        try {                       
            $tiposConcepto->delete();
            $result = ['successMessage' => 'Tipo de concepto eliminado con éxito'];            
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo eliminar el tipo de concepto'];
            \Log::error('TiposConceptoController@update, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }                      
        
        return redirect()->back()->with($result);       
    
   
    }

    public function restore($tipos_concepto_id)
    {
        try {                       
            $tiposConcepto = TiposConcepto::withTrashed()->findOrFail($tipos_concepto_id);
            $tiposConcepto->restore();
            $result = ['successMessage' => 'Tipo de concepto restaurado con éxito'];            
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo restadurar el tipo de concepto'];
            \Log::error('TiposConceptoController@update, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }                      
        
        return redirect()->back()->with($result);       
    
   
    }
}
