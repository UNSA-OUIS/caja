<?php

namespace App\Http\Controllers;

use App\Http\Requests\NumeroComprobanteStoreRequest;
use App\Http\Requests\NumeroComprobanteUpdateRequest;
use App\Models\NumeroComprobante;
use App\Models\PuntosVenta;
use App\Models\TipoComprobante;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NumeroComprobanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = NumeroComprobante::with('puntoVenta', 'tipoComprobante')->where('serie', 'like', '%' . $request->filter . '%');
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
        $numeroOperacion = new NumeroComprobante();
        $numeroOperacion->serie = "";
        $numeroOperacion->correlativo = "00000001";
        $numeroOperacion->tipo_comprobante_id = null;
        $numeroOperacion->punto_venta_id = null;

        $tiposComprobante = TipoComprobante::select('id as value', 'nombre as text')
            ->orderBy('nombre', 'asc')->get();
        
        $puntosVenta = PuntosVenta::select('id as value', 'nombre as text')
            ->orderBy('nombre', 'asc')->get();

        return Inertia::render('Numeros_Operacion/NuevoMostrar', compact('numeroOperacion', 'puntosVenta', 'tiposComprobante'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NumeroComprobanteStoreRequest $request)
    {
        try {
            $numeroOperacion = new NumeroComprobante();
            $numeroOperacion->serie = $request->serie;
            $numeroOperacion->correlativo = $request->correlativo;
            $numeroOperacion->tipo_comprobante_id = $request->tipo_comprobante_id;
            $numeroOperacion->punto_venta_id = $request->punto_venta_id;
            $numeroOperacion->save();
            $result = ['successMessage' => 'Número de operación registrado con éxito'];

        } catch (\Throwable $e) {
            $result = ['errorMessage' => 'No se pudo registrar el número de operación.', 'error' => true];
            \Log::error('NumeroComprobanteController@store, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }

        return redirect()->route('numerosOperacion.iniciar')->with($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(NumeroComprobante $numeroOperacion)
    {
        $tiposComprobante = TipoComprobante::select('id as value', 'nombre as text')
            ->orderBy('nombre', 'asc')->get();
        
        $puntosVenta = PuntosVenta::select('id as value', 'nombre as text')
        ->orderBy('nombre', 'asc')->get();

        return Inertia::render('Numeros_Operacion/NuevoMostrar', compact('numeroOperacion', 'puntosVenta', 'tiposComprobante'));
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
    public function update(NumeroComprobanteUpdateRequest $request, NumeroComprobante $numeroOperacion)
    {
        try {
            $numeroOperacion->serie = $request->serie;
            $numeroOperacion->correlativo = $request->correlativo;
            $numeroOperacion->tipo_comprobante_id = $request->tipo_comprobante_id;
            $numeroOperacion->punto_venta_id = $request->punto_venta_id;
            $numeroOperacion->update();
            $result = ['successMessage' => 'Número de operación actualizado con éxito'];

        } catch (\Throwable $e) {
            $result = ['errorMessage' => 'No se pudo actualizar el número de operación', 'error' => true];
            \Log::error('NumeroComprobanteController@update, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }

        return redirect()->route('numerosOperacion.iniciar')->with($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(NumeroComprobante $numeroOperacion)
    {
        try {
            $numeroOperacion->delete();
            $result = ['successMessage' => 'Número de operación eliminado con éxito'];

        } catch (\Throwable $e) {
            $result = ['errorMessage' => 'No se pudo eliminar el número de operación'];
            \Log::error('NumeroComprobanteController@destroy, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
            
        }

        return redirect()->back()->with($result);
    }

    public function restore($numeroOperacion_id)
    {

        try {
            $numeroOperacion = NumeroComprobante::withTrashed()->findOrFail($numeroOperacion_id);
            $numeroOperacion->restore();
            $result = ['successMessage' => 'Número de operación restaurado con éxito'];
            
        } catch (\Throwable $e) {
            $result = ['errorMessage' => 'No se pudo restaurar el número de operación'];
            \Log::warning('NumeroComprobanteController@restore, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }

        return redirect()->back()->with($result);
    }
}
