<?php

namespace App\Http\Controllers;

use App\Models\Comprobante;
use DateTime;
use Dotenv\Util\Str;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Summary\Summary;
use Greenter\Model\Summary\SummaryDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Greenter\Report\HtmlReport;
use Greenter\Report\PdfReport;
use Luecano\NumeroALetras\NumeroALetras;

class SunatController extends Controller
{


    public function __invoke()
    {
        $noEnviado  = count(DB::table('comprobantes')
            ->where('estado', 'like', 'noEnviado')
            ->get());
        $observado = count(DB::table('comprobantes')
            ->where('estado', 'like', 'observado')
            ->get());
        $rechazado = count(DB::table('comprobantes')
            ->where('estado', 'like', 'rechazado')
            ->get());
        $anulado = count(DB::table('comprobantes')
            ->where('estado', 'like', 'anulado')
            ->get());
        $aceptado = count(DB::table('comprobantes')
            ->where('estado', 'like', 'aceptado')
            ->get());
        return Inertia::render('Sunat/Tablero', compact('noEnviado', 'observado', 'rechazado', 'anulado', 'aceptado'));
    }
    public function getEstados()
    {
        $boletas = count(DB::table('comprobantes')
            ->where('tipo_comprobante_id', 1)
            ->get());

        $facturas = count(DB::table('comprobantes')
            ->where('tipo_comprobante_id', 2)
            ->get());

        $notas_debito = count(DB::table('comprobantes')
            ->where('tipo_comprobante_id', 3)
            ->get());

        $notas_credito = count(DB::table('comprobantes')
            ->where('tipo_comprobante_id', 4)
            ->get());

        $noEnviado  = count(DB::table('comprobantes')
            ->where('enviado', false)
            ->get());

        $observado = count(DB::table('comprobantes')
            ->where('estado', 'like', 'observado')
            ->get());

        $rechazado = count(DB::table('comprobantes')
            ->where('estado', 'like', 'rechazado')
            ->get());

        $anulado = count(DB::table('comprobantes')
            ->where('estado', 'like', 'anulado')
            ->get());

        $aceptado = count(DB::table('comprobantes')
            ->where('estado', 'like', 'aceptado')
            ->get());

        return [
            'noEnviado' => $noEnviado,
            //'observado' => $observado,
            'rechazado' => $rechazado,
            'anulado' => $anulado,
            'aceptado' => $aceptado,
            'boletas' => $boletas,
            'facturas' => $facturas,
            'notas_debito' => $notas_debito,
            'notas_credito' => $notas_credito
        ];
    }
}
