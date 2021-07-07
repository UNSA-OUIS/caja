<?php

namespace App\Http\Controllers;

use App\Models\Clasificador;
use App\Models\Concepto;
use App\Models\DetallesComprobante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReciboIngresoController extends Controller
{
    public function porCajero()
    {
        return Inertia::render('Reportes/Ingresos/RegistroRecibo');
    }

    public function filtrarCajero(Request $request)
    {

        $conceptos = Concepto::whereHas('detalles', function ($query) use ($request) {
            $query->whereDate('created_at','>=',$request->fechaInicio)
            ->whereDate('created_at','<=',$request->fechaFin)
            ->when($request->cajeroId != "",function ($q) {
                return $q->whereHas('comprobante', function ($query) {
                    $query->where('cajero_id', request('cajeroId', 0));
                });
            });
        })->get()->load('detalles')->sortBy(['clasificador_id']);


        $clasificadores = Clasificador::whereHas('conceptos', function ($query) use ($request) {
            $query->whereHas('detalles', function ($query) use ($request) {
                $query->whereDate('created_at','>=',$request->fechaInicio)
                ->whereDate('created_at','<=',$request->fechaFin)
                ->when($request->cajeroId != "",function ($q) {
                    return $q->whereHas('comprobante', function ($query) {
                        $query->where('cajero_id', request('cajeroId', 0));
                    });
                });
            });
        })->with(['conceptos' => function($query) use ($request) {
            $query->with(['detalles' => function($query) {
                    $query->select('concepto_id', DB::raw('SUM(subtotal) as parcial'), DB::raw('COUNT(*) as cobros'))->groupBy('concepto_id');
                }])->whereHas('detalles', function ($query) use ($request) {
                    $query->whereDate('created_at','>=',$request->fechaInicio)
                    ->whereDate('created_at','<=',$request->fechaFin)
                    ->when($request->cajeroId != "",function ($q) {
                        return $q->whereHas('comprobante', function ($query) {
                            $query->where('cajero_id', request('cajeroId', 0));
                        });
                    });
                });
                /*$query->select('conceptos.id', 'conceptos.descripcion', DB::raw('SUM(detalles_comprobante.subtotal) as parcial'), DB::raw('COUNT(detalles_comprobante.*) as cobros'))
                ->whereDate('detalles_comprobante.created_at','>=',$request->fechaInicio)
                    ->whereDate('detalles_comprobante.created_at','<=',$request->fechaFin)
                    ->when($request->cajeroId != "",function ($q) {
                        return $q->whereHas('comprobante', function ($query) {
                            $query->where('cajero_id', request('cajeroId', 0));
                        });
                    })
                ->join('detalles_comprobante', 'detalles_comprobante.concepto_id', '=', 'conceptos.id')->groupBy('conceptos.id');*/

            }])->get();

        $totalRegistros = $clasificadores->count();


                                
        return ['clasificadores' => $clasificadores,
                'totalRegistros' => $totalRegistros,];
    }
}
