<?php

namespace App\Http\Controllers;

use App\Models\Clasificador;
use App\Models\Comprobante;
use App\Models\Concepto;
use App\Models\CuentaCorriente;
use App\Models\DetallesComprobante;
use App\Models\ReciboIngreso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ReciboIngresoController extends Controller
{
    public function porCajero()
    {
        $recibo = new ReciboIngreso();
        $recibo->correlativo = "";
        $recibo->fechaInicio = "";
        $recibo->fechaFin = "";
        $recibo->cajero_id = null;
        $recibo->cuenta_corriente_id = null;

        $cuentas = CuentaCorriente::select('id as value', DB::raw("(CONCAT(banco, ' ', numero_cuenta, ' ', moneda)) AS text"))
                ->orderBy('text', 'asc')->get();

        return Inertia::render('Reportes/Ingresos/RegistroRecibo', compact('recibo', 'cuentas'));
    }

    public function filtrarCajero(Request $request)
    {
        $clasificadores = DB::table('vista_recibo_ingreso')->select(DB::raw('MIN(clasificador_id) as clasificador_id'), 'descripcion',
                                DB::raw('sum(totalcobros) as cantidad'), DB::raw('sum(subtotal) as subtotal'))
                                ->whereDate('created_at','>=',$request->fechaInicio)
                                ->whereDate('created_at','<=',$request->fechaFin)
                                ->where('cajero_id', $request->cajeroId)
                                ->where('cuenta_corriente_id', $request->cuenta_corriente_id)
                                ->whereNull('recibo_ingreso_id')
                                ->groupBy('descripcion')->orderBy('clasificador_id')->get();
        
        $totalRegistros = $clasificadores->count();
                                
        return ['clasificadores' => $clasificadores,
                'totalRegistros' => $totalRegistros,];
    }
    public function store(Request $request){
        try {
            $recibo = new ReciboIngreso();
            $recibo->correlativo = $request->correlativo;
            $recibo->fecha_registro = $request->fechaFin;
            $recibo->cajero_id = $request->cajero_id;
            $recibo->cuenta_corriente_id = $request->cuenta_corriente_id;
            $recibo->save();

            $comprobante = Comprobante::whereDate('created_at','>=',$request->fechaInicio)
                    ->whereDate('created_at','<=',$request->fechaFin)
                    ->where('cajero_id', $request->cajero_id)
                    ->where('cuenta_corriente_id', $request->cuenta_corriente_id)
                    ->whereNull('recibo_ingreso_id')
                    ->update(['recibo_ingreso_id' => $recibo->id]);

        $result = ['successMessage' => 'Recibo de ingresos registrado con éxito.'. $comprobante];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo registrar el recibo de ingresos' . $e];
            Log::error('ReciboIngresoController@store, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->route('recibos.cajero')->with($result);
    }
}
