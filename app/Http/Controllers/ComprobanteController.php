<?php

namespace App\Http\Controllers;

use App\Models\Comprobante;
use App\Models\DetallesComprobante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComprobanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $comprobante = new Comprobante();

            $comprobante->codigo = $request->codigo;
            $comprobante->cui = $request->cui;
            $comprobante->nues = $request->nues;
            $comprobante->serie = $request->serie;
            $comprobante->correlativo = $request->correlativo;
            $comprobante->total = $request->total;
            $comprobante->estado = $request->estado;
            $comprobante->save();
            
            $detalles = new DetallesComprobante();
            $d = $request->submittedDetails;
            foreach ($d as $key => $value) {
                $detalles->id =  $value['id'];
                $detalles->cantidad =  $value['cantidad'];
                $detalles->valor_unitario =  $value['prUnit'];
                $detalles->descuento =  $value['descuento'];
                $detalles->estado =  true;
                $detalles->concepto_id =  $value['codigo'];
                $detalles->comprobante_id =  $comprobante->id;
                $detalles->save();
            }
            DB::commit();
            $result = ['successMessage' => 'Comprobante registrado con éxito'];
        } catch (\Exception $e) {
            DB::rollback();
            $result = ['errorMessage' => 'No se pudo registrar el comprobante'];
        }
        return redirect()->route('comprobantes.iniciar')->with($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
