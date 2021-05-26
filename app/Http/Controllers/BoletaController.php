<?php

namespace App\Http\Controllers;

use App\Models\Comprobante;
use App\Models\ResumenDiario;
use DateTime;
use Exception;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\Document;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Summary\Summary;
use Greenter\Model\Summary\SummaryDetail;
use Greenter\Report\HtmlReport;
use Greenter\Report\PdfReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Luecano\NumeroALetras\NumeroALetras;

class BoletaController extends Controller
{
    private $empresa;

    function __construct()
    {
        $direccion_empresa = (new Address())
            ->setUbigueo(config('caja.direccion.ubigeo'))
            ->setDepartamento(config('caja.direccion.departamento'))
            ->setProvincia(config('caja.direccion.provincia'))
            ->setDistrito(config('caja.direccion.distrito'))
            ->setUrbanizacion(config('caja.direccion.urbanizacion'))
            ->setDireccion(config('caja.direccion.direccion'))
            ->setCodLocal(config('caja.direccion.codigo_local')); // Codigo de establecimiento asignado por SUNAT, 0000 por defecto.

        $this->empresa = (new Company())
            ->setRuc(config('caja.empresa.ruc'))
            ->setRazonSocial(config('caja.empresa.razon_social'))
            ->setNombreComercial(config('caja.empresa.nombre_comercial'))
            ->setAddress($direccion_empresa);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$this->authorize("viewAny", Comprobante::class);
        //$this->fechaInicio = $request->fechaInicio;

        $query = Comprobante::with('comprobanteable')->with('tipo_comprobante')->with('detalles.concepto')
            ->where('tipo_usuario', 'like', 'empresa')
            ->where('tipo_comprobante_id', 'like', 1)
            ->whereIn('estado', ['noEnviado', 'observado'])
            ->whereDate('created_at', '>=', $request->fecha_inicio)
            ->whereDate('created_at', '<=', $request->fecha_fin);

        $sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {
            $sortdirection = $request->sortdesc == "true" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
        }

        return $query->paginate($request->size);
    }


