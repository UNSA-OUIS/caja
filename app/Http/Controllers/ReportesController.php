<?php

namespace App\Http\Controllers;

use App\Models\Comprobante;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{
    public function porCajero()
    {
        //$comprobantes = Comprobante::all();

        //return Inertia::render('Reportes/PorPeriodo/Cajero', compact('comprobantes'));
        return Inertia::render('Reportes/PorPeriodo/Cajero');
    }

    public function filtrarCajero(Request $request)
    {

        /*$user = User::where('email', 'like', '%' . $request->dniCliente . '%')->first();
        $comprobantes = Comprobante::where('usuario_id', $user->id)
                                    ->whereDate('created_at','>=',$request->fechaInicio)
                                    ->whereDate('created_at','<=',$request->fechaFin)->get();
        return ['comprobantes' => $comprobantes];*/

        $comprobantes = User::select('comprobantes.created_at', 'users.email', 'users.name', 'users.email', DB::raw('count(comprobantes.id) as cobros'), DB::raw('count(case when comprobantes.estado = \'anulado\' then 1 else null end) as anulados'), DB::raw('SUM(comprobantes.total) As monto'), DB::raw('SUM(comprobantes.total_descuento) As descuento'), DB::raw('SUM(comprobantes.total_impuesto) As impuesto'))
                                    ->leftJoin('comprobantes', 'comprobantes.cajero_id', '=', 'users.id')
                                    ->whereDate('comprobantes.created_at','>=',$request->fechaInicio)
                                    ->whereDate('comprobantes.created_at','<=',$request->fechaFin)
                                    ->groupBy('comprobantes.created_at', 'users.id')
                                    ->get();
        //dd($comprobantes);
        return ['comprobantes' => $comprobantes];;
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

    public function descuentos()
    {
        $comprobantes = Comprobante::all();
        return Inertia::render('Reportes/PorPeriodo/Descuentos', compact('comprobantes'));
    }

    public function centroDeCosto()
    {
        $comprobantes = Comprobante::all();
        return Inertia::render('Reportes/PorPeriodo/CentroDeCosto', compact('comprobantes'));
    }

    public function reciboIngreso()
    {
        $comprobantes = Comprobante::all();
        return Inertia::render('Reportes/PorPeriodo/ReciboIngreso', compact('comprobantes'));
    }

    public function facturas()
    {
        $comprobantes = Comprobante::all();
        return Inertia::render('Reportes/PorPeriodo/Facturas', compact('comprobantes'));
    }

    public function notas()
    {
        $comprobantes = Comprobante::all();
        return Inertia::render('Reportes/PorPeriodo/Notas', compact('comprobantes'));
    }

    public function consolidado()
    {
        $comprobantes = Comprobante::all();
        return Inertia::render('Reportes/PorPeriodo/Consolidado', compact('comprobantes'));
    }
}
