<?php

namespace App\Http\Controllers;

use App\Models\Comprobante;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade as PDF;

class ReportesController extends Controller
{
    public function porCajero()
    {
        $comprobantes = Comprobante::all();
        return Inertia::render('Reportes/PorPeriodo/Cajero', compact('comprobantes'));
    }

    public function porCajeroPDF(Request $request)
    {
        $comprobantes = Comprobante::all()->take(25);
        //$comprobantes = Comprobante::;
        $comprobantes = (array)json_decode($request->getContent());
        
        //return $comprobantes;
        //return view('reportes.ventas', compact('comprobantes'));
        
        //return $comprobantes;        
        $pdf = PDF::loadView('reportes.cajero', compact('comprobantes'));
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'portrait');
        $pdf->save(storage_path().'fdjfdh.pdf');
        
    // Finally, you can download the file using download function
        //return response()->file(storage_path().'fdjfdh.pdf');
        $pdf->stream('customers.pdf');
        return "done";
        //return $pdf->download('file.pdf');
        //return $pdf;
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
