<?php

namespace App\Http\Controllers;

use App\Models\Admision;
use App\Models\BancoBCP;
use App\Models\Comprobante;
use App\Models\DetallesComprobante;
use App\Models\NumeroComprobante;
use App\Models\Particular;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BancoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$this->authorize("viewAny", Comprobante::class);

        $query = BancoBCP::select('concepto')
            ->whereDate('frecepcion', '>=', $request->fecha_inicio)
            ->whereDate('frecepcion', '<=', $request->fecha_fin)
            ->where('flag', 0)
            ->selectRaw('count(concepto) as cantidad')
            ->selectRaw('sum(mont_pag) as monto_acumulado')
            ->selectRaw("DATE_FORMAT(fpago,'%Y-%m-%d') as fecha_pago")
            ->selectRaw("DATE_FORMAT(frecepcion,'%Y-%m-%d') as fecha_recepcion")
            ->groupBy('concepto', 'fecha_pago', 'fecha_recepcion')
            ->orderBy('concepto', 'ASC')
            ->get();

        return $query;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function procesar_pagos(Request $request)
    {
        //return $request;
        DB::beginTransaction();

        try {
            $pagos_reintegro_admision = array();
            $pagos_inscripcion_admision = array();
            $pagos_pension_cpu = array();
            $pagos_cambio_carrera = array();

            if ($request->proceso == 1) {

                if ($request->subproceso == "1") {
                    $pagos_reintegro_admision = BancoBCP::whereDate('frecepcion', '>=', $request->fecha_inicio)
                        ->whereDate('frecepcion', '<=', $request->fecha_fin)
                        ->where('concepto', 'REINTEGRO ADMISION')
                        ->where('flag', 0)
                        ->get();
                    //return 'subproceso 1';
                } else if ($request->subproceso == "2") {
                    $pagos_inscripcion_admision = BancoBCP::whereDate('frecepcion', '>=', $request->fecha_inicio)
                        ->whereDate('frecepcion', '<=', $request->fecha_fin)
                        ->where('concepto', 'INSCRIPCION ADMISION')
                        ->where('flag', 0)
                        ->get();
                    //return $pagos_inscripcion_admision;
                } else if ($request->subproceso == "3") {
                    $pagos_pension_cpu = BancoBCP::whereDate('frecepcion', '>=', $request->fecha_inicio)
                        ->whereDate('frecepcion', '<=', $request->fecha_fin)
                        ->where('concepto', 'PENSION CPU')
                        ->where('flag', 0)
                        ->get();
                    //return 'subproceso 3';
                } else if ($request->subproceso == "4") {
                    $pagos_cambio_carrera = BancoBCP::whereDate('frecepcion', '>=', $request->fecha_inicio)
                        ->whereDate('frecepcion', '<=', $request->fecha_fin)
                        ->where('concepto', 'CAMBIO CARRERA')
                        ->where('flag', 0)
                        ->get();
                    //return 'subproceso 4';
                } else {
                    $pagos_reintegro_admision = BancoBCP::whereDate('frecepcion', '>=', $request->fecha_inicio)
                        ->whereDate('frecepcion', '<=', $request->fecha_fin)
                        ->where('concepto', 'REINTEGRO ADMISION')
                        ->where('flag', 0)
                        ->get();
                    $pagos_inscripcion_admision = BancoBCP::whereDate('frecepcion', '>=', $request->fecha_inicio)
                        ->whereDate('frecepcion', '<=', $request->fecha_fin)
                        ->where('concepto', 'INSCRIPCION ADMISION')
                        ->where('flag', 0)
                        ->get();
                    $pagos_pension_cpu = BancoBCP::whereDate('frecepcion', '>=', $request->fecha_inicio)
                        ->whereDate('frecepcion', '<=', $request->fecha_fin)
                        ->where('concepto', 'PENSION CPU')
                        ->where('flag', 0)
                        ->get();
                    $pagos_cambio_carrera = BancoBCP::whereDate('frecepcion', '>=', $request->fecha_inicio)
                        ->whereDate('frecepcion', '<=', $request->fecha_fin)
                        ->where('concepto', 'CAMBIO CARRERA')
                        ->where('flag', 0)
                        ->get();
                }

                foreach ($pagos_inscripcion_admision as $indice => $cabecera) {

                    $comprobante = new Comprobante();
                    $cajero = NumeroComprobante::where('punto_venta_id', Auth::user()->id)->where('tipo_comprobante_id', 1)->first();


                    /*$nombres_apellidos = $cabecera['apn'];
                    $indice = stripos($nombres_apellidos, ',');
                    $nombres = substr($nombres_apellidos, 0, $indice);
                    $apellidos = substr($nombres_apellidos, $indice, strlen($nombres_apellidos));*/

                    /*$cantidad_dni = BancoBCP::where('ndoc', $cabecera['ndoc'])->count();
                    if ($cantidad_dni == 1) {
                        $particular = new Particular();
                        $particular->dni = $cabecera['ndoc'];
                        $particular->nombres = 'Jesus';
                        $particular->apellidos = 'Ortiz';
                        $particular->save();
                    } else {
                        $registro = BancoBCP::where('ndoc', $cabecera['ndoc'])->first();
                        $particular = new Particular();
                        $particular->dni = $registro->ndoc;
                        $particular->nombres = 'Jesus';
                        $particular->apellidos = 'Ortiz';
                        $particular->save();
                    }*/
                    $comprobante->tipo_usuario = 'particular';
                    $comprobante->codi_usuario = '48611654';
                    $comprobante->tipo_comprobante_id = $cajero->tipo_comprobante_id;
                    $comprobante->serie = $cajero->serie;
                    $comprobante->correlativo = $cajero->correlativo;
                    $comprobante->total_descuento = 0;
                    $comprobante->total_impuesto = 0;
                    $comprobante->total_inafecta = 0;
                    $comprobante->total_gravada = $cabecera['mont_pag'];
                    $comprobante->total = $cabecera['mont_pag'];
                    $comprobante->tipo_pago = 'Efectivo';
                    $comprobante->estado = 'no_enviado';
                    $comprobante->cajero_id = Auth::user()->id;
                    $comprobante->cuenta_33 = true;
                    $comprobante->enviado = false;
                    $comprobante->save();

                    $proceso_inscripcion_admision = Admision::with('detalles')->where('proceso_id', 2)->first();
                    foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                        $detalle_comprobante = new DetallesComprobante();
                        $detalle_comprobante->cantidad = 1;
                        $detalle_comprobante->valor_unitario =  $detalle['precio_variable'];
                        $detalle_comprobante->gravado =  $detalle['precio_variable'];
                        $detalle_comprobante->inafecto =  0;
                        $detalle_comprobante->impuesto =  0;
                        $detalle_comprobante->codi_depe =  0;
                        $detalle_comprobante->descuento =  0;
                        $detalle_comprobante->tipo_descuento =  'soles';
                        $detalle_comprobante->subtotal =  $detalle['precio_variable'];
                        $detalle_comprobante->concepto_id =  $detalle['concepto_id'];
                        $detalle_comprobante->comprobante_id =  $comprobante->id;
                        $detalle_comprobante->save();
                    }
                    $numeroComp = NumeroComprobante::where('serie', $cajero->serie)->first();
                    $numeroComp->correlativo = str_pad($numeroComp->correlativo + 1, 8, "0", STR_PAD_LEFT);
                    $numeroComp->update();
                }
            } else {
                return 'No es admision';
            }
            DB::commit();
            $result = [
                'successMessage' => 'Comprobante Registrado con exito',
                'error' => false
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = ['errorMessage' => 'No se pudo registrar el comprobante', 'error' => true];
            Log::error('BancoController@procesar_pagos, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }
        return $result;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
