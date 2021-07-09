<?php

namespace App\Http\Controllers;

use App\Models\Clasificador;
use App\Models\Concepto;
use App\Models\CuentaCorriente;
use App\Models\DetallesComprobante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReciboIngresoController extends Controller
{
    public function porCajero()
    {
        $cuentas = CuentaCorriente::select('id as value', DB::raw("(CONCAT(banco, ' ', numero_cuenta, ' ', moneda)) AS text"))
                ->orderBy('text', 'asc')->get();

        return Inertia::render('Reportes/Ingresos/RegistroRecibo', compact('cuentas'));
    }

    public function filtrarCajero(Request $request)
    {
        $clasificadores = DB::table('vista_recibo_ingreso')->select(DB::raw('MIN(clasificador_id) as clasificador_id'), 'descripcion',
                                DB::raw('sum(totalcobros) as cantidad'), DB::raw('sum(subtotal) as subtotal'))
                                ->whereDate('created_at','>=',$request->fechaInicio)
                                ->whereDate('created_at','<=',$request->fechaFin)
                                ->where('cajero_id', $request->cajeroId)
                                ->where('cuenta_corriente_id', $request->cuenta_corriente_id)
                                ->groupBy('descripcion')->orderBy('clasificador_id')->get();
        
        $totalRegistros = $clasificadores->count();
                                
        return ['clasificadores' => $clasificadores,
                'totalRegistros' => $totalRegistros,];
    }
}
