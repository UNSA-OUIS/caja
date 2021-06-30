<?php

namespace App\Http\Controllers;

use App\Models\Clasificador;
use App\Models\Comprobante;
use App\Models\Concepto;
use App\Models\Dependencia;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{
    public function porCajero()
    {
        //$comprobantes = Comprobante::all();
        $this->authorize('cajero');
        //return Inertia::render('Reportes/PorPeriodo/Cajero', compact('comprobantes'));
        return Inertia::render('Reportes/PorPeriodo/Cajero');
    }

    public function filtrarCajero(Request $request)
    {
        $this->authorize('cajero');
        /*$user = User::where('email', 'like', '%' . $request->dniCliente . '%')->first();
        $comprobantes = Comprobante::where('usuario_id', $user->id)
                                    ->whereDate('created_at','>=',$request->fechaInicio)
                                    ->whereDate('created_at','<=',$request->fechaFin)->get();
        return ['comprobantes' => $comprobantes];*/

        $comprobantes = User::select(DB::raw('DATE(comprobantes.created_at) as date'), 'users.id as codigo', 'users.name as nombre', 
                                    DB::raw('count(case when comprobantes.estado != \'anulado\' then 1 else null end) as cobros'), 
                                    DB::raw('count(case when comprobantes.estado = \'anulado\' then 1 else null end) as anulados'), 
                                    DB::raw('SUM(comprobantes.total) As monto'), DB::raw('SUM(comprobantes.total_descuento) As descuento'), 
                                    DB::raw('SUM(comprobantes.total_impuesto) As impuesto'))
                                    ->leftJoin('comprobantes', 'comprobantes.cajero_id', '=', 'users.id')
                                    ->whereDate('comprobantes.created_at','>=',$request->fechaInicio)
                                    ->whereDate('comprobantes.created_at','<=',$request->fechaFin)
                                    ->when($request->cajeroId != "",function ($q) {
                                        return $q->where('comprobantes.cajero_id', request('cajeroId', 0));
                                    })
                                    ->groupBy('date', 'codigo','nombre')
                                    ->get();
        //dd($comprobantes);
        $totalRegistros = $comprobantes->count();
        $totalCobros = $comprobantes->sum('cobros');
        $totalDescuentos = $comprobantes->sum('descuento');
        $totalIGV = $comprobantes->sum('impuesto');
        $totalMontos = $comprobantes->sum('monto');
        $totalAnulados = $comprobantes->sum('anulados');

        return ['comprobantes' => $comprobantes,
                'totalRegistros' => $totalRegistros,
                'totalCobros' => $totalCobros,
                'totalDescuentos' => $totalDescuentos,
                'totalIGV' => $totalIGV,
                'totalMontos' => $totalMontos,
                'totalAnulados' => $totalAnulados,];
    }

    public function porCajeroPDF(Request $request)
    {
        //$comprobantes = (array)json_decode($request->getContent());
           
        $user = User::where('email', 'like', '%' . $request->dniCliente . '%')->first();
        $comprobantes = Comprobante::where('usuario_id', $user->id)
                                    ->whereDate('created_at','>=',$request->fechaInicio)
                                    ->whereDate('created_at','<=',$request->fechaFin)->get();     
        $pdf = PDF::loadView('reportes.cajero', compact('comprobantes'));
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'portrait');
        //$pdf->save(storage_path().'fdjfdh.pdf');
        
    // Finally, you can download the file using download function
        //return file(storage_path().'fdjfdh.pdf');
        return $pdf->stream('customers.pdf');
        //return $pdf->download('file.pdf');
    }

    public function buscarCajero(Request $request)
    {
        $filtro = $request->filtro;

        $cajeros = User::where(DB::raw("(CONCAT(id, ' - ', name))"), 'ilike', '%' . $filtro . '%')
            ->whereHas('roles', function ($q) {
                $q->where('roles.name', '=', 'Cajero');
            })
            ->select('id as cajero_id', 'name',
            DB::raw("(CONCAT(id, ' - ', name)) AS vista_cajero"))
            ->orderBy('id', 'asc')
            ->get();

        return $cajeros;
    }

    public function descuentos()
    {
        $this->authorize('descuento');
        $comprobantes = Comprobante::all();
        return Inertia::render('Reportes/PorPeriodo/Descuentos', compact('comprobantes'));
    }

    public function centroDeCosto()
    {
        $this->authorize('centroCosto');
        //$comprobantes = Comprobante::all();
        return Inertia::render('Reportes/PorPeriodo/CentroDeCosto');
    }

    public function filtrarCentroDeCosto(Request $request)
    {
        $this->authorize('centroCosto');
        $centros = Concepto::with('dependencia:codi_depe,nomb_depe')->select('conceptos.codi_depe', DB::raw('SUM(detalles_comprobante.valor_unitario) as monto'))
                    ->leftJoin('detalles_comprobante', 'detalles_comprobante.concepto_id', '=', 'conceptos.id')
                    ->leftJoin('comprobantes', 'comprobantes.id', 'detalles_comprobante.comprobante_id')
                    ->whereDate('detalles_comprobante.created_at','>=',request('fechaInicio'))
                    ->whereDate('detalles_comprobante.created_at','<=',request('fechaFin'))
                    ->when($request->cajeroId != "",function ($q) {
                        return $q->where('comprobantes.cajero_id', request('cajeroId', 0));
                    })
                    ->when($request->cenCosCod != "",function ($q) {
                        return $q->where('conceptos.codi_depe', request('cenCosCod', 0));
                    })
                    ->groupBy('conceptos.codi_depe')->get();
        $totalMontos = $centros->sum('monto');
        $totalRegistros = $centros->count();
        return [
            'centros' => $centros,
            'totalMontos' => $totalMontos,
            'totalRegistros' => $totalRegistros,
        ];
    }

    public function reciboIngreso()
    {
        $this->authorize('reciboIngreso');
        $comprobantes = Comprobante::all();
        return Inertia::render('Reportes/PorPeriodo/ReciboIngreso', compact('comprobantes'));
    }

    public function facturas()
    {
        $this->authorize('facturas');
        $comprobantes = Comprobante::all();
        return Inertia::render('Reportes/PorPeriodo/Facturas', compact('comprobantes'));
    }

    public function filtrarFactura(Request $request)
    {
        $this->authorize('cajero');

        $comprobantes = Comprobante::with('comprobanteable')->with(['cajero' => function($query) {
                                        $query->select('id', 'name');
                                    }])->when($request->cajeroId != "",function ($q) {
                                        return $q->where('cajero_id', request('cajeroId', 0));
                                    })
                                    ->where('tipo_comprobante_id', config('caja.tipo_comprobante.FACTURA'))
                                    ->when($request->tipo_fecha == 0,function ($q) use ($request) {
                                        return $q->whereDate('comprobantes.created_at','>=',$request->fechaInicio)
                                        ->whereDate('comprobantes.created_at','<=',$request->fechaFin);
                                    })
                                    ->when($request->tipo_fecha == 1,function ($q) use ($request) {
                                        return $q->whereDate('comprobantes.fecha_cancelacion','>=',$request->fechaInicio)
                                        ->whereDate('comprobantes.fecha_cancelacion','<=',$request->fechaFin);
                                    })
                                    ->when($request->tipo_factura == 1,function ($q) {
                                        return $q->where('comprobantes.cancelado', true);
                                    })
                                    ->when($request->tipo_factura == 2,function ($q) {
                                        return $q->where('comprobantes.cancelado', false);
                                    })
                                    ->get();
        //dd($comprobantes);
        $totalRegistros = $comprobantes->count();
        $totalCobros = $comprobantes->count();
        $totalDescuentos = $comprobantes->sum('total_descuento');
        $totalIGV = $comprobantes->sum('total_impuesto');
        $totalMontos = $comprobantes->sum('total');

        return ['comprobantes' => $comprobantes,
                'totalRegistros' => $totalRegistros,
                'totalCobros' => $totalCobros,
                'totalDescuentos' => $totalDescuentos,
                'totalIGV' => $totalIGV,
                'totalMontos' => $totalMontos,];
    }

    public function notas()
    {
        $this->authorize('notas');
        $comprobantes = Comprobante::all();
        return Inertia::render('Reportes/PorPeriodo/Notas', compact('comprobantes'));
    }

    public function filtrarNota(Request $request)
    {
        $this->authorize('cajero');

        $comprobantes = Comprobante::with('comprobanteable', 'comprobante_afectado')->with(['cajero' => function($query) {
                                        $query->select('id', 'name');
                                    }])->when($request->cajeroId != "",function ($q) {
                                        return $q->where('cajero_id', request('cajeroId', 0));
                                    })->whereBetween('tipo_comprobante_id', [config('caja.tipo_comprobante.NOTA DE DÉBITO'), config('caja.tipo_comprobante.NOTA DE CRÉDITO')])
                                    ->when($request->tipoNota == 3,function ($q) {
                                        return $q->where('tipo_comprobante_id', config('caja.tipo_comprobante.NOTA DE DÉBITO'));
                                    })->when($request->tipoNota == 4,function ($q) {
                                        return $q->where('tipo_comprobante_id', config('caja.tipo_comprobante.NOTA DE CRÉDITO'));
                                    })
                                    ->whereDate('comprobantes.created_at','>=',$request->fechaInicio)
                                    ->whereDate('comprobantes.created_at','<=',$request->fechaFin)->get();
        //dd($comprobantes);
        $totalRegistros = $comprobantes->count();
        $totalCobros = $comprobantes->count();
        $totalDescuentos = $comprobantes->sum('total_descuento');
        $totalIGV = $comprobantes->sum('total_impuesto');
        $totalMontos = $comprobantes->sum('total');

        return ['comprobantes' => $comprobantes,
                'totalRegistros' => $totalRegistros,
                'totalCobros' => $totalCobros,
                'totalDescuentos' => $totalDescuentos,
                'totalIGV' => $totalIGV,
                'totalMontos' => $totalMontos,];
    }

    public function consolidado()
    {
        $this->authorize('consolidado');
        $comprobantes = Comprobante::all();
        return Inertia::render('Reportes/PorPeriodo/Consolidado', compact('comprobantes'));
    }

    public function filtrarConsolidado(Request $request)
    {
        $this->authorize('consolidado');
        $clasificadores = Clasificador::select('clasificadores.id as codigo', 'clasificadores.nombre as nombre', DB::raw('SUM(detalles_comprobante.valor_unitario) as monto'))
                                ->leftJoin('conceptos', 'conceptos.clasificador_id', '=', 'clasificadores.id')
                                ->leftJoin('detalles_comprobante', 'detalles_comprobante.concepto_id', '=', 'conceptos.id')
                                ->leftJoin('comprobantes', 'comprobantes.id', 'detalles_comprobante.comprobante_id')
                                ->whereDate('detalles_comprobante.created_at','>=',$request->fechaInicio)
                                ->whereDate('detalles_comprobante.created_at','<=',$request->fechaFin)
                                ->when($request->cajeroId != "",function ($q) {
                                    return $q->where('comprobantes.cajero_id', request('cajeroId', 0));
                                })
                                ->groupBy('clasificadores.id')
                                ->get();
        $totalMontos = $clasificadores->sum('monto');
        $totalRegistros = $clasificadores->count();


                                
        return ['clasificadores' => $clasificadores,
                'totalRegistros' => $totalRegistros,
                'totalMontos' => $totalMontos,];
    }
}
