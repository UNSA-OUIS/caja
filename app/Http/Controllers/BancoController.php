<?php

namespace App\Http\Controllers;

use App\Models\Admision;
use App\Models\BancoBCP;
use App\Models\Comprobante;
use App\Models\Concepto;
use App\Models\DetallesComprobante;
use App\Models\InscReci;
use App\Models\NumeroComprobante;
use App\Models\Particular;
use App\Models\RegiInscripcion;
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

                    foreach ($pagos_reintegro_admision as $indice => $cabecera) {

                        $comprobante = new Comprobante();
                        $cajero = NumeroComprobante::where('punto_venta_id', Auth::user()->id)->where('tipo_comprobante_id', 1)->first();

                        $nombres_apellidos = $cabecera['apn'];
                        $indice = strrpos($nombres_apellidos, ',');
                        $existe_dni = Particular::where('dni', $cabecera['ndoc'])->exists();
                        if (!$existe_dni) {
                            $particular = new Particular();
                            $particular->dni = $cabecera['ndoc'];
                            $particular->nombres = substr($nombres_apellidos, $indice + 1, strlen($nombres_apellidos));
                            $particular->apellidos = str_replace("/", " ", substr($nombres_apellidos, 1, $indice - 1));
                            $particular->save();
                        }

                        $regi_inscripcion = RegiInscripcion::where('insc_codi_web', $cabecera['cod_bancario'])->where('insc_ano_proc', '2021')->first();
                        $insc_reci = InscReci::where('insc_id', $regi_inscripcion->insc_id)->first();

                        $comprobante->tipo_usuario = 'particular';
                        $comprobante->codi_usuario = $cabecera['ndoc'];
                        if ($regi_inscripcion) {
                            $comprobante->nues_espe = $regi_inscripcion->nues;
                        }
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
                        $comprobante->created_at = $cabecera['frecepcion'];
                        $comprobante->save();

                        // Preguntando si es colegio nacional
                        if ($insc_reci->inre_tipo_cole == '1') {

                            /** Sacando los conceptos del proceso de inscripcion admision
                             * que son el derecho de admision en te esta colegio nacional
                             * y el examen de suficiencia dependiendo de la escuela que postulan.
                             */
                            $proceso_inscripcion_admision = Admision::with('detalles')->where('proceso_id', 2)->where('tipo_colegio', 'nacional')->first();

                            if ($regi_inscripcion->nues == '405' || $regi_inscripcion->nues == '406' || $regi_inscripcion->nues == '471' || $regi_inscripcion->nues == '431') {
                                foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                                    $concepto = Concepto::where('id', $detalle['concepto_id'])->first();

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
                                    $detalle_comprobante->clasificador_id =  $concepto->clasificador_id;
                                    $detalle_comprobante->save();
                                }
                            } else {
                                foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                                    $concepto = Concepto::where('id', $detalle['concepto_id'])->first();
                                    if ($concepto->codigo == '141') {
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
                                        $detalle_comprobante->clasificador_id =  $concepto->clasificador_id;
                                        $detalle_comprobante->save();
                                    }
                                }
                            }
                            // Preguntando si es colegio parroquial
                        } elseif ($insc_reci->inre_tipo_cole == '2') {
                            /** Sacando los conceptos del proceso de inscripcion admision
                             * que son el derecho de admision en te esta colegio parroquial
                             * y el examen de suficiencia dependiendo de la escuela que postulan.
                             */
                            $proceso_inscripcion_admision = Admision::with('detalles')->where('proceso_id', 2)->where('tipo_colegio', 'parroquial')->first();
                            if ($regi_inscripcion->nues == '405' || $regi_inscripcion->nues == '406' || $regi_inscripcion->nues == '471' || $regi_inscripcion->nues == '431') {
                                foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                                    $concepto = Concepto::where('id', $detalle['concepto_id'])->first();

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
                                    $detalle_comprobante->clasificador_id =  $concepto->clasificador_id;
                                    $detalle_comprobante->save();
                                }
                            } else {
                                foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                                    $concepto = Concepto::where('id', $detalle['concepto_id'])->first();
                                    if ($concepto->codigo == '142') {
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
                                        $detalle_comprobante->clasificador_id =  $concepto->clasificador_id;
                                        $detalle_comprobante->save();
                                    }
                                }
                            }
                            // Preguntando si es colegio particular
                        } elseif ($insc_reci->inre_tipo_cole == '3') {
                            /** Sacando los conceptos del proceso de inscripcion admision
                             * que son el derecho de admision en te esta colegio particular
                             * y el examen de suficiencia dependiendo de la escuela que postulan.
                             */
                            $proceso_inscripcion_admision = Admision::with('detalles')->where('proceso_id', 2)->where('tipo_colegio', 'particular')->first();
                            if ($regi_inscripcion->nues == '405' || $regi_inscripcion->nues == '406' || $regi_inscripcion->nues == '471' || $regi_inscripcion->nues == '431') {
                                foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                                    $concepto = Concepto::where('id', $detalle['concepto_id'])->first();

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
                                    $detalle_comprobante->clasificador_id =  $concepto->clasificador_id;
                                    $detalle_comprobante->save();
                                }
                            } else {
                                foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                                    $concepto = Concepto::where('id', $detalle['concepto_id'])->first();
                                    if ($concepto->codigo == '143') {
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
                                        $detalle_comprobante->clasificador_id =  $concepto->clasificador_id;
                                        $detalle_comprobante->save();
                                    }
                                }
                            }
                        }


                        $numeroComp = NumeroComprobante::where('serie', $cajero->serie)->first();
                        $numeroComp->correlativo = str_pad($numeroComp->correlativo + 1, 8, "0", STR_PAD_LEFT);
                        $numeroComp->update();
                    }
                } else if ($request->subproceso == "2") {
                    $pagos_inscripcion_admision = BancoBCP::whereDate('frecepcion', '>=', $request->fecha_inicio)
                        ->whereDate('frecepcion', '<=', $request->fecha_fin)
                        ->where('concepto', 'INSCRIPCION ADMISION')
                        ->where('flag', 0)
                        ->get();

                    foreach ($pagos_inscripcion_admision as $indice => $cabecera) {

                        $comprobante = new Comprobante();
                        $cajero = NumeroComprobante::where('punto_venta_id', Auth::user()->id)->where('tipo_comprobante_id', 1)->first();

                        $nombres_apellidos = $cabecera['apn'];
                        $indice = strrpos($nombres_apellidos, ',');
                        $existe_dni = Particular::where('dni', $cabecera['ndoc'])->exists();
                        if (!$existe_dni) {
                            $particular = new Particular();
                            $particular->dni = $cabecera['ndoc'];
                            $particular->nombres = substr($nombres_apellidos, $indice + 1, strlen($nombres_apellidos));
                            $particular->apellidos = str_replace("/", " ", substr($nombres_apellidos, 1, $indice - 1));
                            $particular->save();
                        }

                        $regi_inscripcion = RegiInscripcion::where('insc_codi_web', $cabecera['cod_bancario'])->where('insc_ano_proc', '2021')->first();
                        $insc_reci = InscReci::where('insc_id', $regi_inscripcion->insc_id)->first();

                        $comprobante->tipo_usuario = 'particular';
                        $comprobante->codi_usuario = $cabecera['ndoc'];
                        if ($regi_inscripcion) {
                            $comprobante->nues_espe = $regi_inscripcion->nues;
                        }
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
                        $comprobante->created_at = $cabecera['frecepcion'];
                        $comprobante->save();

                        // Preguntando si es colegio nacional
                        if ($insc_reci->inre_tipo_cole == '1') {

                            /** Sacando los conceptos del proceso de inscripcion admision
                             * que son el derecho de admision en te esta colegio nacional
                             * y el examen de suficiencia dependiendo de la escuela que postulan.
                             */
                            $proceso_inscripcion_admision = Admision::with('detalles')->where('proceso_id', 2)->where('tipo_colegio', 'nacional')->first();

                            if ($regi_inscripcion->nues == '405' || $regi_inscripcion->nues == '406' || $regi_inscripcion->nues == '471' || $regi_inscripcion->nues == '431') {
                                foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                                    $concepto = Concepto::where('id', $detalle['concepto_id'])->first();

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
                                    $detalle_comprobante->clasificador_id =  $concepto->clasificador_id;
                                    $detalle_comprobante->save();
                                }
                            } else {
                                foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                                    $concepto = Concepto::where('id', $detalle['concepto_id'])->first();
                                    if ($concepto->codigo == '141') {
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
                                        $detalle_comprobante->clasificador_id =  $concepto->clasificador_id;
                                        $detalle_comprobante->save();
                                    }
                                }
                            }
                            // Preguntando si es colegio parroquial
                        } elseif ($insc_reci->inre_tipo_cole == '2') {
                            /** Sacando los conceptos del proceso de inscripcion admision
                             * que son el derecho de admision en te esta colegio parroquial
                             * y el examen de suficiencia dependiendo de la escuela que postulan.
                             */
                            $proceso_inscripcion_admision = Admision::with('detalles')->where('proceso_id', 2)->where('tipo_colegio', 'parroquial')->first();
                            if ($regi_inscripcion->nues == '405' || $regi_inscripcion->nues == '406' || $regi_inscripcion->nues == '471' || $regi_inscripcion->nues == '431') {
                                foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                                    $concepto = Concepto::where('id', $detalle['concepto_id'])->first();

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
                                    $detalle_comprobante->clasificador_id =  $concepto->clasificador_id;
                                    $detalle_comprobante->save();
                                }
                            } else {
                                foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                                    $concepto = Concepto::where('id', $detalle['concepto_id'])->first();
                                    if ($concepto->codigo == '142') {
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
                                        $detalle_comprobante->clasificador_id =  $concepto->clasificador_id;
                                        $detalle_comprobante->save();
                                    }
                                }
                            }
                            // Preguntando si es colegio particular
                        } elseif ($insc_reci->inre_tipo_cole == '3') {
                            /** Sacando los conceptos del proceso de inscripcion admision
                             * que son el derecho de admision en te esta colegio particular
                             * y el examen de suficiencia dependiendo de la escuela que postulan.
                             */
                            $proceso_inscripcion_admision = Admision::with('detalles')->where('proceso_id', 2)->where('tipo_colegio', 'particular')->first();
                            if ($regi_inscripcion->nues == '405' || $regi_inscripcion->nues == '406' || $regi_inscripcion->nues == '471' || $regi_inscripcion->nues == '431') {
                                foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                                    $concepto = Concepto::where('id', $detalle['concepto_id'])->first();

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
                                    $detalle_comprobante->clasificador_id =  $concepto->clasificador_id;
                                    $detalle_comprobante->save();
                                }
                            } else {
                                foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                                    $concepto = Concepto::where('id', $detalle['concepto_id'])->first();
                                    if ($concepto->codigo == '143') {
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
                                        $detalle_comprobante->clasificador_id =  $concepto->clasificador_id;
                                        $detalle_comprobante->save();
                                    }
                                }
                            }
                        }


                        $numeroComp = NumeroComprobante::where('serie', $cajero->serie)->first();
                        $numeroComp->correlativo = str_pad($numeroComp->correlativo + 1, 8, "0", STR_PAD_LEFT);
                        $numeroComp->update();
                    }
                } else if ($request->subproceso == "3") {
                    $pagos_pension_cpu = BancoBCP::whereDate('frecepcion', '>=', $request->fecha_inicio)
                        ->whereDate('frecepcion', '<=', $request->fecha_fin)
                        ->where('concepto', 'PENSION CPU')
                        ->get();

                    foreach ($pagos_pension_cpu as $indice => $cabecera) {

                        $comprobante = new Comprobante();
                        $cajero = NumeroComprobante::where('punto_venta_id', Auth::user()->id)->where('tipo_comprobante_id', 1)->first();

                        $nombres_apellidos = $cabecera['apn'];
                        $indice = strrpos($nombres_apellidos, ',');
                        $existe_dni = Particular::where('dni', $cabecera['ndoc'])->exists();
                        if (!$existe_dni) {
                            $particular = new Particular();
                            $particular->dni = $cabecera['ndoc'];
                            $particular->nombres = substr($nombres_apellidos, $indice + 1, strlen($nombres_apellidos));
                            $particular->apellidos = str_replace("/", " ", substr($nombres_apellidos, 1, $indice - 1));
                            $particular->save();
                        }

                        $regi_inscripcion = RegiInscripcion::where('insc_codi_web', $cabecera['cod_bancario'])->where('insc_ano_proc', '2021')->first();
                        $insc_reci = InscReci::where('insc_id', $regi_inscripcion->insc_id)->first();

                        $comprobante->tipo_usuario = 'particular';
                        $comprobante->codi_usuario = $cabecera['ndoc'];
                        if ($regi_inscripcion) {
                            $comprobante->nues_espe = $regi_inscripcion->nues;
                        }
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
                        $comprobante->created_at = $cabecera['frecepcion'];
                        $comprobante->save();

                        // Preguntando si es colegio nacional
                        if ($insc_reci->inre_tipo_cole == '1') {

                            /** Sacando los conceptos del proceso de pension cepreunsa
                             * que son el derecho de admision , el material y las pensiones,
                             * en el caso de la primera pension se divide en inscripcion y
                             * pension, luego las pensiones siguientes son solo de concepto
                             * pension.
                             */
                            $proceso_inscripcion_admision = Admision::with('detalles')->where('proceso_id', 3)->where('tipo_colegio', 'nacional')->first();

                            //if ($regi_inscripcion->nues == '405' || $regi_inscripcion->nues == '406' || $regi_inscripcion->nues == '471' || $regi_inscripcion->nues == '431') {
                            if ($cabecera['pens_nro'] == 'P1') {
                                foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                                    $concepto = Concepto::where('id', $detalle['concepto_id'])->first();

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
                                    $detalle_comprobante->clasificador_id =  $concepto->clasificador_id;
                                    $detalle_comprobante->save();
                                }
                            } else {
                                foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                                    $concepto = Concepto::where('id', $detalle['concepto_id'])->first();
                                    if ($concepto->codigo == '65') {
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
                                        $detalle_comprobante->clasificador_id =  $concepto->clasificador_id;
                                        $detalle_comprobante->save();
                                    }
                                }
                            }
                            /*} else {
                                foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                                    $concepto = Concepto::where('id', $detalle['concepto_id'])->first();
                                    if ($concepto->codigo == '141') {
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
                                        $detalle_comprobante->clasificador_id =  $concepto->clasificador_id;
                                        $detalle_comprobante->save();
                                    }
                                }
                            }*/
                            // Preguntando si es colegio parroquial
                        } elseif ($insc_reci->inre_tipo_cole == '2') {
                            /** Sacando los conceptos del proceso de inscripcion admision
                             * que son el derecho de admision en te esta colegio parroquial
                             * y el examen de suficiencia dependiendo de la escuela que postulan.
                             */
                            $proceso_inscripcion_admision = Admision::with('detalles')->where('proceso_id', 3)->where('tipo_colegio', 'parroquial')->first();
                            if ($cabecera['pens_nro'] == 'P1') {
                                foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                                    $concepto = Concepto::where('id', $detalle['concepto_id'])->first();

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
                                    $detalle_comprobante->clasificador_id =  $concepto->clasificador_id;
                                    $detalle_comprobante->save();
                                }
                            } else {
                                foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                                    $concepto = Concepto::where('id', $detalle['concepto_id'])->first();
                                    if ($concepto->codigo == '65') {
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
                                        $detalle_comprobante->clasificador_id =  $concepto->clasificador_id;
                                        $detalle_comprobante->save();
                                    }
                                }
                            }
                            // Preguntando si es colegio particular
                        } elseif ($insc_reci->inre_tipo_cole == '3') {
                            /** Sacando los conceptos del proceso de inscripcion admision
                             * que son el derecho de admision en te esta colegio particular
                             * y el examen de suficiencia dependiendo de la escuela que postulan.
                             */
                            $proceso_inscripcion_admision = Admision::with('detalles')->where('proceso_id', 3)->where('tipo_colegio', 'particular')->first();
                            if ($cabecera['pens_nro'] == 'P1') {
                                foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                                    $concepto = Concepto::where('id', $detalle['concepto_id'])->first();

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
                                    $detalle_comprobante->clasificador_id =  $concepto->clasificador_id;
                                    $detalle_comprobante->save();
                                }
                            } else {
                                foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                                    $concepto = Concepto::where('id', $detalle['concepto_id'])->first();
                                    if ($concepto->codigo == '65') {
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
                                        $detalle_comprobante->clasificador_id =  $concepto->clasificador_id;
                                        $detalle_comprobante->save();
                                    }
                                }
                            }
                        }


                        $numeroComp = NumeroComprobante::where('serie', $cajero->serie)->first();
                        $numeroComp->correlativo = str_pad($numeroComp->correlativo + 1, 8, "0", STR_PAD_LEFT);
                        $numeroComp->update();
                    }
                } else if ($request->subproceso == "4") {
                    $pagos_cambio_carrera = BancoBCP::whereDate('frecepcion', '>=', $request->fecha_inicio)
                        ->whereDate('frecepcion', '<=', $request->fecha_fin)
                        ->where('concepto', 'CAMBIO CARRERA')
                        ->where('flag', 0)
                        ->get();

                    foreach ($pagos_cambio_carrera as $indice => $cabecera) {

                        $comprobante = new Comprobante();
                        $cajero = NumeroComprobante::where('punto_venta_id', Auth::user()->id)->where('tipo_comprobante_id', 1)->first();

                        $nombres_apellidos = $cabecera['apn'];
                        $indice = strrpos($nombres_apellidos, ',');
                        $existe_dni = Particular::where('dni', $cabecera['ndoc'])->exists();
                        if (!$existe_dni) {
                            $particular = new Particular();
                            $particular->dni = $cabecera['ndoc'];
                            $particular->nombres = substr($nombres_apellidos, $indice + 1, strlen($nombres_apellidos));
                            $particular->apellidos = str_replace("/", " ", substr($nombres_apellidos, 1, $indice - 1));
                            $particular->save();
                        }

                        $regi_inscripcion = RegiInscripcion::where('insc_codi_web', $cabecera['cod_bancario'])->where('insc_ano_proc', '2021')->first();
                        $insc_reci = InscReci::where('insc_id', $regi_inscripcion->insc_id)->first();

                        $comprobante->tipo_usuario = 'particular';
                        $comprobante->codi_usuario = $cabecera['ndoc'];
                        if ($regi_inscripcion) {
                            $comprobante->nues_espe = $regi_inscripcion->nues;
                        }
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
                        $comprobante->created_at = $cabecera['frecepcion'];
                        $comprobante->save();

                        // Preguntando si es colegio nacional
                        if ($insc_reci->inre_tipo_cole == '1') {

                            /** Sacando los conceptos del proceso de inscripcion admision
                             * que son el derecho de admision en te esta colegio nacional
                             * y el examen de suficiencia dependiendo de la escuela que postulan.
                             */
                            $proceso_inscripcion_admision = Admision::with('detalles')->where('proceso_id', 2)->where('tipo_colegio', 'nacional')->first();

                            if ($regi_inscripcion->nues == '405' || $regi_inscripcion->nues == '406' || $regi_inscripcion->nues == '471' || $regi_inscripcion->nues == '431') {
                                foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                                    $concepto = Concepto::where('id', $detalle['concepto_id'])->first();

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
                                    $detalle_comprobante->clasificador_id =  $concepto->clasificador_id;
                                    $detalle_comprobante->save();
                                }
                            } else {
                                foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                                    $concepto = Concepto::where('id', $detalle['concepto_id'])->first();
                                    if ($concepto->codigo == '141') {
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
                                        $detalle_comprobante->clasificador_id =  $concepto->clasificador_id;
                                        $detalle_comprobante->save();
                                    }
                                }
                            }
                            // Preguntando si es colegio parroquial
                        } elseif ($insc_reci->inre_tipo_cole == '2') {
                            /** Sacando los conceptos del proceso de inscripcion admision
                             * que son el derecho de admision en te esta colegio parroquial
                             * y el examen de suficiencia dependiendo de la escuela que postulan.
                             */
                            $proceso_inscripcion_admision = Admision::with('detalles')->where('proceso_id', 2)->where('tipo_colegio', 'parroquial')->first();
                            if ($regi_inscripcion->nues == '405' || $regi_inscripcion->nues == '406' || $regi_inscripcion->nues == '471' || $regi_inscripcion->nues == '431') {
                                foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                                    $concepto = Concepto::where('id', $detalle['concepto_id'])->first();

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
                                    $detalle_comprobante->clasificador_id =  $concepto->clasificador_id;
                                    $detalle_comprobante->save();
                                }
                            } else {
                                foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                                    $concepto = Concepto::where('id', $detalle['concepto_id'])->first();
                                    if ($concepto->codigo == '142') {
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
                                        $detalle_comprobante->clasificador_id =  $concepto->clasificador_id;
                                        $detalle_comprobante->save();
                                    }
                                }
                            }
                            // Preguntando si es colegio particular
                        } elseif ($insc_reci->inre_tipo_cole == '3') {
                            /** Sacando los conceptos del proceso de inscripcion admision
                             * que son el derecho de admision en te esta colegio particular
                             * y el examen de suficiencia dependiendo de la escuela que postulan.
                             */
                            $proceso_inscripcion_admision = Admision::with('detalles')->where('proceso_id', 2)->where('tipo_colegio', 'particular')->first();
                            if ($regi_inscripcion->nues == '405' || $regi_inscripcion->nues == '406' || $regi_inscripcion->nues == '471' || $regi_inscripcion->nues == '431') {
                                foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                                    $concepto = Concepto::where('id', $detalle['concepto_id'])->first();

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
                                    $detalle_comprobante->clasificador_id =  $concepto->clasificador_id;
                                    $detalle_comprobante->save();
                                }
                            } else {
                                foreach ($proceso_inscripcion_admision->detalles as $index => $detalle) {
                                    $concepto = Concepto::where('id', $detalle['concepto_id'])->first();
                                    if ($concepto->codigo == '143') {
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
                                        $detalle_comprobante->clasificador_id =  $concepto->clasificador_id;
                                        $detalle_comprobante->save();
                                    }
                                }
                            }
                        }


                        $numeroComp = NumeroComprobante::where('serie', $cajero->serie)->first();
                        $numeroComp->correlativo = str_pad($numeroComp->correlativo + 1, 8, "0", STR_PAD_LEFT);
                        $numeroComp->update();
                    }
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
            } elseif ($request->proceso == 7) {
                return 'Es Ceprunsa';
            } {
            }
            DB::commit();
            $result = [
                'successMessage' => 'Pagos procesados con exito',
                'error' => false
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = ['errorMessage' => 'Error al procesar los pagos intente nuevamente', 'error' => true];
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