    public function resumenDiario(Request $request)
    {
        //return $request[0]['correlativo'];
        $see = require config_path('Sunat/config.php');
        $boletas = $request->all();
        $correlativo = '';

        try {

            $ultimo = ResumenDiario::latest('created_at')->first();
            if (!$ultimo) {
                $correlativo = '001';
            } else {
                $ultimo->correlativo += 1;
                $correlativo = str_pad($ultimo->correlativo, 3, "0", STR_PAD_LEFT);
            }

            foreach ($boletas as $index => $value) {
                $details[$index] = (new SummaryDetail())
                    ->setTipoDoc('03') // Boleta
                    ->setSerieNro('B00' . $index . '-' . $value['correlativo']);
                if ($value['estado'] == 'anulado') {
                    $details[$index]->setEstado('3'); // Anulación
                } else {
                    $details[$index]->setEstado('2');
                }
                if ($value['tipo_usuario'] == 'alumno') {
                    $details[$index]
                        ->setClienteTipo('1')
                        ->setClienteNro($value['codi_usuario']);
                }
                $details[$index]->setTotal($value['total'])
                    ->setMtoOperGravadas($value['total_impuesto'])
                    ->setMtoIGV(18.00);
            }

            $resumen = new Summary();
            $resumen->setFecGeneracion(new \DateTime(now())) // Fecha de emisión de las boletas.
                ->setFecResumen(new \DateTime(now())) // Fecha de envío del resumen diario.
                ->setCorrelativo($correlativo) // Correlativo, necesario para diferenciar de otros Resumen diario del mismo día.
                ->setCompany($this->empresa)
                ->setDetails($details);

            $resumen_diario = new ResumenDiario();
            $resumen_diario->fecha_envio = now();
            $resumen_diario->fecha_emision = now();
            $resumen_diario->correlativo = $correlativo;
            $resumen_diario->estado = 'noEnviado';
            $resumen_diario->save();

            $result = $see->send($resumen);
            // Guardar XML
            $xmlGuardado = file_put_contents(
                $resumen->getName() . '.xml',
                $see->getFactory()->getLastXml()
            );
            if ($xmlGuardado) {
                $resumen_diario->url_xml =  $resumen->getName() . '.xml';
                $resumen_diario->update();
            }

            if (!$result->isSuccess()) {
                // Si hubo error al conectarse al servicio de SUNAT.
                $resumen_diario->observaciones = var_dump($result->getError());
                $resumen_diario->update();
                return $resumen_diario;
                exit();
            }

            $ticket = $result->getTicket();
            $resumen_diario->ticket = $ticket . PHP_EOL;
            $resumen_diario->update();

            $statusResult = $see->getStatus($ticket);
            if (!$statusResult->isSuccess()) {
                // Si hubo error al conectarse al servicio de SUNAT.
                $resumen_diario->observaciones = var_dump($statusResult->getError());
                $resumen_diario->update();
                return $resumen_diario;
                exit();
            }

            $cdrGuardado = file_put_contents('R-' . $resumen->getName() . '.zip', $statusResult->getCdrZip());
            if ($cdrGuardado) {
                $resumen_diario->url_cdr = 'R-' . $resumen->getName() . '.zip';
                $resumen_diario->update();
            }

            //echo $statusResult->getCdrResponse()->getDescription();
            $cdr = $statusResult->getCdrResponse();
            $code = (int)$cdr->getCode();

            if ($code === 0) {
                $resumen_diario->estado = 'aceptado';
                $resumen_diario->observaciones = $cdr->getDescription() . PHP_EOL;
                $resumen_diario->update();
            } else if ($code === 98) {
                $resumen_diario->estado = 'observado';
                $resumen_diario->observaciones =  $cdr->getDescription() . PHP_EOL;;
                $resumen_diario->update();
            } else if ($code === 99) {
                $resumen_diario->estado = 'rechazado';
                $resumen_diario->observaciones = $cdr->getDescription() . PHP_EOL;
                $resumen_diario->update();
            }
            $html = new HtmlReport();
            $html->setTemplate('summary.html.twig');

            $report = new PdfReport($html);

            $report->setOptions([
                'no-outline',
                'viewport-size' => '1280x1024',
                'page-width' => '21cm',
                'page-height' => '29.7cm',
            ]);
            $report->setBinPath('C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe'); // Ruta relativa o absoluta de wkhtmltopdf

            $params = [
                'system' => [
                    'logo' => file_get_contents(public_path() . '\img\siscaja_blanco.png'), // Logo de Empresa
                    'hash' => 'qqnr2dN4p/HmaEA/CJuVGo7dv5g=', // Valor Resumen
                ],
                'user' => [
                    'header'     => 'Telf: <b>(01) 123375</b>', // Texto que se ubica debajo de la dirección de empresa
                    'extras'     => [
                        // Leyendas adicionales
                        ['name' => 'CONDICION DE PAGO', 'value' => 'Efectivo'],
                        ['name' => 'VENDEDOR', 'value' => 'CAJA UNSA'],
                    ],
                    'footer' => '<p>Nro Resolucion: <b>3232323</b></p>'
                ]
            ];

            $pdf = $report->render($resumen, $params);

            if ($pdf === null) {
                $error = $report->getExporter()->getError();
                echo 'Error: ' . $error;
                return;
            }

            $pdfGuardado = file_put_contents($resumen->getName() . '.pdf', $pdf);
            if ($pdfGuardado) {
                $resumen_diario->url_pdf = $resumen->getName() . '.pdf';
                $resumen_diario->update();
            }
        } catch (\Throwable $th) {
            return $th;
        }

        return redirect()->route('boletas.iniciar');
    }
    public function anular(Comprobante $boleta)
    {
        try {
            $boleta->estado = 'anulado';
            $boleta->observaciones = '';
            $boleta->update();
        } catch (\Exception $e) {
            Log::error('SunatController@anularBoleta, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->route('boletas.iniciar');
    }
}
